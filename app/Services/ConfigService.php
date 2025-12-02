<?php

namespace App\Services;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Config;

class ConfigService extends Service
{
    public function __construct(Config $model)
    {
        parent::__construct($model);
    }

    public function indexPageData($request)
    {
        return [
            'thisData' => $this->first(),
            'id' => $this->model->max('id')
        ];
    }

    public function store($request)
    {
        $data = $request->except('_token');
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'logo', false, null, null);
        }

        if ($request->hasFile('secondary_logo')) {
            $data['secondary_logo'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'secondary_logo', false, null, null);
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'favicon', false, null, null);
        }
        if ($request->hasFile('bank_qr')) {
            $data['bank_qr'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'bank_qr', false, null, null);
        }

        return $this->model->create($data);
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $update = $this->itemByIdentifier($id);
        $faviconPath = $update->favicon ?? null;
        $logoPath = $update->logo ?? null;
        $secondaryLogoPath = $update->secondary_logo ?? null;
        $bankQrPath = $update->bank_qr ?? null;
        if ($request->hasFile('favicon')) {
            if ($faviconPath && file_exists(public_path($faviconPath))) {
                unlink(public_path($faviconPath));
            }
            $data['favicon'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'favicon', false, null, null);

        }
        if ($request->hasFile('secondary_logo')) {
            if ($secondaryLogoPath && file_exists(public_path($secondaryLogoPath))) {
                unlink(public_path($secondaryLogoPath));
            }
            $data['secondary_logo'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'secondary_logo', false, null, null);

        }
        if ($request->hasFile('bank_qr')) {
            if ($bankQrPath && file_exists(public_path($bankQrPath))) {
                unlink(public_path($bankQrPath));
            }
            $data['bank_qr'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'bank_qr', false, null, null);
        }
        if ($request->hasFile('logo')) {
            if ($logoPath && file_exists(public_path($logoPath))) {
                unlink(public_path($logoPath));
            }
            $data['logo'] = $this->fullImageUploadPath . uploadImage($this->fullImageUploadPath, 'logo', false, null, null);
        }
        $update->fill($data)->save();
        $update = $this->itemByIdentifier($id);

        return $update;
    }

}
