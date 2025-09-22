<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\SecurityValidationService;

class SecureParentDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && SecurityValidationService::validateUserPermissions(
            Auth::user(),
            'store_parent_data'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Data Ayah
            'nama_ayah' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'nik_ayah' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'tempat_lahir_ayah' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir_ayah' => ['nullable', 'date', 'before:today', 'after:1920-01-01'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ayah' => ['required', 'string', 'max:255'],
            'penghasilan_ayah' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'telepon_ayah' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
            'alamat_kantor_ayah' => ['nullable', 'string', 'max:500'],

            // Data Ibu
            'nama_ibu' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'nik_ibu' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'tempat_lahir_ibu' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir_ibu' => ['nullable', 'date', 'before:today', 'after:1920-01-01'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ibu' => ['required', 'string', 'max:255'],
            'penghasilan_ibu' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'telepon_ibu' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
            'alamat_kantor_ibu' => ['nullable', 'string', 'max:500'],

            // Data Wali
            'nama_wali' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'nik_wali' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'hubungan_wali' => ['nullable', 'string', 'max:100'],
            'pekerjaan_wali' => ['nullable', 'string', 'max:255'],
            'penghasilan_wali' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'telepon_wali' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
            'alamat_wali' => ['nullable', 'string', 'max:500'],

            // Data Keluarga
            'jumlah_anak' => ['nullable', 'integer', 'min:1', 'max:20'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:20'],
            'status_dalam_keluarga' => ['nullable', 'string', 'max:100'],
            'bahasa_dirumah' => ['nullable', 'string', 'max:100']
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            '*.regex' => 'Format input tidak valid.',
            '*.max' => 'Input terlalu panjang.',
            '*.required' => 'Field ini wajib diisi.',
            'nik_*.size' => 'NIK harus 16 digit.',
            'nik_*.regex' => 'NIK harus berupa angka.',
            'tanggal_lahir_*.before' => 'Tanggal lahir tidak boleh di masa depan.',
            'tanggal_lahir_*.after' => 'Tanggal lahir tidak valid.',
            'telepon_*.regex' => 'Format nomor telepon tidak valid.',
            'penghasilan_*.numeric' => 'Penghasilan harus berupa angka.',
            'penghasilan_*.min' => 'Penghasilan tidak boleh negatif.',
            'jumlah_anak.min' => 'Jumlah anak minimal 1.',
            'jumlah_anak.max' => 'Jumlah anak maksimal 20.',
            'anak_ke.min' => 'Anak ke minimal 1.',
            'anak_ke.max' => 'Anak ke maksimal 20.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        // Sanitize all string inputs
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $this->merge([
                    $key => SecurityValidationService::sanitizeInput($value)
                ]);
            }
        }
    }
}
