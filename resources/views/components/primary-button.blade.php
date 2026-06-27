<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-black border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-zinc-900 focus:bg-zinc-900 active:bg-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
