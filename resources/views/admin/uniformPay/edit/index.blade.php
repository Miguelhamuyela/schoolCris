@extends('layouts.merge.dashboard')
@section('title', 'Editar Pagamento de Uniforme')
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Editar Pagamento de Uniforme</h1>
                </div>
            </div>

            @include('extra._Error.index')

            <div class="card-body">
                <form action="{{ route('admin.uniformPay.update', $uniformPay->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('form._formUniformPay.index')

                </form>
            </div>
        </div>
    </div>
@endsection('content')
