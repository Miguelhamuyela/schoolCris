<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center header-transparent">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- Usando logo como imagem com texto ao lado -->
        <div class="d-flex align-items-center">
            <a href="/" class="logo me-2"><img src="/site/assets/img/logo-sge.png" alt="SGE Logo" class="img-fluid"></a>
            <h1 class="mb-0" style="font-size: 24px; font-weight: bold; color: #E65C00;">Instituto Politécnico Privado Pitágoras de Samos</h1>
        </div>

        <!-- Botão de Entrar Simples -->
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/admin/home/') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Entrar no Sistema</a>
            @endauth
        @endif

    </div>
</header><!-- End Header -->
