@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contas Fixas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ $type == 'income' ? route('app.income') : route('app.expense') }}"><i
                                    class="icon fas fa-exchange-alt"></i> Contas a Fixas</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
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

        <div class="row mb-2">
            <div class="col-md-6">
                <button class="btn btn-small btn-outline-success btn-block" data-toggle="modal" data-target="#modalIncome">
                    <i class="fas fa-plus-circle mr-1"></i> Nova Receita</button>
            </div>
            <div class="col-md-6 float-right">
                <button class="btn btn-small btn-outline-danger btn-block" data-toggle="modal" data-target="#modalExpense">
                    <i class="fas fa-plus-circle mr-1"></i> Nova Despesa</button>
            </div>
        </div><!-- row top filter -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Vencimento</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice as $item)
                                    <tr>
                                        <td><a href="#" class="text-bold">
                                            {{ ($item->type == "fixed_income" ? "Receita / " : "Despesa / "); }}
                                            {{ $item->description }}
                                        </a></td>
                                        <td> Dia {{ (new DateTime($item->due_at))->format('d') }}</td>
                                        <td>{{ $item->categories->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $item->status == 'paid' ? 'badge-success' : 'badge-danger' }} badge-success">
                                                    {{ $item->status == 'paid' ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($item->value, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $invoice->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('client.components.model', ['wallets' => $wallets, 'categories' => $categories])
@endsection
