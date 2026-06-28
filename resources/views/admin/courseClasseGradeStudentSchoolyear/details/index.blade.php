@extends('layouts.merge.dashboard')
@section('title', 'Detalhes da Matrícula')
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
                                        Nome do Aluno: {{ $courseClasseGradeStudentSchoolyear->students->name }}
                                    </h2>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 text-right mt-5">
                                    <a href="{{ route('pdf.studentCard', $courseClasseGradeStudentSchoolyear->id) }}"
                                        target="_blank" class="btn btn-md btn-info shadow-sm text-end">Cartão</a>
                                    <a href="{{ route('admin.student.photo', $courseClasseGradeStudentSchoolyear->students->id) }}"
                                        class="btn btn-md btn-warning shadow-sm text-end">Atualizar Foto</a>
                                    <a href="{{ route('admin.courseClasseGradeStudentSchoolyear.edit', $courseClasseGradeStudentSchoolyear->id) }}"
                                        class="btn btn-md btn-primary shadow-sm text-end">Editar</a>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Nº de Processo</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->students->nProcess }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Curso</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->courses->name }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Classe</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->grades->name }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Turma</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->classes->name }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Turno</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->season }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Ano lectivo</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->schoolyears->name }}</p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Contacto | Alternativo</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->students->contact }} |
                                        {{ $courseClasseGradeStudentSchoolyear->students->contactAlter }} </p>
                                </div>
                                <hr>



                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Detalhes da Matricula</b>
                                    </h5>
                                    <p class="text-dark">{{ $courseClasseGradeStudentSchoolyear->details}}</p>
                                </div>





                            </div>



                            <div class="row align-items-center">
                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <h5>
                                        <b class="mb-1">Foto do Aluno</b>
                                    </h5>
                                    @php
                                        $hasImage = !empty($courseClasseGradeStudentSchoolyear->students->image) && 
                                                    file_exists(storage_path('app/public/images/' . $courseClasseGradeStudentSchoolyear->students->image));
                                    @endphp
                                    
                                    @if($hasImage)
                                        <img src="{{ route('admin.student.image', $courseClasseGradeStudentSchoolyear->students->image) }}"
                                            alt="Foto de {{ $courseClasseGradeStudentSchoolyear->students->name }}"
                                            class="img-fluid" style="width: 160px; height: 200px; object-fit: cover; border-radius: 5px; border: 2px solid #FF7F00;">
                                    @else
                                        <img src="{{ asset('dashboard/assets/img/undraw_profile.svg') }}"
                                            alt="Foto não disponível"
                                            class="img-fluid" style="max-width: 200px; border-radius: 5px; border: 2px solid #FF7F00;">
                                        <p class="mt-2 text-muted">Foto não cadastrada</p>
                                    @endif
                                </div>
                            </div>




                            <div class="row align-items-center">
                                <div class="col-md-7 mb-2">
                                    <hr>
                                    <p class="mb-1 text-dark"><b>Data de Cadastro</b>
                                        {{ $courseClasseGradeStudentSchoolyear->created_at }}
                                    </p>
                                    <p class="mb-1 text-dark"><b>Última Actualização</b>
                                        {{ $courseClasseGradeStudentSchoolyear->updated_at }}
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
