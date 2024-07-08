<a href="{{ $href }}"
    class="p-2 text-sm md:py-2 md:px-4 md:text-base text-nowrap rounded-lg text-white hover:brightness-95 transition {{ $variant === 'danger' ? 'bg-red-500' : 'bg-teal-500' }}">
    {{ $slot }}
</a>
