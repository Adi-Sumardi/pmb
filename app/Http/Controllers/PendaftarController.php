<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\WhatsAppController;

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

    public function index()
    {
        $dt_pendaftars = Pendaftar::all();
        return view('pendaftar.index', compact('dt_pendaftars'));
    }

    public function validasi($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        return view('pendaftar.validasi', compact('pendaftar'));
    }

    public function update($id)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($id);

            // Siapkan data untuk PDF dengan mapping yang benar
            $peserta = (object) [
                'nama' => $pendaftar->nama_murid,
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'nisn' => $pendaftar->nisn,
                'asal_sekolah' => $pendaftar->asal_sekolah,
                'tanggal_lahir' => $pendaftar->tanggal_lahir,
                'alamat' => $pendaftar->alamat,
                'unit' => $pendaftar->unit,
                'nama_ayah' => $pendaftar->nama_ayah,
                'nama_ibu' => $pendaftar->nama_ibu,
                'foto' => basename($pendaftar->foto_murid_path ?? 'default.jpg'),
            ];

            $tahunAjaran = '2026/2027'; // Bisa dibuat dinamis atau dari config

            // Generate PDF
            $pdf = Pdf::loadView('pendaftar.validasi_pdf', compact('peserta', 'tahunAjaran'))
                     ->setPaper('a4', 'portrait');

            $fileName = 'bukti_pendaftaran_' . $pendaftar->no_pendaftaran . '.pdf';
            $filePath = 'data/bukti_pendaftaran/' . $fileName;

            // Simpan PDF ke storage
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update status menjadi diverifikasi dan simpan info PDF
            $pendaftar->update([
                'status' => 'diverifikasi', // Update status sesuai migration
                'bukti_pendaftaran' => $fileName,
                'bukti_pendaftaran_path' => 'storage/' . $filePath,
                'bukti_pendaftaran_mime' => 'application/pdf',
                'bukti_pendaftaran_size' => Storage::disk('public')->size($filePath),
            ]);

            // Kirim WhatsApp
            $phoneNumber = $pendaftar->telp_ayah ?: $pendaftar->telp_ibu;
            $message = "Yth. Orang Tua {$pendaftar->nama_murid},\n\n"
                . "Selamat! Pendaftaran anak Anda di {$pendaftar->unit} telah DIVERIFIKASI.\n"
                . "Nomor Pendaftaran: {$pendaftar->no_pendaftaran}\n"
                . "Bukti pendaftaran telah dikirimkan bersama pesan ini.\n\n"
                . "Silakan simpan bukti ini untuk keperluan administrasi selanjutnya.\n\n"
                . "Terima kasih,\n"
                . "Panitia Penerimaan Murid Baru {$pendaftar->unit}";

            $whatsAppController = new WhatsAppController();
            $responses = $whatsAppController->sendMessages($phoneNumber, $message, $fileName);

            $respStrings = array_map(function($r) {
                if (is_array($r)) {
                    return json_encode($r, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
                return (string) $r;
            }, $responses);

            return redirect()->route('pendaftar')
                ->with('success', 'Pendaftaran berhasil diverifikasi dan bukti PDF telah dikirim. ' . implode(', ', $respStrings));

        } catch (\Exception $e) {
            Log::error('Error saat memverifikasi pendaftaran: ' . $e->getMessage());
            return redirect()->route('pendaftar')
                ->with('error', 'Terjadi kesalahan saat memverifikasi pendaftaran. Silakan coba lagi.');
        }
    }
}
