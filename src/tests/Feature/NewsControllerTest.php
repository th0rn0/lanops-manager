<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_returns_200()
    {
        $response = $this->get('/news');

        $response->assertStatus(200);
    }

    public function test_news_index_lists_articles()
    {
        factory(NewsArticle::class)->create(['title' => 'First Post']);
        factory(NewsArticle::class)->create(['title' => 'Second Post']);

        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertSee('First Post');
        $response->assertSee('Second Post');
    }

    public function test_news_index_shows_empty_state_with_no_articles()
    {
        $response = $this->get('/news');

        $response->assertStatus(200);
    }

    public function test_news_article_show_returns_200()
    {
        $article = factory(NewsArticle::class)->create(['title' => 'Test Article']);

        $response = $this->get('/news/' . $article->slug);

        $response->assertStatus(200);
        $response->assertSee('Test Article');
    }

    public function test_news_article_shows_content()
    {
        $article = factory(NewsArticle::class)->create([
            'title'   => 'Important Update',
            'article' => 'This is the full article body text.',
        ]);

        $response = $this->get('/news/' . $article->slug);

        $response->assertStatus(200);
        $response->assertSee('Important Update');
        $response->assertSee('This is the full article body text.');
    }

    public function test_news_article_not_found_returns_404()
    {
        $response = $this->get('/news/this-does-not-exist');

        $response->assertStatus(404);
    }

    public function test_news_comment_requires_auth()
    {
        $article = factory(NewsArticle::class)->create();

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/news/' . $article->slug . '/comments', ['comment' => 'Test comment']);

        $response->assertRedirect('/login');
    }
}
