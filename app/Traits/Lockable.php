<?php

// app/Traits/Lockable.php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Lockable {
    public function acquireLock($resourceId, $lockDuration = 300) {
        $lockKey = 'lock_' . $resourceId;
        return Cache::add($lockKey, true, $lockDuration);
    }

    public function releaseLock($resourceId) {
        $lockKey = 'lock_' . $resourceId;
        Cache::forget($lockKey);
    }

    public function isLocked($resourceId) {
        $lockKey = 'lock_' . $resourceId;
        return Cache::has($lockKey);
    }
}

