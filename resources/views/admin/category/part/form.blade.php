<div class="form-group">
    <label for="name">Наименование: </label>
    <input type="text" class="form-control" name="name" placeholder="Наименование"
           required maxlength="100" value="{{ old('name') ?? $category->name ?? '' }}">
</div>
<div class="form-group">
    @php
        $parent_id = old('parent_id') ?? $category->parent_id ?? 0;
    @endphp
    <label for="parent_id">Выберите категорию: </label>
    <select name="parent_id" class="form-control" title="Родитель">
        <option value="0">Без родителя</option>
        @include('admin.part.parents', ['level' => -1, 'parent' => 0])
    </select>
</div>
<div class="form-group">
    <label for="content">Краткое описание: </label>
    <textarea class="form-control" name="content" placeholder="Краткое описание" maxlength="500"
              rows="5">{{ old('content') ?? $category->content ?? '' }}</textarea>
</div>
<div class="form-group">
    <input type="file" class="form-control-file" name="image" accept="image/png, image/jpeg">
</div>
@isset($category->image)
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="remove" id="remove">
        <label class="form-check-label" for="remove">
            Удалить загруженное изображение
        </label>
    </div>
@endisset
<div class="form-group">
    <button type="submit" class="btn btn-primary">Сохранить</button>
</div>
