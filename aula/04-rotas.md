<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Arquitetura Laravel

As rotas servem para atribuirmos o caminho da página e o controller que será trabalhado na página, para testarmos isso na prática vamos criar uma rota de teste. Entre na pasta **routes/web.php** e coloque o código abaixo:

```PHP
Route::get('page', function(){
	return 'Hello World';
});
```
> O primeiro parâmetro é o nome da nossa rota, se digitarmos no navegador /page, ele irá acessar esta rota!
> O segundo parâmetro fizemos uma função que retorna um Hello World, podemos colocar no lugar da função o nome do nosso controller e o método, mais pra frente vamos ver isso.

### Exemplos de métodos para a rotas

As rotas tem métodos que respondem a qualquer tipo de verdo (chamada) HTTP, isso é ótimo para realizarmos o CRUD.

```PHP
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
```

## Proteção CSRF

Qualquer formulário HTML que aponte para as rotas POST, PUT ou DELETE que são definidas no arquivo de rotas da Web deve incluir um campo token CSRF. Caso contrário, o pedido será rejeitado. Veja um exemplo prático que deve ser incluso no HTML:

```PHP
<form method="POST" action="/profile">
    {{ csrf_field() }}
    ...
</form>
```
## Parâmetros para as rotas

Muitas vezes vamos precisar criar rotas que precisaremos pegar identificação (conhecido como id, nos banco de dados) de um usuário, produto etc... Para isso é necessário passar na rota como parâmetro, veja um exemplo com parâmetro:

```PHP
Route::get('user/{id}', function ($id) {
    return 'User '.$id;
});
```

## Restrição com Expressão Regular nas rotas

Podemos precisar restringir alguns caracteres nas nossas rotas, pode usar expressões regulares para isto, com o método **where** podemos ter essa liberdade para criar nossas regras de caracteres dentro das rotas.

```PHP
Route::get('user/{id}', function ($id) {
    //
})->where('id', '[0-9]+');
```

> Se digitarmos uma letra ao invés de números perceba que entrará em uma página de erro.

## Atribuindo nome para as rotas

Podemos atribuir nomes para as rotas, isto é muito bom na hora de trabalhar com a rota no controller, uma vez definida na rota podemos chamar pelo nome a página, para isto vamos usar o método **name()**

```PHP
Route::get('user/', function () {
    //
})->name('pageUser');
```

Em um controller seria chamado dessa maneira:

```PHP
...
return redirect()->route('pageUser');
...
```

Se a rota nomeada define parâmetros (identificadores), você pode passar os parâmetros como o segundo argumento para a função de rota. Os parâmetros fornecidos serão automaticamente inseridos no URL em suas posições corretas:

```PHP
Route::get('user/{id}/profile', function ($id) {
    //
})->name('profile');

$url = route('profile', ['id' => 1]);
```
## Grupo de Rotas

Os grupos de rotas permitem que você compartilhe atributos de rota, como middleware ou namespaces, em um grande número de rotas sem precisar definir esses atributos em cada rota individual. Os atributos compartilhados são especificados em um formato de matriz como o primeiro parâmetro para o método Route::group

### Middleware

Para atribuir middleware a todas as rotas dentro de um grupo, você pode usar o método middleware antes de definir o grupo. Middleware são executados na ordem em que estão listados na matriz:

```PHP
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // 
    });

    Route::get('user/profile', function () {
        //
    });
});
```

