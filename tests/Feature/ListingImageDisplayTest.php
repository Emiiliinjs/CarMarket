<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ListingImageDisplayTest extends TestCase
{
    /** @test */
    public function uploaded_images_are_saved_and_served_for_listings(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('listings.store'), [
            'marka' => 'Audi',
            'modelis' => 'A4',
            'gads' => 2020,
            'nobraukums' => 10000,
            'cena' => 25000,
            'degviela' => 'Benzīns',
            'parnesumkarba' => 'Automātiskā',
            'apraksts' => 'Test description',
            'status' => Listing::STATUS_AVAILABLE,
            'contact_info' => '12345678',
            'show_contact' => true,
            'images' => [
                UploadedFile::fake()->image('car.jpg', 1200, 800),
            ],
        ]);

        $response->assertRedirect();

        $listing = Listing::first();
        $this->assertNotNull($listing);
        $this->assertCount(1, $listing->galleryImages);

        $image = $listing->galleryImages->first();
        Storage::disk('public')->assertExists($image->filename);

        $this->get(route('listing-images.show', $image))->assertOk();
    }
}
