<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Controladores

Após a configuração das rotas, podemos criar nossos controladores, conhecidos como controllers. Serão nos controllers que vamos fazer o CRUD e os métodos que trata as oções da nossa view.

Veja abaixo um exemplo de controlador:

```PHP
<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
```

> Observe que nossa class User extende uma classe Controller, essa classe é um padrão do Laravel que contém, alguns métodos prontos que podemos chamar em nosso controller.

> Veja que no nosso controller temos o método **show**, ele serve para mostrar nossos usuários.

Nossa rota ficaria assim:

```PHP
Route::get('user/{id}', 'UserController@show');
```

Veja que agora declaramos o nosso controller no segundo parâmetro e ainda indiciamos o método que vai ser executado ao acessar o caminho **user/{id}**

## Criando um Controller com o comando Artisan

Suponhamos que vamos criar um controller de fotos, para evitar de entrar na pasta e manuzear o mouse para criar o arquivo .php, vamos digitar os camandos que o próprio Laravel nos fornece. Para criar um controller usamos o make do Artisan indiciando que é um controller e o nome do controlle que queremos:

```PHP
php artisan make:controller PhotoController --resource
```

Se verificar na pasta Controller o nosso arquivo **PhotoController** está lá com as pré configurações prontas para serem utilizadas.

## Criando uma rotacom as açõs já pré definidas do Laravel

O Laravel veio para nos ajudar e tenso esse pensamento, ele criou um método para a rota capaz de definir já alguns métodos, assim, não precisamos ficar criando uma rota para o método create, outra para o update e assim por diante, basta declararmos a rota dessa maneira:

```PHP
Route::resource('photos', 'PhotoController');
```

Pronto, temos os seguintes métodos já pré-definidos na rota:

<img src="http://i.imgur.com/ubcdHA4.png" alt="Laravel 5.4 rotas pré definidas" width="650px">

