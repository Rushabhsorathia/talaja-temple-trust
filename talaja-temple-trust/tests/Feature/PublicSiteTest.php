<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\News;
use App\Models\Temple;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_news_list_and_detail_load(): void
    {
        Temple::factory()->create(['is_primary' => true]);
        $news = News::factory()->create(['is_published' => true, 'published_at' => now()]);

        $this->get('/news-and-updates')->assertStatus(200);
        $this->get("/view-news/{$news->slug}")->assertStatus(200);
    }

    public function test_contact_form_stores_feedback(): void
    {
        $this->post('/contact-us', [
            'type' => 'feedback',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '9999999999',
            'message' => 'Great temple!',
        ])->assertRedirect();

        $this->assertDatabaseHas('feedbacks', ['name' => 'Test User']);
    }

    public function test_shop_index_loads(): void
    {
        $this->get('/shop')->assertStatus(200);
    }
}
