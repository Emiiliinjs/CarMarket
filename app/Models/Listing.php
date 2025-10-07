<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_SOLD = 'sold';

    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_RESERVED,
        self::STATUS_SOLD,
    ];

    protected $fillable = [
        'user_id',
        'marka',
        'modelis',
        'gads',
        'nobraukums',
        'cena',
        'degviela',
        'parnesumkarba',
        'apraksts',
        'status',
        'is_approved',
        'contact_info',
        'show_contact',
        'is_admin_bidding',
    ];

    // Automātiski ielādē galerijas bildes, ja vajadzīgs
    protected $with = ['galleryImages', 'user'];

    protected $casts = [
        'is_approved' => 'boolean',
        'show_contact' => 'boolean',
        'is_admin_bidding' => 'boolean',
    ];

    protected $appends = ['status_label'];

    /**
     * Lietotājs, kas ievietoja sludinājumu
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sludinājuma bildes (vienam sludinājumam var būt vairākas bildes)
     */
    public function galleryImages()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_listings')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(ListingReport::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(ListingBid::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeAdminBidding($query)
    {
        return $query->where('is_admin_bidding', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when($filters['marka'] ?? null, fn ($q, $marka) => $q->where('marka', 'like', "%{$marka}%"))
            ->when($filters['modelis'] ?? null, fn ($q, $modelis) => $q->where('modelis', 'like', "%{$modelis}%"))
            ->when($filters['price_min'] ?? null, fn ($q, $priceMin) => $q->where('cena', '>=', $priceMin))
            ->when($filters['price_max'] ?? null, fn ($q, $priceMax) => $q->where('cena', '<=', $priceMax))
            ->when($filters['year_from'] ?? null, fn ($q, $yearFrom) => $q->where('gads', '>=', $yearFrom))
            ->when($filters['year_to'] ?? null, fn ($q, $yearTo) => $q->where('gads', '<=', $yearTo))
            ->when($filters['degviela'] ?? null, fn ($q, $fuel) => $q->where('degviela', $fuel))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $like = "%{$search}%";

                $q->where(function ($query) use ($like) {
                    $query->where('marka', 'like', $like)
                        ->orWhere('modelis', 'like', $like)
                        ->orWhere('apraksts', 'like', $like);
                });
            });

        return $query;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_RESERVED => 'Rezervēts',
            self::STATUS_SOLD => 'Pārdots',
            default => 'Pieejams',
        };
    }

    public function biddingState(int $limit = 20): array
    {
        $increment = ListingBid::MINIMUM_INCREMENT;

        $highestBidAmount = $this->bids()
            ->orderByDesc('amount')
            ->value('amount');

        $currentBid = round(max((float) ($highestBidAmount ?? 0), (float) $this->cena), 2);
        $nextBid = round($currentBid + $increment, 2);

        $history = $this->bids()
            ->with('user:id,name')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function (ListingBid $bid) {
                return [
                    'id' => $bid->id,
                    'amount' => (float) $bid->amount,
                    'user' => $bid->user?->name ?? __('Anonīms solītājs'),
                    'created_at' => $bid->created_at->toIso8601String(),
                    'created_at_human' => $bid->created_at->diffForHumans(),
                ];
            })
            ->values();

        return [
            'minIncrement' => $increment,
            'currentBid' => $currentBid,
            'nextBidAmount' => $nextBid,
            'bids' => $history,
        ];
    }
}
