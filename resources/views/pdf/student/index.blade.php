@extends('pdf.layouts.report-header')

@section('title', 'Lista de Alunos')
@section('report-title', 'LISTA DE ALUNOS')
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
<div class="main">
    @if ($students->count() <= 0)
        <div class="alert alert-warning text-center">
            Nenhum aluno encontrado com os filtros selecionados.
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NOME</th>
                    <th>Nº PROCESSO</th>
                    <th>Nº BI</th>
                    <th>ANO LETIVO</th>
                    <th>CONTACTO</th>
                    @if(isset($registrations))
                    <th>CURSO</th>
                    <th>CLASSE</th>
                    <th>TURMA</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->nProcess }}</td>
                        <td>{{ $item->nBi }}</td>
                        <td>{{ $item->schoolyear }}</td>
                        <td>{{ $item->contact }}</td>
                        @if(isset($registrations))
                        <td>
                            @php
                                $registration = $registrations->where('fk_students_id', $item->id)->first();
                            @endphp
                            {{ $registration ? $registration->courses->name : 'N/A' }}
                        </td>
                        <td>
                            {{ $registration ? $registration->grades->name : 'N/A' }}
                        </td>
                        <td>
                            {{ $registration ? $registration->classes->name : 'N/A' }}
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right mt-3">
            <p>Total de alunos: {{ $students->count() }}</p>
        </div>
    @endif
</div>
@endsection
