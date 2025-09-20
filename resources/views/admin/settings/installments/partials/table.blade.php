@forelse ($installments as $index => $installment)
<tr>
    <td class="align-middle">{{ $installments->firstItem() + $index }}</td>
    <td class="align-middle">
        <div>
            <div class="fw-semibold">{{ $installment->name }}</div>
            @if($installment->description)
                <small class="text-muted">{{ Str::limit($installment->description, 50) }}</small>
            @endif
        </div>
    </td>
    <td class="align-middle">
        @php
            $levelColors = [
                'tk' => 'success',
                'sd' => 'info',
                'smp' => 'warning',
                'sma' => 'primary'
            ];
            $color = $levelColors[strtolower($installment->school_level)] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $color }}">{{ strtoupper($installment->school_level) }}</span>
    </td>
    <td class="align-middle">
        <div class="fw-semibold">{{ $installment->installment_count }}x</div>
        <small class="text-muted">{{ ucfirst($installment->payment_interval ?? 'monthly') }}</small>
    </td>
    <td class="align-middle">
        <div class="fw-semibold">{{ $installment->first_payment_percentage }}%</div>
        <small class="text-muted">Pembayaran awal</small>
    </td>
    <td class="align-middle">
        @if($installment->status == 'active')
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-warning">Nonaktif</span>
        @endif
    </td>
    <td class="align-middle">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.settings.installments.edit', $installment) }}"
               class="btn btn-sm btn-outline-warning"
               data-bs-toggle="tooltip"
               title="Edit Pengaturan">
                <i class="bi bi-pencil"></i>
            </a>
            <button type="button"
                    class="btn btn-sm btn-outline-danger delete-btn"
                    data-id="{{ $installment->id }}"
                    data-name="{{ $installment->name }}"
                    data-bs-toggle="tooltip"
                    title="Hapus Pengaturan">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-4">
        <div class="d-flex flex-column align-items-center">
            <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada pengaturan cicilan</h5>
            <p class="text-muted">Klik tombol "Tambah Pengaturan" untuk menambahkan data pertama</p>
            <a href="{{ route('admin.settings.installments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Pengaturan
            </a>
        </div>
    </td>
</tr>
@endforelse
