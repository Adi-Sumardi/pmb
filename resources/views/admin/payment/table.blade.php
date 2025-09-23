<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-gray-800">Riwayat Pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-3">No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-end pe-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftars as $pendaftar)
                    <tr>
                        <!-- No Pendaftaran -->
                        <td class="ps-3">
                            <span class="fw-medium">{{ $pendaftar->no_pendaftaran }}</span>
                        </td>

                        <!-- Nama Pendaftar -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2 bg-light text-secondary">
                                    {{ strtoupper(substr($pendaftar->nama_murid, 0, 1)) }}
                                </div>
                                <div class="fw-medium">{{ $pendaftar->nama_murid }}</div>
                            </div>
                        </td>

                        <!-- Unit -->
                        <td>
                            @php
                                $unitBadgeClass = [
                                    'TK' => 'bg-info bg-opacity-10 text-info',
                                    'SD' => 'bg-success bg-opacity-10 text-success',
                                    'SMP' => 'bg-warning bg-opacity-10 text-warning',
                                    'SMA' => 'bg-danger bg-opacity-10 text-danger',
                                ][$pendaftar->unit] ?? 'bg-secondary bg-opacity-10 text-secondary';
                            @endphp
                            <span class="badge {{ $unitBadgeClass }}">{{ $pendaftar->unit }}</span>
                        </td>

                        <!-- Status -->
                        <td>
                            @if($pendaftar->sudah_bayar_formulir)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle-fill me-1 small"></i>Lunas
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-clock me-1 small"></i>Menunggu
                                </span>
                            @endif
                        </td>

                        <!-- Nominal -->
                        <td class="text-end fw-medium">
                            @php
                                $formulirAmount = match($pendaftar->unit) {
                                    'RA Sakinah' => 100000,
                                    'PG Sakinah' => 400000,
                                    'TKIA 13' => 450000,
                                    'SDIA 13', 'SD Islam Al Azhar 13 - Rawamangun' => 550000,
                                    'SMPIA 12' => 550000,
                                    'SMPIA 55' => 550000,
                                    'SMAIA 33', 'SMA Islam Al Azhar 33 - Jatimakmur' => 550000,
                                    default => $pendaftar->payment_amount ?? 0
                                };
                            @endphp
                            Rp {{ number_format($formulirAmount, 0, ',', '.') }}
                        </td>

                        <!-- Tanggal -->
                        <td class="text-end pe-3 text-secondary">
                            @if($pendaftar->latestPayment && $pendaftar->latestPayment->paid_at)
                                {{ $pendaftar->latestPayment->paid_at->format('d M Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="py-5">
                                <div class="mb-3 text-muted">
                                    <i class="bi bi-inbox fs-3"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Tidak Ada Data Pembayaran</h6>
                                <p class="text-muted small">Belum ada riwayat pembayaran yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
    }

    .table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }

    @media (max-width: 767.98px) {
        .table-responsive {
            border-radius: 0.375rem;
        }

        .avatar-circle {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
    }
</style>
