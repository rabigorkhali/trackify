<?php

namespace App\Services;

use App\Models\ActivityLog;
use Schema;
class ActivityService extends Service
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

}
