<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsAppController extends Controller
{
    public function sendMessages($phoneNumber, $message = null, $bukti_pendaftaran = null, $nama_murid = null, $no_pendaftaran = null)
    {
        $client = new Client(['timeout' => 15]);
        $apiKey = "SGIKDRJTT0MDQRVX";
        $numberKey = "f49e10YYE2Gee1hb";
        $headers = ['Content-Type' => 'application/json'];

        $responses = [];

        if (empty($phoneNumber)) {
            return ['error' => 'Phone number is empty'];
        }

        try {
            // Kirim pesan teks dulu (jika ada)
            if (!empty($message)) {
                $payload = [
                    "api_key"    => $apiKey,
                    "number_key" => $numberKey,
                    "phone_no"   => $phoneNumber,
                    "message"    => $message,
                ];

                $resp = $client->post('https://api.watzap.id/v1/send_message', [
                    'headers' => $headers,
                    'json'    => $payload,
                ]);

                $responses[] = json_decode((string)$resp->getBody(), true);
            }

            // Kirim file PDF jika ada
            if (!empty($bukti_pendaftaran)) {
                if (strpos($bukti_pendaftaran, 'storage/') === 0) {
                    $fileUrl = env('APP_URL') . $bukti_pendaftaran;
                } else {
                    $fileUrl = asset('storage/data/bukti_pendaftaran/' . $bukti_pendaftaran);
                }

                // Generate custom filename untuk WhatsApp
                $customFileName = $this->generateCustomFileName($bukti_pendaftaran, $nama_murid, $no_pendaftaran);

                try {
                    $head = $client->head($fileUrl, ['http_errors' => false, 'timeout' => 10]);
                    $status = $head->getStatusCode();
                    if ($status < 200 || $status >= 400) {
                        $responses[] = [
                            'error' => 'File not publicly accessible',
                            'url' => $fileUrl,
                            'http_code' => $status,
                        ];
                    } else {
                        $payloadFile = [
                            "api_key"    => $apiKey,
                            "number_key" => $numberKey,
                            "phone_no"   => $phoneNumber,
                            "url"        => $fileUrl,
                            "caption"    => "Berikut bukti pendaftaran Anda dalam format PDF.",
                            "filename"   => $customFileName, // Tambahkan custom filename
                        ];

                        $respFile = $client->post('https://api.watzap.id/v1/send_file_url', [
                            'headers' => $headers,
                            'json'    => $payloadFile,
                        ]);

                        $responses[] = json_decode((string)$respFile->getBody(), true);
                    }
                } catch (\Exception $e) {
                    $responses[] = ['error' => 'HEAD check failed: ' . $e->getMessage(), 'url' => $fileUrl];
                }
            }
        } catch (RequestException $e) {
            $body = $e->hasResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $responses[] = ['exception' => $body];
        }

        return $responses;
    }

    /**
     * Generate custom filename untuk WhatsApp
     */
    private function generateCustomFileName($originalFileName, $nama_murid, $no_pendaftaran)
    {
        // Jika nama murid dan no pendaftaran tersedia
        if (!empty($nama_murid) && !empty($no_pendaftaran)) {
            // Bersihkan nama murid dari karakter yang tidak diinginkan
            $cleanName = $this->cleanFileName($nama_murid);

            // Format: Nama_Murid_NoPendaftaran.pdf
            return $cleanName . '_' . $no_pendaftaran . '.pdf';
        }

        // Fallback ke nama file asli jika data tidak lengkap
        return $originalFileName;
    }

    /**
     * Bersihkan nama file dari karakter khusus
     */
    private function cleanFileName($name)
    {
        // Ubah ke lowercase
        $name = strtolower($name);

        // Ganti spasi dengan underscore
        $name = str_replace(' ', '_', $name);

        // Hapus karakter khusus, hanya biarkan huruf, angka, dan underscore
        $name = preg_replace('/[^a-z0-9_]/', '', $name);

        // Hilangkan underscore berturut-turut
        $name = preg_replace('/_+/', '_', $name);

        // Hilangkan underscore di awal dan akhir
        $name = trim($name, '_');

        return $name;
    }
}
