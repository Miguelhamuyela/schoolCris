@extends('layouts.merge.dashboard')
@section('title', 'Relatório de Alunos')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gerar Relatório de Alunos</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h6 class="m-0 font-weight-bold text-primary">Preencha pelo menos um dos campos para gerar o relatório</h6>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('pdf.student') }}" method="get" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <label for="schoolyear">Ano Letivo (opcional)</label>
                            <select class="form-control" name="schoolyear" id="schoolyear">
                                <option value=""></option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->name }}" {{ ($schoolyear->name == \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName()) ? 'selected' : '' }}>{{ $schoolyear->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 py-2">
                            <label for="classe">Turma (opcional)</label>
                            <select class="form-control" name="classe" id="classe">
                                <option value=""></option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->name }}">{{ $classe->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="course">Curso</label>
                            <select class="form-control" name="course" id="course" required>
                                <option value="">Selecione um curso</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="grade">Classe (opcional)</label>
                            <select class="form-control" name="grade" id="grade">
                                <option value=""></option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> O campo Curso é obrigatório. Selecione pelo menos um filtro adicional para refinar o relatório. 
                                Se quiser relatório de todos os alunos de um curso, selecione apenas o curso.
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-pdf mr-2"></i> Gerar Relatório
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnBuscarAluno = document.getElementById('btnBuscarAluno');
            const nprocessInput = document.getElementById('nprocess');
            const studentIdInput = document.getElementById('student_id');
            const studentInfoDiv = document.getElementById('student_info');
            const studentNameSpan = document.getElementById('student_name');
            const studentNprocessSpan = document.getElementById('student_nprocess');

            btnBuscarAluno.addEventListener('click', function() {
                const nprocess = nprocessInput.value.trim();
                if (!nprocess) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Digite o número de processo do aluno',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Buscar aluno via AJAX
                fetch(`/get/student/${nprocess}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Aluno não encontrado');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.id) {
                            studentIdInput.value = data.id;
                            studentNameSpan.textContent = data.name;
                            studentNprocessSpan.textContent = data.nProcess;
                            studentInfoDiv.classList.remove('d-none');
                        } else {
                            throw new Error('Dados do aluno inválidos');
                        }
                    })
                    .catch(error => {
                        studentIdInput.value = '';
                        studentInfoDiv.classList.add('d-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Aluno não encontrado. Verifique o número de processo.',
                            confirmButtonText: 'OK'
                        });
                    });
            });
        });
    </script>
@endsection('content') 