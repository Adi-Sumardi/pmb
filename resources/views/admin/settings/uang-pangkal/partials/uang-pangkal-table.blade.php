@forelse ($uangPangkalSettings as $index => $uangPangkal)
<tr>
    <td class="align-middle">{{ $uangPangkalSettings->firstItem() + $index }}</td>
    <td class="align-middle">
        <div>
            <div class="fw-semibold">{{ $uangPangkal->name }}</div>
            @if($uangPangkal->academic_year)
                <small class="text-muted">Tahun {{ $uangPangkal->academic_year }}</small>
            @endif
        </div>
    </td>
    <td class="align-middle">
        @if($uangPangkal->school_level == 'SD')
            <span class="badge bg-primary">{{ $uangPangkal->school_level }}</span>
        @elseif($uangPangkal->school_level == 'SMP')
            <span class="badge bg-info">{{ $uangPangkal->school_level }}</span>
        @elseif($uangPangkal->school_level == 'SMA')
            <span class="badge bg-warning">{{ $uangPangkal->school_level }}</span>
        @else
            <span class="badge bg-secondary">{{ $uangPangkal->school_level }}</span>
        @endif
    </td>
    <td class="align-middle">
        @if($uangPangkal->school_origin == 'internal')
            <span class="badge bg-success">Internal</span>
        @else
            <span class="badge bg-warning">Eksternal</span>
        @endif
    </td>
    <td class="align-middle">
        <div class="fw-semibold">Rp {{ number_format($uangPangkal->amount, 0, ',', '.') }}</div>
    </td>
    <td class="align-middle">
        @if($uangPangkal->status == 'active')
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-warning">Nonaktif</span>
        @endif
    </td>
    <td class="align-middle">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.settings.uang-pangkal.edit', $uangPangkal) }}"
               class="btn btn-sm btn-outline-warning"
               data-bs-toggle="tooltip"
               title="Edit Uang Pangkal">
                <i class="bi bi-pencil"></i>
            </a>
            <button type="button"
                    class="btn btn-sm btn-outline-danger delete-btn"
                    data-id="{{ $uangPangkal->id }}"
                    data-name="{{ $uangPangkal->name }}"
                    data-bs-toggle="tooltip"
                    title="Hapus Uang Pangkal">
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
            <h5 class="text-muted">Belum ada data uang pangkal</h5>
            <p class="text-muted">Klik tombol "Tambah Uang Pangkal" untuk menambahkan data pertama</p>
            <a href="{{ route('admin.settings.uang-pangkal.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>Tambah Uang Pangkal
            </a>
        </div>
    </td>
</tr>
@endforelse
