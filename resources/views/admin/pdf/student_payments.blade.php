@extends('layouts.merge.dashboard')
@section('title', 'Relatório Geral de Pagamentos')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Relatório Geral de Pagamentos do Aluno</h1>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Seleccione o aluno para gerar o relatório completo</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pdf.studentPayments') }}" method="get" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <label for="schoolyear_id">Ano Lectivo (opcional)</label>
                            <select class="form-control" name="schoolyear_id" id="schoolyear_id">
                                <option value="">Todos os anos lectivos</option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->id }}" {{ ($schoolyear->id == \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) ? 'selected' : '' }}>{{ $schoolyear->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 py-2">
                            <label for="student_id">Seleccione o Aluno</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nprocess" placeholder="Digite o Nº de Processo do aluno">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarAluno">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Digite o número de processo do aluno e clique em Buscar.</small>
                            <input type="hidden" name="student_id" id="student_id">
                            <div id="student_info" class="mt-2 p-2 border rounded d-none">
                                <p class="mb-1"><strong>Nome:</strong> <span id="student_name"></span></p>
                                <p class="mb-0"><strong>Nº de Processo:</strong> <span id="student_nprocess"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" id="btnGerar" disabled>
                            <i class="fas fa-file-pdf"></i> Gerar Relatório
                        </button>
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
            const btnBuscar = document.getElementById('btnBuscarAluno');
            const nprocessInput = document.getElementById('nprocess');
            const studentIdInput = document.getElementById('student_id');
            const studentInfoDiv = document.getElementById('student_info');
            const studentNameSpan = document.getElementById('student_name');
            const studentNprocessSpan = document.getElementById('student_nprocess');
            const btnGerar = document.getElementById('btnGerar');

            btnBuscar.addEventListener('click', function() {
                const nprocess = nprocessInput.value.trim();
                if (!nprocess) {
                    Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Digite o número de processo do aluno', confirmButtonText: 'OK' });
                    return;
                }

                fetch('/get/student/' + nprocess)
                    .then(response => {
                        if (!response.ok) throw new Error('Aluno não encontrado');
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.id) {
                            studentIdInput.value = data.id;
                            studentNameSpan.textContent = data.name;
                            studentNprocessSpan.textContent = data.nProcess;
                            studentInfoDiv.classList.remove('d-none');
                            btnGerar.disabled = false;
                        } else {
                            throw new Error('Dados do aluno inválidos');
                        }
                    })
                    .catch(() => {
                        studentIdInput.value = '';
                        studentInfoDiv.classList.add('d-none');
                        btnGerar.disabled = true;
                        Swal.fire({ icon: 'error', title: 'Erro', text: 'Aluno não encontrado. Verifique o número de processo.', confirmButtonText: 'OK' });
                    });
            });
        });
    </script>
@endsection('content')
