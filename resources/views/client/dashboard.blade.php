@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-7">
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
                            <canvas id="areaChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 586px;"
                                width="586" height="250" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>

                <!--  Coluns Unpaid and Paid -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-plus mr-1"></i> À Receber
                                </h3>
                            </div>
                            <div class="card-body">
                                ...
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-minus mr-1"></i> À Pagar
                                </h3>
                            </div>
                            <div class="card-body">
                                ...
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="wallets col-lg-5">
                <div class="d-flex justify-content-around aligns-items-center mb-2">
                    <div class="col-sm-6"><button class="btn btn-lg btn-outline-success btn-block" data-toggle="modal" data-target="#modalIncome">
                        <i class="fas fa-plus-circle mr-1"></i> Nova Receita</button></div>
                    <div class="col-sm-6"><button class="btn btn-lg btn-outline-danger btn-block" data-toggle="modal" data-target="#modalExpense">
                        <i class="fas fa-plus-circle mr-1"></i> Nova Despesa</button></div>
                </div>
                <div class="small-box mx-2 bg-{{ $bg }}">
                    <div class="pb-4 pt-4 px-4">
                        <h3>R$ {{ number_format($paid - $unpaid, 2, ',', '.') }}</h3>
                        <p class="mb-0">Receitas: R$ {{ number_format($paid, 2, ',', '.') }}</p>
                        <p class="mb-2">Despesas: R$ {{ number_format($unpaid, 2, ',', '.') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Mais Informações <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </section>
        </div>
    </div>

    @include('client.components.model')
@endsection
