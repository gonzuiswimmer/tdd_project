@if ($errors->any())
    <ul class="error">
        @foreach ($errors as $error )
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
