<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInsuranceCompanyRequest;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCoverageRule;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsuranceCompanyController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request)
    {
        $companies = InsuranceCompany::when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->withCount('insuranceCards')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('insurance.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('insurance.companies.create');
    }

    public function store(StoreInsuranceCompanyRequest $request)
    {
        $company = InsuranceCompany::create(array_merge($request->validated(), ['created_by' => Auth::id()]));
        $this->audit->log('created', $company);

        return redirect()->route('insurance.companies.index')->with('success', __('common.saved'));
    }

    public function show(InsuranceCompany $company)
    {
        $company->load(['coverageRules.product', 'coverageRules.productCategory', 'insuranceCards.customer'])
                ->loadCount('insuranceCards');

        $products   = \App\Models\Product::active()->orderBy('generic_name')->get();
        $categories = \App\Models\ProductCategory::orderBy('name')->get();

        return view('insurance.companies.show', compact('company', 'products', 'categories'));
    }

    public function edit(InsuranceCompany $company)
    {
        return view('insurance.companies.edit', compact('company'));
    }

    public function update(StoreInsuranceCompanyRequest $request, InsuranceCompany $company)
    {
        $company->update($request->validated());
        $this->audit->log('updated', $company);

        return redirect()->route('insurance.companies.show', $company)->with('success', __('common.updated'));
    }

    public function destroy(InsuranceCompany $company)
    {
        $company->delete();
        return redirect()->route('insurance.companies.index')->with('success', __('common.deleted'));
    }

    public function storeCoverageRule(Request $request, InsuranceCompany $company)
    {
        $request->validate([
            'product_id'          => 'nullable|exists:products,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'requires_preauth'    => 'boolean',
            'notes'               => 'nullable|string',
        ]);

        $company->coverageRules()->create($request->only(
            'product_id', 'product_category_id', 'coverage_percentage', 'requires_preauth', 'notes'
        ));

        return back()->with('success', __('insurance.rule_saved'));
    }

    public function destroyCoverageRule(InsuranceCoverageRule $rule)
    {
        $rule->delete();
        return back()->with('success', __('common.deleted'));
    }
}
