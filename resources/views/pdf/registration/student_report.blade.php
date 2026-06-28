@extends('pdf.layouts.report-header')

@section('title', 'Matrícula de Aluno')
@section('report-title', 'FICHA DE MATRÍCULA')
@section('report-subtitle')
    {{ $student->name }} - Nº PROCESSO: {{ $student->nProcess }}
@endsection

@section('content')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
</div>
<div class="main">
    <div class="student-info">
        <div class="row">
            <div class="col-md-3 text-center mb-3">
                @if($student->image && file_exists(storage_path('app/public/images/' . $student->image)))
                    <img src="{{ storage_path('app/public/images/' . $student->image) }}" 
                         alt="Foto de {{ $student->name }}" 
                         class="img-fluid rounded" style="max-height: 150px; max-width: 120px; object-fit: cover;">
                @else
                    <div style="width: 120px; height: 150px; border: 1px solid #d1d3e2; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; color: #6c757d; font-size: 12px;">
                        Sem foto
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <h4>{{ $student->name }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nº de Processo:</strong> {{ $student->nProcess }}</p>
                        <p><strong>Nº do BI:</strong> {{ $student->nBi }}</p>
                        <p><strong>Data de Nascimento:</strong> {{ $student->dateBirth }}</p>
                        <p><strong>Contacto:</strong> {{ $student->contact }} / {{ $student->contactAlter }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>E-mail:</strong> {{ $student->email }}</p>
                        <p><strong>Nome do Pai:</strong> {{ $student->father }}</p>
                        <p><strong>Nome da Mãe:</strong> {{ $student->mother }}</p>
                        <p><strong>Ano Lectivo:</strong> {{ $student->schoolyear }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-4 mb-3">Detalhes da Matrícula</h5>
    
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 35%">Curso:</th>
                    <td>{{ $registration->courses->name }}</td>
                </tr>
                <tr>
                    <th>Classe:</th>
                    <td>{{ $registration->grades->name }}</td>
                </tr>
                <tr>
                    <th>Turma:</th>
                    <td>{{ $registration->classes->name }}</td>
                </tr>
                <tr>
                    <th>Ano Letivo:</th>
                    <td>{{ $registration->schoolyears->name }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 35%">Turno:</th>
                    <td>{{ $registration->season }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        @if($registration->status == 'Confirmada')
                            <span class="badge badge-success">Confirmada</span>
                        @elseif($registration->status == 'Pendente')
                            <span class="badge badge-warning">Pendente</span>
                        @elseif($registration->status == 'Cancelada')
                            <span class="badge badge-danger">Cancelada</span>
                        @else
                            {{ $registration->status }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Data da Matrícula:</th>
                    <td>{{ $registration->created_at }}</td>
                </tr>
                <tr>
                    <th>Última Atualização:</th>
                    <td>{{ $registration->updated_at }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($registration->details)
    <div class="mt-4">
        <h5>Observações</h5>
        <div class="p-3 bg-light border rounded">
            {{ $registration->details }}
        </div>
    </div>
    @endif
</div>
@endsection 
