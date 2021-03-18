{{-- Для показа отдельного поста блога --}}
@extends('layout.site', ['title' => $post->name])

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h1>{{ $post->name }}</h1>
        </div>
        <div class="card-body">
            @if($post->user_id == 1)
                <img src="{{ Storage::url('public/post/admin/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">
            @else
                <img src="{{ Storage::url('public/post/user/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">
            @endif
            <div class="mt-4">{!! $post->content !!}</div>
        </div>
        <div class="card-footer">
            Автор:
            <a href="{{ route('blog.author', ['user' => $post->user->id]) }}">
                {{ $post->user->name }}
            </a>

            {{--========================Количество просмотров To Show Post========================--}}
            <span class="float-right">
                <a href="#">View</a> 0
            </span>
            {{--========================Количество просмотров To Show Post========================--}}

            <br>
            Дата: {{ $post->created_at }}

            {{--========================Like To Show Post========================--}}
            <span class="float-right">
                <a href="#">Like</a> 0
            </span>
            {{--========================Like To Show Post========================--}}

        </div>
        @if ($post->tags->count())
            <div class="card-footer">
                Теги:
                @foreach($post->tags as $tag)
                    @php($comma = $loop->last ? '' : ' • ')
                    <a href="{{ route('blog.tag', ['tag' => $tag->slug]) }}">
                        {{ $tag->name }}</a>
                    {{ $comma }}
                @endforeach
            </div>
        @endif
    </div>
    @include('blog.part.comments', ['comments' => $comments])
@endsection
