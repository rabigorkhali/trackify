<?php

namespace App\Services;

use App\Models\Partner;

class PartnerService extends Service
{
    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }
}
