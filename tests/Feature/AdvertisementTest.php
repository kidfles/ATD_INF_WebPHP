<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_max_4_advertisements()
    {
        $user = User::factory()->create();

        // Create 4 ads successfully
        for ($i = 0; $i < 4; $i++) {
            $response = $this->actingAs($user)->post('/advertisements', [
                'title' => "Ad $i",
                'description' => 'Description',
                'price' => 10,
                'type' => 'sell',
            ]);
            $response->assertRedirect('/advertisements');
        }

        $this->assertEquals(4, $user->advertisements()->count());

        // Try to create 5th ad -> specific error or forbidden?
        // authorize() returns false -> 403 Forbidden
        $response = $this->actingAs($user)->post('/advertisements', [
            'title' => "Ad 5",
            'description' => 'Description',
            'price' => 10,
            'type' => 'sell',
        ]);

        $response->assertStatus(403);
        $this->assertEquals(4, $user->advertisements()->count());
    }

    public function test_advertisements_can_be_filtered()
    {
        $user = User::factory()->create();
        
        Advertisement::factory()->create([
            'user_id' => $user->id,
            'title' => 'Gazelle Fiets',
            'description' => 'Mooie fiets',
        ]);
        
        Advertisement::factory()->create([
            'user_id' => $user->id,
            'title' => 'Iphone 13',
            'description' => 'Telefoon',
        ]);

        $response = $this->actingAs($user)->get('/advertisements?search=fiets');
        
        $response->assertSee('Gazelle Fiets');
        $response->assertDontSee('Iphone 13');
    }

    public function test_advertisements_can_be_sorted()
    {
        $user = User::factory()->create();

        $cheap = Advertisement::factory()->create(['user_id' => $user->id, 'price' => 10, 'title' => 'Cheap Item']);
        $expensive = Advertisement::factory()->create(['user_id' => $user->id, 'price' => 100, 'title' => 'Expensive Item']);

        // Sort price_desc -> Expensive (index 0) then Cheap (index 1) in collection?
        // It's hard to test order perfectly in HTML, but we can inspect the view data
        $response = $this->actingAs($user)->get('/advertisements?sort=price_desc');
        
        $ads = $response->viewData('advertisements');
        $this->assertEquals($expensive->id, $ads->first()->id);
        
        $response = $this->actingAs($user)->get('/advertisements?sort=price_asc');
        $ads = $response->viewData('advertisements');
        $this->assertEquals($cheap->id, $ads->first()->id);
    }
    
    public function test_upsells_can_be_linked()
    {
        $user = User::factory()->create();
        $ad1 = Advertisement::factory()->create(['user_id' => $user->id]);
        $ad2 = Advertisement::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->post('/advertisements', [
            'title' => 'New Ad with Upsell',
            'description' => 'Description',
            'price' => 50,
            'type' => 'sell',
            'upsells' => [$ad1->id, $ad2->id],
        ]);
        
        $response->assertRedirect('/advertisements');
        
        $newAd = Advertisement::where('title', 'New Ad with Upsell')->first();
        $this->assertCount(2, $newAd->upsells);
        $this->assertTrue($newAd->upsells->contains($ad1));
        $this->assertTrue($newAd->upsells->contains($ad2));
    }
}
