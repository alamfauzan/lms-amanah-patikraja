<x-guest-layout>
    <div class="flex justify-center mb-6">
        <div class="w-20 h-20 bg-white border border-slate-150 rounded-2xl flex items-center justify-center p-2 shadow-sm">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="max-h-full max-w-full object-contain">
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 text-center tracking-tight mb-1">
        Verify Email
    </h2>
    <p class="text-sm text-gray-500 text-center mb-6">
        Please verify your email address to continue
    </p>

    <div class="mb-4 text-sm text-gray-600 leading-relaxed text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
