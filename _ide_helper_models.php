<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $no_pendaftaran
 * @property string $nama_murid
 * @property string|null $nisn
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $alamat
 * @property string $jenjang
 * @property string $unit
 * @property string|null $asal_sekolah
 * @property string|null $nama_sekolah
 * @property string|null $kelas
 * @property string $nama_ayah
 * @property string $telp_ayah
 * @property string $nama_ibu
 * @property string $telp_ibu
 * @property string|null $foto_murid_path
 * @property string|null $foto_murid_mime
 * @property int|null $foto_murid_size
 * @property string|null $akta_kelahiran_path
 * @property string|null $akta_kelahiran_mime
 * @property int|null $akta_kelahiran_size
 * @property string|null $kartu_keluarga_path
 * @property string|null $kartu_keluarga_mime
 * @property int|null $kartu_keluarga_size
 * @property string $status
 * @property bool $sudah_bayar_formulir
 * @property string|null $bukti_pendaftaran
 * @property string|null $bukti_pendaftaran_path
 * @property string|null $bukti_pendaftaran_mime
 * @property int|null $bukti_pendaftaran_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereAktaKelahiranMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereAktaKelahiranPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereAktaKelahiranSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereAsalSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereBuktiPendaftaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereBuktiPendaftaranMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereBuktiPendaftaranPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereBuktiPendaftaranSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereFotoMuridMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereFotoMuridPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereFotoMuridSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereKartuKeluargaMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereKartuKeluargaPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereKartuKeluargaSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNamaMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNamaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereNoPendaftaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereSudahBayarFormulir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereTelpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereTelpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendaftar whereUpdatedAt($value)
 */
	class Pendaftar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

