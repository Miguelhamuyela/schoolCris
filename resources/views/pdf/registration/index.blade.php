@extends('pdf.layouts.report-header')

@section('title', 'Lista de Alunos Matriculados')
@section('report-title', 'LISTA DE ALUNOS MATRICULADOS')
@section('report-subtitle')
    ANO LETIVO: {{ isset($schoolyear) && !empty($schoolyear) ? $schoolyear : 'TODOS' }}
    @if(isset($grade) && !empty($grade))
    - CLASSE: {{ $grade }}
    @endif
    @if(isset($course) && !empty($course))
    - CURSO: {{ $course }}
    @endif
    @if(isset($classe) && !empty($classe))
    - TURMA: {{ $classe }}
    @endif
@endsection

@section('content')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
</div>
<div class="main">
    @if ($studentSchoolYear->count() <= 0)
        <div class="alert alert-warning text-center">
            Nenhuma matrícula encontrada com os filtros selecionados.
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NOME</th>
                    <th>Nº PROCESSO</th>
                    <th>Nº BI</th>
                    <th>CURSO</th>
                    <th>CLASSE</th>
                    <th>TURMA</th>
                    <th>ANO LETIVO</th>
                    <th>TURNO</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentSchoolYear as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->students->name }}</td>
                        <td>{{ $item->students->nProcess }}</td>
                        <td>{{ $item->students->nBi }}</td>
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
        <div class="text-right mt-3">
            <p>Total de matrículas: {{ $studentSchoolYear->count() }}</p>
        </div>
    @endif
</div>
@endsection
