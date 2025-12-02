<?php

namespace App\Services;

use App\Models\Sms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService extends Service
{
    private $aakashSmsToken = "e63cb9ee6d8c43674e31b3d11c8b6cf3f47d1b02f08b6b7c2f9ffaa7827b46ed";
    private $aakashSmsUrl = 'https://sms.aakashsms.com/sms/v3/send';

    public function __construct(Sms $model)
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
                if (Schema::hasColumn($table, 'sender')) {
                    $subQuery->orWhere('sender', 'like', '%' . $keyword . '%');
                }

                if (Schema::hasColumn($table, 'receiver')) {
                    $subQuery->orWhereRaw("JSON_SEARCH(LOWER(receiver), 'all', LOWER(?)) IS NOT NULL", ["%$keyword%"]);
                }

                if (Schema::hasColumn($table, 'message')) {
                    $subQuery->orWhere('message', 'like', '%' . $keyword . '%');
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

    private function sendAakashSms($to, $message)
    {
        try {
            $response = Http::post($this->aakashSmsUrl, [
                'auth_token' => $this->aakashSmsToken,
                'to' => $to,
                'text' => $message
            ]);

            if (!$response->successful()) {
                Log::error('AakashSMS API Error: ' . $response->body());
                throw new \Exception('Failed to send SMS: ' . $response->body());
            }

            $result = $response->json();

            if (isset($result['error']) && $result['error']) {
                Log::error('AakashSMS Error: ' . json_encode($result));
                throw new \Exception('SMS sending failed: ' . ($result['message'] ?? 'Unknown error'));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('AakashSMS Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function store($request)
    {
        $data = $request->except('_token');
        if (!is_array($data['receiver'])) {
            $data['receiver'] = [$data['receiver']];
        }

        try {
            DB::beginTransaction();

            $sms = $this->model->create($data);

            if ($data['status'] === 'send-now') {
                $recipients = implode(',', $data['receiver']);
                $this->sendAakashSms($recipients, $data['message']);
                $this->model->where('id', $sms->id)->update(['status' => 'sent']);
            }

            DB::commit();
            return $sms;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SMS sending failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $sms = $this->itemByIdentifier($id);

        if (!is_array($data['receiver'])) {
            $data['receiver'] = [$data['receiver']];
        }
        $sms->fill($data)->save();
        if ($data['status'] === 'send-now') {
            $recipients = implode(',', $data['receiver']);
            $this->sendAakashSms($recipients, $data['message']);
            $this->model->where('id', $id)->update(['status' => 'sent']);
        }
        return $sms;
    }
}
