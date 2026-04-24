<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('reports.index')" :label="__('reports.reports')" /></x-slot>
    <x-slot name="heading">{{ __('reports.insurance_report') }}</x-slot>

    <div class="space-y-4">
        <x-card class="px-5 py-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <select name="company" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('insurance.all_companies') }}</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" @selected(request('company') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    @foreach(\App\Models\InsuranceClaim::STATUSES as $k => $v)
                        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </x-card>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-stat-card :label="__('insurance.total_claimed')" :value="number_format($summary['total_claimed'] ?? 0, 2) . ' MT'" />
            <x-stat-card :label="__('insurance.total_approved')" :value="number_format($summary['total_approved'] ?? 0, 2) . ' MT'" />
            <x-stat-card :label="__('insurance.total_paid')" :value="number_format($summary['total_paid'] ?? 0, 2) . ' MT'" />
            <x-stat-card :label="__('insurance.pending_claims')" :value="$summary['pending_count'] ?? 0" color="yellow" />
        </div>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('reports.by_insurer') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.insurer') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('reports.num_claims') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.total_claimed') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.total_approved') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.total_paid') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($byCompany as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $row->company_name }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $row->count }}</td>
                            <td class="px-5 py-3 text-right text-gray-900">{{ number_format((float)$row->total_claimed, 2) }} MT</td>
                            <td class="px-5 py-3 text-right text-purple-700">{{ number_format((float)$row->total_approved, 2) }} MT</td>
                            <td class="px-5 py-3 text-right font-semibold text-green-700">{{ number_format((float)$row->total_paid, 2) }} MT</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('insurance.claims') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.claim_number') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.insurer') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.customer') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.amount_claimed') }}</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($claims as $claim)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $claim->claim_number }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $claim->insuranceCompany->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $claim->insuranceCard->customer->name }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($claim->amount_claimed, 2) }} MT</td>
                            <td class="px-5 py-3 text-center">
                                @php $sc = ['pending'=>'yellow','submitted'=>'blue','approved'=>'purple','paid'=>'green','rejected'=>'red'][$claim->status] ?? 'gray'; @endphp
                                <x-badge :color="$sc">{{ \App\Models\InsuranceClaim::STATUSES[$claim->status] }}</x-badge>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('insurance.claims.show', $claim) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.view') }}</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($claims->hasPages()) <div class="px-5 py-4 border-t border-gray-100">{{ $claims->links() }}</div> @endif
        </x-card>
    </div>
</x-app-layout>
