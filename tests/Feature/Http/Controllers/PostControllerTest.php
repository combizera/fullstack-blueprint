<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\ReviewPost;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PostController
 */
final class PostControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertViewIs('post.index');
        $response->assertViewHas('posts');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('posts.create'));

        $response->assertOk();
        $response->assertViewIs('post.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PostController::class,
            'store',
            \App\Http\Requests\PostStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $title = $this->faker->sentence(4);
        $slug = $this->faker->slug();

        Mail::fake();

        $response = $this->post(route('posts.store'), [
            'title' => $title,
            'slug' => $slug,
        ]);

        $posts = Post::query()
            ->where('title', $title)
            ->where('slug', $slug)
            ->get();
        $this->assertCount(1, $posts);
        $post = $posts->first();

        $response->assertRedirect(route('post.create'));

        Mail::assertSent(ReviewPost::class, function ($mail) use ($post) {
            return $mail->hasTo($post->author) && $mail->post->is($post);
        });
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('post.create'));
        $response->assertSessionHas('post.message', $post->message);

        $this->assertModelMissing($post);
    }
}
