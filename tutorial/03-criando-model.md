<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Criando os Model

Antes de começarmos a construir nossas **rotas** e nossos **controllers**, vamos montar nossos models, teremos dois model, um para o blog que chamaremos de **Newspaper** e um model que iremos chamar de **Category** que será o cadastro das nossas categorias do blog.

## Criando o Model Category

Para criar um model no Laravel, digitamos o seguinte comando:

```PHP
php artisan make:model Category --migration
```

> Nesse modelo ao digitar ele cria dois arquivos, um para os **Models** e um outro que fica em **database->migrations**. Vamos **deletar** esse arquivo que é gerado nas **migrations**, pois iremos trabalhar com ele diretamente no modelo **Newspaper**.

No arquivo Category criado em Models, vamos colocar o seguinte código:

```PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'titulo'
    ];

    public function posts() {
        return $this->hasMany('App\Models\Newspaper', 'category_id')->where('status', 1);
    }

}

```

## Criando o Model Newspaper

Digite o comando de criar a Migration e o Model:

```PHP
php artisan make:model Newspaper --migration
```

Abrindo o arquivo Newspaper em model, coloque o código abaixo:

```PHP
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newspaper extends Model {

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'data', 'titulo', 'descricao', 'imagem', 'fonte', 'status', 'legenda_imagem',
        'category_id'
    ];

    /**
     * Scopes!
     */
    public function scopeDateFromTo($query, $begin = null, $end = null) {
        if (!is_null($begin) and !empty($begin)) {
            $begin = Carbon::createFromFormat('d/m/Y', $begin)->format('Y-m-d');
            $query->where("created_at", ">=", "$begin");
        }

        if (!is_null($end) and !empty($end)) {
            $end = Carbon::createFromFormat('d/m/Y', $end)->format('Y-m-d');
            $query->where("created_at", "<=", "$end");
        }
    }

    public function scopeSearchByCategory($query, $category = null) {
        if (!is_null($category) && !empty($category)) {
            $query->where('newspapers.category_id', $category);
        }
    }

    public function scopeSearchByNameDescription($query, $search = null) {
        if (!is_null($search) && !empty($search)) {
            $query->where('newspapers.titulo', 'like', '%'.$search.'%');
            $query->orWhere('newspapers.descricao', 'like', '%'.$search.'%');
        }
    }

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function gallery() {
        return $this->hasMany('App\Models\GalleryNewspaper', 'newspaper_id');
    }
}

```

Dentro de migration, foi gerado um arquivo newspapers, substitua o código com o de baixo:

```PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewspapersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');

                $table->string('titulo');
                $table->integer('status')->default(0);

                $table->timestamps();
                $table->softDeletes();

            });

        Schema::create('newspapers', function (Blueprint $table) {
                $table->increments('id');

                $table->date('data');
                $table->string('titulo');
                $table->text('descricao');
                $table->string('imagem');
                $table->string('fonte');

                $table->integer('status')->default(0);
                $table->string('legenda_imagem');

                $table->integer('category_id')->unsigned()->index();
                $table->foreign('category_id')->references('id')->on('categories');

                $table->timestamps();
                $table->softDeletes();

            });

        Schema::create('newspapers_images', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('newspaper_id')->unsigned()->index();
                $table->foreign('newspaper_id')->references('id')->on('newspapers');
                $table->string('imagem', 250);
                $table->string('legenda', 250);
                $table->timestamps();

            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('newspapers_images');
        Schema::drop('newspapers');
        Schema::drop('categories');
    }
}

```

## Subindo as tabelas categoria e newspaper parao phpmyadmin

```PHP
php artisan migrate:refresh --seed
```
