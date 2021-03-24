<div class="card mb-4">
    <div class="card-header">
        <h2>{{ $post->name }}</h2>
    </div>
    <div class="card-body">
{{--        @if($post->user_id == 1)--}}
            <img src="{{ Storage::url('public/post/admin/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">
{{--        @else--}}
{{--            <img src="{{ Storage::url('public/post/user/'.$post->image) ?? asset('img/404.jpg') }}" alt="" class="img-fluid">--}}
{{--        @endif--}}

        <p class="mt-3 mb-0">{{ $post->excerpt }}</p>
        <br/>
    </div>
    <div class="card-footer">
        <div class="clearfix">
            <span class="float-left">
                Автор:
                <a href="{{ route('blog.author', ['user' => $post->user->id]) }}">
                    {{ $post->user->name }}
                </a>
                <br>
                Дата: {{ $post->created_at }}
            </span>
            <span class="float-right">
                <a href="{{ route('blog.post', ['post' => $post->slug]) }}"
                   class="btn btn-dark">Читать дальше</a>
            </span>
        </div>
    </div>
    @if ($post->tags->count())
        <div class="card-footer">
            Теги:
            @foreach($post->tags as $tag)
                @php($comma = $loop->last ? '' : ' • ')
                <a href="{{ route('blog.tag', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>
                {{ $comma }}
            @endforeach
        </div>
    @endif
</div>
