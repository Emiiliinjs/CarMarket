<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary uppercase tracking-wide']) }}>
    {{ $slot }}
</button>
