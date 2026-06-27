<x-guest-layout>
    <div class="flex justify-center mb-6">
        <div class="w-20 h-20 bg-white border border-slate-150 rounded-2xl flex items-center justify-center p-2 shadow-sm">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="max-h-full max-w-full object-contain">
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 text-center tracking-tight mb-1">
        Confirm Password
    </h2>
    <p class="text-sm text-gray-500 text-center mb-6">
        Confirm your password for this secure area
    </p>

    <div class="mb-4 text-sm text-gray-600 leading-relaxed text-center">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
