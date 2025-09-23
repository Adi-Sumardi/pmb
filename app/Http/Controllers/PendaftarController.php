<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\User; // Add this import
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Add this import
use Illuminate\Support\Facades\Auth; // Add Auth facade
use Illuminate\Support\Str; // Add this import
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WhatsAppController;

class PendaftarController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log submission details for debugging iOS issues
            $clientInfo = $request->input('client_info');
            $isIOS = false;

            if ($clientInfo) {
                $clientData = json_decode($clientInfo, true);
                $isIOS = $clientData['isIOS'] ?? false;

                Log::info('Form submission received', [
                    'client_info' => $clientData,
                    'submission_timestamp' => $request->input('submission_timestamp'),
                    'request_method' => $request->method(),
                    'is_ios' => $isIOS,
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);
            }

            // Check for duplicate submission by email and name combination
            $existingPendaftar = Pendaftar::where('user_id', Auth::id())
                ->where('nama_murid', $request->nama_murid)
                ->where('created_at', '>', now()->subMinutes(5)) // Within last 5 minutes
                ->first();

            if ($existingPendaftar) {
                Log::warning('Duplicate form submission detected', [
                    'user_id' => Auth::id(),
                    'nama_murid' => $request->nama_murid,
                    'existing_id' => $existingPendaftar->id
                ]);

                return redirect()->route('user.dashboard')
                    ->with('error', 'Data pendaftaran sudah pernah disubmit. Silakan cek dashboard Anda.');
            }

            $validated = $request->validate([
                'nama_murid'   => 'required|string|max:255',
                'nisn'         => 'nullable|string|max:50',
                'tanggal_lahir'=> 'required|date',
                'alamat'       => 'required|string',
                'jenjang'      => 'required|string',
                'unit'         => 'required|string',
                'asal_sekolah' => 'nullable|string',
                'nama_sekolah' => 'nullable|string',
                'kelas'        => 'nullable|string',
                'nama_ayah'    => 'required|string|max:255',
                'telp_ayah'    => 'required|string|max:20',
                'nama_ibu'     => 'required|string|max:255',
                'telp_ibu'     => 'required|string|max:20',
                'foto_murid' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'akta_kelahiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = $validated;

            // Add academic year
            $data['academic_year'] = '2026/2027';

            // Calculate payment amount based on UNIT (not jenjang) using new system
            $data['payment_amount'] = $this->getFormulirAmountByUnit($validated['unit']);

            Log::info('Processing pendaftaran with unit-based pricing', [
                'unit' => $validated['unit'],
                'calculated_amount' => $data['payment_amount'],
                'nama_murid' => $validated['nama_murid']
            ]);

            if ($request->hasFile('foto_murid')) {
                $file = $request->file('foto_murid');
                $path = $file->store('uploads/foto_murid', 'public');
                $data['foto_murid_path'] = $path;
                $data['foto_murid_mime'] = $file->getClientMimeType();
                $data['foto_murid_size'] = $file->getSize();
            }

            // Upload akta kelahiran
            if ($request->hasFile('akta_kelahiran')) {
                $file = $request->file('akta_kelahiran');
                $path = $file->store('uploads/akta_kelahiran', 'public');
                $data['akta_kelahiran_path'] = $path;
                $data['akta_kelahiran_mime'] = $file->getClientMimeType();
                $data['akta_kelahiran_size'] = $file->getSize();
            }

            // Upload kartu keluarga
            if ($request->hasFile('kartu_keluarga')) {
                $file = $request->file('kartu_keluarga');
                $path = $file->store('uploads/kartu_keluarga', 'public');
                $data['kartu_keluarga_path'] = $path;
                $data['kartu_keluarga_mime'] = $file->getClientMimeType();
                $data['kartu_keluarga_size'] = $file->getSize();
            }

            $data['status_pendaftaran'] = 'menunggu_verifikasi';
            $data['overall_status'] = 'Draft';
            $data['current_status'] = 'Draft';
            $data['data_completion_status'] = 'incomplete';
            $data['sudah_bayar_formulir'] = false;

            // Generate nomor pendaftaran unik
            $month = date('m');
            $year = date('y');
            $lastPendaftar = Pendaftar::whereMonth('created_at', $month)
                ->whereYear('created_at', '20'.$year)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastPendaftar ? ((int)substr($lastPendaftar->no_pendaftaran, -4) + 1) : 1;
            $data['no_pendaftaran'] = 'PMB' . $month . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Create pendaftar with transaction to ensure atomicity
            $pendaftar = DB::transaction(function() use ($data) {
                return Pendaftar::create($data);
            });

            Log::info('Pendaftaran successfully created', [
                'pendaftar_id' => $pendaftar->id,
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'unit' => $pendaftar->unit,
                'payment_amount' => $pendaftar->payment_amount
            ]);

            return view('notif.success')->with([
                'message' => 'Pendaftaran berhasil! Data Anda telah tersimpan.',
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'payment_amount' => $pendaftar->payment_amount
            ]);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan pendaftaran: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['foto_murid', 'akta_kelahiran', 'kartu_keluarga']),
                'client_info' => $request->input('client_info'),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'headers' => $request->headers->all()
            ]);

            // Check if this is iOS Safari and provide specific guidance
            $clientInfo = $request->input('client_info');
            $isIOS = false;

            if ($clientInfo) {
                $clientData = json_decode($clientInfo, true);
                $isIOS = $clientData['isIOS'] ?? false;
            }

            if ($isIOS) {
                Log::warning('iOS Safari submission error detected', [
                    'error' => $e->getMessage(),
                    'client_data' => $clientData ?? null
                ]);

                return view('notif.error')->with([
                    'message' => 'Terjadi kesalahan pada perangkat iOS. Silakan coba langkah berikut: 1) Refresh halaman, 2) Pastikan koneksi internet stabil, 3) Coba lagi dalam beberapa menit.',
                    'ios_specific' => true
                ]);
            }

            return view('notif.error')->with('message', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function index()
    {
        $dt_pendaftars = Pendaftar::orderByRaw("
            CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'diverifikasi' THEN 2
                ELSE 3
            END
        ")->orderBy('created_at', 'desc')->get();
        return view('admin.pendaftar.index', compact('dt_pendaftars'));
    }

    public function validasi($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        return view('admin.pendaftar.validasi', compact('pendaftar'));
    }

    public function update($id)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($id);

            $email = $pendaftar->no_pendaftaran . '@yapinet.id';

            // Generate password
            $symbols = '!@#$%^&*';
            $password =
                Str::random(2) .
                strtoupper(Str::random(2)) .
                rand(10, 99) .
                $symbols[rand(0, strlen($symbols) - 1)] .
                Str::random(1);

            $password = str_shuffle($password);

            if (strlen($password) < 8) {
                $password .= Str::random(8 - strlen($password));
            }

            // Cek apakah user sudah ada berdasarkan pendaftar_id
            $existingUser = User::where('pendaftar_id', $pendaftar->id)->first();

            if ($existingUser) {
                // Update user yang sudah ada
                $existingUser->update([
                    'name' => $pendaftar->nama_murid,
                    'password' => Hash::make($password),
                ]);
                $user = $existingUser;
                $email = $existingUser->email;
                $statusMessage = "DIPERBARUI";
            } else {
                // Cek email conflict
                $counter = 1;
                $originalEmail = $email;
                while (User::where('email', $email)->exists()) {
                    $email = str_replace('@yapinet.id', $counter . '@yapinet.id', $originalEmail);
                    $counter++;
                }

                // Buat user baru
                $user = User::create([
                    'name' => $pendaftar->nama_murid,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'user',
                    'pendaftar_id' => $pendaftar->id,
                ]);
                $statusMessage = "DIBUAT";
            }

            // Siapkan data untuk PDF dengan mapping yang benar + tambahkan email dan password
            $peserta = (object) [
                'nama' => $pendaftar->nama_murid,
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'nisn' => $pendaftar->nisn,
                'nama_sekolah' => $pendaftar->nama_sekolah,
                'tanggal_lahir' => $pendaftar->tanggal_lahir,
                'alamat' => $pendaftar->alamat,
                'unit' => $pendaftar->unit,
                'nama_ayah' => $pendaftar->nama_ayah,
                'telp_ayah' => $pendaftar->telp_ayah,
                'nama_ibu' => $pendaftar->nama_ibu,
                'telp_ibu' => $pendaftar->telp_ibu,
                'foto' => basename($pendaftar->foto_murid_path ?? 'default.jpg'),
                'email' => $email, // Tambahkan email
                'password' => $password, // Tambahkan password
            ];

            $tahunAjaran = '2026/2027'; // Bisa dibuat dinamis atau dari config

            // Generate PDF
            $pdf = Pdf::loadView('admin.pendaftar.validasi_pdf', compact('peserta', 'tahunAjaran'))
                     ->setPaper('a4', 'portrait');

            $fileName = 'bukti_pendaftaran_' . $pendaftar->no_pendaftaran . '.pdf';
            $filePath = 'data/bukti_pendaftaran/' . $fileName;

            // Simpan PDF ke storage
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update pendaftar dengan user_id
            $pendaftar->update([
                'status' => 'diverifikasi',
                'bukti_pendaftaran' => $fileName,
                'bukti_pendaftaran_path' => 'storage/' . $filePath,
                'bukti_pendaftaran_mime' => 'application/pdf',
                'bukti_pendaftaran_size' => Storage::disk('public')->size($filePath),
                'user_id' => $user->id,
            ]);

            // Kirim WhatsApp dengan informasi akun
            $phoneNumber = $pendaftar->telp_ayah ?: $pendaftar->telp_ibu;
            $message = "Yth. Orang Tua {$pendaftar->nama_murid},\n\n"
                . "Selamat! Pendaftaran anak Anda di {$pendaftar->unit} telah DIVERIFIKASI.\n"
                . "Nomor Pendaftaran: {$pendaftar->no_pendaftaran}\n\n"
                . "🔐 AKUN SISWA TELAH DIBUAT:\n"
                . "Email: {$email}\n"
                . "Password: {$password}\n\n"
                . "⚠️ PENTING:\n"
                . "- Informasi akun juga tersedia di bukti PDF\n"
                . "- Simpan informasi akun ini dengan aman\n"
                . "- Ganti password setelah login pertama\n"
                . "- Akun ini untuk akses sistem PMB lebih lanjut\n\n"
                . "Bukti pendaftaran telah dikirimkan bersama pesan ini.\n\n"
                . "Terima kasih,\n"
                . "Panitia Penerimaan Murid Baru {$pendaftar->unit}";

            $whatsAppController = new WhatsAppController();
            $responses = $whatsAppController->sendMessages(
                $phoneNumber,
                $message,
                $fileName,
                $pendaftar->nama_murid,
                $pendaftar->no_pendaftaran
            );

            $respStrings = array_map(function($r) {
                if (is_array($r)) {
                    return json_encode($r, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
                return (string) $r;
            }, $responses);

            return redirect()->route('admin.pendaftar.index')
                ->with('success', 'Pendaftaran berhasil diverifikasi, akun user telah dibuat dengan email: ' . $email . ', dan bukti PDF (termasuk akun) telah dikirim. ' . implode(', ', $respStrings));

        } catch (\Exception $e) {
            Log::error('Error saat memverifikasi pendaftaran: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('admin.pendaftar.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkVerify(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
            }

            // Update status ke diverifikasi dan generate StudentBills
            $updated = 0;
            $billsGenerated = 0;

            foreach ($ids as $id) {
                $pendaftar = Pendaftar::find($id);

                if ($pendaftar && $pendaftar->status === 'pending') {
                    // Update status
                    $pendaftar->update([
                        'status' => 'diverifikasi',
                        'admin_verification_status' => 'approved',
                        'admin_verification_date' => now()
                    ]);

                    // Generate default StudentBills for verified pendaftar
                    $generated = $this->generateDefaultStudentBills($pendaftar);
                    $billsGenerated += $generated;
                    $updated++;
                }
            }

            if ($updated > 0) {
                return redirect()->back()->with('success', "Berhasil memverifikasi {$updated} pendaftar dan generate {$billsGenerated} tagihan.");
            } else {
                return redirect()->back()->with('error', 'Tidak ada data yang dapat diverifikasi.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
            }

            // Hapus file terkait terlebih dahulu
            $pendaftars = Pendaftar::whereIn('id', $ids)->get();

            foreach ($pendaftars as $pendaftar) {
                // Hapus file foto
                if ($pendaftar->foto_murid_path && Storage::disk('public')->exists($pendaftar->foto_murid_path)) {
                    Storage::disk('public')->delete($pendaftar->foto_murid_path);
                }

                // Hapus file akta kelahiran
                if ($pendaftar->akta_kelahiran_path && Storage::disk('public')->exists($pendaftar->akta_kelahiran_path)) {
                    Storage::disk('public')->delete($pendaftar->akta_kelahiran_path);
                }

                // Hapus file kartu keluarga
                if ($pendaftar->kartu_keluarga_path && Storage::disk('public')->exists($pendaftar->kartu_keluarga_path)) {
                    Storage::disk('public')->delete($pendaftar->kartu_keluarga_path);
                }

                // Hapus file bukti pendaftaran
                if ($pendaftar->bukti_pendaftaran_path && Storage::disk('public')->exists($pendaftar->bukti_pendaftaran_path)) {
                    Storage::disk('public')->delete($pendaftar->bukti_pendaftaran_path);
                }
            }

            // Hapus data dari database
            $deleted = Pendaftar::whereIn('id', $ids)->delete();

            if ($deleted > 0) {
                return redirect()->back()->with('deleted', "Berhasil menghapus {$deleted} pendaftar beserta file terkait.");
            } else {
                return redirect()->back()->with('error', 'Tidak ada data yang dapat dihapus.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pendaftars,id',
            'overall_status' => 'required|string|in:Draft,Diverifikasi,Sudah Bayar,Observasi,Tes Tulis,Praktek Shalat & BTQ,Wawancara,Psikotest,Lulus,Tidak Lulus'
        ]);

        $ids = $request->input('ids');
        $status = $request->input('overall_status');

        try {
            // Log the received IDs and status for debugging
            Log::info('Bulk updating status', [
                'ids' => $ids,
                'status' => $status,
                'count' => count($ids)
            ]);

            // Use update on the query builder directly
            $updated = DB::table('pendaftars')
                ->whereIn('id', $ids)
                ->update(['overall_status' => $status, 'current_status' => $status]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "$updated pendaftar telah diperbarui statusnya menjadi '$status'",
                    'updated' => $updated
                ]);
            }

            return redirect()->back()->with('info', "$updated pendaftar telah diperbarui statusnya menjadi '$status'");
        } catch (\Exception $e) {
            Log::error('Error in bulk update status: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status pendaftar: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui status pendaftar: ' . $e->getMessage());
        }
    }

    public function bulkUpdateStudentStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pendaftars,id',
            'student_status' => 'required|string|in:inactive,active,graduated,dropped_out,transferred'
        ]);

        $ids = $request->input('ids');
        $studentStatus = $request->input('student_status');

        try {
            // Log the received IDs and status for debugging
            Log::info('Bulk updating student status', [
                'ids' => $ids,
                'student_status' => $studentStatus,
                'count' => count($ids)
            ]);

            // Use update on the query builder directly
            $updated = DB::table('pendaftars')
                ->whereIn('id', $ids)
                ->update([
                    'student_status' => $studentStatus,
                    'student_activated_at' => $studentStatus === 'active' ? now() : null,
                    'updated_at' => now()
                ]);

            // Get status text for response
            $statusText = match($studentStatus) {
                'active' => 'Aktif',
                'graduated' => 'Lulus',
                'dropped_out' => 'Keluar',
                'transferred' => 'Pindah',
                default => 'Belum Aktif'
            };

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "$updated pendaftar telah diperbarui status siswanya menjadi '$statusText'",
                    'updated' => $updated
                ]);
            }

            return redirect()->back()->with('info', "$updated pendaftar telah diperbarui status siswanya menjadi '$statusText'");
        } catch (\Exception $e) {
            Log::error('Error in bulk update student status: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status siswa: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui status siswa: ' . $e->getMessage());
        }
    }

    /**
     * Generate default StudentBills for verified pendaftar
     */
    private function generateDefaultStudentBills(Pendaftar $pendaftar): int
    {
        $generated = 0;
        $currentYear = now()->year;
        $academicYear = $currentYear . '/' . ($currentYear + 1);

        // Default bills based on jenjang
        $defaultBills = [
            // Registration fee (formulir) - always required - amount based on unit
            [
                'bill_type' => 'registration_fee',
                'description' => 'Biaya Formulir Pendaftaran',
                'total_amount' => $this->getFormulirAmountByUnit($pendaftar->unit),
                'due_date' => now()->addDays(7),
                'notes' => 'Biaya formulir pendaftaran peserta didik baru'
            ],
            // Uang pangkal - required for all levels
            [
                'bill_type' => 'uang_pangkal',
                'description' => 'Uang Pangkal',
                'total_amount' => $this->getUangPangkalAmount($pendaftar->jenjang),
                'due_date' => now()->addDays(30),
                'notes' => 'Uang pangkal untuk konfirmasi penerimaan'
            ]
        ];

        // Add SPP for first month if TK/SD/SMP levels
        if (in_array(strtolower($pendaftar->jenjang), ['tk', 'sd', 'smp'])) {
            $defaultBills[] = [
                'bill_type' => 'spp',
                'description' => 'SPP ' . now()->format('F Y'),
                'total_amount' => $this->getSppAmount($pendaftar->jenjang),
                'due_date' => now()->endOfMonth(),
                'month' => now()->month,
                'notes' => 'Sumbangan Pembinaan Pendidikan'
            ];
        }

        foreach ($defaultBills as $billData) {
            // Check if bill already exists
            $existingBill = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
                ->where('bill_type', $billData['bill_type'])
                ->when(isset($billData['month']), function($query) use ($billData) {
                    return $query->where('month', $billData['month']);
                })
                ->first();

            if (!$existingBill) {
                \App\Models\StudentBill::create([
                    'pendaftar_id' => $pendaftar->id,
                    'bill_type' => $billData['bill_type'],
                    'description' => $billData['description'],
                    'total_amount' => $billData['total_amount'],
                    'paid_amount' => 0,
                    'remaining_amount' => $billData['total_amount'],
                    'due_date' => $billData['due_date'],
                    'academic_year' => $academicYear,
                    'semester' => null, // No semester needed for school registration
                    'month' => $billData['month'] ?? null,
                    'payment_status' => 'pending',
                    'notes' => $billData['notes'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Get uang pangkal amount based on education level
     */
    private function getUangPangkalAmount(string $jenjang): int
    {
        return match(strtolower($jenjang)) {
            'tk', 'paud' => 2000000,
            'sd' => 3000000,
            'smp' => 4000000,
            'sma', 'smk' => 5000000,
            default => 2500000
        };
    }

    /**
     * Get SPP amount based on education level
     */
    private function getSppAmount(string $jenjang): int
    {
        return match(strtolower($jenjang)) {
            'tk', 'paud' => 200000,
            'sd' => 250000,
            'smp' => 300000,
            'sma', 'smk' => 350000,
            default => 250000
        };
    }

    /**
     * Get formulir amount based on school unit (updated amounts per request)
     */
    private function getFormulirAmountByUnit(string $unit): int
    {
        // Exact unit name mappings based on user requirements
        $formulirAmounts = [
            // RA Sakinah = Rp.100.000
            'RA Sakinah' => 100000,
            'RA Sakinah - Rawamangun' => 100000,

            // PG Sakinah = Rp 400.000
            'PG Sakinah' => 400000,
            'PG Sakinah - Rawamangun' => 400000,
            'Playgroup Sakinah' => 400000,
            'Playgroup Sakinah - Rawamangun' => 400000,

            // TKIA 13 = Rp 450.000
            'TKIA 13' => 450000,
            'TK Islam Al Azhar 13' => 450000,
            'TK Islam Al Azhar 13 - Rawamangun' => 450000,

            // SDIA 13 = Rp 550.000
            'SDIA 13' => 550000,
            'SD Islam Al Azhar 13' => 550000,
            'SD Islam Al Azhar 13 - Rawamangun' => 550000,

            // SMPIA 12 = Rp 550.000
            'SMPIA 12' => 550000,
            'SMP Islam Al Azhar 12' => 550000,
            'SMP Islam Al Azhar 12 - Rawamangun' => 550000,

            // SMPIA 55 = Rp 550.000
            'SMPIA 55' => 550000,
            'SMP Islam Al Azhar 55' => 550000,
            'SMP Islam Al Azhar 55 - Rawamangun' => 550000,

            // SMAIA 33 = Rp 550.000
            'SMAIA 33' => 550000,
            'SMA Islam Al Azhar 33' => 550000,
            'SMA Islam Al Azhar 33 - Rawamangun' => 550000,
            'SMA Islam Al Azhar 33 - Jatimakmur' => 550000,
        ];

        // Check exact match first
        if (isset($formulirAmounts[$unit])) {
            return $formulirAmounts[$unit];
        }

        // Fallback: check partial matches for flexibility
        $unitLower = strtolower($unit);

        if (strpos($unitLower, 'ra') !== false && strpos($unitLower, 'sakinah') !== false) {
            return 100000; // RA Sakinah
        }

        if (strpos($unitLower, 'playgroup') !== false && strpos($unitLower, 'sakinah') !== false) {
            return 400000; // PG Sakinah
        }

        if (strpos($unitLower, 'tk') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '13') !== false) {
            return 450000; // TKIA 13
        }

        if (strpos($unitLower, 'sd') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '13') !== false) {
            return 550000; // SDIA 13
        }

        if (strpos($unitLower, 'smp') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '12') !== false) {
            return 550000; // SMPIA 12
        }

        if (strpos($unitLower, 'smp') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '55') !== false) {
            return 550000; // SMPIA 55
        }

        if (strpos($unitLower, 'sma') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '33') !== false) {
            return 550000; // SMAIA 33
        }

        // No fallback - unit not recognized, return 0 to indicate error
        Log::warning('Unit formulir tidak dikenali', ['unit' => $unit]);
        return 0;
    }
}
