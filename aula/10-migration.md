<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Migrations

As migrações são como controle de versão para o seu banco de dados, permitindo que sua equipe facilmente modifique e compartilhe o esquema do banco de dados do aplicativo. As migrações geralmente são combinadas com o construtor de esquema do Laravel para criar facilmente o esquema de banco de dados do aplicativo. Se você já teve que dizer a um colega de equipe para adicionar manualmente uma coluna ao seu esquema de banco de dados local, você enfrentou o problema que as migrações de banco de dados resolvem.

# Gerando as Migrações

Para criar uma migration usamos o comando do artisan:

```PHP
php artisan make:migration create_users_table
```
A nova migração será colocada no seu **database/migrations**. Cada nome de arquivo de migração contém um identificador de data / hora que permite que Laravel determine a ordem das migrações.

# Subindo Migrações

Para executar todas as migrações, digite:

```PHP
php artisan migrate
```
## Forçando as migrações a subirem para produção

Algumas operações de migração são destrutivas, o que significa que elas podem fazer com que você perca dados. Para protegê-lo de executar esses comandos em seu banco de dados de produção, você será solicitado a confirmar antes de os comandos serem executados. Para forçar os comandos a serem executados sem um prompt, use o sinalizador --force:

```PHP
php artisan migrate --force
```
## Usando o Rollback

Para reverter a última operação de migração, você pode usar o comando rollback. Este comando reverte o último "lote" de migrações, que pode incluir vários arquivos de migração:

```PHP
php artisan migrate:rollback
```

Você pode reverter um número limitado de migrações fornecendo a opção de etapa para o comando rollback. Por exemplo, o seguinte comando irá reverter as últimas cinco migrações:

```PHP
php artisan migrate:rollback --step=5
```

O comando migrate:reset irá reverter todas as migrações do seu aplicativo:

```PHP
php artisan migrate:reset
```

## Rollback e Migrate em um único comando

O comando migrate:refresh reverterá todas as suas migrações e, em seguida, executará o comando migrate. Esse comando efetivamente recria o banco de dados inteiro:

```PHP
php artisan migrate:refresh --seed
```

# Criando Tabelas

Para criar uma nova tabela de banco de dados, use o método **create** na **Schema**. O método **create** aceita dois argumentos. O primeiro é o nome da tabela, enquanto o segundo é um **Closure** que recebe um objeto **Blueprint** que pode ser usado para definir a nova tabela:

```PHP
Schema::create('users', function (Blueprint $table) {
    $table->increments('id');
});
```

## Verificando se existe uma Tabela ou uma Coluna

É possível verificarmos se existe uma tabela ou coluna com um determinado nome:

```PHP
if (Schema::hasTable('users')) {
    //
}

if (Schema::hasColumn('users', 'email')) {
    //
}
```
## Motor de Conexão e Armazenamento

Se você quiser executar uma operação de Schema em uma conexão de banco de dados que não é sua conexão padrão, use o método de conexão:

```PHP
Schema::connection('foo')->create('users', function (Blueprint $table) {
    $table->increments('id');
});
```

Você pode usar a propriedade engine no construtor de esquema para definir o mecanismo de armazenamento da tabela:

```PHP
Schema::create('users', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->increments('id');
});
```

## Método Renaming

Para renomear uma tabela de banco de dados existente, use o método renomear:

```PHP
Schema::rename($from, $to);
```

## Tipos de colunas disponíveis no Laravel

Dentro das nossas tabelas podemos declarar os atributos e os seus tipos que existirá na tabela, [Clique aqui](https://laravel.com/docs/5.4/migrations#creating-columns) e veja a lista dos tipos.

## Modicando Colunas

As vezes precisamos alterar uma das colunas, como por exemplo o tamanho de um atributo nome. O Laravel é incrivel fazendo isso com o doctrine/dbal, basta instalar em nossas dependencias:

```PHP
composer require doctrine/dbal
```

### Alterando um atributo

O método change permite que você modifique alguns tipos de colunas existentes para um novo tipo ou modifique os atributos da coluna. Por exemplo, você pode aumentar o tamanho de uma coluna de string. Para ver o método de mudança em ação, vamos aumentar o tamanho da coluna do nome de 25 para 50:

```PHP
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 50)->change();
});
```

Podemos também deixar o tipo de um atributo null:

```PHP
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 50)->nullable()->change();
});
```

