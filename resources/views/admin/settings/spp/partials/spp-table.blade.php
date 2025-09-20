@forelse($sppSettings as $index => $sppSetting)
<tr>
    <td class="align-middle">{{ ($sppSettings->currentPage() - 1) * $sppSettings->perPage() + $index + 1 }}</td>
    <td class="align-middle">
        <div>
            <div class="fw-semibold">{{ $sppSetting->name }}</div>
            @if($sppSetting->academic_year)
                <small class="text-muted">Tahun {{ $sppSetting->academic_year }}</small>
            @endif
        </div>
    </td>
    <td class="align-middle">
        @if($sppSetting->school_level == 'sd')
            <span class="badge bg-primary">SD</span>
        @elseif($sppSetting->school_level == 'smp')
            <span class="badge bg-info">SMP</span>
        @elseif($sppSetting->school_level == 'sma')
            <span class="badge bg-warning">SMA</span>
        @elseif($sppSetting->school_level == 'tk')
            <span class="badge bg-success">TK</span>
        @else
            <span class="badge bg-secondary">{{ strtoupper($sppSetting->school_level) }}</span>
        @endif
    </td>
    <td class="align-middle">
        @if($sppSetting->school_origin == 'internal')
            <span class="badge bg-success">Internal</span>
        @elseif($sppSetting->school_origin == 'external')
            <span class="badge bg-warning">Eksternal</span>
        @else
            <span class="badge bg-secondary">{{ ucfirst($sppSetting->school_origin) }}</span>
        @endif
    </td>
    <td class="align-middle">
        <div class="fw-semibold">Rp {{ number_format($sppSetting->amount, 0, ',', '.') }}</div>
    </td>
    <td class="align-middle">
        @if($sppSetting->status == 'active')
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-warning">Nonaktif</span>
        @endif
    </td>
    <td class="align-middle">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.settings.spp.edit', $sppSetting->id) }}"
               class="btn btn-outline-primary btn-sm"
               data-bs-toggle="tooltip"
               data-bs-placement="top"
               title="Edit data SPP {{ $sppSetting->name }}">
                <i class="bi bi-pencil"></i>
            </a>
            <button class="btn btn-outline-danger btn-sm"
                    onclick="deleteSpp({{ $sppSetting->id }})"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Hapus data SPP {{ $sppSetting->name }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="text-muted">
            <i class="bi bi-inbox display-6"></i>
            <div class="mt-2">Belum ada data SPP</div>
            <a href="{{ route('admin.settings.spp.create') }}" class="btn btn-info btn-sm mt-3">
                <i class="bi bi-plus"></i> Tambah SPP
            </a>
        </div>
    </td>
</tr>
@endforelse