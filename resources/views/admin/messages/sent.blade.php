@extends('layouts.merge.dashboard')
@section('title', 'Mensagens Enviadas')
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-paper-plane mr-2 text-primary"></i> Mensagens Enviadas
        </h1>
        <div>
            <a href="{{ route('admin.messages.create') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm mr-1"></i> Nova Mensagem
            </a>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-inbox fa-sm mr-1"></i> Caixa de Entrada
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enviadas</h6>
        </div>
        <div class="card-body p-0">
            @if($messages->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-paper-plane fa-3x mb-3"></i>
                    <p>Ainda não enviaste nenhuma mensagem.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($messages as $msg)
                        <a href="{{ route('admin.messages.show', $msg->id) }}"
                           class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-dark">{{ $msg->subject }}</div>
                                    <small class="text-muted">
                                        Para: <strong>{{ $msg->receiver->name ?? 'Desconhecido' }}</strong>
                                        &nbsp;·&nbsp;
                                        @if($msg->read)
                                            <span class="text-success"><i class="fas fa-check-double mr-1"></i>Lida</span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-clock mr-1"></i>Não lida</span>
                                        @endif
                                    </small>
                                </div>
                                <small class="text-muted flex-shrink-0 ml-3">
                                    {{ $msg->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
