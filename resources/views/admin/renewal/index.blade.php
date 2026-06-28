@extends('layouts.merge.dashboard')
@section('title', 'Confirmação de Alunos')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Confirmação de Alunos</h1>
            <a href="{{ route('admin.renewal.confirmed') }}" class="d-none d-sm-inline-block btn btn-info shadow-sm">
                <i class="fas fa-clipboard-check fa-sm text-white-50 mr-1"></i> Ver Alunos Confirmados
            </a>
        </div>

        <!-- Filtros -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtrar Alunos para Confirmação</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.renewal.index') }}" method="get">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 py-2">
                            <input type="hidden" name="schoolyear_old" value="{{ $schoolyear_old ?? \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}">
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
                            <label for="grade">Classe Atual (opcional)</label>
                            <select class="form-control" name="grade" id="grade">
                                <option value="">Todas as classes</option>
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
                                <i class="fas fa-search mr-2"></i> Filtrar Alunos
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($registrations) && count($registrations) > 0)
        <!-- Lista de Alunos para Confirmação -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Alunos para Confirmação - {{ $course_name }}</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Ações:</div>
                        <button class="dropdown-item" id="btnSelectAll">Selecionar Todos</button>
                        <button class="dropdown-item" id="btnDeselectAll">Desmarcar Todos</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.renewal.process') }}" method="post" id="formRenewal">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Renovar</th>
                                    <th>Nº Processo</th>
                                    <th>Nome do Aluno</th>
                                    <th>Ano Letivo Atual</th>
                                    <th>Classe Atual</th>
                                    <th>Nova Classe</th>
                                    <th>Nova Turma</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Buscar classes disponíveis
                                    $classes = App\Models\Classe::orderBy('name')->get();
                                    $allGrades = App\Models\Grade::orderBy('name')->get();
                                @endphp

                                @foreach($registrations as $index => $registration)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="renew[{{ $registration->students->id }}]" value="1" class="check-renew">
                                            <input type="hidden" name="student_ids[]" value="{{ $registration->students->id }}">
                                        </td>
                                        <td>{{ $registration->students->nProcess }}</td>
                                        <td>{{ $registration->students->name }}</td>
                                        <td>{{ $registration->schoolyears->name }}</td>
                                        <td>{{ $registration->grades->name }}</td>
                                        <td>
                                            <select class="form-control" name="new_grade_ids[]" required>
                                                @foreach($allGrades as $grade_option)
                                                    @php
                                                        // Lógica para selecionar a próxima classe automaticamente
                                                        $currentGradeNumber = intval($registration->grades->name);
                                                        $nextGradeNumber = $currentGradeNumber + 1;
                                                        $isNextGrade = strpos($grade_option->name, $nextGradeNumber) !== false;
                                                        $isSameGrade = $grade_option->id == $registration->fk_grades_id;
                                                    @endphp
                                                    <option value="{{ $grade_option->id }}" 
                                                        {{ $isNextGrade ? 'selected' : '' }}>
                                                        {{ $grade_option->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="new_classe_ids[]" required>
                                                @foreach($classes as $classe)
                                                    <option value="{{ $classe->id }}" 
                                                        {{ $classe->id == $registration->fk_classes_id ? 'selected' : '' }}>
                                                        {{ $classe->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" name="course_id" value="{{ $course }}">
                    <input type="hidden" name="new_schoolyear" value="{{ $next_schoolyear }}">

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informações da Confirmação</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Curso:</strong> {{ $course_name }}</p>
                                    <p><strong>Novo Ano Letivo:</strong> {{ $next_schoolyear ?? \App\Helpers\SchoolYearHelper::getNextSchoolYearName() }}</p>
                                    <p><strong>Total de Alunos:</strong> <span id="totalStudents">{{ count($registrations) }}</span></p>
                                    <p><strong>Alunos Selecionados:</strong> <span id="selectedStudents">0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Instruções</h5>
                                <p>1. Selecione os alunos que deseja confirmar para o próximo ano letivo.</p>
                                <p>2. Verifique se a nova classe e turma estão corretas para cada aluno.</p>
                                <p>3. Clique em "Confirmar Alunos" para processar.</p>
                                <hr>
                                <p class="mb-0">Esta ação irá criar novas matrículas para o ano letivo {{ $next_schoolyear }}.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg" id="btnSubmit" disabled>
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Alunos
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if(isset($registrations) && count($registrations) == 0)
        <div class="alert alert-warning">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Nenhum aluno encontrado</h5>
            <p>Não foram encontrados alunos com os filtros selecionados. Por favor, tente outros critérios de busca.</p>
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
            const checkboxes = document.querySelectorAll('.check-renew');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnDeselectAll = document.getElementById('btnDeselectAll');
            const btnSubmit = document.getElementById('btnSubmit');
            const selectedStudentsCount = document.getElementById('selectedStudents');
            
            // Função para atualizar contadores e estado do botão
            function updateCounters() {
                const checked = document.querySelectorAll('.check-renew:checked').length;
                selectedStudentsCount.textContent = checked;
                btnSubmit.disabled = checked === 0;
            }
            
            // Atualizar quando um checkbox é alterado
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCounters);
            });
            
            // Selecionar todos
            btnSelectAll.addEventListener('click', function(e) {
                e.preventDefault();
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateCounters();
            });
            
            // Desmarcar todos
            btnDeselectAll.addEventListener('click', function(e) {
                e.preventDefault();
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateCounters();
            });
            
            // Confirmação antes de enviar
            document.getElementById('formRenewal').addEventListener('submit', function(e) {
                e.preventDefault();
                const checked = document.querySelectorAll('.check-renew:checked').length;
                
                if (checked === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Selecione pelo menos um aluno para confirmar a matrícula.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                Swal.fire({
                    icon: 'question',
                    title: 'Confirmar Alunos',
                    text: `Você está prestes a confirmar ${checked} alunos para o próximo ano letivo. Deseja continuar?`,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
            
            // Inicializar contadores
            updateCounters();
        });
    </script>
@endsection 