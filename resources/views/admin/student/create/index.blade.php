@extends('layouts.merge.dashboard')
@section('title', 'Inscrição e Matrícula de Aluno') 
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Inscrição e Matrícula de Aluno</h1>
                </div>
            </div>

            @include('extra._Error.index')

            <div class="card-body">
                <form action="{{ route('admin.student.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Dados do Aluno</h6>
                        </div>
                        <div class="card-body">
                    @include('form._formStudent.index')
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Dados da Matrícula</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 py-2">
                                    <label for="fk_courses_id">Escolha o Curso</label>
                                    <select class="form-control" name="fk_courses_id" id="fk_courses_id" required>
                                        <option selected></option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('fk_courses_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 py-2">
                                    <label for="fk_classes_id">Escolha a Turma</label>
                                    <select class="form-control" name="fk_classes_id" id="fk_classes_id" required>
                                        <option selected></option>
                                        @foreach ($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('fk_classes_id') == $classe->id ? 'selected' : '' }}>
                                                {{ $classe->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 py-2">
                                    <label for="fk_grades_id">Escolha a Classe</label>
                                    <select class="form-control" name="fk_grades_id" id="fk_grades_id" required>
                                        <option selected></option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ old('fk_grades_id') == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="fk_schoolyears_id" value="{{ \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId() }}">

                                <div class="col-md-6 py-2">
                                    <label for="season">Escolha o Turno</label>
                                    <select class="form-control" name="season" id="season" required>
                                        <option selected></option>
                                        <option value="Manhã" {{ old('season') == 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                        <option value="tarde" {{ old('season') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                                        <option value="Noite" {{ old('season') == 'Noite' ? 'selected' : '' }}>Noite</option>
                                    </select>
                                </div>

                                <div class="col-md-6 py-2">
                                    <label for="registration_status">Status da Matrícula</label>
                                    <select class="form-control" name="registration_status" id="registration_status" required>
                                        <option selected></option>
                                        <option value="Pendente" {{ old('registration_status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="Confirmada" {{ old('registration_status') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="Cancelada" {{ old('registration_status') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </div>

                                <div class="col-md-12 py-2">
                                    <label for="details">Observações da Matrícula</label>
                                    <textarea class="form-control" name="details" id="details" cols="30" rows="3" required 
                                        placeholder="Adicione informações importantes como necessidades especiais, disciplinas pendentes, documentos adicionais, etc.">{{ old('details') }}</textarea>
                                    <span class="text-muted"><i class="fas fa-info-circle"></i> Inclua qualquer informação relevante para a matrícula</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save mr-1"></i> Salvar Inscrição e Matrícula
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection('content')
