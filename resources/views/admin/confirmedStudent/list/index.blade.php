@extends('layouts.merge.dashboard')
@section('title', 'Alunos Confirmados')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Alunos Confirmados</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.renewal.confirmed') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="schoolyear">Ano Lectivo</label>
                            <select name="schoolyear" id="schoolyear" class="form-control">
                                <option value="">Todos os Anos Lectivos</option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->id }}" {{ ($filterSchoolyear ?? \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) == $schoolyear->id ? 'selected' : '' }}>
                                        {{ $schoolyear->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="course">Curso</label>
                            <select name="course" id="course" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ $filterCourse == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="class">Turma</label>
                            <select name="class" id="class" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ $filterClass == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="grade">Classe</label>
                            <select name="grade" id="grade" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ $filterGrade == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter fa-sm"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.renewal.confirmed') }}" class="btn btn-secondary">
                                <i class="fas fa-undo fa-sm"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Alunos Confirmados</h6>
                    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        data-toggle="modal" data-target="#myModal">
                        <i class="fas fa-file-pdf fa-sm text-white-50"></i> Imprimir lista
                    </button>
                </div>
            </div>
            <div class="card-body">
                @include('extra._Error.index')
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOME</th>
                                <th>Nº PROCESSO</th>
                                <th>Nº BI</th>
                                <th>CURSO</th>
                                <th>TURMA</th>
                                <th>CLASSE</th>
                                <th>ANO LETIVO</th>
                                <th>CONTACTO</th>
                                <th>ACÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->nProcess }}</td>
                                    <td>{{ $item->nBi }}</td>
                                    <td>{{ $item->course }}</td>
                                    <td>{{ $item->class }}</td>
                                    <td>{{ $item->grade }}</td>
                                    <td>{{ $item->schoolyear }}</td>
                                    <td>{{ $item->contact }}</td>
                                    <td>
                                        <div class="dropdown mb-4">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            </button>
                                            <div class="dropdown-menu animated--fade-in"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.student.show', $item->id) }}">Detalhes</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.student.edit', $item->id) }}">Editar</a>
                                                <a class="dropdown-item" href="{{ route('admin.confirmedStudent.confirm', $item->id) }}"
                                                    onclick="return confirm('Tem certeza que deseja matricular este aluno?')">Matricular</a>
                                                <a class="dropdown-item delete-button" href="#"
                                                    data-based-id="{{ route('admin.student.destroy', $item->id) }}">Remover</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Nenhum aluno confirmado encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        $('#dataTable').DataTable({
            language: {
                url: '/dashboard/assets/vendor/datatables/Portuguese-Brasil.json'
            },
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush 