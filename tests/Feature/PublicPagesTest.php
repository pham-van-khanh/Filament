<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('chuaminh.vn')
            ->assertDontSee('□');
    }

    public function test_memories_listing_loads(): void
    {
        $this->seed();

        $this->get('/memories')
            ->assertOk()
            ->assertSee('Bo suu tap');
    }

    public function test_memory_detail_page_loads(): void
    {
        $this->seed();

        $this->get('/memories/mot-ngay-chung-minh-o-giua-may-troi')
            ->assertOk()
            ->assertSee('Gallery mosaic')
            ->assertSee('+2')
            ->assertSee('Những khung hình như postcard')
            ->assertSee('Hành trình của chúng mình')
            ->assertSee('data-bottom-navigation', false)
            ->assertSee('Viết cảm nhận hoặc lời nhắn của bạn về kỷ niệm này...')
            ->assertSee('Chia sẻ kỷ niệm')
            ->assertDontSee('□');
    }
}
