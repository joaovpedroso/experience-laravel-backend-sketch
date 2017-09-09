# Desenvolvendo o módulo de contato

Agora que vimos como são os procedimentos de criação de módulos no painel administrativo, criação de controllers, rotas e views o nosso desafio agora é seguir os passos abaixo para desenvolver facilmente o módulo de contato. Depois teremos o exercicio 3 que desafia a estilização das páginas de contato.

## Construindo a estrutura Frontend

Vamos criar o front do painel, onde iremos receber nossos dados. No diretório **resource/views**, crie uma pasta chamada contatos.

Temos que criar quatro arquivos blades:

1. index.blade.php
2. show.blade.php
3. show_fields.blade.php
4. table.blade.php

## Criando o model para os Contatos

Com o comando do Artisan vamos criar nosso model, para gravar os dados dos contatos:

```PHP
php artisan make:model Contato --migration
```
No arquivo Contato criado em Models, vamos colocar o seguinte código:

```PHP
<?php
namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Contato
 * @package App\Models
 * @version May 30, 2017, 7:52 pm UTC
 */
class Contato extends Model
{
    use SoftDeletes;
    public $table = 'contatos';
    
    protected $dates = ['deleted_at'];
    public $fillable = [
        'nome',
        'email',
        'mensagem'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nome' => 'string',
        'email' => 'string',
        'mensagem' => 'string'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'email' => 'required|email',
        'mensagem' => 'exit'
    ];
    
}
```

Dentro de migrations, foi gerado um arquivo contatos, substitua o código com o de baixo:

```PHP
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('email');
            $table->text('mensagem');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contatos');
    }
}
```

## Subindo as tabela contato para o phpmyadmin

```PHP
php artisan migrate:refresh --seed
```
## Rotas para o Contato Controller/FRONT

Se acessarmos as nossas rotas em **routes/web.php**, observe que conforme os tutoriais anteriores já incluimos nossa rota de contato, estando assim:

```PHP
Route::resource('/contatos', 'Backend\ContatoController');
```

Como podemos observar esta rota está ligada ao controller do contato, mas precisamos também ter uma rota para o nosso front, onde iremos digitar os dados do formulário e receber no painel esses dados, para isso, fora da middleware do painel vamos criar duas rotas, a primeira é para abrir nossa página de formulário e a segunda é para realizar a tarefa de enviar os dados:

```PHP
Route::get('/contato', 'Frontend\ContatoController@index');
Route::post('/contato-enviar', 'Frontend\ContatoController@contatoEnvia')->name('save.contato');
```
## Criando os Controllers

Primeiro vamos criar nossa rota de contato para o painel:

```PHP
php artisan make:controller ContatoController
```
Mova o arquivo gerado para dentro do diretório Backend, e coloque o código abaixo:

```PHP
<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateContatoRequest;
use App\Http\Requests\UpdateContatoRequest;
use App\Models\Contato;
use App\Repositories\ContatoRepository;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
class ContatoController extends AppBaseController {
	/** @var  ContatoRepository */
	private $contatoRepository;
	public function __construct(ContatoRepository $contatoRepo) {
		$this->contatoRepository = $contatoRepo;
	}
	/**
	 * Display a listing of the Contato.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$this->contatoRepository->pushCriteria(new RequestCriteria($request));
		$contatos = $this->contatoRepository->all();
		return view('contatos.index')
			->with('contatos', $contatos);
	}
	/**
	 * Show the form for creating a new Contato.
	 *
	 * @return Response
	 */
	public function create() {
		return view('contatos.create');
	}
	/**
	 * Store a newly created Contato in storage.
	 *
	 * @param CreateContatoRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateContatoRequest $request) {
		$input = $request->all();
		$contato = $this->contatoRepository->create($input);
		Flash::success('Contato saved successfully.');
		return redirect(route('contatos.index'));
	}
	/**
	 * Display the specified Contato.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id) {
		$contato = $this->contatoRepository->findWithoutFail($id);
		if (empty($contato)) {
			Flash::error('Contato not found');
			return redirect(route('contatos.index'));
		}
		return view('contatos.show')->with('contato', $contato);
	}
	/**
	 * Show the form for editing the specified Contato.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id) {
		$contato = $this->contatoRepository->findWithoutFail($id);
		if (empty($contato)) {
			Flash::error('Contato not found');
			return redirect(route('contatos.index'));
		}
		return view('contatos.edit')->with('contato', $contato);
	}
	/**
	 * Update the specified Contato in storage.
	 *
	 * @param  int              $id
	 * @param UpdateContatoRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateContatoRequest $request) {
		$contato = $this->contatoRepository->findWithoutFail($id);
		if (empty($contato)) {
			Flash::error('Contato not found');
			return redirect(route('contatos.index'));
		}
		$contato = $this->contatoRepository->update($request->all(), $id);
		Flash::success('Contato updated successfully.');
		return redirect(route('contatos.index'));
	}
	/**
	 * Remove the specified Contato from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id) {
		$contato = $this->contatoRepository->findWithoutFail($id);
		if (empty($contato)) {
			Flash::error('Contato not found');
			return redirect(route('contatos.index'));
		}
		$this->contatoRepository->delete($id);
		Flash::success('Contato deleted successfully.');
		return redirect(route('contatos.index'));
	}
	public function contatoEnvia(Request $request) {
		$contato = Contato::create(['nome' => $request->name, 'email' => $request->email, 'mensagem' => $request->mensagem]);
		return redirect()->back();
	}
	public function contatoEnviaFront(){
		return view('contato');
	}
}
```

Agora precisamos criar o controller para o nosso front, para isso digite:

```PHP
php artisan make:controller ContatoController
```
Mova o arquivo gerado para a pasta Frontend dentro de controller e coloque o código abaixo:

```PHP
<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateContatoRequest;
use App\Http\Requests\UpdateContatoRequest;
use App\Models\Contato;
use App\Repositories\ContatoRepository;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
class ContatoController extends AppBaseController
{
    public function contatoEnvia(Request $request) {
		$contato = Contato::create(['nome' => $request->name, 'email' => $request->email, 'mensagem' => $request->mensagem]);
		return redirect()->back();
	}
	public function contatoEnviaFront(){
		return view('frontend.contato');
    }
    
    public function index(){
        return view('frontend.contato');
    }
}
```

Pronto, nossos controller tanto para o backend quanto o front já está pronto, agora vamos construir os arquivos blade.

## Construindo o layout

Vamos primeiro criar o layout do painel, onde será recebido os dados que os usuário irão escrever, no diretório **resource/views/contatos** dentro do arquivo index, coloque o código abaixo:

```HTML
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Contatos</h1>
        <h1 class="pull-right">
           {{-- <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('contatos.create') !!}">Add New</a> --}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('contatos.table')
            </div>
        </div>
    </div>
@endsection
```

Abra o arquivo **show.blade.php** e coloque o código abaixo:

```HTML
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contatos
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('contatos.show_fields')
                    <a href="{!! route('contatos.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
```
Abra o arquivo **show_fields.blade.php** e coloque o código abaixo:

```HTML
<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $contato->id !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group">
    {!! Form::label('nome', 'Nome:') !!}
    <p>{!! $contato->nome !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{!! $contato->email !!}</p>
</div>

<!-- Mensagem Field -->
<div class="form-group">
    {!! Form::label('mensagem', 'Mensagem:') !!}
    <p>{!! $contato->mensagem !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $contato->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $contato->updated_at !!}</p>
</div>
```
Abra o arquivo **table.blade.php** e coloque o código abaixo:

```HTML
<table class="table table-responsive" id="contatos-table">
    <thead>
        <th>Nome</th>
        <th>Email</th>
        <th>Mensagem</th>
        <th colspan="3">Ação</th>
    </thead>
    <tbody>
    @foreach($contatos as $contato)
        <tr>
            <td>{!! $contato->nome !!}</td>
            <td>{!! $contato->email !!}</td>
            <td>{!! $contato->mensagem !!}</td>
            <td>
                {!! Form::open(['route' => ['contatos.destroy', $contato->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('contatos.show', [$contato->id]) !!}" class='btn btn-default btn-xs' title='Visualizar Dados'><i class="glyphicon glyphicon-eye-open"></i></a>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
```
Para testarmos se está funcionando precisamos criar uma página front contendo o formulário para que seja enviado os dados. Na pasta **resource/views**, crie um pasta chamada **frontend**, dentro dessa pasta crie um arquivo chamado **contato.blade.php** e coloque o código abaixo:

```HTML
<h3>Enviar Mensagem</h3>
<form action="contato-enviar" method="post">
	{!! csrf_field() !!}
	<input type="text"  class="name" name="name" placeholder="Nome" required>
	<input type="text"  class="email" name="email" placeholder="E-mail" required>
	<input type="text"  class="assunto" name="assunto" placeholder="Assunto" required>
	<textarea name="mensagem" placeholder="Digite a mensagem..." required></textarea>
	<input type="submit" value="Enviar Mensagem">
</form>
```
> Agora para testar acesse a rota /contato e abrirá o formulário. Digite os dados e envie, observe que irá aparecer no painel de contato.

## Criando um caminho para a listagem dos contato

Agora no menu temos que colocar o item de contatos para quando clicar ser redirecionado para a lista de contatos. Na pasta **resource->views**, já temos um arquivo para realizarmos esta tarefa, acesse a pasta **layouts** e abra o arquivo **menu.blade.php**. Coloque o código de contato:

```HTML
<li class="{{ Request::is('contatos*') ? 'active' : '' }}">
    <a href="{!! route('contatos.index') !!}"><i class="fa fa-edit"></i><span>Contatos</span></a>
</li>
```
