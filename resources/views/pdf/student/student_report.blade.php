@extends('pdf.layouts.report-header')

@section('title', 'Relatório de Aluno')
@section('report-title', 'FICHA DO ALUNO')
@section('report-subtitle')
    {{ $student->name }} - Nº PROCESSO: {{ $student->nProcess }}
@endsection

@section('content')
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

    <h5 class="mt-4 mb-3">Informações de Matrícula</h5>
    
    @if ($registrations->count() <= 0)
        <div class="alert alert-warning">
            Este aluno não possui matrículas registradas.
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CURSO</th>
                    <th>CLASSE</th>
                    <th>TURMA</th>
                    <th>ANO LETIVO</th>
                    <th>TURNO</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrations as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->courses->name }}</td>
                        <td>{{ $item->grades->name }}</td>
                        <td>{{ $item->classes->name }}</td>
                        <td>{{ $item->schoolyears->name }}</td>
                        <td>{{ $item->season }}</td>
                        <td>
                            @if($item->status == 'Confirmada')
                                <span class="badge badge-success">Confirmada</span>
                            @elseif($item->status == 'Pendente')
                                <span class="badge badge-warning">Pendente</span>
                            @elseif($item->status == 'Cancelada')
                                <span class="badge badge-danger">Cancelada</span>
                            @else
                                {{ $item->status }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        <p><strong>Data de Cadastro:</strong> {{ $student->created_at }}</p>
        <p><strong>Última Atualização:</strong> {{ $student->updated_at }}</p>
    </div>
</div>
@endsection 
