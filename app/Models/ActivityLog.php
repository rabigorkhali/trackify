<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $table = 'activity_log'; // Ensure it matches your database table

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer(): MorphTo
    {
        return parent::causer();
    }

    public function subject(): MorphTo
    {
        return parent::subject();
    }
    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties, $propertyName, $defaultValue);
    }
}

