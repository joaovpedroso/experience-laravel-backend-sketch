<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Criando Views

As views são nossas páginas HTML, o Laravel deixa a lógica do controller separado das lógicas de apresentação. As visualizações são armazenadas no diretório **resource**

Um exemplo simples, mostrando uma informação vinda do controller ou da nossa rota, seria:

```HTML
<html>
    <body>
        <h1>Hello, {{ $name }}</h1>
    </body>
</html>
```

Agora em uma rota por exemplo, podemos declarar isto facilmente, para que nossa variável $name receba um valor:

```PHP
Route::get('/', function () {
    return view('index', ['name' => 'alex']);
});
```

> Observe que em nossa rota, o primeiro parâmetro do método view é o nome do arquivo blade que está localizado na pasta resource. O segundo parâmetro é o valor que irá receber a variável

## Verificando se a View existe

Para verificarmos se uma view existe, podemos criar um condição if e utilizando o método exists para verificar:

```PHP
use Illuminate\Support\Facades\View;

if (View::exists('emails.customer')) {
    //
}
```
## Passando dados para a View

Como fizemos no primeiro exemplo, podemos paassar vários dados para a view:

```PHP
return view('greetings', ['name' => 'Victoria']);
```
Dessa forma estamos enviando em forma de array, podendo ter vários outros atributos. Podemos usar o método with para enviar dados individuais:

```PHP
return view('greeting')->with('name', 'Victoria');
```

