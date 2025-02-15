<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class UnitBaseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        # Run migrations
        $this->artisan('migrate');
    }
}
