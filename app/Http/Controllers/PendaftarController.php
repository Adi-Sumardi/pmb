<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

            // Redirect ke halaman success.blade.php
            return view('notif.success')->with('message', 'Pendaftaran berhasil! Data Anda telah tersimpan.');

        } catch (\Exception $e) {
            // Jika ada error selain validasi (misalnya DB error), arahkan ke error.blade.php
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
        $pendaftar = Pendaftar::findOrFail($id);

        $pdf = Pdf::loadView('pendaftar.validasi_pdf', compact('pendaftar'));

        $fileName = 'bukti_pendaftaran_' . $pendaftar->id . '.pdf';
        $filePath = 'data/bukti_pendaftaran/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        $pendaftar->bukti_pendaftaran = $fileName;
        $pendaftar->bukti_pendaftaran_path = 'storage/' . $filePath; // opsional, kalau mau tetap simpan path
        $pendaftar->bukti_pendaftaran_mime = mime_content_type(Storage::disk('public')->path($filePath));
        $pendaftar->bukti_pendaftaran_size = Storage::disk('public')->size($filePath);
        $pendaftar->save();

        $bukti_pendaftaran = $pendaftar->bukti_pendaftaran;

        $phoneNumber = $pendaftar->telp_ayah ?: $pendaftar->telp_ibu;
        $message = "Yth. {$pendaftar->nama_murid},\n\n"
            . "Terima kasih telah melakukan pendaftaran di {$pendaftar->unit}. "
            . "Bukti pendaftaran Anda telah berhasil dibuat dan disimpan. "
            . "Silakan simpan bukti ini sebagai arsip.\n\n"
            . "Hormat kami,\n"
            . "Panitia Penerimaan Murid Baru {$pendaftar->unit}";

        $whatsAppController = new WhatsAppController();
        $responses = $whatsAppController->sendMessages($phoneNumber, $message, $bukti_pendaftaran);
        $respStrings = array_map(function($r) {
        if (is_array($r)) {
            return json_encode($r, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            return (string) $r;
        }, $responses);

        return redirect()->route('pendaftar')
            ->with('success', 'PDF Kartu pendaftaran berhasil disimpan. ' . implode(', ', $respStrings));
    }

}
