<?php

namespace App\Services;

use App\Models\Redirection;
use Illuminate\Support\Facades\Schema;

class RedirectionService extends Service
{
    public function __construct(Redirection $model)
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
            if (Schema::hasColumn($table, 'name')) {
                $query->orWhereRaw('LOWER(name) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'title')) {
                $query->orWhereRaw('LOWER(title) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'source_link')) {
                $query->orWhereRaw('LOWER(source_link) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'destination_link')) {
                $query->orWhereRaw('LOWER(destination_link) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
        }
        if ($pagination) {
            return $query->orderBy('created_at', 'DESC')->paginate($show ?? 10);
        } else {
            return $query->orderBy('created_at', 'DESC')->get();
        }
    }

}
