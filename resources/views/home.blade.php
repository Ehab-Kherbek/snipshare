<x-layout>
    <div class="home-grid">
        <section class="card">
            <h1 class="page-title">Share a code snippet</h1>

            @if ($errors->any())
                <ul class="errors">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('snippets.store') }}" method="POST" class="snippet-form">
                @csrf

                <label for="title">Title</label>
                <input id="title" type="text" name="title" placeholder="Laravel route example" value="{{ old('title') }}">

                <label for="code">Code</label>
                <textarea id="code" name="code" placeholder="Paste your code here...">{{ old('code') }}</textarea>

                <div class="form-row">
                    <div>
                        <label for="language">Language</label>
                        <select id="language" name="language">
                            <option value="php" @selected(old('language') == 'php')>PHP</option>
                            <option value="javascript" @selected(old('language') == 'javascript')>JavaScript</option>
                            <option value="python" @selected(old('language') == 'python')>Python</option>
                            <option value="java" @selected(old('language') == 'java')>Java</option>
                            <option value="csharp" @selected(old('language') == 'csharp')>C#</option>
                            <option value="cpp" @selected(old('language') == 'cpp')>C++</option>
                            <option value="ruby" @selected(old('language') == 'ruby')>Ruby</option>
                            <option value="go" @selected(old('language') == 'go')>Go</option>
                            <option value="swift" @selected(old('language') == 'swift')>Swift</option>
                            <option value="kotlin" @selected(old('language') == 'kotlin')>Kotlin</option>
                        </select>
                    </div>

                    <div>
                        <label for="expires">Expires after</label>
                        <select id="expires" name="expires">
                            <option value="1d" @selected(old('expires') == '1d')>1 day</option>
                            <option value="3d" @selected(old('expires') == '3d')>3 days</option>
                            <option value="7d" @selected(old('expires') == '7d')>7 days</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save snippet</button>
            </form>
        </section>

        <aside class="card">
            <h2 class="section-title">Recent snippets</h2>

            @forelse ($snippets as $snippet)
                <a href="{{ route('snippets.show', $snippet->slug) }}" class="snippet-item">
                    <span class="snippet-title">{{ $snippet->title }}</span>
                    <span class="meta">
                        <span class="tag">{{ $snippet->language }}</span>
                        {{ $snippet->views }} {{ Str::plural('view', $snippet->views) }} · {{ $snippet->created_at->diffForHumans() }}
                    </span>
                </a>
            @empty
                <p class="muted">No snippets yet. Be the first.</p>
            @endforelse
        </aside>
    </div>
</x-layout>
