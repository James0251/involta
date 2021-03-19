@extends('layout.user', ['title' => 'Личный кабинет'])

@section('content')
    <h1>Личный кабинет</h1>

    <p>Добрый день {{ auth()->user()->name }}!</p>

    @perm('create-post')
    <a href="{{ route('user.post.create') }}" class="btn btn-success">
        Новая публикация
    </a>
    @endperm
    <a href="{{ route('user.post.index') }}" class="btn btn-primary">
        Ваши публикации
    </a>
    <a href="{{ route('user.comment.index') }}" class="btn btn-primary">
        Ваши комментарии
    </a>

{{--    Если пользователь - Админ, то в личном кабинете видна кнопка перехода в админку--}}
    @if(auth()->user()->id == 1)
        <a href="{{ route('admin.index') }}" class="btn btn-primary">
            Dashboard
        </a>

{{--    Если пользователь - Юзер, то кнопка перехода скрыта--}}
    @else
        <a href="{{ route('admin.index') }}" class="btn btn-primary" hidden>
            Dashboard
        </a>
    @endif

@endsection
