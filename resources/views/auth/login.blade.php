<x-guest-layout>

    {{-- Title --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1.5">
            {{ app()->getLocale() === 'pt' ? 'Entrar' : 'Sign In' }}
        </h1>
        <p class="text-sm text-gray-400">
            {{ app()->getLocale() === 'pt'
                ? 'Bem-vindo. Introduza as suas credenciais para continuar.'
                : 'Welcome back. Enter your credentials to continue.' }}
        </p>
    </div>

    <x-auth-session-status class="mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('common.email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="{{ app()->getLocale() === 'pt' ? 'o.seu@email.com' : 'your@email.com' }}"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition
                          {{ $errors->has('email') ? 'border-red-300 bg-red-50 focus:ring-red-200 focus:border-red-400' : 'border-gray-200 bg-white hover:border-gray-300' }}" />
            @error('email')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    {{ app()->getLocale() === 'pt' ? 'Palavra-passe' : 'Password' }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-gray-400 hover:text-gray-700 transition-colors">
                        {{ app()->getLocale() === 'pt' ? 'Esqueceu?' : 'Forgot?' }}
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 transition
                          {{ $errors->has('password') ? 'border-red-300 bg-red-50 focus:ring-red-200 focus:border-red-400' : 'border-gray-200 bg-white hover:border-gray-300' }}" />
            @error('password')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember me --}}
        <label class="flex items-center gap-2.5 cursor-pointer group">
            <div class="relative flex-shrink-0">
                <input type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-1 focus:ring-gray-900 cursor-pointer">
            </div>
            <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors">
                {{ app()->getLocale() === 'pt' ? 'Manter sessão iniciada' : 'Keep me signed in' }}
            </span>
        </label>

        {{-- Submit button — styled like reference: full-width with arrow --}}
        <button type="submit"
                class="w-full flex items-center justify-between px-5 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl
                       hover:bg-gray-700 active:scale-[0.99] transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 group mt-2">
            <span>{{ app()->getLocale() === 'pt' ? 'Entrar na conta' : 'Sign in to account' }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>
    </form>

</x-guest-layout>
