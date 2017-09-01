<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Validações

O Laravel oferece várias abordagens diferentes para validar os dados recebidos do seu aplicativo. Por padrão, a classe de controlador base da Laravel usa uma característica **ValidatesRequests** que fornece um método conveniente para validar solicitação HTTP recebida com uma variedade de poderosas regras de validação.

## Definindo as rotas

Primeiro temos que definir as rotas:

```PHP
Route::get('post/create', 'PostController@create');

Route::post('post', 'PostController@store');
```

> A rota GET exibirá um formulário para o usuário criar uma nova postagem no blog, enquanto a rota POST irá armazenar a nova postagem do blog no banco de dados.

## Criando o Controller

Em seguida, vamos dar uma olhada em um controlador simples que lida com essas rotas. Deixaremos os métodos vazios por enquanto:

```PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Show the form to create a new blog post.
     *
     * @return Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate and store the blog post...
    }
}
```

Agora, estamos prontos para preencher o nosso método **store** com a lógica para validar a nova postagem do blog. Se você examinar a classe de controlador base **( App\Http\Controllers\Controller )** do seu aplicativo, você verá que a classe usa uma característica **ValidatesRequests**. Essa característica fornece um método de validação conveniente para todos os seus controladores.

Para obter uma melhor compreensão do método de validação, vamos voltar para o método store:

```PHP
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // The blog post is valid, store in database...
}
```

Como você pode ver, simplesmente passamos a solicitação HTTP recebida e as regras de validação desejadas para o método validado. Novamente, se a validação falhar, a resposta adequada será gerada automaticamente. Se a validação for passada, nosso controlador continuará executando normalmente.

### Parando na primeira falha de validação

Às vezes, você pode querer parar de executar as regras de validação em um atributo após a primeira falha de validação. Para fazer isso, atribua a regra de fiança ao atributo:

```PHP
'title' => 'bail|unique:posts|max:255',
```

### Sobre atributos aninhados

Se sua solicitação HTTP contiver parâmetros "aninhados", você pode especificá-los em suas regras de validação usando a sintaxe "ponto":

```PHP
$this->validate($request, [
    'title' => 'required|unique:posts|max:255',
    'author.name' => 'required',
    'author.description' => 'required',
]);
```

### Exibindo os erros de validação

Então, e se os parâmetros de solicitação recebidos não passarem nas regras de validação fornecidas? Como mencionado anteriormente, o Laravel redirecionará automaticamente o usuário de volta para sua localização anterior. Além disso, todos os erros de validação serão automaticamente ativados para a sessão.

Agora vamos criar nossa view para testar o controller e nossas rotas, lembrando que o usuário será redirecionado para o método de criação do nosso controlador quando a validação falhar, permitindo que exibamos as mensagens de erro na visualização:

```HTML
<!-- /resources/views/post/create.blade.php -->

<h1>Create Post</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create Post Form -->
```
