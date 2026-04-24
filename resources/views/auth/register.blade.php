<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1.5">
            {{ app()->getLocale() === 'pt' ? 'Criar conta' : 'Create account' }}
        </h1>
        <p class="text-sm text-gray-400">
            {{ app()->getLocale() === 'pt'
                ? 'Preencha os dados abaixo para criar o seu acesso.'
                : 'Fill in the details below to create your access.' }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('common.name') }}
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="{{ app()->getLocale() === 'pt' ? 'Nome completo' : 'Full name' }}"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('name')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('common.email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="{{ app()->getLocale() === 'pt' ? 'o.seu@email.com' : 'your@email.com' }}"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('email')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ app()->getLocale() === 'pt' ? 'Palavra-passe' : 'Password' }}
            </label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password" placeholder="••••••••"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('password')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ app()->getLocale() === 'pt' ? 'Confirmar Palavra-passe' : 'Confirm Password' }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password" placeholder="••••••••"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full flex items-center justify-between px-5 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl
                       hover:bg-gray-700 active:scale-[0.99] transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 group mt-2">
            <span>{{ app()->getLocale() === 'pt' ? 'Criar conta' : 'Create account' }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>

        <p class="text-center text-xs text-gray-400 pt-1">
            {{ app()->getLocale() === 'pt' ? 'Já tem conta?' : 'Already have an account?' }}
            <a href="{{ route('login') }}" class="text-gray-700 font-medium hover:text-gray-900 transition-colors">
                {{ app()->getLocale() === 'pt' ? 'Entrar' : 'Sign in' }}
            </a>
        </p>
    </form>

</x-guest-layout>
