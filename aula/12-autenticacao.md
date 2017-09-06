<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Autenticação

Por padrão, o Laravel inclui um modelo **App/User** Eloquent no diretório do seu aplicativo. Este modelo pode ser usado com o driver de autenticação Eloquent padrão. Se o seu aplicativo não estiver usando o Eloquent, você pode usar o driver de autenticação **database** que usa o construtor de consultas Laravel.

Ao construir o esquema database para o modelo App\User, verifique se a coluna da senha tem pelo menos 60 caracteres. Manter o comprimento da coluna de cadeia padrão de 255 caracteres seria uma boa escolha.

Além disso, você deve verificar se a tabela de seus users (ou equivalente) contém uma coluna de 100 caracteres de **remember_token** nula. Esta coluna será usada para armazenar um token para usuários que selecionam a opção "lembrar-me" ao fazer login em seu aplicativo.

O Laravel é fornecido com vários controladores de autenticação pré-construídos, que estão localizados no espaço de nome **App\Http\Controllers\Auth**. O **RegisterController** lida com o novo registro de usuário, o **LoginController** lida com a autenticação, o **ForgotPasswordController** lida com links de e-mail para reiniciar senhas e o **ResetPasswordController** contém a lógica para redefinir senhas. Cada um desses controladores usa uma característica para incluir seus métodos necessários. Para muitas aplicações, você não precisará modificar esses controladores.

## Roteamento

Laravel fornece uma maneira rápida de armazenar todas as rotas e visualizações que você precisa para autenticação usando um comando simples:

```PHP
php artisan make:auth
```
Este comando deve ser usado em novos aplicativos e irá instalar uma exibição de layout, registro e login, bem como rotas para todos os pontos finais de autenticação. Um **HomeController** também será gerado para lidar com solicitações de início de sessão no painel do aplicativo.

## Views

Conforme mencionado na seção anterior, o comando  _php artisan make:auth_ criará todas as visualizações que você precisa para autenticação localizadas em **resources/views/auth**.

O comando _make:auth_ também criará um diretório **resources/views/layouts** contendo um layout de base para sua aplicação. Todos esses pontos de vista usam o framework CSS do Bootstrap, mas você pode personalizá-los como desejar.