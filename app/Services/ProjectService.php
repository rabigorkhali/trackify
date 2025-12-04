<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ProjectService extends Service
{
    public function __construct(Project $model)
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
        $query = $this->query();
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        $table = $this->model->getTable();
        if ($keyword) {
            if (Schema::hasColumn($table, 'name')) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
            if (Schema::hasColumn($table, 'key')) {
                $query->orWhereRaw('LOWER(key) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
        }
        if ($pagination) {
            return $query->with('creator')->orderBy('created_at', 'DESC')->paginate($show ?? 10);
        } else {
            return $query->with('creator')->orderBy('created_at', 'DESC')->get();
        }
    }

    public function createPageData($request)
    {
        return [
            'users' => User::orderBy('name', 'asc')->get(),
        ];
    }

    public function editPageData($request, $id)
    {
        return [
            'thisData' => $this->itemByIdentifier($id),
            'users' => User::orderBy('name', 'asc')->get(),
        ];
    }

    public function store($request)
    {
        $data = $request->except('_token', 'members');
        $data['created_by'] = auth()->id();

        if ($request->file('avatar')) {
            $data['avatar'] = $this->fullImageUploadPath.uploadImage($this->fullImageUploadPath, 'avatar', true, 200, null);
        }

        $project = $this->model->create($data);

        // Add project members
        $members = [];

        // Add creator as owner
        $members[auth()->id()] = ['role' => 'owner'];

        // Add selected members as regular members
        if ($request->has('members') && is_array($request->members)) {
            foreach ($request->members as $userId) {
                if ($userId != auth()->id()) { // Don't duplicate the owner
                    $members[$userId] = ['role' => 'member'];
                }
            }
        }

        $project->members()->sync($members);

        return $project;
    }

    public function update($request, $id)
    {
        $data = $request->except('_token', 'members');
        $update = $this->itemByIdentifier($id);
        $avatarPath = $update->avatar ?? null;

        if ($request->hasFile('avatar')) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                removeImage($avatarPath);
            }
            $data['avatar'] = $this->fullImageUploadPath.uploadImage($this->fullImageUploadPath, 'avatar', true, 200, null);
        }

        $update->fill($data)->save();

        // Update project members
        $members = [];

        // Keep the owner
        $owner = $update->members()->wherePivot('role', 'owner')->first();
        if ($owner) {
            $members[$owner->id] = ['role' => 'owner'];
        }

        // Add selected members as regular members
        if ($request->has('members') && is_array($request->members)) {
            foreach ($request->members as $userId) {
                if ($owner && $userId != $owner->id) { // Don't duplicate the owner
                    $members[$userId] = ['role' => 'member'];
                }
            }
        }

        $update->members()->sync($members);

        return $update;
    }
}
