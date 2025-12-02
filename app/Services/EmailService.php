<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService extends Service
{
    public function __construct(Email $model)
    {
        parent::__construct($model);
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        $keyword = $data->get('keyword');
        $show = $data->get('show');
        $status = $data->get('status');
        $query = $this->query();
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        $table = $this->model->getTable();
        if ($keyword) {
            $query->where(function ($subQuery) use ($keyword, $table) {
                if (Schema::hasColumn($table, 'to_email')) {
                    // Use JSON_SEARCH for partial match in JSON field
                    $subQuery->orWhereRaw("JSON_SEARCH(LOWER(to_email), 'all', LOWER(?)) IS NOT NULL", ["%$keyword%"]);
                }

                if (Schema::hasColumn($table, 'from_email')) {
                    $subQuery->orWhere('from_email', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn($table, 'subject')) {
                    $subQuery->orWhere('subject', 'like', '%' . $keyword . '%');
                }
            });
        }

        if ($status) {
            $query->where('status', $status);
        }
        if ($pagination) {
            return $query->orderBy('created_at', 'DESC')->paginate($show ?? 10);
        } else {
            return $query->orderBy('created_at', 'DESC')->get();
        }
    }

    public function store($request)
    {
        $data = $request->except('_token');
        if (!is_array($data['to_email'])) {
            $data['to_email'] = [$data['to_email']];
        }
        $email = $this->model->create($data);
        foreach ($data['to_email'] as $recipient) {
            if ($data['status'] == 'send-now') {
                Mail::to($recipient)->send(new \App\Mail\BulkMail($data['subject'], $data['body']));
            }
        }
        if ($data['status'] == 'send-now') {
            $this->model->where('id', $email->id)->update(['status'=>'sent']);
        }
        return $email;
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $update = $this->itemByIdentifier($id);
        $update->fill($data)->save();
        $update = $this->itemByIdentifier($id);
        if (!is_array($data['to_email'])) {
            $data['to_email'] = [$data['to_email']];
        }
        foreach ($data['to_email'] as $recipient) {
            if ($data['status'] == 'send-now') {
                Mail::to($recipient)->send(new \App\Mail\BulkMail($data['subject'], $data['body']));
            }
        }
        if ($data['status'] == 'send-now') {
            $this->model->where('id',$id)->update(['status'=>'sent']);
        }
        return $update;
    }

}
