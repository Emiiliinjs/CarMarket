<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('listing creation fails with invalid data', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    // Apzināti atstāj trūkstošu "marka" un nederīgu bildi
    $response = $this->post(route('listings.store'), [
        'modelis'       => 'A4',
        'gads'          => 2020,
        'nobraukums'    => 150000,
        'cena'          => 9500,
        'degviela'      => 'Dīzelis',
        'parnesumkarba' => 'Automātiskā',
        'apraksts'      => 'Bez markas lauka',
        'status'        => 'available',
        'contact_info'  => 'tests@example.com',
        'show_contact'  => true,
        'images'        => ['not-an-image'], // nederīgs fails
    ]);

    // Jāatgriež validācijas kļūda
    $response->assertSessionHasErrors(['marka', 'images.0']);

    // Nedrīkst saglabāt nevienu sludinājumu DB
    $this->assertDatabaseMissing('listings', [
        'modelis' => 'A4',
    ]);

    // Nedrīkst būt saglabātu bilžu
    Storage::disk('public')->assertMissing('car.jpg');
});