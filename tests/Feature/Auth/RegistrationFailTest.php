<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration fails with invalid data', function () {
    // mēģina reģistrēties ar nederīgiem laukiem
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => '123',
        'password_confirmation' => '321', // nesakrīt
    ]);

    // jāparādās validācijas kļūdām
    $response->assertSessionHasErrors(['name', 'email', 'password']);

    // lietotājs nedrīkst būt autentificēts
    $this->assertGuest();
});
