<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <!-- Логотип и кнопка «Гамбургер» -->
    @isset($user) <i class="far fa-user text-success mr-2"></i> @endisset
    <a class="navbar-brand" href="https://involta.ru/">Involta</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbar-blog" aria-controls="navbar-blog"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Основная часть меню (может содержать ссылки, формы и прочее) -->
    <div class="collapse navbar-collapse" id="navbar-blog">
        <!-- Этот блок расположен слева -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('blog.index') }}">Блог</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Теги</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Контакты</a>
            </li>
        </ul>
        <!-- Этот блок расположен посередине -->
        <form class="form-inline my-2 my-lg-0" action="{{ route('search.results') }}">
            <input name="query" class="form-control mr-sm-2" type="search"
                   placeholder="Поиск по блогу" aria-label="Search">
            <button class="btn btn-outline-info my-2 my-sm-0"
                    type="submit">Искать</button>
        </form>
        <!-- Этот блок расположен справа -->
        <ul class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.login') }}">Войти</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.register') }}">Регистрация</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.index') }}">{{ auth()->user()->name }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.logout') }}">Выйти</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
