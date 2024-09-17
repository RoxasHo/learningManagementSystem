<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatDate($date)
    {
        $createdAt = Carbon::parse($date);
        $now = Carbon::now();

        $diffInDays = $createdAt->diffInDays($now);

        if ($diffInDays > 3) {
            // If the post is older than 3 days, show the exact date and time.
            return $createdAt->format('M d, Y h:i A');
        } else {
            // Otherwise, use Carbon's diffForHumans for more reliable human-readable differences
            return $createdAt->diffForHumans($now, true) . " ago";
        }
    }
}
