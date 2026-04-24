<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\CreditSettlement;
use App\Models\Customer;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request)
    {
        $customers = Customer::when($request->search, fn($q) => $q->search($request->search))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create(array_merge($request->validated(), ['created_by' => Auth::id()]));
        $this->audit->log('created', $customer);

        return redirect()->route('customers.show', $customer)->with('success', __('common.saved'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['insuranceCards.insuranceCompany', 'creditSettlements']);

        $sales = $customer->sales()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customers.show', compact('customer', 'sales'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        $this->audit->log('updated', $customer);

        return redirect()->route('customers.show', $customer)->with('success', __('common.updated'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', __('common.deleted'));
    }

    public function settleCredit(Request $request, Customer $customer)
    {
        $request->validate([
            'amount'           => 'required|numeric|min:0.01|max:' . $customer->credit_balance,
            'payment_method'   => 'required|in:cash,mpesa,emola,card',
            'reference_number' => 'nullable|string|max:60',
        ]);

        CreditSettlement::create([
            'customer_id'      => $customer->id,
            'amount'           => $request->amount,
            'payment_method'   => $request->payment_method,
            'reference_number' => $request->reference_number,
            'created_by'       => Auth::id(),
        ]);

        $customer->decrement('credit_balance', $request->amount);
        $this->audit->log('credit_settled', $customer, [], ['amount' => $request->amount]);

        return back()->with('success', __('customers.credit_settled'));
    }
}
