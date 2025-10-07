@php
    $carData = $carModels ?? [];

    if (is_object($carData) && method_exists($carData, 'all')) {
        $carData = $carData->all();
    }

    if (! is_array($carData)) {
        $carData = [];
    }
@endphp

<script id="car-models-data" type="application/json">
    @json($carData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
</script>
