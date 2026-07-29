@props([
    'disabled' => false,
    'error' => false,
])

<input
    @disabled($disabled)

    {{ $attributes->class([
        'form-control',
        'is-invalid' => $error,
    ]) }}
>