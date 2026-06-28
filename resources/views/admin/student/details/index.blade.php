@extends('layouts.merge.dashboard')
@section('title', 'Detalhes do Estudante')
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Detalhes</h1>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12">


                            <div class="row justify-content-between mb-4">
                                <div class="col-12 col-md-6 col-lg-6 mt-5 ml-5">
                                    <h2 class="h3 page-title">
                                        Nome: {{ $student->name }}
                                    </h2>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 text-right mt-5">
                                    @if(isset($registration))
                                        <a href="{{ route('pdf.studentCard', $registration->id) }}"
                                            target="_blank" class="btn btn-md btn-info shadow-sm text-end">Cartao</a>
                                    @endif
                                    <a href="{{ route('admin.student.photo', $student->id) }}"
                                        class="btn btn-md btn-warning shadow-sm text-end">Atualizar Foto</a>
                                    <a href="{{ route('admin.student.edit', $student->id) }}"
                                        class="btn btn-md btn-primary shadow-sm text-end">Editar</a>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12 col-md-4 col-lg-3 text-center">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Foto do Aluno</h6>
                                        </div>
                                        <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 220px;">
                                            @if($student->image && file_exists(storage_path('app/public/images/' . $student->image)))
                                                <img src="{{ route('admin.student.image', $student->image) }}" 
                                                     alt="Foto de {{ $student->name }}" 
                                                     class="img-fluid rounded shadow-sm" style="width: 160px; height: 200px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('dashboard/assets/img/undraw_profile.svg') }}" 
                                                     alt="Foto não disponível" 
                                                     class="img-fluid rounded opacity-50" style="max-height: 150px; object-fit: contain;">
                                                <p class="mt-2 text-muted small w-100 text-center">Foto não cadastrada</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-8 col-lg-9">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Número de Processo</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->nProcess }}</p>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Número de BI</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->nBi }}</p>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">E-mail do Aluno</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->email }}</p>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Data de Nascimento</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->dateBirth }}</p>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Contacto do Aluno / Alternativo</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->contact }} / {{ $student->contactAlter }}</p>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Nome do Pai</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->father }}</p>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Nome da Mâe</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->mother }}</p>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-2">
                                            <h5>
                                                <b class="mb-1">Ano lectivo</b>
                                            </h5>
                                            <p class="text-dark">{{ $student->schoolyear }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalhes da Matrícula -->
                            @if(isset($registration))
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informações da Matrícula</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Curso:</b></h5>
                                            <p>{{ $registration->courses->name ?? 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Turma:</b></h5>
                                            <p>{{ $registration->classes->name ?? 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Classe:</b></h5>
                                            <p>{{ $registration->grades->name ?? 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Ano Lectivo:</b></h5>
                                            <p>{{ $registration->schoolyears->name ?? 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Turno:</b></h5>
                                            <p>{{ $registration->season ?? 'Não informado' }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h5><b>Status da Matrícula:</b></h5>
                                            @if(isset($registration->status))
                                                @if($registration->status == 'Confirmada')
                                                    <p class="badge badge-success p-2">{{ $registration->status }}</p>
                                                @elseif($registration->status == 'Pendente')
                                                    <p class="badge badge-warning p-2">{{ $registration->status }}</p>
                                                @elseif($registration->status == 'Cancelada')
                                                    <p class="badge badge-danger p-2">{{ $registration->status }}</p>
                                                @else
                                                    <p>{{ $registration->status }}</p>
                                                @endif
                                            @else
                                                <p>Não informado</p>
                                            @endif
                                        </div>
                                        <div class="col-md-12 mb-3 mt-2">
                                            <h5><b>Observações:</b></h5>
                                            <p>{{ $registration->details ?? 'Sem observações' }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="mb-1"><b>Data de Matrícula:</b> {{ $registration->created_at ? date('d/m/Y', strtotime($registration->created_at)) : 'N/A' }}</p>
                                            <p class="mb-1"><b>Última Atualização:</b> {{ $registration->updated_at ? date('d/m/Y', strtotime($registration->updated_at)) : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Aluno Não Matriculado</h5>
                                <p>Este aluno ainda não possui matrícula registrada. Clique no botão abaixo para matricular o aluno.</p>
                                <hr>
                                <a href="{{ route('admin.courseClasseGradeStudentSchoolyear.create') }}" class="btn btn-warning">
                                    <i class="fas fa-user-graduate"></i> Matricular Aluno
                                </a>
                            </div>
                            @endif
                            
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-2">
                                    <hr>
                                    <p class="mb-1 text-dark"><b>Data de Cadastro</b> {{ $student->created_at }}
                                    </p>
                                    <p class="mb-1 text-dark"><b>Última Actualização</b> {{ $student->updated_at }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>

    </div>
@endsection('content')
