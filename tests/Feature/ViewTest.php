<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ViewTest extends TestCase
{
    public function testViewContains(): void
    {
        $response = $this->get("/test-view");
//        $response->assertViewHas();
    }
}
