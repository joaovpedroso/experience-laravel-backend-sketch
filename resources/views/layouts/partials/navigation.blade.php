<div class="page-sidebar" id="main-menu">
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">

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
    </div>
</div>
