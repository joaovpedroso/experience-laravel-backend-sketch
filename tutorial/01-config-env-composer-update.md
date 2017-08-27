<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Atualizando o composer e configurando o arquivo .env

## Criando o arquivo .env e alterando as linhas abaixo conforme a configuração do banco de dados local:

> DB_DATABASE=homestead
> DB_USERNAME=homestead
> DB_PASSWORD=secret

## Atualizando o composer.json do projeto

> composer update

<hr>

## Gerando a APP-Key

> php artisan key:generate

<hr>

## Inserindo nosso user administrador

> database->seeds->UserTableSeeder

É necessário alterar os atributos **name**, **email** e **password**. Este será o nosso acesso ao painel administrador do Blog.

<hr>

## Subindo as tabelas de user no phpmyadmin

```PHP
php artisan migrate:refresh --seed
```
