<?php

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('listing creation fails with invalid data', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    // apzināti nederīgi dati
    $response = $this->post(route('listings.store'), [
        'modelis' => 'A4',
        // nav 'marka'
        'gads' => 2020,
        'cena' => 9000,
        'images' => ['not-an-image'],
    ]);

    // pārbauda validācijas kļūdas
    $response->assertSessionHasErrors(['marka', 'images.0']);

    // pārliecinās, ka nekas nav saglabāts DB
    $this->assertDatabaseMissing('listings', [
        'modelis' => 'A4',
    ]);

    // pārliecinās, ka netika izveidots fails
    Storage::disk('public')->assertMissing('car.jpg');
});
