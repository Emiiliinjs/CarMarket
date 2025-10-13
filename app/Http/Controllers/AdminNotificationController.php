<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $data = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer', 'exists:admin_notifications,id'],
            'mark_all' => ['sometimes', 'boolean'],
        ]);

        $updated = 0;

        if (! empty($data['mark_all'])) {
            $updated = AdminNotification::query()
                ->unread()
                ->update(['read_at' => now()]);
        } elseif (! empty($data['ids'])) {
            $updated = AdminNotification::query()
                ->unread()
                ->whereIn('id', $data['ids'])
                ->update(['read_at' => now()]);
        }

        $remaining = AdminNotification::unread()->count();

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'status' => 'ok',
                'updated' => $updated,
                'remaining' => $remaining,
            ]);
        }

        $message = $updated > 1
            ? 'Visi paziņojumi atzīmēti kā izlasīti.'
            : ($updated === 1 ? 'Paziņojums atzīmēts kā izlasīts.' : 'Nebija paziņojumu, ko atzīmēt.');

        return redirect()->back()->with('success', $message);
    }
}
