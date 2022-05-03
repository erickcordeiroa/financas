@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contas a {{ $type == 'income' ? 'Receber' : 'Pagar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ $type == 'income' ? route('app.income') : route('app.expense') }}"><i
                                    class="icon fas fa-calendar"></i> Contas a
                                {{ $type == 'income' ? 'Receber' : 'Pagar' }}</a></li>
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

        <div class="row">
            <div class="col-md-10">

                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <select name="status" required class="form-control">
                                <option value="all">Todas</option>
                                <option value="paid">Todas as Recebidas</option>
                                <option value="unpaid">Todas as Não Recebidas</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <select name="category" required class="form-control">
                                <option value="all">Todas</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="date" name="start" required placeholder="dd/mm/yyyy" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <input type="date" name="end" required placeholder="dd/mm/yyyy" class="form-control">
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" class="btn btn-outline-primary btn-small"><i
                                    class="fas fa-filter"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2 float-right">
                @if ($type == 'income')
                    <div class="col-md-12">
                        <button class="btn btn-small btn-outline-success btn-block" data-toggle="modal"
                            data-target="#modalIncome">
                            <i class="fas fa-plus-circle mr-1"></i> Nova Receita</button>
                    </div>
                @else
                    <div class="col-md-12"><button class="btn btn-small btn-outline-danger btn-block"
                            data-toggle="modal" data-target="#modalExpense">
                            <i class="fas fa-plus-circle mr-1"></i> Nova Despesa</button></div>
                @endif
            </div>
        </div><!-- row top filter -->
        <div class="row">
            <div class="col-md-12">
                @include('client.components.grid-invoices', ['invoice' => ($type == 'income')? $income : $expense ])
            </div>
        </div>
    </div>
    @include('client.components.model', ['wallets' => $wallets, 'categories' => $categories])
@endsection
