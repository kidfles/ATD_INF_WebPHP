<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Advertisement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdvertisementFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_filter_without_any_parameters()
    {
        // Issue 17: Confirm scope doesn't crash on empty input
        $query = Advertisement::filter([]);
        
        $this->assertNotNull($query);
        // If we reach here without exception, potential crash is avoided.
    }

    /** @test */
    public function it_ignores_invalid_sort_parameter()
    {
        $query = Advertisement::filter(['sort' => 'invalid_sort_column']);
        
        // Default behavior is usually 'created_at' desc or similar, 
        // but we mainly check it doesn't throw SQLExceptions.
        $sql = $query->toSql();
        $this->assertStringContainsString('order by', strtolower($sql));
    }

    /** @test */
    public function it_filters_by_type_correctly()
    {
        $adSelling = Advertisement::factory()->create(['type' => 'sell']);
        $adRenting = Advertisement::factory()->create(['type' => 'rent']);

        $results = Advertisement::filter(['type' => 'sell'])->get();

        $this->assertTrue($results->contains($adSelling));
        $this->assertFalse($results->contains($adRenting));
    }
}
