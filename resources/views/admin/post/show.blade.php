@extends('layout.site', ['title' => $post->name, 'admin' => true])

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h1>
                @if ( ! $post->isVisible())
                    <i class="far fa-eye-slash text-danger" title="Предварительный просмотр"></i>
                @else
                    <i class="far fa-eye text-success" title="Этот пост опубликован"></i>
                @endif
                {{ $post->name }}
            </h1>
        </div>
        <div class="card-body">
            @if($post->user_id == 1)
                <img src="{{ Storage::url('public/post/admin/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">
            @else
                <img src="{{ Storage::url('public/post/user/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">
            @endif
            <div class="justify-content-center">
                @perm('manage-posts')
                {!! $post->content !!}
                @else
                    <p>{{ $post->excerpt }}</p>
                @endperm
            </div>
        </div>
        <div class="card-footer">
            <span>
                Автор:
                <a href="{{ route('blog.author', ['user' => $post->user->id]) }}">
                    {{ $post->user->name }}
                </a>

            {{--========================Количество просмотров и лайков To Admin Show Post========================--}}
                <span class="float-right">
                    <i class="fa fa-eye" aria-hidden="true"></i>  {{ $post->view_count }}

                    &nbsp;&nbsp;

                    <a href="{{ url("/admin/like/{$post->id}") }}"><i class="fa fa-heart" aria-hidden="true"></i></a>
                    {{ $likeCount }}
                </span>
            {{--========================Количество просмотров и лайков To Admin Show Post========================--}}
                <br>

                Дата: {{ $post->created_at }}

            </span>

            <br/>

            <span class="float-right">
                @perm('publish-post')
                    @if ($post->isVisible())
                    <a href="{{ route('admin.post.disable', ['post' => $post->id]) }}"
                       class="btn btn-dark" title="Запретить публикацию">
                                <i class="fas fa-toggle-on text-success"></i>
                            </a>
                @else
                    <a href="{{ route('admin.post.enable', ['post' => $post->id]) }}"
                       class="btn btn-dark" title="Разрешить публикацию">
                                <i class="fas fa-toggle-off text-white"></i>
                            </a>
                @endif
                @endperm
                @perm('edit-post')
                    <a href="{{ route('admin.post.edit', ['post' => $post->id]) }}"
                       class="btn btn-primary" title="Редактировать пост">
                        <i class="far fa-edit"></i>
                    </a>
                @endperm
                @perm('delete-post')
                    <form action="{{ route('admin.post.destroy', ['post' => $post->id]) }}"
                          method="post" class="d-inline" onsubmit="return confirm('Удалить этот пост?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Удалить пост">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                @endperm
            </span>
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
    @isset($comments)
        @include('admin.post.comments', ['comments' => $comments])
    @endisset
@endsection
