<img
    src="{{ asset('images/logo.png') }}"
    {{ $attributes->merge([
        'class' => 'h-8 w-auto',
        'alt' => config('app.name', 'CarMarket') . ' logo',
        'loading' => 'lazy',
    ]) }}
>
