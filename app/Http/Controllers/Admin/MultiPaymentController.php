<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MultiPayment;
use Illuminate\Http\Request;

class MultiPaymentController extends Controller
{
    /**
     * Display a listing of the Multi Payments.
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');

        $query = MultiPayment::orderBy('created_at', 'desc');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $multiPayments = $query->paginate(10);

        // Statistics
        $totalMultiPayments = MultiPayment::count();
        $mandatoryMultiPayments = MultiPayment::where('is_mandatory', true)->count();
        $optionalMultiPayments = MultiPayment::where('is_mandatory', false)->count();
        $activeMultiPayments = MultiPayment::where('status', 'active')->count();
        $avgAmount = MultiPayment::avg('amount') ?? 0;

        // Categories
        $categories = MultiPayment::select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        return view('admin.settings.multi-payments.index', compact(
            'multiPayments',
            'category',
            'totalMultiPayments',
            'mandatoryMultiPayments',
            'optionalMultiPayments',
            'activeMultiPayments',
            'avgAmount',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new Multi Payment.
     */
    public function create()
    {
        return view('admin.settings.multi-payments.create');
    }

    /**
     * Store a newly created Multi Payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'school_level' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'is_mandatory' => 'required|boolean',
            'academic_year' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        MultiPayment::create($validated);

        return redirect()->route('admin.settings.multi-payments.index')
                        ->with('success', 'Multi Payment berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified Multi Payment.
     */
    public function edit(MultiPayment $multiPayment)
    {
        return view('admin.settings.multi-payments.edit', compact('multiPayment'));
    }

    /**
     * Update the specified Multi Payment in storage.
     */
    public function update(Request $request, MultiPayment $multiPayment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'school_level' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'is_mandatory' => 'required|boolean',
            'academic_year' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $multiPayment->update($validated);

        return redirect()->route('admin.settings.multi-payments.index')
                        ->with('success', 'Multi Payment berhasil diperbarui!');
    }

    /**
     * Remove the specified Multi Payment from storage.
     */
    public function destroy(MultiPayment $multiPayment)
    {
        $multiPayment->delete();

        return response()->json(['success' => true, 'message' => 'Multi Payment berhasil dihapus!']);
    }
}
