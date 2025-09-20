<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UangPangkalSetting;
use Illuminate\Http\Request;

class UangPangkalController extends Controller
{
    /**
     * Display a listing of the Uang Pangkal.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = UangPangkalSetting::query();

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('school_level', 'ILIKE', "%{$search}%")
                  ->orWhere('school_origin', 'ILIKE', "%{$search}%")
                  ->orWhere('status', 'ILIKE', "%{$search}%");
            });
        }

        $uangPangkalSettings = $query->orderBy('created_at', 'desc')->paginate(10);
        $uangPangkalSettings->appends($request->query());

        // Statistics (optimized with direct DB queries)
        $totalUangPangkal = UangPangkalSetting::count();
        $activeUangPangkal = UangPangkalSetting::where('status', 'active')->count();
        $inactiveUangPangkal = UangPangkalSetting::where('status', 'inactive')->count();
        $avgAmount = UangPangkalSetting::avg('amount') ?? 0;

        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.settings.uang-pangkal.partials.uang-pangkal-table', compact('uangPangkalSettings'))->render();
            $pagination = $uangPangkalSettings->links()->toHtml();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'statistics' => [
                    'totalUangPangkal' => $totalUangPangkal,
                    'activeUangPangkal' => $activeUangPangkal,
                    'inactiveUangPangkal' => $inactiveUangPangkal,
                    'avgAmount' => $avgAmount
                ]
            ]);
        }

        return view('admin.settings.uang-pangkal.index', compact(
            'uangPangkalSettings',
            'totalUangPangkal',
            'activeUangPangkal',
            'inactiveUangPangkal',
            'avgAmount',
            'search'
        ));
    }

    /**
     * Show the form for creating a new Uang Pangkal.
     */
    public function create()
    {
        return view('admin.settings.uang-pangkal.create');
    }

    /**
     * Store a newly created Uang Pangkal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'school_origin' => 'required|in:internal,external',
            'academic_year' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        UangPangkalSetting::create($validated);

        return redirect()->route('admin.settings.uang-pangkal.index')
                        ->with('success', 'Uang Pangkal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified Uang Pangkal.
     */
    public function edit(UangPangkalSetting $uangPangkal)
    {
        return view('admin.settings.uang-pangkal.edit', compact('uangPangkal'));
    }

    /**
     * Update the specified Uang Pangkal in storage.
     */
    public function update(Request $request, UangPangkalSetting $uangPangkal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'school_origin' => 'required|in:internal,external',
            'academic_year' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $uangPangkal->update($validated);

        return redirect()->route('admin.settings.uang-pangkal.index')
                        ->with('success', 'Uang Pangkal berhasil diperbarui!');
    }

    /**
     * Remove the specified Uang Pangkal from storage.
     */
    public function destroy(UangPangkalSetting $uangPangkal)
    {
        $uangPangkal->delete();

        return response()->json(['success' => true, 'message' => 'Uang Pangkal berhasil dihapus!']);
    }
}
