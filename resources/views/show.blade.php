<x-layout :title="$snippet->title . ' - SnipShare'">
    <x-slot:head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    </x-slot:head>

    @if (session('created'))
        <div class="alert">Snippet saved. Share the link below.</div>
    @endif

    <div class="card">
        <div class="snippet-header">
            <div>
                <h1 class="page-title">{{ $snippet->title }}</h1>
                <p class="meta">
                    <span class="tag">{{ $snippet->language }}</span>
                    {{ $snippet->views }} {{ Str::plural('view', $snippet->views) }} · {{ $snippet->created_at->diffForHumans() }}
                    @if ($snippet->expires_at)
                        · expires {{ $snippet->expires_at->diffForHumans() }}
                    @endif
                </p>
            </div>

            <div class="actions">
                <button class="btn" onclick="copyText(document.getElementById('code').textContent, this)">Copy</button>
                <a href="{{ route('snippets.raw', $snippet->slug) }}" target="_blank" class="btn">Raw</a>
            </div>
        </div>

        <pre class="code-block"><code id="code" class="language-{{ $snippet->language }}">{{ $snippet->code }}</code></pre>

        <div class="share">
            <label for="share-link">Share link</label>
            <div class="share-row">
                <input id="share-link" type="text" readonly value="{{ route('snippets.show', $snippet->slug) }}">
                <button class="btn btn-primary" onclick="copyText('{{ route('snippets.show', $snippet->slug) }}', this)">Copy link</button>
            </div>
        </div>
    </div>

    <script>
        hljs.highlightAll();

        function copyText(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const label = button.textContent;
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = label, 1500);
            });
        }
    </script>
</x-layout>
