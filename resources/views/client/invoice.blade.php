@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Conta</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><i class="icon fas fa-pencil-alt"></i> Editar </a></li>
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
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card">
                    <form action="{{ route('app.update.invoice', ['id' => $invoice->id]) }}" method="post"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="type" value="{{ $invoice->type }}">
                            <input type="hidden" name="currency" value="BRL" />
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label for="description" class="text-gray"><i class="fas fa-book-open"></i>
                                            Descrição:</label>
                                        <input type="text" name="description" required class="form-control"
                                            placeholder="Ex: Aluguel" value="{{ $invoice->description }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="value" class="text-gray"><i class="far fa-money-bill-alt"></i>
                                            Valor:</label>
                                        <input type="text" name="value" required class="form-control" placeholder="0,00"
                                            maxlength="22" value="{{ number_format($invoice->value, 2, ',', '.') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="due_at" class="text-gray"><i class="far fa-calendar-alt"></i>
                                            Dia Vencimento:</label>
                                        <input type="number" name="due_day" required class="form-control"
                                            value="{{ date('d', strtotime($invoice->due_at)) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label for="wallet" class="text-gray"><i class="fas fa-wallet"></i>
                                            Carteira:</label>
                                        <select name="wallet" required class="form-control">
                                            @foreach ($wallets as $item)
                                                <option {{ $invoice->wallet_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">&ofcir; {{ $item->wallet }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="category" class="text-gray"><i class="fas fa-filter"></i>
                                            Categoria:</label>
                                        <select name="category" required class="form-control">
                                            @foreach ($categories as $item)
                                                <option {{ $invoice->category_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">&ofcir; {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-gray"><i class="fas fa-filter"></i> Status:</label>
                                        <select name="status" class="form-control">
                                            <?php if ($invoice->type == "fixed_income" || $invoice->type == "fixed_expense"): ?>
                                            <option <?= $invoice->status != 'paid' ?: 'selected' ?> value="paid">&ofcir;
                                                Ativa</option>
                                            <option <?= $invoice->status != 'unpaid' ?: 'selected' ?> value="unpaid">&ofcir;
                                                Inativa
                                            </option>
                                            <?php else: ?>
                                            <option <?= $invoice->status == 'paid' ? 'selected' : '' ?> value="paid">
                                                &ofcir; <?= $invoice->type == 'income' ? 'Recebida' : 'Paga' ?></option>
                                            <option <?= $invoice->status == 'unpaid' ? 'selected' : '' ?> value="unpaid">
                                                &ofcir; <?= $invoice->type == 'income' ? 'Não recebida' : 'Não paga' ?>
                                            </option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                            </div> <!-- ROW -->
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-lg btn-success"><i class="fas fa-check"></i> EDITAR
                                {{ $invoice->type == 'income' ? 'RECEITA' : 'DESPESA' }}</button>
                            <a href="{{ route('app.dash') }}" class="btn btn-small btn-outline-secondary"
                                data-dismiss="modal">Cancelar</a>
                        </div>
                    </form>
                </div>
                <form action="{{ route('app.delete.invoice', ['id' => $invoice->id]) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button onclick="return confirm('Você tem certeza que deseja excluir esse registro?');" 
                        type="submit" class="btn btn-small text-danger"><i
                            class="fas fa-times"></i>
                        EXCLUIR</button>
                </form>
            </div>
        </div>
    </div>
@endsection
