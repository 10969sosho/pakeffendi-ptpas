<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $description, ?string $data = null, ?int $actorId = null): void
    {
        ActivityLog::create([
            'description' => $description,
            'data' => $data,
            'actor_id' => $actorId ?? auth()->id(),
        ]);
    }
}

