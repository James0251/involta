<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('js/menu.js') }}">
    <title>{{ $title ?? 'Панель управления' }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
</head>
<body>
<div class="container-fluid">
    <div class="vertical-nav bg-white" id="sidebar">
        <div class="py-4 px-3 mb-4 bg-light">
            <div class="media d-flex align-items-center"><img src="https://res.cloudinary.com/mhmd/image/upload/v1556074849/avatar-1_tcnd60.png" alt="..." width="65" class="mr-3 rounded-circle img-thumbnail shadow-sm">
                <div class="media-body">
                    <h4 class="m-0">{{ auth()->user()->name }}</h4>
                    <p class="font-weight-light text-muted mb-0">
                        {{ \Illuminate\Support\Facades\Auth::user()->roles()->first()->name }}
                    </p>
                </div>
            </div>
        </div>

        <p class="text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">Main</p>

        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <a href="{{ route('admin.index') }}" class="nav-link text-dark font-italic bg-light">
                    <i class="fa fa-th-large mr-3 text-primary fa-fw"></i>
                    Home
                </a>
            </li>
            @perm('manage-posts')
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('admin.post.index') }}">
                    <i class="fa fa-newspaper mr-3 text-primary" aria-hidden="true"></i>
                    Посты
                </a>
            </li>
            @endperm
            @perm('manage-comments')
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('admin.comment.index') }}">
                    <i class="fa fa-comment mr-3 text-primary" aria-hidden="true"></i>
                    Комментарии
                </a>
            </li>
            @endperm
            @perm('manage-categories')
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('admin.category.index') }}">
                    <i class="fa fa-sitemap mr-3 text-primary" aria-hidden="true"></i>
                    Категории
                </a>
            </li>
            @endperm
            @perm('manage-tags')
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('admin.tag.index') }}">
                    <i class="fa fa-hashtag mr-3 text-primary" aria-hidden="true"></i>
                    Теги
                </a>
            </li>
            @endperm
            @perm('manage-users')
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('admin.user.index') }}">
                    <i class="fa fa-user mr-3 text-primary" aria-hidden="true"></i>
                    Пользователи
                </a>
            </li>
            @endperm
            <li class="nav-item">
                <a class="nav-link text-dark font-italic bg-light" href="{{ route('auth.logout') }}">
                    <i class="fa fa-sign-out mr-3 text-primary" aria-hidden="true"></i>
                    Выйти
                </a>
            </li>
        </ul>
    </div>

    <div class="page-content p-5" id="content">
        <!-- Demo content -->
{{--        <div class="row clearfix">--}}
{{--            <div class="col-lg-3 col-md-6 col-sm-6">--}}
{{--                <div class="card card-stats">--}}
{{--                    <div class="card-header card-header-warning card-header-icon">--}}
{{--                        <div class="card-icon">--}}
{{--                            <i class="material-icons">content_copy</i>--}}
{{--                        </div>--}}
{{--                        <p class="card-category">Used Space</p>--}}
{{--                        <h3 class="card-title">49/50--}}
{{--                            <small>GB</small>--}}
{{--                        </h3>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer">--}}
{{--                        <div class="stats">--}}
{{--                            <i class="material-icons text-danger">warning</i>--}}
{{--                            <a href="#pablo">Get More Space...</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-3 col-md-6 col-sm-6">--}}
{{--                <div class="card card-stats">--}}
{{--                    <div class="card-header card-header-success card-header-icon">--}}
{{--                        <div class="card-icon">--}}
{{--                            <i class="material-icons">store</i>--}}
{{--                        </div>--}}
{{--                        <p class="card-category">Revenue</p>--}}
{{--                        <h3 class="card-title">$34,245</h3>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer">--}}
{{--                        <div class="stats">--}}
{{--                            <i class="material-icons">date_range</i> Last 24 Hours--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-3 col-md-6 col-sm-6">--}}
{{--                <div class="card card-stats">--}}
{{--                    <div class="card-header card-header-danger card-header-icon">--}}
{{--                        <div class="card-icon">--}}
{{--                            <i class="material-icons">info_outline</i>--}}
{{--                        </div>--}}
{{--                        <p class="card-category">Fixed Issues</p>--}}
{{--                        <h3 class="card-title">75</h3>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer">--}}
{{--                        <div class="stats">--}}
{{--                            <i class="material-icons">local_offer</i> Tracked from Github--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-3 col-md-6 col-sm-6">--}}
{{--                <div class="card card-stats">--}}
{{--                    <div class="card-header card-header-info card-header-icon">--}}
{{--                        <div class="card-icon">--}}
{{--                            <i class="fa fa-twitter"></i>--}}
{{--                        </div>--}}
{{--                        <p class="card-category">Followers</p>--}}
{{--                        <h3 class="card-title">+245</h3>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer">--}}
{{--                        <div class="stats">--}}
{{--                            <i class="material-icons">update</i> Just Updated--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="separator"></div>--}}

        <div class="row">
            <div class="col">
                @if ($message = session('success'))
                    <div class="alert alert-success alert-dismissible mt-0" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $message }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible mt-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>
</div>




<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
