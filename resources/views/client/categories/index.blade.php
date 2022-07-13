@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Categorias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('app.categories') }}"><i
                                    class="icon fas fa-calendar"></i> Categoria</a></li>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="col-md-2 p-3">
                        <a class="btn btn-small btn-outline-success btn-block"
                            href="{{ route('app.categories.create') }}">
                            <i class="fas fa-plus-circle mr-1"></i> Nova Categoria</a>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th width="250">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$categories->isEmpty())
                                    @foreach ($categories as $item)
                                        <tr>
                                            <td class="text-bold">{{ $item->name }}</td>
                                            @if ($item->type == 'expense')
                                                <td>Despesa</td>
                                            @else
                                                <td>Receita</td>
                                            @endif
                                            <td class="d-flex">
                                                <div class="mr-2">
                                                    <a href="{{ route('app.categories.edit', ['id' => $item->id]) }}"
                                                        class="btn btn-primary btn-sm">Editar</a>
                                                </div>
                                                <form action="{{ route('app.categories.destroy', ['id' => $item->id]) }}"
                                                    method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('Você tem certeza que deseja excluir esse registro?');"
                                                        type="submit" class="btn btn-sm btn-danger">
                                                        Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <p class="alert alert-info"><i class="fas fa-exclamation-triangle"></i> No
                                                momento, não existem contas registradas. Comece lançando agora mesmo! </p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if (isset($filters))
                            {{ $categories->appends($filters)->links() }}
                        @else
                            {{ $categories->links() }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
