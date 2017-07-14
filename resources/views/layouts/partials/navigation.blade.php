<div class="page-sidebar" id="main-menu">
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">

        {{-- MINI-PROFILE --}}
        <div class="user-info-wrapper">
            <div class="profile-wrapper">
                @if (Auth::user()->photo)
                    <img src="{{ asset( "img/profile/".Auth::user()->photo) }}" alt="" width="69" height="69"/>
                @else
                    <img src="{{ asset('img/profile/default.png') }}" alt="" width="69" height="69">
                @endif
            </div>
            <div class="user-info">
                <div class="greeting">Bem vindo</div>
                <div class="username">{{ head(explode(" ", Auth::user()->name)) }}</div>
                <div class="status">
                    {{-- Ver perfil --}}
                </div>
            </div>
        </div>

        {{-- SIDEBAR MENU --}}
        <p class="menu-title">NAVEGAÇÃO</p>
        <ul>
            <li>
                {{-- label --}}
                <a href="/">
                    <i class="fa fa-tachometer"></i>
                    <span class="title">Dashboard</span>
                </a>
            @foreach ($nav as $module)
                {{-- module --}}

                @if((\Illuminate\Support\Facades\Auth::user()->hasModule($module->slug) > 0) or (\Illuminate\Support\Facades\Auth::user()->hasRole('Admin')))
                    <li @if ((isset($currentModule)) and ($module->url == $currentModule->url)) class="active" @endif>
                        {{-- label --}}
                        <a href="{{ url($module->url) }}">
                            <i class="fa fa-{{ $module->icon }}"></i>
                            <span class="title">{{ $module->name }}</span>

                            @if (count($module->submodules) > 0)
                                <span class="arrow"></span>
                            @endif
                        </a>

                        {{-- submodules --}}
                        @if (count($module->submodules) > 0)
                            <ul class="sub-menu">
                                @foreach ($module->submodules as $submodule)

                                        <li>
                                            <a href="{{ url($module->url.$submodule->url) }}">{{ $submodule->name }}</a>
                                        </li>

                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach


            {{-- Configs for Mobile --}}
            @if ((!Auth::user()->hasRole('Usuário')) and (count($navConfig) > 0))
                <li class="visible-xs divider"></li>
                <li class="visible-xs">
                    <a href="{{ route('config.index') }}">
                        <i class="fa fa-cog"></i>
                        <span class="title">Configurações</span>
                    </a>
                </li>
            @endif
        </ul>

        {{-- FOLDER MENU --}}
        <!--<div class="side-bar-widgets">
            <p class="menu-title">PRODUTIVIDADE</p>
            <ul class="folders">
                <li>
                    <a href="https://webmail.{{ str_replace(['http://', 'https://'], "", config('app.url')) }}"
                       target="_blank">
                        <div class="status-icon red"></div>
                        Webmail
                    </a>
                </li>
            </ul>
        </div>-->
    </div>
</div>
