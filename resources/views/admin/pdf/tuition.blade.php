@extends('layouts.merge.dashboard')
@section('title', 'Relatório de Propinas')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gerar Relatório de Propinas</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h6 class="m-0 font-weight-bold text-primary">Preencha os campos para gerar o relatório</h6>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('pdf.tuition') }}" method="get" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <label for="schoolyear">Escolha o Ano lectivo</label>
                            <select class="form-control" name="schoolyear" id="schoolyear" required>
                                <option value=""></option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->name }}" {{ ($schoolyear->name == \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName()) ? 'selected' : '' }}>{{ $schoolyear->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 py-2">
                            <label for="month">Escolha o Mês</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value=""></option>
                                @php
                                    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                @endphp
                                @foreach ($meses as $mes)
                                    <option value="{{ $mes }}">
                                        {{ $mes }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 py-2">
                            <label for="student_id">Aluno Específico (opcional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nprocess" name="nprocess" placeholder="Digite o Nº de Processo do aluno">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarAluno">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Deixe em branco para ver todos os alunos. Se preencher, apenas o aluno específico será mostrado no relatório, com todos os seus pagamentos do mês selecionado.</small>
                            <input type="hidden" name="student_id" id="student_id">
                            <div id="student_info" class="mt-2 p-2 border rounded d-none">
                                <p class="mb-1"><strong>Nome:</strong> <span id="student_name"></span></p>
                                <p class="mb-0"><strong>Nº de Processo:</strong> <span id="student_nprocess"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex my-3 align-items-center">
                        <div class="col-md-4 form-check">
                            <input class="form-check-input" name="pay" type="checkbox" value="pay" id="pay" checked>
                            <label class="form-check-label" for="pay">
                                Incluir Pagos
                            </label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input class="form-check-input" name="cancel" type="checkbox" value="cancel" id="cancel" checked>
                            <label class="form-check-label" for="cancel">
                                Incluir Cancelados
                            </label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input class="form-check-input" name="pending" type="checkbox" value="pending" id="pending" checked>
                            <label class="form-check-label" for="pending">
                                Incluir Pendentes
                            </label>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
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