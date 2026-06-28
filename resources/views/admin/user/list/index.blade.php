@extends('layouts.merge.dashboard')
@section('title', 'Usuários')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Lista de Usuários</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Usuários</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.user.list') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="nBi" placeholder="Pesquisar utilizador por Nº de BI..." value="{{ request('nBi') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                Pesquisar
                            </button>
                        </div>
                    </div>
                </form>


                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Nível de Acesso</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ $user->profile_image_url }}" alt="Foto"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd;">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            {{ $role->name }}
                                            @break
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="dropdown mb-4">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            </button>
                                            <div class="dropdown-menu animated--fade-in"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.user.show', $user->id) }}">Detalhes</a>
                                                @if (auth()->user()->hasRole('admin'))
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.user.edit', $user->id) }}">Editar</a>
                                                    <a class="dropdown-item delete-button" href="#"
                                                        data-based-id="{{ route('admin.user.destroy', $user->id) }}">Remover</a>
                                                @endif

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>
@endsection('content')
