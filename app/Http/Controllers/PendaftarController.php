<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\User; // Add this import
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Add this import
use Illuminate\Support\Str; // Add this import
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\DB;

class PendaftarController extends Controller
{
    public function store(Request $request)
    {
        try {
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

            // Calculate payment amount based on jenjang
            $data['payment_amount'] = $this->getPaymentAmount($validated['jenjang']);

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

            $data['status'] = 'pending';
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

            Pendaftar::create($data);

            return view('notif.success')->with('message', 'Pendaftaran berhasil! Data Anda telah tersimpan.');

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan pendaftaran: ' . $e->getMessage());
            return view('notif.error')->with('message', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    /**
     * Calculate payment amount based on jenjang
     */
    private function getPaymentAmount($jenjang)
    {
        $paymentAmounts = [
            'sanggar' => 325000,
            'kelompok' => 325000,
            'tka' => 355000,
            'tkb' => 355000,
            'sd' => 425000,
            'smp' => 455000,
            'sma' => 525000,
        ];

        return $paymentAmounts[$jenjang] ?? 0;
    }

    /**
     * Get jenjang display name
     */
    private function getJenjangName($jenjang)
    {
        $jenjangNames = [
            'sanggar' => 'Sanggar Bermain',
            'kelompok' => 'Kelompok Bermain',
            'tka' => 'TK A',
            'tkb' => 'TK B',
            'sd' => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA',
        ];

        return $jenjangNames[$jenjang] ?? strtoupper($jenjang);
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

            // Update status ke diverifikasi
            $updated = Pendaftar::whereIn('id', $ids)
                ->where('status', 'pending')
                ->update(['status' => 'diverifikasi']);

            if ($updated > 0) {
                return redirect()->back()->with('success', "Berhasil memverifikasi {$updated} pendaftar.");
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
}
