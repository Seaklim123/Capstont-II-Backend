<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_endpoint_returns_success(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp'
            ])
            ->assertJson([
                'status' => 'ok'
            ]);
    }
}
