@extends('layouts.merge.dashboard')
@section('title', 'Caixa de Entrada')
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-inbox mr-2 text-primary"></i> Caixa de Entrada
            @if($unread > 0)
                <span class="badge badge-danger ml-2">{{ $unread }}</span>
            @endif
        </h1>
        <a href="{{ route('admin.messages.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-paper-plane fa-sm mr-1"></i> Nova Mensagem
        </a>
    </div>

    @if(session('destroy'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> Mensagem apagada com sucesso.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Mensagens Recebidas</h6>
            <a href="{{ route('admin.messages.sent') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-paper-plane mr-1"></i> Enviadas
            </a>
        </div>
        <div class="card-body p-0">
            @if($messages->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-envelope-open fa-3x mb-3"></i>
                    <p>Não tens mensagens na caixa de entrada.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($messages as $msg)
                        <a href="{{ route('admin.messages.show', $msg->id) }}"
                           class="list-group-item list-group-item-action {{ !$msg->read ? 'bg-light font-weight-bold' : '' }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    @if(!$msg->read)
                                        <span class="badge badge-primary mr-3 flex-shrink-0">Nova</span>
                                    @else
                                        <i class="fas fa-envelope-open text-muted mr-3"></i>
                                    @endif
                                    <div>
                                        <div class="text-dark {{ !$msg->read ? 'font-weight-bold' : '' }}">
                                            {{ $msg->subject }}
                                        </div>
                                        <small class="text-muted">
                                            De: <strong>{{ $msg->sender->name ?? 'Desconhecido' }}</strong>
                                        </small>
                                    </div>
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
