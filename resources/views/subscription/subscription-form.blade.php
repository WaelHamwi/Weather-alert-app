<form action="{{ $action }}" method="POST" class="mb-4">
    @csrf
    @if(isset($plan))
    <input type="hidden" name="plan" value="{{ $plan }}">
    @endif
    <button type="submit" class="{{ $btnClass }}">{{ $buttonText }}</button>
</form>