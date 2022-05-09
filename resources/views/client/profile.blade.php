@extends('adminlte::page')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Informações</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.dash') }}"><i class="icon fas fa-home"></i>
                                Controle</a></li>
                        <li class="breadcrumb-item"><i class="icon fas fa-user"></i> Meu Perfil </a></li>
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
                    <form action="{{ route('app.update.profile', ['id' => $user->id]) }}" method="post"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label for="name" class="text-gray"><i class="fas fa-user"></i>
                                            Nome:</label>
                                        <input type="text" name="name" required
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Ex: João Alves" value="{{ $user->name }}">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label for="email" class="text-gray"><i class="fas fa-envelope"></i>
                                            E-mail:</label>
                                        <input type="email" name="email" required
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Ex: joaoalves@gmail.com" value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="password" class="text-gray"><i class="fas fa-lock"></i>
                                            Senha:</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="*******">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="text-gray"><i
                                                class="fas fa-lock"></i>
                                            Confirmar Senha:</label>
                                        <input type="password" name="password_confirmation"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="*******">
                                    </div>
                                </div>


                            </div> <!-- ROW -->
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-small btn-success"><i class="fas fa-check"></i> EDITAR
                                INFORMAÇÕES</button>
                            <a href="{{ route('app.dash') }}" class="btn btn-small btn-outline-secondary"
                                data-dismiss="modal">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
