<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Arquitetura Laravel

Precisamos entender como funciona a arquitetura Laravel, suas pastas e seus arquivos, para isso deixo separado abaixo as mais importantes por enquanto:

1. Diretório Public
2. Diretório Config
3. Diretório Storage

## Entendendo o uso do arquivo .env.example

No nosso arquivo env, fica as configurações mais importante do banco, como por exemplo o nome e o usuário que acessa o banco de dados.

É nesse arquivo que temos que gerar nossa APP_KEY, que irá trabalhar com os caches do nosso projeto. Para gerar, basta digitar o comando:

```PHP
php artisan key:generate
```

>Após realizar essas pequenas configurações, você já pode desenvolver seu projeto.

## Configuração do cache.php

Na pasta **config/cache.php**, é uma API unificada, configurada para os cache do backend. Neste arquivo, você pode especificar qual driver de cache você gostaria de utilizar e ao longo da sua aplicação você pode modificar.

Por padrão, o Laravel está configurado para usar o driver de cache de arquivos, que armazena os objetos em série e em cache no sistema de arquivos.

Além do cache de arquivos, o Laravel trabalhando muito bem com o Mencached e Redis.

Para saber qual drive está sendo rodado, basta verificar a seguinte linha no arquivo:

```PHP
'default' => env('CACHE_DRIVER', 'file'),
```


## Configuração da database.php

Na pasta **config/database.php**, é o local onde você pode definir várias conexões de banco de dados, inclusive o padrão para o projeto.

Para saber drive de DB está sendo rodado, basta verificar a seguinte linha no arquivo:

```PHP
'default' => env('DB_CONNECTION', 'mysql'),
```

## Configuração da Session.php

Na Pasta **config/session.php**, é localizado as configurações das sessões em nosso navegador. Por padrão e funcional em quase todos os projetos está o de arquivos. Mas dependendo o projeto quando estiver em produção é melhor utilizar um outro, como o Mencached ou Redis.

Para saber qual drive está sendo rodado, basta verificar a seguinte linha no arquivo:

```PHP
 'driver' => env('SESSION_DRIVER', 'file'),
```