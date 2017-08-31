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