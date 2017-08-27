<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Atualizando o composer e configurando o arquivo .env

## Atualizando o composer.json do projeto

> composer update

## Criando o arquivo .env e alterando as linhas abaixo conforme a configuração do banco de dados local:

> DB_DATABASE=homestead
> DB_USERNAME=homestead
> DB_PASSWORD=secret

<hr>

## Gerando a APP-Key

> php artisan key:generate

<hr>

## Inserindo nosso user administrador

> database->seeds->UserTableSeeder

É necessário alterar os atributos **name**, **email** e **password**. Este será o nosso acesso ao painel administrador do Blog.
