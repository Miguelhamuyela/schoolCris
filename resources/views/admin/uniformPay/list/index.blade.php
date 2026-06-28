@extends('layouts.merge.dashboard')
@section('title', 'Histórico de Pagamentos de Uniformes')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Histórico de Pagamentos de Uniformes</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registros de Pagamentos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aluno</th>
                                <th>Nº de Processo</th>
                                <th>Ano lectivo</th>
                                <th>Preço</th>
                                <th>Estado</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($uniformPays as $uniformPay)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $uniformPay->students->name }}</td>
                                    <td>{{ $uniformPay->students->nProcess }}</td>
                                    <td>{{ $uniformPay->schoolyears->name }}</td>
                                    <td>{{ number_format($uniformPay->price, 2, ',', '.') }} kz</td>
                                    <td>
                                        @if ($uniformPay->state == 'Pago')
                                            <h5><span class="badge badge-success"> {{ $uniformPay->state }}</span></h5>
                                        @elseif($uniformPay->state == 'Pendente')
                                            <h5><span class="badge badge-warning"> {{ $uniformPay->state }}</span></h5>
                                        @elseif($uniformPay->state == 'Cancelado')
                                            <h5><span class="badge badge-danger"> {{ $uniformPay->state }}</span></h5>
                                        @else
                                            <h5><span class="badge badge-secondary"> {{ $uniformPay->state }}</span></h5>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="dropdown mb-4">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            </button>
                                            <div class="dropdown-menu animated--fade-in"
                                                aria-labelledby="dropdownMenuButton">
                                                @if ($uniformPay->state == 'Pago')
                                                    <a class="dropdown-item"
                                                        href="{{ route('pdf.uniformPayStudent', $uniformPay->id) }}"
                                                        target="_blank">Factura</a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.uniformPay.show', $uniformPay->id) }}">Detalhes</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.uniformPay.edit', $uniformPay->id) }}">Editar</a>
                                                <a class="dropdown-item delete-button" href="#"
                                                    data-based-id="{{ route('admin.uniformPay.destroy', $uniformPay->id) }}">Remover</a>
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
