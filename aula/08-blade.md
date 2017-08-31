<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Introdução ao Blade

O Laravel tem um motor simples e poderoso para o FRONT conhecido como Blade. Ao contrário de outros motores de modelos PHP populares, o Blade não o impede de usar o código PHP simples em suas visualizações. De fato, todas as visualizações são compiladas em código PHP simples e armazenadas em cache até serem modificadas. Os arquivos de exibição usam a extensão de arquivo **.blade.php** e normalmente são armazenados no diretório resource/views.

## Definindo um Layout

Dois dos principais benefícios do uso da Blade são a herança de modelo e as seções. Para começar, vamos criar um layout "mestre". Uma vez que a maioria dos aplicativos da Web mantêm o mesmo layout geral em várias páginas, é conveniente definir esse layout como uma única visualização de Blade:

```PHP
<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
```
Como você pode ver, este arquivo contém marcações HTML típicas. No entanto, tome nota das diretivas **@section** e **@yield**. A diretiva @section, como o nome indica, define uma seção de conteúdo, enquanto a diretiva @yield é usada para exibir o conteúdo de uma determinada seção.

## Extendendo o Layout Mestre

Ao definir uma visão secundária, use a diretiva Blade **@extends** para especificar qual layout a visão filha deve "herdar". As visualizações que estendem um layout do Blade podem injetar conteúdo nas seções do layout usando as diretivas do @section. Lembre-se, como visto no exemplo acima, o conteúdo dessas seções será exibido no layout usando @yield:

```HTML
<!-- Stored in resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection
```
Neste exemplo, a seção da barra lateral está utilizando a diretiva **@parent** para anexar conteúdo (em vez de sobrescrever) para a barra lateral do layout. A diretiva @parent será substituída pelo conteúdo do layout quando a exibição for renderizada.

## Estruturas de controle

Você pode construir instruções se usando as diretivas **@if**, **@elseif**, **@else** e **@endif**. Essas diretivas funcionam de forma idêntica às suas partes equivalentes de PHP:

```HTML
@if (count($records) === 1)
    I have one record!
@elseif (count($records) > 1)
    I have multiple records!
@else
    I don't have any records!
@endif
```
Além das diretivas condicionais já discutidas, as diretivas @isset e @empty podem ser usadas como atalhos convenientes para suas respectivas funções PHP:

```HTML
@isset($records)
    // $records is defined and is not null...
@endisset

@empty($records)
    // $records is "empty"...
@endempty
```
## Loops

Além das declarações condicionais, a Blade fornece diretrizes simples para trabalhar com as estruturas de loop do PHP. Mais uma vez, cada uma dessas diretivas funciona de forma idêntica às suas partes equivalentes de PHP:

```HTML
@for ($i = 0; $i < 10; $i++)
    The current value is {{ $i }}
@endfor

@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach

@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>No users</p>
@endforelse

@while (true)
    <p>I'm looping forever.</p>
@endwhile
```
Ao usar loops, você também pode terminar o loop ou ignorar a iteração atual:

```HTML
@foreach ($users as $user)
    @if ($user->type == 1)
        @continue
    @endif

    <li>{{ $user->name }}</li>

    @if ($user->number == 5)
        @break
    @endif
@endforeach
```

## Comentários no código

O Blade também permite que você defina comentários em suas visualizações. No entanto, ao contrário dos comentários HTML, os comentários da Blade não estão incluídos no HTML retornado pelo seu aplicativo:

```PHP
{{-- This comment will not be present in the rendered HTML --}}
```
## Tag para iniciar uma sequência de códigos PHP puro

Em algumas situações, é útil inserir o código PHP em suas visualizações. Você pode usar a diretiva Blade @php para executar um bloco de PHP simples no seu modelo:

```HTML
@php
    //
@endphp
```

## Incluindo Sub-Views

```HTML
<div>
    @include('shared.errors')

    <form>
        <!-- Form Contents -->
    </form>
</div>
```