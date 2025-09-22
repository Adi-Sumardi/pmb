<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\SecurityValidationService;

class SecureStudentDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && SecurityValidationService::validateUserPermissions(
            Auth::user(),
            'store_student_data'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'nama_panggilan' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'nisn' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'unique:student_details,nisn'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', 'unique:student_details,nik'],
            'no_kk' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'agama' => ['required', 'in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu,Lainnya'],
            'kewarganegaraan' => ['required', 'string', 'max:50'],
            'bahasa_sehari_hari' => ['nullable', 'string', 'max:100'],

            // Data Fisik
            'tinggi_badan' => ['nullable', 'numeric', 'min:50', 'max:250'],
            'berat_badan' => ['nullable', 'numeric', 'min:10', 'max:200'],
            'golongan_darah' => ['nullable', 'in:A,B,AB,O,Tidak Tahu'],

            // Alamat
            'alamat_lengkap' => ['required', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:5', 'regex:/^[0-9]+$/'],
            'rw' => ['nullable', 'string', 'max:5', 'regex:/^[0-9]+$/'],
            'kelurahan' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota_kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10', 'regex:/^[0-9]+$/'],
            'jarak_ke_sekolah' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'transportasi' => ['nullable', 'string', 'max:100'],

            // Data Tinggal
            'tinggal_dengan' => ['nullable', 'in:Orang Tua,Wali,Kos,Asrama,Panti Asuhan,Lainnya'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:20']
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'nisn.regex' => 'NISN harus berupa angka.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'no_kk.regex' => 'Nomor KK harus berupa 16 digit angka.',
            'no_kk.size' => 'Nomor KK harus 16 digit.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan.',
            'tanggal_lahir.after' => 'Tanggal lahir tidak valid.',
            'tinggi_badan.min' => 'Tinggi badan minimal 50 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal 250 cm.',
            'berat_badan.min' => 'Berat badan minimal 10 kg.',
            'berat_badan.max' => 'Berat badan maksimal 200 kg.',
            'rt.regex' => 'RT harus berupa angka.',
            'rw.regex' => 'RW harus berupa angka.',
            'kode_pos.regex' => 'Kode pos harus berupa angka.',
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
