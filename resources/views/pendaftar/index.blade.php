<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertSuccess">
                        <strong>Sukses!</strong> Data pengajuan anggaran berhasil dibuat.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <script>
                        setTimeout(function() {
                            let alert = document.getElementById('alertSuccess');
                            if (alert) {
                                $(alert).alert('close');
                            }
                        }, 5000);
                    </script>
                @endif
                <div class="p-6 text-gray-900">
                    <div class="tabel-responsive overflow-scroll">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Calon Murid</th>
                                    <th>No Pendaftaran</th>
                                    <th>Unit Sekolah</th>
                                    <th>Juli 2026</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dt_pendaftars as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_murid }}</td>
                                        <td>{{ $item->no_pendaftaran }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->diff(\Carbon\Carbon::create(2026,7,1))->format('%y tahun %m bulan') }}</td>
                                        <td>
                                            <!-- File Akta Kelahiran -->
                                            <a href="{{ asset($item->akta_kelahiran_path) }}" target="_blank" class="me-2" title="Akta Kelahiran">
                                                <i class="bi bi-file-earmark-text-fill text-info fs-5"></i> Akta
                                            </a>

                                            <a href="{{ asset($item->kartu_keluarga_path) }}" target="_blank" class="me-2" title="Kartu Keluarga">
                                                <i class="bi bi-file-earmark-text-fill text-info fs-5"></i> KK
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-secondary">Pending</span>
                                            @elseif($item->status == 'diverifikasi')
                                                <span class="badge bg-success">Diverifikasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pendaftar.validasi', $item->id) }}" class="btn btn-sm btn-primary">Validasi</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
