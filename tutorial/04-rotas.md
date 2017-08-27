<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Criando as rotas para os Controllers e o FRONT

Agora que temos arquivos blade, precisamos agora criar as rotas que ligará o front e ao controller desses front's.

Na pasta routes, existe o arquivo web.php, lá fica nossas rotas. Por enquanto, vamos trabalhar dentro da seguinte rota:

```PHP
Route::group(['middleware' => 'auth', 'prefix' => '/sistema'], function () {
        Route::get('/', 'HomeController@index');
});
```

Aqui estamos dizendo que é um _grupo de rotas_ que acessará a url **/sistema** somente se estiver autenticado.

Vamos deixar as nossas rotas, dessa maneira:

```PHP

//PAINEL ### ROTAS ###
Route::group(['middleware' => 'auth', 'prefix' => '/sistema'], function () {
        Route::get('/', 'HomeController@index');

        Route::resource('/contatos', 'Backend\ContatoController');
        Route::resource('usuarios', 'Backend\UsuariosController');
        Route::resource('users', 'UserController');
        Route::resource('/contatos', 'Backend\ContatoController');

        Route::get('/newspapers/{newspapers}/restore', 'Backend\NewspapersController@restore')->name('newspapers.restore');
        Route::get('/newspapers/status', 'Backend\NewspapersController@status');
        Route::post('/newspapers/order', 'Backend\NewspapersController@order');
        Route::get('/newspapers/trash', 'Backend\NewspapersController@trash')->name('newspapers.trash');
        Route::resource('/newspapers', 'Backend\NewspapersController', ['except' => 'show']);
        Route::get('/newspapers', 'Backend\NewspapersController@index')->name('newspapers.index');
        Route::get('/newspapers/featured', 'Backend\NewspapersController@featured');

        // noticias gallery
        Route::get('/newspapers/gallery/{newspapers?}', 'Backend\NewspapersController@gallery')->name('newspapers.gallery');
        Route::post('/newspapers/gallery/save', 'Backend\NewspapersController@save')->name('newspapers.gallaries.save');
        Route::post('/newspapers/gallery/remove', 'Backend\NewspapersController@remove')->name('newspapers.gallaries.remove');
        Route::post('/newspapers/gallery/legenda', 'Backend\NewspapersController@legenda')->name('newspapers.gallaries.legenda');

        Route::post('/newspapers/remove_files', 'Backend\NewspapersController@remove_files')->name('newspapers.remove_files');

        //Category
        Route::get('/newspapers/categories', 'Backend\CategoriesController@index')->name('newspapers.categories.index');

        Route::get('/newspapers/categories/create', 'Backend\CategoriesController@create')->name('newspapers.categories.create');

        Route::get('/newspapers/categories/{categories}/restore',
            'Backend\CategoriesController@restore')->name('newspapers.categories.restore');
        Route::post('/newspapers/categories/order', 'Backend\CategoriesController@order');
        Route::get('/newspapers/categories/trash', 'Backend\CategoriesController@trash')
            ->name('newspapers.categories.trash');

        Route::post('/newspapers/categories/create/store', 'Backend\CategoriesController@store')->name('newspapers.categories.store');

        Route::get('/newspapers/categories/edit/{id}', 'Backend\CategoriesController@edit')->name('newspapers.categories.edit');

        Route::get('/newspapers/categories/destroy', 'Backend\CategoriesController@destroy')->name('newspapers.categories.destroy');

        Route::get('/newspapers/categories/restore', 'Backend\CategoriesController@restore')->name('newspapers.restore');

        Route::patch('/newspapers/categories/update/{id}', 'Backend\CategoriesController@update')->name('newspapers.categories.update');

    });

```