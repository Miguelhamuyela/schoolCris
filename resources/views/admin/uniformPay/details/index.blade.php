@extends('layouts.merge.dashboard')
@section('title', 'Detalhes do Pagamento de Uniforme')
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Detalhes do Pagamento</h1>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5><b>Aluno:</b> {{ $uniformPay->students->name }}</h5>
                        <h5><b>Nº de Processo:</b> {{ $uniformPay->students->nProcess }}</h5>
                        <h5><b>Ano Lectivo:</b> {{ $uniformPay->schoolyears->name }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Preço:</b> {{ number_format($uniformPay->price, 2, ',', '.') }} kz</h5>
                        <h5><b>Estado:</b> 
                            @if ($uniformPay->state == 'Pago')
                                <span class="badge badge-success"> {{ $uniformPay->state }}</span>
                            @elseif($uniformPay->state == 'Pendente')
                                <span class="badge badge-warning"> {{ $uniformPay->state }}</span>
                            @else
                                <span class="badge badge-danger"> {{ $uniformPay->state }}</span>
                            @endif
                        </h5>
                        <h5><b>Data:</b> {{ $uniformPay->created_at->format('d/m/Y H:i') }}</h5>
                    </div>
                </div>
                <div class="mt-3">
                    @if ($uniformPay->state == 'Pago')
                        <a href="{{ route('pdf.uniformPayStudent', $uniformPay->id) }}" class="btn btn-warning" target="_blank">Gerar Factura</a>
                    @endif
                    <a href="{{ route('admin.uniformPay.edit', $uniformPay->id) }}" class="btn btn-primary">Editar</a>
                    <a href="{{ route('admin.uniformPay.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
@endsection('content')
