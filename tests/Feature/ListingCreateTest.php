<?php

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('authenticated user can create a listing with image', function () {
    // Fake storage, lai testā netiktu reāli saglabātas bildes
    Storage::fake('public');

    // Izveido lietotāju
    $user = User::factory()->create();

    // Autentificē lietotāju
    $this->actingAs($user);

    // Veic POST pieprasījumu
    $response = $this->post(route('listings.store'), [
        'marka'         => 'Audi',
        'modelis'       => 'A4',
        'gads'          => 2020,
        'nobraukums'    => 150000,
        'cena'          => 9500,
        'degviela'      => 'Dīzelis',
        'parnesumkarba' => 'Automātiskā',
        'apraksts'      => 'Ļoti labs auto testam',
        'status'        => Listing::STATUS_AVAILABLE,
        'contact_info'  => 'tests@example.com',
        'show_contact'  => true,
        'images'        => [UploadedFile::fake()->image('car.jpg')],
    ]);

    // Pārbauda, vai notika redirect uz show lapu
    $response->assertRedirect();

    // Pārbauda, vai sludinājums ir DB
    $this->assertDatabaseHas('listings', [
        'marka' => 'Audi',
        'modelis' => 'A4',
        'user_id' => $user->id,
    ]);

    // Pārbauda, vai bilde tika saglabāta storage
    $listing = Listing::latest()->first();
    Storage::disk('public')->assertExists($listing->galleryImages->first()->filename);
});
