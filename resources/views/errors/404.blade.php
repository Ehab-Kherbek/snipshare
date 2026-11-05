<x-layout title="Not Found - SnipShare">
    <div class="card error-card">
        <p class="error-code">404</p>
        <h1 class="page-title">Snippet not found</h1>
        <p class="muted">This snippet doesn't exist or has expired.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Create a new snippet</a>
    </div>
</x-layout>
