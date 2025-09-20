<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of discounts.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Discount::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                  ->orWhere('description', 'ILIKE', '%' . $search . '%')
                  ->orWhere('value', 'ILIKE', '%' . $search . '%')
                  ->orWhere('type', 'ILIKE', '%' . $search . '%')
                  ->orWhere('status', 'ILIKE', '%' . $search . '%');
            });
        }

        $discounts = $query->paginate(10);

        // Statistics
        $totalDiscounts = Discount::count();
        $activeDiscounts = Discount::where('status', 'active')->count();
        $inactiveDiscounts = Discount::where('status', 'inactive')->count();
        $totalDiscountValue = Discount::where('type', 'fixed')->sum('value');

        // Preserve search in pagination
        $discounts->appends($request->only(['search']));

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.discounts.partials.discount-table', compact('discounts'))->render(),
                'pagination' => $discounts->links()->toHtml(),
                'statistics' => [
                    'totalDiscounts' => $totalDiscounts,
                    'activeDiscounts' => $activeDiscounts,
                    'inactiveDiscounts' => $inactiveDiscounts,
                    'totalDiscountValue' => $totalDiscountValue
                ]
            ]);
        }

        return view('admin.settings.discounts.index', compact(
            'discounts',
            'totalDiscounts',
            'activeDiscounts',
            'inactiveDiscounts',
            'totalDiscountValue'
        ));
    }

        /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        return view('admin.settings.discounts.create');
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'target' => 'required|in:uang_pangkal,spp,multi_payment,all',
            'school_level' => 'nullable|string',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_usage' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'conditions' => 'nullable|array'
        ]);

        Discount::create($validated);

        return redirect()->route('admin.settings.discounts.index')
                        ->with('success', 'Diskon berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount)
    {
        // Debug to check if discount is loaded correctly
        if (!$discount->exists) {
            abort(404, 'Discount not found');
        }

        return view('admin.settings.discounts.edit', compact('discount'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'target' => 'required|in:uang_pangkal,spp,multi_payment,all',
            'school_level' => 'nullable|string',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_usage' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'conditions' => 'nullable|array'
        ]);

        $discount->update($validated);

        return redirect()->route('admin.settings.discounts.index')
                        ->with('success', 'Diskon berhasil diperbarui.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Diskon berhasil dihapus.'
        ]);
    }
}
