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

