<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SnippetController extends Controller
{
    public function index()
    {
        $snippets = Snippet::where('expires_at', '>', now())
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact('snippets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:60000',
            'language' => 'required|in:php,javascript,python,java,csharp,cpp,ruby,go,swift,kotlin',
            'expires' => 'nullable|in:1d,3d,7d',
        ]);

        $expiresAt = match ($validated['expires'] ?? '1d') {
            '1d' => now()->addDay(),
            '3d' => now()->addDays(3),
            '7d' => now()->addWeek(),
            default => now()->addDay(),
        };

        $snippet = Snippet::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug(),
            'code' => $validated['code'],
            'language' => $validated['language'],
            'expires_at' => $expiresAt,
        ]);

        return redirect()
            ->route('snippets.show', $snippet->slug)
            ->with('created', true);
    }

    public function show(string $slug)
    {
        $snippet = Snippet::where('slug', $slug)->firstOrFail();

        if ($snippet->expires_at?->isPast()) {
            abort(404);
        }

        $snippet->increment('views');

        return view('show', compact('snippet'));
    }

    public function raw(string $slug)
    {
        $snippet = Snippet::where('slug', $slug)->firstOrFail();

        if ($snippet->expires_at?->isPast()) {
            abort(404);
        }

        return response($snippet->code)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('X-Robots-Tag', 'noindex');
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::random(8);
        } while (Snippet::where('slug', $slug)->exists());

        return $slug;
    }
}
