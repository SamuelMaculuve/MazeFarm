<x-guest-layout>

    <div class="mb-8">
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-700 transition-colors mb-6">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            {{ app()->getLocale() === 'pt' ? 'Voltar ao login' : 'Back to sign in' }}
        </a>

        <h1 class="text-3xl font-bold text-gray-900 mb-1.5">
            {{ app()->getLocale() === 'pt' ? 'Recuperar acesso' : 'Reset password' }}
        </h1>
        <p class="text-sm text-gray-400">
            {{ app()->getLocale() === 'pt'
                ? 'Introduza o seu email e enviaremos um link para redefinir a palavra-passe.'
                : 'Enter your email and we will send you a password reset link.' }}
        </p>
    </div>

    <x-auth-session-status
        class="mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-3"
        :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('common.email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus
                   placeholder="{{ app()->getLocale() === 'pt' ? 'o.seu@email.com' : 'your@email.com' }}"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('email')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit"
                class="w-full flex items-center justify-between px-5 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl
                       hover:bg-gray-700 active:scale-[0.99] transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 group mt-2">
            <span>{{ app()->getLocale() === 'pt' ? 'Enviar link de recuperação' : 'Send reset link' }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>
    </form>

</x-guest-layout>
