<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppBulkSetting;
use Illuminate\Http\Request;

class SppBulkController extends Controller
{
    /**
     * Display a listing of the SPP Bulk Settings.
     */
    public function index(Request $request)
    {
        $query = SppBulkSetting::query();

        // Filter by period if specified
        if ($request->has('period') && $request->period !== 'all') {
            $query->where('months_count', $request->period);
        }

        $sppBulkSettings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $sppBulkSettings->appends($request->query());

        return view('admin.settings.spp-bulk.index', compact('sppBulkSettings'));
    }

    /**
     * Show the form for creating a new SPP Bulk Setting.
     */
    public function create()
    {
        return view('admin.settings.spp-bulk.create');
    }

    /**
     * Store a newly created SPP Bulk Setting in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'months_count' => 'required|in:3,6,12',
            'discount_percentage' => 'required|numeric|min:0|max:50',
            'minimum_months' => 'nullable|integer|min:1',
            'academic_year' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        SppBulkSetting::create($validated);

        return redirect()->route('admin.settings.spp-bulk.index')
                        ->with('success', 'Pengaturan SPP Bulk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified SPP Bulk Setting.
     */
    public function edit(SppBulkSetting $sppBulkSetting)
    {
        return view('admin.settings.spp-bulk.edit', compact('sppBulkSetting'));
    }

    /**
     * Update the specified SPP Bulk Setting in storage.
     */
    public function update(Request $request, SppBulkSetting $sppBulkSetting)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'months_count' => 'required|in:3,6,12',
            'discount_percentage' => 'required|numeric|min:0|max:50',
            'minimum_months' => 'nullable|integer|min:1',
            'academic_year' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $sppBulkSetting->update($validated);

        return redirect()->route('admin.settings.spp-bulk.index')
                        ->with('success', 'Pengaturan SPP Bulk berhasil diperbarui!');
    }

    /**
     * Remove the specified SPP Bulk Setting from storage.
     */
    public function destroy(SppBulkSetting $sppBulkSetting)
    {
        $sppBulkSetting->delete();

        return response()->json(['success' => true, 'message' => 'Pengaturan SPP Bulk berhasil dihapus!']);
    }
}
