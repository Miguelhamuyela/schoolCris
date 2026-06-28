@extends('layouts.merge.dashboard')
@section('title', 'Nova Mensagem')
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-paper-plane mr-2 text-primary"></i> Nova Mensagem
        </h1>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Voltar à Caixa de Entrada
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Escrever Mensagem</h6>
        </div>
        <div class="card-body">
            @include('extra._Error.index')

            <form action="{{ route('admin.messages.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="receiver_id"><i class="fas fa-user mr-1"></i> Destinatário</label>
                    <select class="form-control" name="receiver_id" id="receiver_id" required>
                        <option value="">-- Selecionar Destinatário --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                ({{ $user->roles->first()->name ?? 'Sem função' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject"><i class="fas fa-tag mr-1"></i> Assunto</label>
                    <input type="text" class="form-control" name="subject" id="subject"
                           placeholder="Assunto da mensagem" required value="{{ old('subject') }}">
                </div>

                <div class="form-group">
                    <label for="body"><i class="fas fa-align-left mr-1"></i> Mensagem</label>
                    <textarea class="form-control" name="body" id="body" rows="8"
                              placeholder="Escreva aqui a sua mensagem..." required>{{ old('body') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar Mensagem
                </button>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
            </form>
        </div>
    </div>
</div>

@endsection
