<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-secondary uppercase tracking-wide']) }}>
    {{ $slot }}
</button>
