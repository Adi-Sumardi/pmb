<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the Installment Settings.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Installment::orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('school_level', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        $installments = $query->paginate(10);
        $installments->appends($request->query());

        // Statistics
        $totalInstallments = Installment::count();
        $activeInstallments = Installment::where('status', 'active')->count();
        $inactiveInstallments = Installment::where('status', 'inactive')->count();
        $avgInstallmentCount = Installment::avg('installment_count') ?? 0;
        $avgFirstPayment = Installment::avg('first_payment_percentage') ?? 0;

        // For AJAX search
        if ($request->ajax()) {
            $html = view('admin.settings.installments.partials.table', compact('installments'))->render();
            $pagination = $installments->links()->toHtml();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'statistics' => [
                    'totalInstallments' => $totalInstallments,
                    'activeInstallments' => $activeInstallments,
                    'inactiveInstallments' => $inactiveInstallments,
                    'avgInstallmentCount' => $avgInstallmentCount,
                    'avgFirstPayment' => $avgFirstPayment
                ]
            ]);
        }

        return view('admin.settings.installments.index', compact(
            'installments',
            'search',
            'totalInstallments',
            'activeInstallments',
            'inactiveInstallments',
            'avgInstallmentCount',
            'avgFirstPayment'
        ));
    }

    /**
     * Show the form for creating a new Installment Setting.
     */
    public function create()
    {
        return view('admin.settings.installments.create');
    }

    /**
     * Store a newly created Installment Setting in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'installment_count' => 'required|integer|min:2|max:12',
            'first_payment_percentage' => 'required|numeric|min:10|max:90',
            'monthly_due_date' => 'nullable|integer|min:1|max:31',
            'late_fee_amount' => 'nullable|numeric|min:0',
            'grace_period_days' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        Installment::create($validated);

        return redirect()->route('admin.settings.installments.index')
                        ->with('success', 'Pengaturan cicilan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified Installment Setting.
     */
    public function edit(Installment $installment)
    {
        return view('admin.settings.installments.edit', compact('installment'));
    }

    /**
     * Update the specified Installment Setting in storage.
     */
    public function update(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_level' => 'required|string',
            'installment_count' => 'required|integer|min:2|max:12',
            'first_payment_percentage' => 'required|numeric|min:10|max:90',
            'monthly_due_date' => 'nullable|integer|min:1|max:31',
            'late_fee_amount' => 'nullable|numeric|min:0',
            'grace_period_days' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $installment->update($validated);

        return redirect()->route('admin.settings.installments.index')
                        ->with('success', 'Pengaturan cicilan berhasil diperbarui!');
    }

    /**
     * Remove the specified Installment Setting from storage.
     */
    public function destroy(Installment $installment)
    {
        $installment->delete();

        return response()->json(['success' => true, 'message' => 'Pengaturan cicilan berhasil dihapus!']);
    }
}
