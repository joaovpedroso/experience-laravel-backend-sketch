@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">
            <ul class="breadcrumb">
                <li>
                    <p>VOCÊ ESTÁ AQUI</p>
                </li>
                <li>
                    <a href="{{ route('contacts.index') }}" class="active">
                        Contato
                    </a>
                </li>
            </ul>

            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple no-border">

                        <div class="grid-title no-border descriptive">

                            <div class="pull-right m-r-5">
                                <a class="btn btn-xs btn-danger btn-mini"
                                   href="javascript:checkDelete({{ $contact->id }});">
                                    <i class="fa fa-trash"></i> Excluir
                                </a>
                            </div>

                            <a href="{{ route('contacts.index') }}" class="pull-right btn btn-mini btn-default m-r-15">&larrhk;
                                Voltar</a>

                            <h4 class="semi-bold"></h4>

                            <p style="line-height: 14px;">
                                por
                                <span class="text-success bold"><?php echo $contact->name ?></span> -
                                <?php
                                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                echo strftime('%A, %d de %B de %Y', strtotime($contact->created_at));
                                ?>
                            </p>
                        </div>

                        <div class="grid-body no-border">
                            <div class="post" style="margin-top: 0">
                                <div class="info-wrapper">
                                    <div class="info">
                                        <p><strong>Nome:</strong> <?php echo $contact->name ?></p>
                                        <p><strong>E-mail:</strong> <?php echo $contact->email ?></p>
                                        <p><strong>Telefone:</strong> <?php echo $contact->phone ?></p>
                                        <p><strong>Cidade/UF:</strong> <?php echo $contact->city ?></p>
                                        <p><strong>Mensagem:</strong></p>
                                        <p><?php echo $contact->message ?></p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@if (!isset($trash))
@section('js')
    @include('helpers.status-button')
    <script>
        function checkDelete(id) {
            $.ajax({
                url: '{{ url("/sistema/contacts") }}' + '/' + id,
                type: 'DELETE',
                data: {id},
                success: function (result) {
                    location.href = '{{ route("contacts.index") }}';
                }
            });
        }
    </script>
@endsection
@endif
