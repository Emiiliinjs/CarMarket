<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger uppercase tracking-wide']) }}>
    {{ $slot }}
</button>
