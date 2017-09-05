<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Eloquent ORM

O **Eloquent ORM** incluído com o Laravel fornece uma implementação **ActiveRecord** simples, para trabalhar com seu banco de dados. 
Cada tabela de banco de dados tem um "Model" correspondente que é usado para interagir com essa tabela. Models permitem consultar dados em suas tabelas, bem como inserir novos registros na tabela. 

Antes de começar, certifique-se de configurar uma conexão de banco de dados em **config/database.php**.

## Definindo Models

Para começar, vamos criar um Eloquent Model. Os Models por padrão fica em **APP**, mas você pode colocá-los em qualquer lugar que possa ser carregado automaticamente de acordo com seu arquivo composer.json. Todos os models Eloquent estendem de **Illuminate\Database\Eloquent\Model**.

A maneira mais fácil de criar uma instância de modelo é usando o **make:model** do Artisan:

```PHP
php artisan make:model User
```

É possível criar uma migração ao criar o model, basta digitar no final **--migration** ou **-**:

```PHP
php artisan make:model User -m
```
## Convenções do Eloquent Model

Agora, vejamos um exemplo de modelo, que usaremos para recuperar e armazenar informações da nossa tabela de banco de dados de users:

```PHP
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
}
```

### Nome de Tabelas

Observe que não dissemos ao Eloquent qual tabela usar para o modelo de User. Por convenção, o nome plural da classe será usado como o nome da tabela, a menos que outro nome esteja explicitamente especificado. Então, neste caso, o Eloquent assumirá os registros das lojas do modelo User na tabela. Você pode especificar uma tabela personalizada definindo uma propriedade de tabela em seu modelo:

```PHP
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'my_users';
}
```
### Adicionando adicionais

O método **all** retornará todos os resultados na tabela do modelo. Uma vez que cada modelo Eloquent serve como um construtor de consultas, você também pode adicionar restrições às consultas e, em seguida, usar o método **get** para recuperar os resultados:

```PHP
$user = App\User::where('active', 1)
               ->orderBy('name', 'desc')
               ->take(10)
               ->get();
```

> Através do código iremos explorar melhor os métodos e como funciona o CRUD no controller