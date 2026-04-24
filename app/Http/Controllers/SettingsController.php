<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $branches   = Branch::orderBy('name')->get();
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('settings.index', compact('branches', 'categories'));
    }

    public function toggleBranch(Branch $branch)
    {
        $branch->update(['is_active' => !$branch->is_active]);
        return back()->with('success', __('common.updated'));
    }

    public function storeBranch(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email',
            'nuit'    => 'nullable|string|max:20',
        ]);

        Branch::create($request->only('name', 'address', 'phone', 'email', 'nuit'));

        return back()->with('success', __('common.saved'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        ProductCategory::create($request->only('name', 'description'));

        return back()->with('success', __('common.saved'));
    }

    public function destroyCategory(ProductCategory $category)
    {
        abort_if($category->products()->exists(), 422, 'Cannot delete category with products.');
        $category->delete();
        return back()->with('success', __('common.deleted'));
    }
}
