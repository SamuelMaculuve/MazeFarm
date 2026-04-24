<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1.5">
            {{ app()->getLocale() === 'pt' ? 'Nova palavra-passe' : 'New password' }}
        </h1>
        <p class="text-sm text-gray-400">
            {{ app()->getLocale() === 'pt'
                ? 'Escolha uma nova palavra-passe segura para a sua conta.'
                : 'Choose a new secure password for your account.' }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('common.email') }}
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username"
                   class="w-full px-4 py-3 text-sm text-gray-900 placeholder-gray-300 border border-gray-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-900 hover:border-gray-300 transition" />
            @error('email')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                {{ app()->getLocale() === 'pt' ? 'Nova Palavra-passe' : 'New Password' }}
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
            <span>{{ app()->getLocale() === 'pt' ? 'Redefinir palavra-passe' : 'Reset password' }}</span>
            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>
    </form>

</x-guest-layout>
