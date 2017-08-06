# Instalação:

```
git clone https://github.com/AlexMCoder/experience-laravel-backend-sketch
```

# Liberando acesso administrador no projeto (apenas no linux)

```
sudo chmod 777 -R experience-laravel-backend-sketch
```

# Instalando o composer no projeto

```
composer install
```

# Configurando o arquivo Env

 O arquivo .env serve para definirmos configurações padrões do nosso projeto, em nosso sketch já vem um arquivo com o nome .env.example, este é um exemplo de configuração que o Laravel disponibiliza. Agora precisamos criar o nosso, para isso é necessário abrir o projeto em uma IDE de sua preferência.

 Inicialmente precisamos apenas de copiar tudo que tem dentro do arquivo .env.example para o nosso novo arquivo .env

# Criando um banco de dados

Antes de realizarmos mais algumas configurações no .env, precisamos primeiro criar nosso banco de dados. Para isso acesse o phpmyadmin e crie um banco de dados, geralmente o nome do banco é o nome do site.

# Configurando o banco de dados no .env

Agora precisamos abrir nosso arquivo .env que criamos nos tópicos anteriores e configurar os seguintes atributos:

* DB_DATABASE=
* DB_USERNAME=
* DB_PASSWORD=

Esses são os dados do phpmyadmin

# Gerando uma key no arquivo Env

Precisamos gerar uma key em nosso arquivo env para realizar a validação da aplicação, para isso no terminal digite: 

```
php artisan key:generate
```

# Alterando o e-mail e senha para acessar o painel

*Pasta*: **database - > seeds -> UsersTableSeeder.php**

# Ativando o seed

Para termos acesso a usuários e login dentro do Painel Administrativo do Sistema, precisamos digitar o seguinte comando:

```
php artisan migrate --seed
```

# Executando o projeto

Feito todos as configurações dos tópicos anteriores, agora podemos iniciar o projeto no navegador. Para isso, digite no terminal:

```
php artisan serve
```
