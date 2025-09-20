@forelse($discounts as $index => $discount)
<tr>
    <td class="px-3">{{ ($discounts->currentPage() - 1) * $discounts->perPage() + $index + 1 }}</td>
    <td class="px-3">
        <div class="fw-bold">{{ $discount->name }}</div>
        @if($discount->description)
            <small class="text-muted">{{ $discount->description }}</small>
        @endif
    </td>
    <td class="px-3">
        @if($discount->type == 'percentage')
            <span class="badge bg-info">Persentase</span>
        @elseif($discount->type == 'fixed')
            <span class="badge bg-warning">Nominal</span>
        @else
            <span class="badge bg-secondary">{{ ucfirst($discount->type) }}</span>
        @endif
    </td>
    <td class="px-3">
        @if($discount->type == 'percentage')
            <span class="fw-bold text-success">{{ $discount->value }}%</span>
        @elseif($discount->type == 'fixed')
            <span class="fw-bold text-success">Rp {{ number_format($discount->value, 0, ',', '.') }}</span>
        @else
            <span class="fw-bold text-success">{{ $discount->value }}</span>
        @endif
    </td>
    <td class="px-3">
        @if($discount->target == 'uang_pangkal')
            Uang Pangkal
        @elseif($discount->target == 'spp')
            SPP
        @elseif($discount->target == 'multi_payment')
            Multi Payment
        @elseif($discount->target == 'all')
            Semua
        @else
            {{ ucfirst(str_replace('_', ' ', $discount->target)) }}
        @endif
    </td>
    <td class="px-3">
        <small class="text-muted">
            @if($discount->start_date && $discount->end_date)
                {{ \Carbon\Carbon::parse($discount->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($discount->end_date)->format('d M Y') }}
            @elseif($discount->start_date)
                Mulai {{ \Carbon\Carbon::parse($discount->start_date)->format('d M Y') }}
            @elseif($discount->end_date)
                Sampai {{ \Carbon\Carbon::parse($discount->end_date)->format('d M Y') }}
            @else
                Sepanjang tahun
            @endif
        </small>
    </td>
    <td class="px-3">
        @if($discount->status == 'active')
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-secondary">Tidak Aktif</span>
        @endif
    </td>
    <td class="px-3 text-center">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.settings.discounts.edit', $discount->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i>
            </a>
            <button class="btn btn-outline-danger btn-sm" onclick="deleteDiscount({{ $discount->id }})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-5">
        <div class="text-muted">
            <i class="bi bi-inbox display-6"></i>
            <div class="mt-2">Belum ada data diskon</div>
            <a href="{{ route('admin.settings.discounts.create') }}" class="btn btn-primary btn-sm mt-3">
                <i class="bi bi-plus"></i> Tambah Diskon
            </a>
        </div>
    </td>
</tr>
@endforelse
