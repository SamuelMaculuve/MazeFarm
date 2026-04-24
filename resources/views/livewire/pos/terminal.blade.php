<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 h-[calc(100vh-10rem)]">

    {{-- ===== LEFT: Product search + Cart ===== --}}
    <div class="lg:col-span-3 flex flex-col gap-4 overflow-hidden">

        @if($saleCompleted)
        {{-- Sale success screen --}}
        <div class="flex-1 flex flex-col items-center justify-center bg-white rounded-2xl shadow-sm p-8 text-center">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('pos.sale_completed') }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ __('pos.sale_number') }}: #{{ $lastSaleId }}</p>
            <div class="flex gap-3">
                <a href="{{ route('pos.sales.show', $lastSaleId) }}" target="_blank"
                   class="px-4 py-2 border border-gray-200 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    {{ __('pos.view_receipt') }}
                </a>
                <button wire:click="newSale" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                    {{ __('pos.new_sale') }}
                </button>
            </div>
        </div>
        @elseif($showPaymentStep)
        {{-- ===== PAYMENT STEP ===== --}}
        <div class="flex-1 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                <button wire:click="$set('showPaymentStep', false)" class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h3 class="text-sm font-semibold text-gray-900">{{ __('pos.payment') }}</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-5 space-y-4">
                @if($this->cartInsuranceTotal > 0)
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700 font-medium">{{ __('pos.insurance_covers') }}</span>
                        <span class="font-bold text-blue-900">{{ number_format($this->cartInsuranceTotal, 2) }} MT</span>
                    </div>
                    @if($insuranceCardId)
                    <div class="mt-2">
                        <x-input-label for="auth_code" :value="__('pos.auth_code')" />
                        <input type="text" wire:model="authCode" id="auth_code" placeholder="{{ __('pos.auth_code_placeholder') }}"
                               class="mt-1 w-full text-sm border border-blue-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-400 bg-white">
                    </div>
                    @endif
                </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-900">{{ __('pos.customer_pays') }}: <span class="text-gray-700">{{ number_format($this->cartCustomerTotal, 2) }} MT</span></p>
                        <button wire:click="addPaymentLine" type="button" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">+ {{ __('pos.add_payment') }}</button>
                    </div>

                    <div class="space-y-2">
                        @foreach($payments as $i => $payment)
                        <div class="flex items-center gap-2">
                            <select wire:model="payments.{{ $i }}.method"
                                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400 flex-shrink-0">
                                @foreach(\App\Models\SalePayment::METHODS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.01" wire:model="payments.{{ $i }}.amount"
                                   class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400" placeholder="0.00" />
                            @if(in_array($payments[$i]['method'], ['mpesa','emola','card']))
                            <input type="text" wire:model="payments.{{ $i }}.reference" placeholder="{{ __('pos.reference') }}"
                                   class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400" />
                            @endif
                            @if(count($payments) > 1)
                            <button wire:click="removePaymentLine({{ $i }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">×</button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" :value="__('common.notes')" />
                    <textarea wire:model="notes" id="notes" rows="2"
                              class="mt-1 w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400 resize-none"></textarea>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                @if($errorMessage)
                <p class="text-sm text-red-600 mb-3">{{ $errorMessage }}</p>
                @endif
                <button wire:click="completeSale" wire:loading.attr="disabled"
                        class="w-full py-3 bg-gray-900 text-white font-semibold text-sm rounded-xl hover:bg-gray-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>{{ __('pos.complete_sale') }}</span>
                    <span wire:loading>{{ __('pos.processing') }}…</span>
                </button>
            </div>
        </div>
        @else
        {{-- ===== MAIN TERMINAL ===== --}}
        {{-- Product Search --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="productSearch"
                       placeholder="{{ __('pos.search_product') }}"
                       class="w-full pl-10 pr-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 bg-gray-50"
                       autofocus />
            </div>

            @if(strlen($productSearch) >= 2)
            <div class="mt-2 border border-gray-100 rounded-xl overflow-hidden max-h-64 overflow-y-auto">
                @forelse($this->productResults as $p)
                <button type="button" @mousedown.prevent wire:click="addProduct({{ $p->id }})"
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors text-left border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $p->generic_name }}</p>
                        @if($p->commercial_name) <p class="text-xs text-gray-400">{{ $p->commercial_name }}</p> @endif
                        <div class="flex gap-1 mt-0.5">
                            @if($p->requires_prescription) <x-badge color="blue" class="text-xs">MSR</x-badge> @endif
                            @if($p->stock <= 0) <x-badge color="red" class="text-xs">{{ __('pos.out_of_stock') }}</x-badge> @endif
                        </div>
                    </div>
                    <div class="text-right ml-4 flex-shrink-0">
                        <p class="text-sm font-bold text-gray-900">{{ number_format($p->sale_price, 2) }} MT</p>
                        <p class="text-xs text-gray-400">{{ __('pos.stock') }}: {{ $p->stock }}</p>
                    </div>
                </button>
                @empty
                <p class="px-4 py-3 text-sm text-gray-400">{{ __('common.no_records') }}</p>
                @endforelse
            </div>
            @endif
        </div>

        {{-- Cart --}}
        <div class="flex-1 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">{{ __('pos.cart') }} ({{ count($cart) }})</h3>
            </div>
            <div class="flex-1 overflow-y-auto divide-y divide-gray-50">
                @forelse($cart as $i => $item)
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ number_format($item['unit_price'], 2) }} MT</p>
                        @if($item['insurance_coverage_pct'] > 0)
                        <p class="text-xs text-blue-600">{{ __('pos.insurance') }}: {{ $item['insurance_coverage_pct'] }}%</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button wire:click="updateQuantity({{ $i }}, {{ $item['quantity'] - 1 }})"
                                class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-gray-700 font-bold text-sm">−</button>
                        <span class="w-8 text-center text-sm font-semibold text-gray-900">{{ $item['quantity'] }}</span>
                        <button wire:click="updateQuantity({{ $i }}, {{ $item['quantity'] + 1 }})"
                                class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-gray-700 font-bold text-sm">+</button>
                    </div>
                    <div class="text-right flex-shrink-0 min-w-[80px]">
                        <p class="text-sm font-bold text-gray-900">{{ number_format($item['subtotal'], 2) }} MT</p>
                        @if($item['insurance_amount'] > 0)
                        <p class="text-xs text-blue-500">−{{ number_format($item['insurance_amount'], 2) }}</p>
                        @endif
                    </div>
                    <button wire:click="removeFromCart({{ $i }})" class="w-6 h-6 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors text-sm">×</button>
                </div>
                @empty
                <div class="flex-1 flex items-center justify-center py-16">
                    <p class="text-sm text-gray-400">{{ __('pos.cart_empty_hint') }}</p>
                </div>
                @endforelse
            </div>

            @if($errorMessage && !$showPaymentStep)
            <div class="px-5 py-2 bg-red-50 border-t border-red-100">
                <p class="text-xs text-red-600">{{ $errorMessage }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ===== RIGHT PANEL: Customer + Summary ===== --}}
    @unless($saleCompleted)
    <div class="lg:col-span-2 flex flex-col gap-4 overflow-hidden">

        {{-- Customer --}}
        <div class="bg-white rounded-2xl shadow-sm p-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">{{ __('pos.customer') }}</h3>

            @if($this->selectedCustomer)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $this->selectedCustomer->name }}</p>
                    <p class="text-xs text-gray-400">{{ $this->selectedCustomer->phone }}</p>
                </div>
                <button wire:click="clearCustomer" class="text-xs text-gray-400 hover:text-red-500 transition-colors">×</button>
            </div>

            {{-- Insurance cards --}}
            @if($this->selectedCustomer->activeInsuranceCards->count())
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 mb-2">{{ __('pos.insurance_cards') }}</p>
                <div class="space-y-1.5">
                    @foreach($this->selectedCustomer->activeInsuranceCards as $card)
                    <button wire:click="{{ $insuranceCardId === $card->id ? 'clearInsuranceCard' : "selectInsuranceCard({$card->id})" }}"
                            @class(['w-full flex items-center justify-between px-3 py-2 rounded-xl border text-sm transition-colors text-left',
                                    'border-gray-900 bg-gray-900 text-white' => $insuranceCardId === $card->id,
                                    'border-gray-200 hover:bg-gray-50 text-gray-700' => $insuranceCardId !== $card->id])>
                        <div>
                            <p class="text-xs font-semibold">{{ $card->insuranceCompany->name }}</p>
                            <p @class(['text-xs', 'text-gray-400' => $insuranceCardId !== $card->id, 'text-gray-300' => $insuranceCardId === $card->id])>{{ $card->card_number }}</p>
                        </div>
                        <span @class(['text-xs font-bold', 'text-white' => $insuranceCardId === $card->id, 'text-gray-900' => $insuranceCardId !== $card->id])>
                            {{ $card->insuranceCompany->default_coverage_pct }}%
                        </span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            @else
            <div>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="customerSearch"
                           placeholder="{{ __('pos.search_customer') }}"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 bg-gray-50" />
                </div>
                @if(strlen($customerSearch) >= 2)
                <div class="mt-1 border border-gray-100 rounded-xl overflow-hidden max-h-40 overflow-y-auto">
                    @forelse($this->customerResults as $c)
                    <button type="button" @mousedown.prevent wire:click="selectCustomer({{ $c->id }})"
                            class="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors text-left border-b border-gray-50 last:border-0">
                        <div class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($c->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $c->name }}</p>
                            <p class="text-xs text-gray-400">{{ $c->phone }}</p>
                        </div>
                    </button>
                    @empty
                    <p class="px-3 py-2.5 text-sm text-gray-400">{{ __('common.no_records') }}</p>
                    @endforelse
                </div>
                @endif
            </div>
            @endif

            {{-- Prescription --}}
            @if(collect($cart)->contains('requires_prescription', true))
            <div class="mt-3 pt-3 border-t border-gray-100 space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="hasPrescription" class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm font-medium text-gray-900">{{ __('pos.has_prescription') }}</span>
                </label>
                @if($hasPrescription)
                <input type="text" wire:model="prescriptionNumber" placeholder="{{ __('pos.prescription_number') }}"
                       class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400" />
                <input type="text" wire:model="prescriptionDoctor" placeholder="{{ __('pos.prescription_doctor') }}"
                       class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400" />
                @endif
            </div>
            @endif
        </div>

        {{-- Order summary --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 flex-1 flex flex-col">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">{{ __('pos.summary') }}</h3>
            <div class="flex-1 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('pos.subtotal') }}</span>
                    <span class="font-medium text-gray-900">{{ number_format($this->cartTotal, 2) }} MT</span>
                </div>
                @if($this->cartInsuranceTotal > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-blue-600">{{ __('pos.insurance_coverage') }}</span>
                    <span class="font-medium text-blue-600">−{{ number_format($this->cartInsuranceTotal, 2) }} MT</span>
                </div>
                @endif
                <div class="pt-3 border-t border-gray-100 flex justify-between">
                    <span class="text-sm font-bold text-gray-900">{{ __('pos.total_to_pay') }}</span>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($this->cartCustomerTotal, 2) }} MT</span>
                </div>
            </div>
            <button wire:click="proceedToPayment"
                    @class(['mt-4 w-full py-3 text-sm font-semibold rounded-xl transition-colors',
                            'bg-gray-900 text-white hover:bg-gray-700' => count($cart) > 0,
                            'bg-gray-200 text-gray-400 cursor-not-allowed' => count($cart) === 0])
                    @disabled(count($cart) === 0)>
                {{ __('pos.proceed_to_payment') }} →
            </button>
        </div>
    </div>
    @endunless
</div>
