<x-layout title="Too Many Requests - SnipShare">
    <div class="card error-card">
        <p class="error-code">429</p>
        <h1 class="page-title">Slow down</h1>
        <p class="muted">You're saving snippets too fast. Wait a minute and try again.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
    </div>
</x-layout>
