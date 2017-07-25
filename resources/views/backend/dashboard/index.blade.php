@extends('layouts.app')

@section('content')
    <style>
        .cor1 {
            background-color: #1F2055;
        }

        .blend {
            color: white !important;
        }

        .table_dash a:link {
            color: #0f0f0f !important;
        }

        /* link que foi visitado */
        .table_dash a:visited {
            color: #0f0f0f !important;
        }

        /* mouse over */
        .table_dash a:hover {
            color: #2a2a2a !important;
        }

        /* link selecionado */
        .table_dash a:active {
            color: #0f0f0f !important;
        }
    </style>
    <div class="page-content">
        <div class="content">
            <h1>Dashboard Info</h1>
        </div>
@endsection