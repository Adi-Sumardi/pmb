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
            // Kirim pesan teks saja (tidak ada pengiriman file)
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

            // File sending feature removed - only text messages now
            // Bukti pendaftaran dapat diakses langsung melalui sistem

        } catch (RequestException $e) {
            $body = $e->hasResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $responses[] = ['exception' => $body];
        }

        return $responses;
    }

    /**
     * Send message dengan prioritas telp_ibu dulu, kemudian telp_ayah jika gagal
     */
    public function sendWithPriority($telp_ibu, $telp_ayah, $message, $nama_murid = null, $no_pendaftaran = null)
    {
        $responses = [];
        $success = false;

        // Format nomor telepon
        $formatted_ibu = $this->formatPhoneNumber($telp_ibu);
        $formatted_ayah = $this->formatPhoneNumber($telp_ayah);

        // Prioritas 1: Coba kirim ke nomor ibu
        if (!empty($formatted_ibu)) {
            $responses['ibu'] = $this->sendMessages($formatted_ibu, $message, null, $nama_murid, $no_pendaftaran);

            // Cek apakah pengiriman ke ibu berhasil
            if (isset($responses['ibu'][0]) && !isset($responses['ibu'][0]['error']) && !isset($responses['ibu'][0]['exception'])) {
                $success = true;
                $responses['used_number'] = $formatted_ibu;
                $responses['success_recipient'] = 'ibu';
                $responses['original_number'] = $telp_ibu;
            }
        }

        // Prioritas 2: Jika gagal ke ibu, coba ke ayah
        if (!$success && !empty($formatted_ayah)) {
            $responses['ayah'] = $this->sendMessages($formatted_ayah, $message, null, $nama_murid, $no_pendaftaran);

            // Cek apakah pengiriman ke ayah berhasil
            if (isset($responses['ayah'][0]) && !isset($responses['ayah'][0]['error']) && !isset($responses['ayah'][0]['exception'])) {
                $success = true;
                $responses['used_number'] = $formatted_ayah;
                $responses['success_recipient'] = 'ayah';
                $responses['original_number'] = $telp_ayah;
            }
        }

        $responses['final_success'] = $success;

        // Jika tidak ada nomor yang valid
        if (empty($formatted_ibu) && empty($formatted_ayah)) {
            $responses['error'] = 'No valid phone numbers provided';
        }

        return $responses;
    }    /**
     * Format phone number untuk WhatsApp API
     */
    private function formatPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return null;
        }

        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If starts with 0, replace with 62
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }

        // If doesn't start with 62, add it
        if (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }

        return $phoneNumber;
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
