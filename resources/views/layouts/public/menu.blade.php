@php
    $pages = App\Utility::generate_menu();
@endphp
<nav class="navbar navbar-main navbar-expand-lg navbar-light bg-white">
    <div class="navbar-main__head">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/svg/logo.svg" alt="" style="width: 250px">
        </a>
    </div>

    <div class="navbar-collapse navbar-main__container" id="navbarSupportedContent">
        <div class="navbar-main__topbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">F</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">T</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ln</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            </form>
        </div>
        <div class="navbar-main__menubar">
            <ul class="navbar-nav mr-auto">
                @foreach ($pages as $key => $page)
                    @if ($page->sub_pages->count() == 0 || $page->slug == $active )
                        <li class="nav-item">
                            <a class="nav-link {{ $page->slug == $active ? 'active' : null }}" href="{{ url($page->slug) }}">{{ $page->title }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $page->title }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach ($page->sub_pages as $key => $sub)
                                    <a class="dropdown-item" href="{{ url($page->slug.'/'.$sub->slug) }}">
                                        {{ $sub->title }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @foreach ($pages as $key => $page)
            @if ($page->slug == $active)
                <div class="navbar-main__submenu">
                    <ul class="navbar-nav mr-auto">
                        @foreach ($page->sub_pages as $key => $sub)
                            <li class="nav-item">
                                <a class="nav-link {{ $sub->slug == $subactive ? 'active' : null }}" href="{{ url($page->slug.'/'.$sub->slug) }}">{{ $sub->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </div>
</nav>
