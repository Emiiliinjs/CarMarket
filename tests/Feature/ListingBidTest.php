<?php

use App\Models\Listing;
use App\Models\ListingBid;
use App\Models\User;

test('user can place a bid that is much higher than minimum increment', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();

    $listing = Listing::create([
        'user_id' => $owner->id,
        'marka' => 'Audi',
        'modelis' => 'A6',
        'gads' => 2019,
        'nobraukums' => 125000,
        'cena' => 10000,
        'degviela' => 'Dīzelis',
        'parnesumkarba' => 'Automātiskā',
        'status' => Listing::STATUS_AVAILABLE,
        'is_approved' => true,
    ]);

    $response = $this
        ->actingAs($bidder)
        ->postJson(route('listings.bids.store', $listing), [
            'amount' => 11550,
        ]);

    $response
        ->assertCreated()
        ->assertJsonPath('currentBid', 11550.0)
        ->assertJsonPath('nextBidAmount', 11650.0);

    $this->assertDatabaseHas('listing_bids', [
        'listing_id' => $listing->id,
        'user_id' => $bidder->id,
        'amount' => 11550.00,
    ]);
});

test('bid must still be at least minimum increment above current highest bid', function () {
    $owner = User::factory()->create();
    $bidder = User::factory()->create();
    $previousBidder = User::factory()->create();

    $listing = Listing::create([
        'user_id' => $owner->id,
        'marka' => 'BMW',
        'modelis' => '530d',
        'gads' => 2018,
        'nobraukums' => 198000,
        'cena' => 9000,
        'degviela' => 'Dīzelis',
        'parnesumkarba' => 'Automātiskā',
        'status' => Listing::STATUS_AVAILABLE,
        'is_approved' => true,
    ]);

    ListingBid::create([
        'listing_id' => $listing->id,
        'user_id' => $previousBidder->id,
        'amount' => 10000,
    ]);

    $response = $this
        ->actingAs($bidder)
        ->postJson(route('listings.bids.store', $listing), [
            'amount' => 10050,
        ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('amount');

    $this->assertDatabaseMissing('listing_bids', [
        'listing_id' => $listing->id,
        'user_id' => $bidder->id,
        'amount' => 10050.00,
    ]);
});
