@extends('layouts.merge.dashboard')
@section('title', $message->subject)
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope-open-text mr-2 text-primary"></i> {{ $message->subject }}
        </h1>
        <div>
            <a href="{{ route('admin.messages.create') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-reply fa-sm mr-1"></i> Responder
            </a>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm mr-1"></i> Voltar
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="font-weight-bold text-dark">{{ $message->subject }}</span>
            </div>
            <div>
                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Tens a certeza que queres apagar esta mensagem?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash fa-sm"></i> Apagar
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            {{-- Cabeçalho do email --}}
            <div class="border-bottom pb-3 mb-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3"
                         style="width:42px;height:42px;flex-shrink:0">
                        <span class="text-white font-weight-bold">
                            {{ strtoupper(substr($message->sender->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">{{ $message->sender->name ?? 'Desconhecido' }}</div>
                        <small class="text-muted">
                            Para: <strong>{{ $message->receiver->name ?? 'Desconhecido' }}</strong>
                            &nbsp;·&nbsp;
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
                @if($message->read && $message->read_at)
                    <small class="text-success">
                        <i class="fas fa-check-double mr-1"></i> Lida em {{ $message->read_at->format('d/m/Y H:i') }}
                    </small>
                @endif
            </div>

            {{-- Corpo da mensagem --}}
            <div class="message-body" style="white-space: pre-wrap; line-height: 1.7; font-size: 1rem;">{{ $message->body }}</div>
        </div>
    </div>
</div>

@endsection
