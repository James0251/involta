@csrf
<div class="form-group">
    <label for="name">Наименование:</label>
    <input type="text" class="form-control" name="name" placeholder="Наименование"
           required maxlength="100" value="{{ old('name') ?? $post->name ?? '' }}">
</div>
<div class="form-group">
    @php
        $parent_id = old('parent_id') ?? $category->parent_id ?? 0;
    @endphp
    <label for="parent_id">Выберите категорию: </label>
    <select name="parent_id" class="form-control" title="Родитель">
        <option value="0" disabled>Выберите категорию</option>
        @include('admin.part.parents', ['level' => -1, 'parent' => 0])
    </select>
</div>
<div class="form-group">
    <label for="excerpt">Анонс поста: </label>
    <textarea class="form-control" name="excerpt" placeholder="Анонс поста"
              required maxlength="500">{{ old('excerpt') ?? $post->excerpt ?? '' }}</textarea>
</div>
<div class="form-group">
    <label for="content">Текст поста: </label>
    <textarea class="form-control" name="content" placeholder="Текст поста"
              required rows="4">{{ old('content') ?? $post->content ?? '' }}</textarea>
</div>
<div class="form-group">
    <input type="file" class="form-control-file" name="image" accept="image/png, image/jpeg">
</div>
@isset($post->image)
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="remove" id="remove">
        <label class="form-check-label" for="remove">
            Удалить загруженное изображение
        </label>
    </div>
@endisset

{{-- Все тэги --}}
@include('admin.part.all-tags')

<div class="form-group">
    <button type="submit" class="btn btn-primary">Сохранить</button>
</div>
