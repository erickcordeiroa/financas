@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <div class="row justify-content-end">
                        <div class="col-md-4 col-sm-12">
                            <form method="GET">
                                <select name="wallet" class="form-control" onchange="this.form.submit()">
                                    <option value="">Saldo Geral</option>
                                    @foreach ($wallets as $item)
                                        <option {{ $walletSelected == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->wallet }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
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
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i> Controle
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="areaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex justify-content-around aligns-items-center mb-2">
                    <div class="col-sm-6"><button class="btn btn-lg btn-outline-success btn-block" data-toggle="modal"
                            data-target="#modalIncome">
                            <i class="fas fa-plus-circle mr-1"></i> Nova Receita</button></div>
                    <div class="col-sm-6"><button class="btn btn-lg btn-outline-danger btn-block" data-toggle="modal"
                            data-target="#modalExpense">
                            <i class="fas fa-plus-circle mr-1"></i> Nova Despesa</button></div>
                </div>
                <div class="small-box mx-2 {{ $wallet->balance == 'positive' ? 'bg-success' : 'bg-danger' }}">
                    <div class="pb-4 pt-4 px-4">
                        <h2>{{$wallet->walletName}}</h2>
                        <h3>R$ {{ number_format($wallet->wallet, 2, ',', '.') }}</h3>
                        <p class="mb-0">Receitas: R$ {{ number_format($wallet->income, 2, ',', '.') }}</p>
                        <p class="mb-2">Despesas: R$ {{ number_format($wallet->expense, 2, ',', '.') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="{{ $wallet->balance == 'positive' ? route('app.income') : route('app.expense') }}"
                        class="small-box-footer">
                        Mais Informações <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!--  Coluns Unpaid and Paid -->
            <section class="invoices col-md-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-plus mr-1"></i> À Receber
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            @if (!$income->isEmpty())
                                                @foreach ($income as $item)
                                                    @include('client.components.balance', [
                                                        'invoice' => $item,
                                                    ])
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <p class="alert alert-success"><i
                                                                class="fas fa-exclamation-triangle"></i> No
                                                            momento, não existem contas registradas. Comece lançando agora
                                                            mesmo!
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card  card-danger">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-minus mr-1"></i> À Pagar
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <tbody>
                                            @if (!$expense->isEmpty())
                                                @foreach ($expense as $item)
                                                    @include('client.components.balance', [
                                                        'invoice' => $item,
                                                    ])
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <p class="alert alert-danger"><i
                                                                class="fas fa-exclamation-triangle"></i> No
                                                            momento, não existem contas registradas. Comece lançando agora
                                                            mesmo!
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('client.components.model', ['wallets' => $wallets, 'categories' => $categories])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = [
            {!! $chart->categories !!}
        ];

        const data = {
            labels: labels,
            datasets: [{
                    label: 'À Receber',
                    backgroundColor: 'rgb(40, 167, 69)',
                    borderColor: 'rgb(40, 167, 69)',
                    data: [{!! $chart->income !!}],
                },
                {
                    label: 'À Pagar',
                    backgroundColor: 'rgb(220, 53, 69)',
                    borderColor: 'rgb(220, 53, 69)',
                    data: [{!! $chart->expense !!}],
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        const myChart = new Chart(
            document.getElementById('areaChart'),
            config
        );
    </script>
@endsection
