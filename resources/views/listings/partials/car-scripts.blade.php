@php
    $repository = app(\App\Support\CarModelRepository::class);
    $dataForJson = $carData ?? null;

    if ($dataForJson instanceof \Illuminate\Support\Collection) {
        $dataForJson = $dataForJson->toArray();
    }

    if (! is_array($dataForJson) || $dataForJson === []) {
        $dataForJson = $repository->all();
    }
@endphp

<script type="application/json" id="car-models-data">
    {!! $repository->toJson($dataForJson) !!}
</script>
