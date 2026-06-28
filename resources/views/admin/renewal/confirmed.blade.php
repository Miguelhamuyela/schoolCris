@extends('layouts.merge.dashboard')
@section('title', 'Alunos Confirmados')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Alunos Confirmados</h1>
            <a href="{{ route('admin.renewal.index') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                <i class="fas fa-user-check fa-sm text-white-50 mr-1"></i> Confirmar Novos Alunos
            </a>
        </div>

        <!-- Filtros -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtrar Alunos Confirmados</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.renewal.confirmed') }}" method="get">
                    <div class="row">
                        <div class="col-md-4 py-2">
                            <label for="schoolyear">Ano Lectivo</label>
                            <select name="schoolyear" id="schoolyear" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach ($schoolyears as $sy)
                                    <option value="{{ $sy->name }}" {{ ($schoolyear ?? \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName()) == $sy->name ? 'selected' : '' }}>
                                        {{ $sy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 py-2">
                            <label for="course">Curso</label>
                            <select class="form-control" name="course" id="course" required>
                                <option value="">Selecione um curso</option>
                                @foreach ($courses as $course_item)
                                    <option value="{{ $course_item->id }}" {{ isset($course) && $course == $course_item->id ? 'selected' : '' }}>
                                        {{ $course_item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 py-2">
                            <label for="grade">Classe (opcional)</label>
                            <select class="form-control" name="grade" id="grade">
                                <option value="">Todas as classes</option>
                                @php
                                    $grades = App\Models\Grade::orderBy('name')->get();
                                @endphp
                                @foreach ($grades as $grade_item)
                                    <option value="{{ $grade_item->id }}" {{ isset($grade) && $grade == $grade_item->id ? 'selected' : '' }}>
                                        {{ $grade_item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-2"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($confirmedStudents) && count($confirmedStudents) > 0)
        <!-- Lista de Alunos Confirmados -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Alunos Confirmados - {{ $course_name }} - {{ $schoolyear }}
                    @if(isset($grade_name)) - {{ $grade_name }} @endif
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Ações:</div>
                        <a class="dropdown-item" href="#" id="btnPrint">
                            <i class="fas fa-print fa-sm text-primary-400 mr-2"></i> Imprimir Lista
                        </a>
                        <a class="dropdown-item" href="#" onclick="exportToExcel()">
                            <i class="fas fa-file-excel fa-sm text-success mr-2"></i> Exportar Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nº Processo</th>
                                <th>Nome do Aluno</th>
                                <th>Classe</th>
                                <th>Turma</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($confirmedStudents as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->students->nProcess }}</td>
                                    <td>{{ $student->students->name }}</td>
                                    <td>{{ $student->grades->name }}</td>
                                    <td>{{ $student->classes->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.student.show', $student->students->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </a>
                                        <a href="{{ route('pdf.studentCard', $student->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-id-card"></i> Cartão
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <div class="alert alert-info">
                        <p><strong>Total de Alunos Confirmados:</strong> {{ $count }}</p>
                    </div>
                </div>
            </div>
        </div>
        @elseif(isset($confirmedStudents) && count($confirmedStudents) == 0)
        <div class="alert alert-warning">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Nenhum aluno encontrado</h5>
            <p>Não foram encontrados alunos confirmados com os filtros selecionados.</p>
        </div>
        @endif
    </div>

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar a tabela como DataTable
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });
            
            // Função para imprimir
            document.getElementById('btnPrint').addEventListener('click', function(e) {
                e.preventDefault();
                window.print();
            });
        });
        
        // Função para exportar para Excel
        function exportToExcel() {
            // Obter a tabela
            var table = document.getElementById("dataTable");
            
            // Obter dados da tabela
            var data = [];
            
            // Adicionar cabeçalho
            var headers = [];
            for (var i = 0; i < table.rows[0].cells.length - 1; i++) { // -1 para excluir coluna de ações
                headers.push(table.rows[0].cells[i].innerText);
            }
            data.push(headers);
            
            // Adicionar linhas
            for (var i = 1; i < table.rows.length; i++) {
                var row = [];
                for (var j = 0; j < table.rows[i].cells.length - 1; j++) { // -1 para excluir coluna de ações
                    row.push(table.rows[i].cells[j].innerText);
                }
                data.push(row);
            }
            
            // Converter para CSV
            var csv = data.map(function(row) {
                return row.join(',');
            }).join('\n');
            
            // Criar link de download
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", "alunos_confirmados.csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .card-body, .card-body * {
                visibility: visible;
            }
            .card-body {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .card-header, .dropdown, .dropdown-menu, .actions, button, a {
                display: none !important;
            }
        }
    </style>
@endsection 