<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Introdução

Laravel é um Framework PHP utilizado para o desenvolvimento web, que utiliza a arquitetura MVC e tem como principal característica ajudar a desenvolver aplicações seguras e performáticas de forma rápida, com código limpo e simples, já que ele incentiva o uso de boas práticas de programação.

Para a criação de interface gráfica, o Laravel utiliza uma Engine de template chamada Blade, que traz uma gama de ferramentas que ajudam a criar interfaces bonitas e funcionais de forma rápida e evitar a duplicação de código.

### Por que Laravel?

1. Documentação completa e fácil de ser compreendida
2. A curva de aprendizado é muito pequena e a mais plana
3. O engajamento da comunidade de desenvolvedores que utilizam (e amam) o Laravel é bastante grande

### O que preciso fazer para rodar o Laravel?

O Laravel utiliza Composer para gerenciar suas dependências. Então, antes de usar o Laravel, certifique-se de ter o Composer instalado em sua máquina.

É possível ele rodar no Linux e no Windows, basta ter instalado no computador o [composer](https://getcomposer.org/).

##### Instalando Laravel via composer

Pelo próprio composer, vamos instalar o Laravel, digitando o seguinte comando:

```PHP
composer global require "laravel/installer"
```

E para criar um projeto de teste, digite apenas o comando abaixo:

```PHP
laravel new blog
```
>Outra possibilidade de instalar e criar o projeto é digitando o comando abaixo, sem precisar dos comandos anteriores:

```PHP
composer create-project --prefer-dist laravel/laravel blog
```

No Windows, é mais seguro inserir a versão do laravel junto a instalação:

```PHP
composer create-project laravel/laravel laravel "5.4.*"
```

Para rodar o projeto, acesse o arquivo do projeto criado, no exemplo o nome do projeto é blog, e digite o comando abaixo:

```PHP
php artisan serve
```
