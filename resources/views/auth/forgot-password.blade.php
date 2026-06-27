<x-guest-layout>
    <div class="flex justify-center mb-6">
        <div class="w-20 h-20 bg-white border border-slate-150 rounded-2xl flex items-center justify-center p-2 shadow-sm">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="max-h-full max-w-full object-contain">
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 text-center tracking-tight mb-1">
        Forgot Password
    </h2>
    <p class="text-sm text-gray-500 text-center mb-6">
        Reset your portal access password
    </p>

    <div class="mb-4 text-sm text-gray-600 leading-relaxed text-center">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
