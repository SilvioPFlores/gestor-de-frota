@props(['messages'])

@if ($messages)
    @foreach ($messages as $message)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @endforeach
@endif