@extends('layout.admin', ['title' => 'Редактирование поста'])

@section('content')
    <div class="row justify-content-center">
        <h1>Редактирование поста</h1>
    </div>

    <form method="post" enctype="multipart/form-data"
          action="{{ route('admin.post.update', ['post' => $post->id]) }}">
        @method('PUT')
        @include('admin.post.part.form')
    </form>
@endsection
