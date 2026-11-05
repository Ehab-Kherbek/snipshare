<?php

namespace Tests\Feature;

use App\Models\Snippet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnippetTest extends TestCase
{
    use RefreshDatabase;

    private function createSnippet(array $attributes = []): Snippet
    {
        return Snippet::create(array_merge([
            'title' => 'Test snippet',
            'slug' => 'abc12345',
            'code' => 'echo "hello";',
            'language' => 'php',
            'expires_at' => now()->addDay(),
        ], $attributes));
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'My snippet',
            'code' => 'echo "hello";',
            'language' => 'php',
            'expires' => '1d',
        ], $overrides);
    }

    /* ---------- Home ---------- */

    public function test_home_page_loads(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Share a code snippet');
    }

    public function test_home_page_shows_active_snippets_and_hides_expired_ones(): void
    {
        $this->createSnippet(['title' => 'Still active', 'slug' => 'active01']);
        $this->createSnippet(['title' => 'Already expired', 'slug' => 'expired1', 'expires_at' => now()->subDay()]);

        $this->get('/')
            ->assertSee('Still active')
            ->assertDontSee('Already expired');
    }

    /* ---------- Store ---------- */

    public function test_snippet_is_saved_and_user_is_redirected_to_it(): void
    {
        $response = $this->post('/snippets', $this->validData());

        $snippet = Snippet::first();

        $this->assertNotNull($snippet);
        $this->assertSame('My snippet', $snippet->title);
        $this->assertSame(8, strlen($snippet->slug));
        $response->assertRedirect(route('snippets.show', $snippet->slug));
    }

    public function test_expiry_choice_sets_the_right_date(): void
    {
        $this->freezeSecond();

        $this->post('/snippets', $this->validData(['expires' => '7d']));

        $this->assertTrue(Snippet::first()->expires_at->equalTo(now()->addWeek()));
    }

    public function test_title_and_code_are_required(): void
    {
        $this->post('/snippets', $this->validData(['title' => '', 'code' => '']))
            ->assertSessionHasErrors(['title', 'code']);

        $this->assertSame(0, Snippet::count());
    }

    public function test_unknown_language_is_rejected(): void
    {
        $this->post('/snippets', $this->validData(['language' => 'cobol']))
            ->assertSessionHasErrors('language');
    }

    public function test_saving_is_limited_to_10_per_minute(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/snippets', $this->validData())->assertRedirect();
        }

        $this->post('/snippets', $this->validData())->assertStatus(429);
    }

    /* ---------- Show ---------- */

    public function test_snippet_page_shows_the_code_and_counts_views(): void
    {
        $snippet = $this->createSnippet();

        $this->get('/s/abc12345')
            ->assertOk()
            ->assertSee('Test snippet')
            ->assertSee('echo "hello";');

        $this->assertSame(1, $snippet->fresh()->views);
    }

    public function test_html_in_code_is_escaped(): void
    {
        $this->createSnippet(['code' => '<script>alert(1)</script>']);

        $this->get('/s/abc12345')
            ->assertSee('<script>alert(1)</script>')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_missing_snippet_returns_404(): void
    {
        $this->get('/s/nothere1')
            ->assertNotFound()
            ->assertSee('Snippet not found');
    }

    public function test_expired_snippet_returns_404(): void
    {
        $this->createSnippet(['expires_at' => now()->subMinute()]);

        $this->get('/s/abc12345')->assertNotFound();
    }

    /* ---------- Raw ---------- */

    public function test_raw_returns_plain_text(): void
    {
        $this->createSnippet();

        $this->get('/s/abc12345/raw')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertContent('echo "hello";');
    }

    public function test_raw_returns_404_for_expired_snippet(): void
    {
        $this->createSnippet(['expires_at' => now()->subMinute()]);

        $this->get('/s/abc12345/raw')->assertNotFound();
    }

    /* ---------- Pruning ---------- */

    public function test_prune_deletes_only_expired_snippets(): void
    {
        $this->createSnippet(['slug' => 'active01']);
        $this->createSnippet(['slug' => 'expired1', 'expires_at' => now()->subDay()]);

        $this->artisan('model:prune');

        $this->assertDatabaseHas('snippets', ['slug' => 'active01']);
        $this->assertDatabaseMissing('snippets', ['slug' => 'expired1']);
    }
}
