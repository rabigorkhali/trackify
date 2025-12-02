<?php

namespace App\Services;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Config;

class MenuService extends Service
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
        dd(1);
        $data = $request->except('_token');
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path(getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model)))), $filename);
            $data['logo'] = getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model))).'/'.$filename;
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path(getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model)))), $filename);
            $data['favicon'] = getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model))).'/'.$filename;
        }
        if ($request->hasFile('bank_qr')) {
            $file = $request->file('bank_qr');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path(getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model)))), $filename);
            $data['bank_qr'] = getImageUploadFirstLevelPath().'/'.strtolower(class_basename(get_class($this->model))).'/'.$filename;
        }

        return $this->model->create($data);
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $update = $this->itemByIdentifier($id);
        $update->fill($data)->save();
        $update = $this->itemByIdentifier($id);
        return $update;
    }

}
