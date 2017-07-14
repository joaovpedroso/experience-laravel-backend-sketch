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
            <!-- MÓDULO DE INFORMAÇÕES ================================================== -->
            <div class="row">
                <div class="col-md-3 m-b-20">
                    <div class="tiles cor1 added-margin">
                        <div class="tiles-body">
                            <div class="tiles-title">

                                Usuários

                            </div>
                            <div class="heading">
                                <span class="animate-number" data-value="30"
                                      data-animation-duration="1200">
                                   20
                                </span>
                            </div>
                            <div class="description">
                                <span class="text-white mini-description">
                                  <span class="blend">total de usuários cadastrados</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.INFORMAÇÕES -->
            <div class="row">

                <!-- CONTATO
                ================================================== -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="grid-body no-border email-body" style="padding:15px;background-color:white">

                                <h3>Lista de últimos <span class="semi-bold">Contatos</span></h3>
                                @if (count($contacts) == 0)
                                    <h5>Nenhuma Mensagem encontrada.</h5>
                                @else
                                    <table class="table table-striped table-hover no-more-tables table_dash"
                                           id="datatable"
                                           style="background-color:white;">
                                        <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Nome</th>
                                            <th>Telefone</th>
                                            <th>E-mail</th>
                                            <th>Cidade</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach ($contacts as $cont)
                                            <tr>
                                                <td class="no-padding">
                                                    <a href="{{  route('contacts.show', [$cont->id]) }}">
                                                        {{ date('d/m/Y H:i:s', strtotime($cont->created_at)) }}
                                                    </a>
                                                </td>
                                                <td class="no-padding">
                                                    <a href="{{  route('contacts.show', [$cont->id]) }}">
                                                        {{ $cont->name }}
                                                    </a>
                                                </td>
                                                <td class="no-padding">
                                                    <a href="{{  route('contacts.show', [$cont->id]) }}">
                                                        {{ $cont->phone }}
                                                    </a>
                                                </td>
                                                <td class="no-padding">
                                                    <a href="{{  route('contacts.show', [$cont->id]) }}">
                                                        {{ $cont->email }}
                                                    </a>
                                                </td>
                                                <td class="no-padding">
                                                    <a href="{{  route('contacts.show', [$cont->id]) }}">
                                                        {{ $cont->city }} / {{ $cont->state }}
                                                    </a>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </div>

                        <!-- /.CONTATO -->



                    </div>
                </div>
                <!-- /.ORÇAMENTOS -->


                <!-- SUPORTE -->
                <div class="col-md-4 m-b-20">
                    <div class="tiles red">
                        <div class="tiles-body" style="height: 300px !important; background-color: #9f041b !important;">
                            <div class="row">

                                <h5 class="text-white col-md-12">
                                    <span class="semi-bold">SUPORTE</span> PRESTIGE
                                </h5>

                                <div class="suporte col-md-12 m-t-15 m-b-15 p-t-10 p-b-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>
                                                <i class="fa fa-phone text-white"></i>&nbsp;
                                                <a class="text-white" href="tel:04436228831">(44) 3622-8831</a>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>
                                                <i class="fa fa-phone text-white"></i>&nbsp;
                                                <a class="text-white" href="tel:04430561163">(44) 3056-1163</a>
                                            </h5>
                                        </div>

                                        <div class="col-md-12">
                                            <h5>
                                                <i class="fa fa-envelope text-white"></i>&nbsp;
                                                <a href="mailto:suporte@prestige.com.br" class="text-white">
                                                    suporte@prestige.com.br
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <p class="bold">
                                        Horário de atendimento:
                                    </p>
                                    <p>
                                        De Segunda à Sexta:
                                        <br>
                                        08:00 às 12:00 - 14:00 às 18:00
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.SUPORTE -->

            </div>
        </div>
@endsection