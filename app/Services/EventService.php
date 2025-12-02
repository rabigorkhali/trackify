<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventImage;

class EventService extends Service
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function store($request)
    {

        $data = $request->except('_token');
        $gallery = $request->only('gallery');
        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path(getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model)))), $filename);
            $data['thumbnail_image'] = getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model))) . '/' . $filename;
        }
        $createResponse = $this->model->create($data);
        if ($gallery) {
            foreach ($gallery['gallery'] as $galleryDatum) {
                $dataGalley = [];
                $dataGalley['event_id'] = $createResponse->id;
                $file = $galleryDatum;
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path(getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model)))), $filename);
                $dataGalley['image'] = getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model))) . '/' . $filename;
                EventImage::create($dataGalley);
            }
        }
        return $createResponse;
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $update = $this->itemByIdentifier($id);
        $thumbnailImage = $update->thumbnail_image ?? null;

        if ($request->hasFile('thumbnail_image')) {
            if ($thumbnailImage && file_exists(public_path($thumbnailImage))) {
                unlink(public_path($thumbnailImage));
            }

            $file = $request->file('thumbnail_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path(getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model)))), $filename);
            $data['thumbnail_image'] = getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model))) . '/' . $filename;
        }

        $update->fill($data)->save();
        $update = $this->itemByIdentifier($id);
        $gallery = $request->only('gallery');
        if ($gallery) {
            foreach ($gallery['gallery'] as $galleryDatum) {
                $dataGalley = [];
                $dataGalley['event_id'] = $id;
                $file = $galleryDatum;
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path(getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model)))), $filename);
                $dataGalley['image'] = getImageUploadFirstLevelPath() . '/' . strtolower(class_basename(get_class($this->model))) . '/' . $filename;
                EventImage::create($dataGalley);
            }
        }

        return $update;
    }

    public function deleteGallery($id)
    {
        $update = EventImage::find($id);
        $image = $update->image ?? null;
        if ($update && file_exists(public_path($image))) {
            unlink(public_path($image));
        }
        $update->delete();
        return $update;
    }

    public function delete($request, $id)
    {

        $item = $this->itemByIdentifier($id);
        $thumbnailImage = $item->thumbnail_image ?? null;
        $allGallery = EventImage::where('event_id', $id)->get();
        EventImage::where('event_id', $id)->delete();
        $deleteResp = $item->delete();

        foreach ($allGallery as $allGalleryDatum) {
            if (file_exists(public_path($allGalleryDatum->image))) {
                unlink(public_path($allGalleryDatum->image));
            }
        }
        if ($thumbnailImage && file_exists(public_path($thumbnailImage))) {
            unlink(public_path($thumbnailImage));
        }
        return $deleteResp;
    }


}
