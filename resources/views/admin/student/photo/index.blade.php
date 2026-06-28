@extends('layouts.merge.dashboard')
@section('title', 'Upload de Foto de Aluno')
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Upload de Foto do Aluno "{{ $student->name }}"</h1>
                </div>
            </div>

            @include('extra._Error.index')

            <div class="card-body">

                <form action="{{ route('admin.student.photo.upload', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Selecione uma foto</label>
                                <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
                                <small class="form-text text-muted">
                                    Tamanho máximo: 2MB. Formatos aceitos: JPEG, PNG, GIF. Este campo é opcional.
                                </small>
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Enviar Foto</button>
                                <a href="{{ route('admin.student.show', $student->id) }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="text-center">
                                <h5>Foto Atual:</h5>
                                @if($student->image)
                                    <div class="d-flex justify-content-center align-items-center border rounded p-2 bg-light" style="min-height: 220px;">
                                        <img src="{{ route('admin.student.image', $student->image) }}" alt="Foto atual" 
                                             class="img-thumbnail" style="width: 160px; height: 200px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="d-flex justify-content-center align-items-center border rounded p-2 bg-light" style="min-height: 220px;">
                                        <img src="{{ asset('dashboard/assets/img/undraw_profile.svg') }}" alt="Sem foto" 
                                             class="img-thumbnail opacity-50" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5>Dicas para uma boa foto:</h5>
                            <ul>
                                <li>Use uma foto com fundo claro e uniforme</li>
                                <li>O rosto do aluno deve estar bem iluminado</li>
                                <li>Evite fotos com objetos ou pessoas ao fundo</li>
                                <li>Prefira fotos recentes e nítidas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
