<?php

namespace App\Services;

use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Schema;

class NewsletterSubscriptionService extends Service
{
    public function __construct(NewsletterSubscription $model)
    {
        parent::__construct($model);
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        $keyword = $data->get('keyword');
        $show = $data->get('show');
        $query = $this->query();
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        $table = $this->model->getTable();
        if ($keyword) {
            if (Schema::hasColumn($table, 'email')) {
                $query->orWhereRaw('LOWER(email) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'title')) {
                $query->orWhereRaw('LOWER(title) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
        }
        if ($pagination) {
            return $query->orderBy('created_at', 'DESC')->paginate($show ?? 10);
        } else {
            return $query->orderBy('created_at', 'DESC')->get();
        }
    }

}
