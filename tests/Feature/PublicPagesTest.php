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
            ->assertSee('chuaminh.vn');
    }

    public function test_memories_listing_loads(): void
    {
        $this->seed();

        $this->get('/memories')
            ->assertOk()
            ->assertSee('Bo suu tap');
    }
}
