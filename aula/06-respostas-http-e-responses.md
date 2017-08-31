<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Request

Para obter uma resposta HTTP atual via dependência, você deve adicionar a **Illuminate\Http\Request** no seu controller. A instância de solicitação recebida será automaticamente injetada pelo contêiner do serviço.

Se o método do seu controlador também estiver esperando a entrada de um parâmetro que está configurado na rota, você deve listar seus parâmetros rota após suas outras dependências. Vamos na pratica, para entender melhor. Vamos criar uma rota recebendo uma entrada (um parâmetro), que no caso será um id de user:

```PHP
Route::put('user/{id}', 'UserController@update');
```

> Observe que estamos fazendo o método put na rota e no nosso controller deve ter o método update para realizar esta tarefa.

Para realizar uma injeção de dependência vamos adicionar no nosso controller o request do próprio Laravel: **Illuminate\Http\Request**

No nosso controller, onde temos o update, vamos adicionar como parâmetro o request e nosso id que está localizado em nossa rota:

```PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified user.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
```

Os métodos request você consegue trabalhar com os inputs, arquivos (pode ser de fotos também), verificar se é um arquivo, trabalhar com cookie, entre outros. Os exemplos serão aplicados diretamente no projeto do curso, com mais detalhes.

# Criando Respostas

Todas as rotas e controladores devem retornar uma resposta para serem enviadas de volta ao navegador do usuário.

Laravel fornece várias maneiras diferentes de retornar as respostas. A resposta mais básica é simplesmente retornar uma string de uma rota ou controlador. A estrutura converterá automaticamente a string em uma resposta HTTP completa:

```PHP
Route::get('/', function () {
    return 'Hello World';
});
```

Além de retornar cadeias de caracteres de suas rotas e controladores, você também pode retornar arrays. A estrutura converterá automaticamente a matriz em uma resposta JSON:

```PHP
Route::get('/', function () {
    return [1, 2, 3];
});
```
## Redirecionamentos

As respostas de redirecionamento são instâncias da classe **Illuminate\Http\RedirectResponse** e contém os cabeçalhos apropriados necessários para redirecionar o usuário para outro URL. Existem várias maneiras de gerar uma instância do RedirectResponse. O método mais simples é usar o ajudante de redirecionamento global:

```PHP
Route::get('dashboard', function () {
    return redirect('home/dashboard');
});
```

## Redirecionando para rotas nomeadas

Quando você chama o **redirect** de redirecionamento sem parâmetros, uma instância do **Illuminate\Routing\Redirector** é retornada, permitindo que você ligue para qualquer método na instância do Redirecionador. Por exemplo, para gerar uma **RedirectResponse** para uma rota nomeada, você pode usar o método de rota:

```PHP
return redirect()->route('login');
```
Se sua rota tiver parâmetros, você pode passá-los como o segundo argumento para o método de rota:

```PHP
return redirect()->route('profile', ['id' => 1]);
```
