<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInsuranceCardRequest;
use App\Models\Customer;
use App\Models\InsuranceCard;
use App\Models\InsuranceCompany;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsuranceCardController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request)
    {
        $cards = InsuranceCard::with(['customer', 'insuranceCompany'])
            ->when($request->search, fn($q) => $q->where('card_number', 'like', "%{$request->search}%")
                ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$request->search}%")))
            ->when($request->company, fn($q) => $q->where('insurance_company_id', $request->company))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $companies = InsuranceCompany::active()->orderBy('name')->get();

        return view('insurance.cards.index', compact('cards', 'companies'));
    }

    public function create(Request $request)
    {
        $customers = Customer::active()->orderBy('name')->get();
        $companies = InsuranceCompany::active()->orderBy('name')->get();
        $selectedCustomer = $request->customer_id ? Customer::find($request->customer_id) : null;

        return view('insurance.cards.create', compact('customers', 'companies', 'selectedCustomer'));
    }

    public function store(StoreInsuranceCardRequest $request)
    {
        $card = InsuranceCard::create(array_merge($request->validated(), ['created_by' => Auth::id()]));
        $this->audit->log('created', $card);

        return redirect()->route('insurance.cards.index')->with('success', __('common.saved'));
    }

    public function edit(InsuranceCard $card)
    {
        $customers = Customer::active()->orderBy('name')->get();
        $companies = InsuranceCompany::active()->orderBy('name')->get();
        return view('insurance.cards.edit', compact('card', 'customers', 'companies'));
    }

    public function update(StoreInsuranceCardRequest $request, InsuranceCard $card)
    {
        $data = $request->validated();
        $data['expiry_date']  = $data['expiry_date'] ?? $data['valid_until'] ?? null;
        $card->update($data);
        $this->audit->log('updated', $card);
        return redirect()->route('insurance.cards.index')->with('success', __('common.updated'));
    }

    public function destroy(InsuranceCard $card)
    {
        $card->delete();
        return redirect()->route('insurance.cards.index')->with('success', __('common.deleted'));
    }
}
