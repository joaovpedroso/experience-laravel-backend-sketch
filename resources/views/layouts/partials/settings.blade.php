{{-- USER NAME --}}
<a href="#" class="dropdown-toggle" id="user-options" data-placement="bottom" data-toggle="dropdown">
  <div class="user-details">
    <div class="username">
      {{-- first name --}}
      {{ head(explode(" ", Auth::user()->name)) }}

      {{-- last name --}}
      @if (count(explode(" ", Auth::user()->name)) > 1)
        <span class="bold">
          {{ last(explode(" ", Auth::user()->name)) }}
        </span>
      @endif
    </div>
  </div>
  <div class="iconset top-down-arrow"></div>
</a>

{{-- NAVIGATION --}}
<ul class="dropdown-menu user-options" role="menu" aria-labelledby="user-options">
   <li>
      <a href="{{ route('profile.edit') }}">Editar Perfil</a>
   </li>
   <li>
     <a href="{{ route('profile.password.edit') }}">Alterar Senha</a>
   </li>

  <li class="divider"></li>

  <li>
    <a href="{{ url('/logout') }}">
      <i class="fa fa-power-off"></i>
      Logout
    </a>
  </li>
</ul>
