<x-layout title="Server Error - SnipShare">
    <div class="card error-card">
        <p class="error-code">500</p>
        <h1 class="page-title">Something went wrong</h1>
        <p class="muted">That's on us, not you. Please try again in a moment.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
    </div>
</x-layout>
