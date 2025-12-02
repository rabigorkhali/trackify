<?php

namespace App\Services;

use App\Models\Team;

class TeamService extends Service
{
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }
}
