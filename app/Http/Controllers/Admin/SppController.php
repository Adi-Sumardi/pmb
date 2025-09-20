<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppSetting;
use Illuminate\Http\Request;

class SppController extends Controller
{
    /**
     * Display a listing of the SPP.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = SppSetting::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                  ->orWhere('school_level', 'ILIKE', '%' . $search . '%')
                  ->orWhere('school_origin', 'ILIKE', '%' . $search . '%')
                  ->orWhere('amount', 'ILIKE', '%' . $search . '%')
                  ->orWhere('status', 'ILIKE', '%' . $search . '%')
                  ->orWhere('description', 'ILIKE', '%' . $search . '%');
            });
        }

        $sppSettings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics
        $totalSpp = SppSetting::count();
        $activeSpp = SppSetting::where('status', 'active')->count();
        $inactiveSpp = SppSetting::where('status', 'inactive')->count();
        $avgAmount = SppSetting::avg('amount');

        // Preserve search in pagination
        $sppSettings->appends($request->only(['search']));

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.spp.partials.spp-table', compact('sppSettings'))->render(),
                'pagination' => $sppSettings->links()->toHtml(),
                'statistics' => [
                    'totalSpp' => $totalSpp,
                    'activeSpp' => $activeSpp,
                    'inactiveSpp' => $inactiveSpp,
                    'avgAmount' => $avgAmount
                ]
            ]);
        }

        return view('admin.settings.spp.index', compact(
            'sppSettings',
            'totalSpp',
            'activeSpp',
            'inactiveSpp',
            'avgAmount'
        ));
    }

    /**
     * Show the form for creating a new SPP.
     */
    public function create()
    {
        return view('admin.settings.spp.create');
    }

    /**
     * Store a newly created SPP in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'school_origin' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'academic_year' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        SppSetting::create($validated);

        return redirect()->route('admin.settings.spp.index')
                        ->with('success', 'SPP berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified SPP.
     */
    public function edit(SppSetting $spp)
    {
        return view('admin.settings.spp.edit', compact('spp'));
    }

    /**
     * Update the specified SPP in storage.
     */
    public function update(Request $request, SppSetting $spp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'school_origin' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'academic_year' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $spp->update($validated);

        return redirect()->route('admin.settings.spp.index')
                        ->with('success', 'SPP berhasil diperbarui!');
    }

    /**
     * Remove the specified SPP from storage.
     */
    public function destroy(SppSetting $spp)
    {
        $spp->delete();

        return response()->json([
            'success' => true,
            'message' => 'SPP berhasil dihapus.'
        ]);
    }
}
