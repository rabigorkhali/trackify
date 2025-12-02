<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Label Name *</label>
        <input required type="text" name="name" id="name" class="form-control" value="{{ old('name', $thisData->name ?? '') }}" placeholder="e.g., Bug, Feature, Documentation">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="color">Color *</label>
        <input required type="color" name="color" id="color" class="form-control form-control-color" value="{{ old('color', $thisData->color ?? '#6c757d') }}">
    </div>

    <div class="col-md-12">
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Optional description...">{{ old('description', $thisData->description ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <div class="form-check">
            <input type="radio" id="status_active" name="status" value="1" {{ old('status', $thisData->status ?? 1) == 1 ? 'checked' : '' }} class="form-check-input">
            <label for="status_active" class="form-check-label">Active</label>
        </div>
        <div class="form-check">
            <input type="radio" id="status_inactive" name="status" value="0" {{ old('status', $thisData->status ?? 1) == 0 ? 'checked' : '' }} class="form-check-input">
            <label for="status_inactive" class="form-check-label">Inactive</label>
        </div>
    </div>
</div>

