@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Assinaturas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.wallets') }}"><i
                                    class="icon fas fa-mug-hot"></i>
                                Assinaturas</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row mb-2">
            @if ($errors->any())
                <div class="col-md-12 py-1">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Atenção!</h5>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="col-md-12 py-1">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5">
                    <div class="card-header bg-gradient-info text-center">
                        <div class="center py-3" style="max-width: 650px; diplay:block; margin: 0 auto">
                            <span style="font-size: 4rem"><i class="fas fa-funnel-dollar"></i></span>
                            <h1>Seja PRO por apenas R$ 0,66 centavos por dia e controle tudo!</h1>
                            <h6>Crie multiplas carteiras para controlar suas finanças PF, PJ, contas bancárias, cartões de
                                crédito, poupanças... e libere o controle absoluto de suas contas.</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 py-4 text-center border-bottom mb-4">
                                <h2 class="text-gray"><strong>Compare as versões FREE e PRO e entenda!</strong></h2>
                            </div>
                            <div class="col-sm-6 col-md-4 text-center">
                                <h5 class="text-black-50 mb-4"><strong>Recursos</strong></h5>
                                <p>Contas a receber</p>
                                <p>Contas a pagar</p>
                                <p>Parcelamento</p>
                                <p>Contas a fixas</p>
                                <p>Carteiras ilimitadas</p>
                                <p>Vencimentos por e-mail</p>
                                <p>PF, PJ, cartões, etc</p>
                                <p>Filtro por fonte (carteira)</p>
                                <p>Controle de saldo geral</p>
                            </div>
                            <div class="col-sm-3 col-md-4 text-center">
                                <h5 class="text-black-50 mb-4"><i class="icon fas fa-user-plus"></i> <strong>FREE</strong>
                                </h5>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-danger"><i class="fas fa-times-circle"></i></p>
                                <p class="text-danger"><i class="fas fa-times-circle"></i></p>
                                <p class="text-danger"><i class="fas fa-times-circle"></i></p>
                                <p class="text-danger"><i class="fas fa-times-circle"></i></p>
                                <p class="text-danger"><i class="fas fa-times-circle"></i></p>

                            </div>
                            <div class="col-sm-3 col-md-4 text-center">
                                <h5 class="text-black-50 mb-4"><i class="icon fas fa-mug-hot"></i> <strong>PRO</strong></h5>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                                <p class="text-success"><i class="fas fa-check-circle"></i></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mt-4">
                        <h3 class="text-black-50 mb-1 text-center"><i class="icon fas fa-mug-hot"></i> <strong>Assine o
                                PRO</strong>
                        </h3>
                        <p class="text-black-50 text-center">E libere todos os recursos do ConTanno</p>
                        <form class="m-auto" action="#" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="custom-control custom-radio">
                                                <input checked class="custom-control-input" type="radio" id="customRadio1"
                                                    name="recurrence" value="recurrence_month">
                                                <label for="customRadio1" class="custom-control-label text-black-50">PRO R$
                                                    19,90/Mês</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio2"
                                                    name="recurrence" value="recurrence_year">
                                                <label for="customRadio2" class="custom-control-label text-black-50">PRO R$
                                                    200,00/Ano</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group">
                                                <label for="description" class="text-gray">
                                                    Número do Cartão:</label>
                                                <input type="tel" name="number_card" required class="form-control"
                                                    placeholder="**** **** **** ****">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group">
                                                <label for="value" class="text-gray">
                                                    Nome do Títular:</label>
                                                <input type="text" name="name_card" required class="form-control"
                                                    placeholder="Igual ao impresso no cartão">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="due_at" class="text-gray">
                                                    Data de Expiração:</label>
                                                <input type="text" name="date_card" required class="form-control"
                                                    placeholder="mm/yy">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="due_at" class="text-gray">
                                                    CVV:</label>
                                                <input type="number" name="cvv_card" required class="form-control"
                                                    placeholder="***" maxlength="3">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success" style="width: 100%"><i
                                                    class="fas fa-check-square"></i> CONFIRMAR PAGAMENTO</button>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- ROW -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>
    <script>
        $('input[name=number_card]').mask('0000 0000 0000 0000');
        $('input[name=date_card').mask('00/00');
    </script>
@endsection
