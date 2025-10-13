<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class AuthRedirect
{
    /**
     * Determine the default route name for the authenticated user.
     */
    public static function homeRoute(?Authenticatable $user): string
    {
        return $user?->is_admin ? 'dashboard' : 'listings.index';
    }

    /**
     * Resolve the URL for the authenticated user's default destination.
     */
    public static function homeUrl(?Authenticatable $user, bool $absolute = false): string
    {
        return route(self::homeRoute($user), absolute: $absolute);
    }
}
