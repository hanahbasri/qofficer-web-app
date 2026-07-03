<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Root URL redirects to the Q-Officer login page.
     *
     * @return void
     */
    public function test_root_redirects_to_login()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
