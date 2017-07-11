<li class="{{ Request::is('professorUsers*') ? 'active' : '' }}">
    <a href="{!! route('professorUsers.index') !!}"><i class="fa fa-edit"></i><span>Professores</span></a>
</li>

<li class="{{ Request::is('cursos*') ? 'active' : '' }}">
    <a href="{!! route('cursos.index') !!}"><i class="fa fa-edit"></i><span>Cursos</span></a>
</li>

<li class="{{ Request::is('contatos*') ? 'active' : '' }}">
    <a href="{!! route('contatos.index') !!}"><i class="fa fa-edit"></i><span>Contatos</span></a>
</li>
