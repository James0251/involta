@extends('layout.site')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Результаты поиска: "{{ Request::input('query') }}" </h3>

            <ul class="list2a">
                @foreach($users as $user)
                    <li>
                        <a href="{{ route('blog.author', ['user' => $user->id]) }}">
                            {{ $user->name }}<br/>
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="list2a">
                @foreach($posts as $post)
                    <li>
                        <a href="{{ route('blog.post', ['post' => $post->slug]) }}">
                            {{ $post->name }}<br/><br/>
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="list2a">
                @foreach($comments as $comment)
                    <li>
                        <a href="{{ route('blog.post', ['post' => $comment->post->slug])}}">
                            {{ $comment->content }}<br/><br/>
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="list2a">
                @foreach($tags as $tag)
                    @if ($tags->count())
                        <li>
                            <a href="{{ route('blog.tag', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <br/><br/><br/>
    {{ $posts->links() }}
@endsection

