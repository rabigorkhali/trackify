<?php

namespace App\Services;

use App\Models\TicketLabel;

class TicketLabelService extends Service
{
    public function __construct(TicketLabel $model)
    {
        parent::__construct($model);
    }

    public function indexPageData($request)
    {
        return [
            'thisDatas' => $this->getAllData($request),
        ];
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        $keyword = $data->get('keyword');
        $show = $data->get('show');
        $projectId = $data->get('project_id');
        
        $query = $this->query();
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        
        if ($keyword) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%']);
        }
        
        if ($projectId) {
            $query->where(function($q) use ($projectId) {
                $q->where('project_id', $projectId)
                  ->orWhereNull('project_id'); // Include global labels
            });
        }
        
        if ($pagination) {
            return $query->orderBy('name', 'ASC')->paginate($show ?? 10);
        } else {
            return $query->orderBy('name', 'ASC')->get();
        }
    }
}

