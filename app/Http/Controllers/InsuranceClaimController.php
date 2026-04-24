<?php

namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\InsuranceCompany;
use App\Services\AuditService;
use Illuminate\Http\Request;

class InsuranceClaimController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request)
    {
        $claims = InsuranceClaim::with(['sale', 'insuranceCard.customer', 'insuranceCompany'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->company, fn($q) => $q->where('insurance_company_id', $request->company))
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $companies = InsuranceCompany::active()->orderBy('name')->get();
        $statuses  = InsuranceClaim::STATUSES;

        return view('insurance.claims.index', compact('claims', 'companies', 'statuses'));
    }

    public function show(InsuranceClaim $claim)
    {
        $claim->load(['sale.items.product', 'insuranceCard.customer', 'insuranceCompany']);
        return view('insurance.claims.show', compact('claim'));
    }

    public function updateStatus(Request $request, InsuranceClaim $claim)
    {
        $request->validate([
            'status'           => 'required|in:submitted,approved,paid,rejected',
            'amount_approved'  => 'nullable|numeric|min:0',
            'amount_paid'      => 'nullable|numeric|min:0',
            'rejection_reason' => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
        ]);

        $updates = ['status' => $request->status, 'notes' => $request->notes];

        if ($request->status === 'submitted')  $updates['submitted_at'] = now();
        if ($request->status === 'approved')   { $updates['approved_at'] = now(); $updates['amount_approved'] = $request->amount_approved; }
        if ($request->status === 'paid')       { $updates['paid_at'] = now(); $updates['amount_paid'] = $request->amount_paid; }
        if ($request->status === 'rejected')   $updates['rejection_reason'] = $request->rejection_reason;

        $claim->update($updates);
        $this->audit->log('claim_status_updated', $claim, [], $updates);

        return back()->with('success', __('insurance.claim_updated'));
    }
}
