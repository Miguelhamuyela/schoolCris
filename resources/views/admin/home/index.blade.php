@extends('layouts.merge.dashboard')
@section('title', 'Dashboard')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Saudação personalizada -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow border-0 rounded-lg">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-auto pr-0">
                                <div class="avatar-circle bg-primary mr-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 50%;">
                                    <i class="fas fa-user text-white fa-2x"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="mb-1">Olá, {{ Auth::user()->name }}!</h3>
                                <p class="text-muted mb-0">
                                    @php
                                        $hora = date('H');
                                        $saudacao = '';
                                        if($hora >= 5 && $hora < 12) {
                                            $saudacao = 'Bom dia';
                                        } elseif($hora >= 12 && $hora < 18) {
                                            $saudacao = 'Boa tarde';
                                        } else {
                                            $saudacao = 'Boa noite';
                                        }
                                    @endphp
                                    {{ $saudacao }}! Hoje é {{ date('d/m/Y') }} | Ano letivo: {{ \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destaques e ações rápidas -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <h5 class="border-bottom pb-2">Como posso ajudar hoje?</h5>
                        <div class="row mt-4">
                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Secretaria'))
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.student.create') }}" class="text-decoration-none">
                                    <div class="text-center h-100 p-3 rounded bg-light">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                                        </div>
                                        <span class="d-block text-dark">Inscrever novo aluno</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.courseClasseGradeStudentSchoolyear.create') }}" class="text-decoration-none">
                                    <div class="text-center h-100 p-3 rounded bg-light">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-user-graduate fa-2x text-success"></i>
                                        </div>
                                        <span class="d-block text-dark">Fazer matrícula</span>
                                    </div>
                                </a>
                            </div>
                            @endif

                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Secretaria'))
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.tuition.create') }}" class="text-decoration-none">
                                    <div class="text-center h-100 p-3 rounded bg-light">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                                        </div>
                                        <span class="d-block text-dark">Pagar Propinas</span>
                                    </div>
                                </a>
                            </div>
                            @endif

                            @if (auth()->user()->hasRole('admin'))
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.teacher.create') }}" class="text-decoration-none">
                                    <div class="text-center h-100 p-3 rounded bg-light">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                                        </div>
                                        <span class="d-block text-dark">Adicionar professor</span>
                                    </div>
                                </a>
                            </div>
                            @endif

                            <!-- Ação de Mensagens para TODOS -->
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.messages.create') }}" class="text-decoration-none">
                                    <div class="text-center h-100 p-3 rounded bg-light">
                                        <div class="icon-wrapper mb-3">
                                            <i class="fas fa-paper-plane fa-2x text-secondary"></i>
                                        </div>
                                        <span class="d-block text-dark">Enviar Mensagem</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>

            <div class="col-lg-4">
                <div class="card shadow border-0 rounded-lg mb-4 bg-gradient-primary">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-book-reader fa-2x mr-3"></i>
                            <h5 class="mb-0">Visão Geral</h5>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span>Alunos matriculados</span>
                            <span class="font-weight-bold">{{ App\Models\Student::count() }}</span>
                        </div>
                        @if (auth()->user()->hasRole('admin'))
                        <div class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span>Professores</span>
                            <span class="font-weight-bold">{{ App\Models\Teacher::count() }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between py-2 border-bottom border-light">
                            <span>Turmas</span>
                            <span class="font-weight-bold">{{ App\Models\Classe::count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Cursos</span>
                            <span class="font-weight-bold">{{ App\Models\Course::count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dicas de Uso do Sistema -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-0 rounded-lg mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fas fa-lightbulb text-warning mr-2"></i>Dicas de Uso do Sistema</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success mr-2"></i>
                                Utilize o menu lateral para navegar entre as principais funcionalidades do sistema.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-user-plus text-primary mr-2"></i>
                                Para cadastrar um novo aluno, clique em "Inscrever novo aluno" na seção de ações rápidas.
                            </li>
                            @if (auth()->user()->hasRole('admin'))
                            <li class="mb-3">
                                <i class="fas fa-chalkboard-teacher text-info mr-2"></i>
                                Professores podem ser adicionados facilmente pela opção "Adicionar professor".
                            </li>
                            @endif
                            <li class="mb-3">
                                <i class="fas fa-search text-secondary mr-2"></i>
                                Use a barra de pesquisa para encontrar rapidamente alunos, professores ou turmas.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-save text-success mr-2"></i>
                                Lembre-se de salvar todas as alterações antes de sair de uma página de cadastro ou edição.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection('content')
