<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingBid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListingBidController extends Controller
{
    public function index(Request $request, Listing $listing): JsonResponse
    {
        $this->ensureAccessible($request, $listing);

        return response()->json($listing->biddingState());
    }

    public function store(Request $request, Listing $listing): JsonResponse|RedirectResponse
    {
        $this->ensureAccessible($request, $listing);

        $state = $listing->biddingState();
        $currentBid = $state['currentBid'];
        $increment = ListingBid::MINIMUM_INCREMENT;

        if ($listing->status !== Listing::STATUS_AVAILABLE) {
            $message = __('Šim sludinājumam vairs nav aktīvas izsoles.');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    ...$state,
                ], 409);
            }

            return back()->with('error', $message);
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric'],
        ], [
            'amount.required' => __('Norādi solījuma summu.'),
            'amount.numeric' => __('Solis ir jānorāda kā skaitlis.'),
        ]);

        $validator->after(function ($validator) use ($currentBid, $increment) {
            $amount = $validator->getData()['amount'] ?? null;

            if ($amount === null || $amount === '') {
                return;
            }

            $amountValue = (float) $amount;
            $amountCents = (int) round($amountValue * 100);
            $currentCents = (int) round($currentBid * 100);
            $incrementCents = (int) round($increment * 100);

            if ($amountCents < $currentCents + $incrementCents) {
                $validator->errors()->add('amount', __('Minimālais pieaugums ir :amount €.', ['amount' => $increment]));

                return;
            }

            if (($amountCents - $currentCents) % $incrementCents !== 0) {
                $validator->errors()->add('amount', __('Soli var palielināt tikai par :amount € intervāliem.', ['amount' => $increment]));
            }
        });

        $validated = $validator->validate();

        $amount = round((float) $validated['amount'], 2);

        $listing->bids()->create([
            'user_id' => $request->user()->id,
            'amount' => $amount,
        ]);

        $payload = $listing->biddingState();
        $successMessage = __('Tavs solis ir pieņemts!');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $successMessage,
                ...$payload,
            ], 201);
        }

        return back()->with('success', $successMessage);
    }

    private function ensureAccessible(Request $request, Listing $listing): void
    {
        $user = $request->user();

        if (! $listing->is_approved && $user?->id !== $listing->user_id && ! $user?->is_admin) {
            abort(404);
        }
    }
}
