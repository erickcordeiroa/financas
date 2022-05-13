@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Carteiras</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.wallets') }}"><i
                                    class="icon fas fa-wallet"></i> Carteiras</a></li>
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

        <!-- Wallets -->
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-gradient-info text-center p-4">
                    <span style="font-size: 48px"><i class="fas fa-info-circle"></i></span>
                    <h3>Crie e gerencie suas carteiras</h3>
                    <p style="line-height: 1">Organize suas contas de diferentes fontes como <strong>Minha Casa para
                            PF</strong>, <strong>Minha Empresa para PJ</strong>, ou ainda
                        <strong>Cartão 6431</strong> para organizar cartões. Controle tudo...
                    </p>
                    <button class="btn btn-small btn-dark" data-toggle="modal" data-target="#modalWallet"><i
                            class="fas fa-plus-circle"></i> Adicionar Nova
                        Carteira</button>
                </div>
            </div>
            @if (!$wallets->isEmpty())
                @foreach ($wallets as $item)
                    <div class="col-md-4">
                        <div class="card bg-gradient-dark text-center p-4">
                            <span style="font-size: 48px"><i class="fas fa-wallet"></i></span>
                            <h2>{{ $item->wallet }}</h2>
                            <h6>Receitas: R$ {{ number_format($item->balance()->income, 2, ',', '.') }}</h6>
                            <h6>Despesas: R$ {{ number_format($item->balance()->expense, 2, ',', '.') }}</h6>
                            <form action="{{ route('app.delete.wallets', ['id' => $item->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @method("DELETE")
                                @csrf
                                <button
                                    onclick="return confirm('Você realmente deseja excluir a sua carteira? Todas as contas vinculadas a ela serão excluidas!')"
                                    class="btn btn-small btn-danger"><i class="fas fa-times-circle"></i> Excluir
                                    Carteira</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    <!-- Modal Wallet -->
    <div class="modal fade" id="modalWallet" tabindex="-1" role="dialog" aria-labelledby="newWallet" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newWallet"><i class="fas fa-wallet"></i> Nova Carteira</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('app.store.wallets') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="wallet" class="text-gray"><i class="fas fa-book-open"></i>
                                        Descrição:</label>
                                    <input type="text" name="wallet" required class="form-control"
                                        placeholder="Ex: Minha Casa">
                                </div>
                            </div>
                        </div> <!-- ROW -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> CRIAR NOVA
                            CARTEIRA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
