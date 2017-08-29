<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Criando os menus

Agora precisamos criar dois botões, um para acessar o **index** do **blog** e um outro para acessar o **index** das **categorias**. Na pasta **resource->views**, já temos um arquivo para realizarmos esta tarefa, acesse a pasta **layouts** e abra o arquivo **menu.blade.php**.

Dentro desse arquivo, coloque o código abaixo:

```PHP
<li class="{{ Request::is('newspapers*') ? 'active' : '' }}">
    <a href="{!! route('newspapers.index') !!}"><i class="fa fa-edit"></i><span>Blog</span></a>
</li>
<li class="{{ Request::is('categories*') ? 'active' : '' }}">
    <a href="{!! route('newspapers.categories.index') !!}"><i class="fa fa-edit"></i><span>Categoria</span></a>
</li>
```

