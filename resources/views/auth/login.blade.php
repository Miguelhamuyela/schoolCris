@extends('layouts.merge.auth')
@section('title', 'Entrar')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-10 text-center mb-3">
            <div class="login-logo">
                <img src="/site/assets/img/logo-sge.png" class="img-fluid" alt="Instituto Politécnico Privado Pitágoras de Samos Logo">
                <h4 class="mt-3" style="color:#0d47a1; font-weight:900; letter-spacing:-0.5px;">Instituto Politécnico Privado Pitágoras de Samos</h4>
            </div>
        </div>
    </div>
    
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-body p-3">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-3 alert alert-info" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-3 alert alert-danger" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email input -->
                        <div class="form-outline mb-3">
                            <label class="form-label" for="form1Example13">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="form1Example13" class="form-control"
                                    value="{{ old('email') }}" required autofocus placeholder="Digite seu email" />
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <label class="form-label" for="form1example23">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-lock"></i></span>
                                <input name="password" type="password" id="form1example23" class="form-control" 
                                    required autocomplete="current-password" placeholder="Digite sua senha">
                                <span class="input-group-text bg-light" onclick="visualizarSenha()">
                                    <img src="/eye.png" id="eye" alt="eye" style="width: 1rem; height: 1rem; cursor: pointer;">
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small" for="remember">
                                        Lembrar-me
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a class="text-primary small" href="{{ route('password.request') }}">
                                    Esqueceu a senha?
                                </a>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <p class="small text-muted mb-0">
                    <i class="bi bi-shield-lock me-1"></i>Acesso restrito
                </p>
            </div>
        </div>
    </div>
@endsection
