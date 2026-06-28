<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.home') }}">
        <div class="sidebar-brand-icon">
            <img src="/dashboard/assets/img/logo-sge-small.png" alt="SGE Logo" width="60" class="img-profile rounded-circle shadow-sm">
        </div>
        <div class="sidebar-brand-text mx-2">
                @if (auth()->user())
                    {{ auth()->user()->roles->first()->name ?? 'Utilizador' }}
                @endif
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link py-3" href="{{ route('admin.home') }}">
            <i class="fas fa-home fa-fw mr-1"></i>
            <span>Início</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Mensagens -->
    @php
        $unreadMessages = auth()->check() ? \App\Models\Message::where('receiver_id', auth()->id())->where('read', false)->count() : 0;
    @endphp
    <li class="nav-item">
        <a class="nav-link py-2" href="{{ route('admin.messages.index') }}">
            <i class="fas fa-envelope fa-fw mr-1"></i>
            <span>Mensagens</span>
            @if($unreadMessages > 0)
                <span class="badge badge-danger ml-1">{{ $unreadMessages }}</span>
            @endif
        </a>
    </li>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('Secretaria'))
    <!-- Sistema -->
    <div class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-2">
        <span>Sistema</span>
        <i class="fas fa-cogs text-gray-400"></i>
    </div>

    <!-- Usuários -->
    @if (auth()->user() && auth()->user()->hasRole('admin'))
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseUser"
            aria-expanded="true" aria-controls="collapseUser">
            <i class="fas fa-users-cog fa-fw mr-1"></i>
            <span>Usuários</span>
        </a>
        <div id="collapseUser" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.user.create') }}">
                    <span class="mr-2"><i class="fas fa-user-plus fa-sm text-primary-400"></i></span> Adicionar Usuário
                </a>
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.user.list') }}">
                    <span class="mr-2"><i class="fas fa-users fa-sm text-primary-400"></i></span> Todos os Usuários
                </a>
            </div>
        </div>
    </li>
    @endif
    
    <!-- Alunos -->
    <div class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-2">
        <span>Alunos</span>
        <i class="fas fa-graduation-cap text-gray-400"></i>
    </div>

    <!-- Matrículas -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-user-graduate fa-fw mr-1"></i>
            <span>Matrículas</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.student.create') }}">
                    <span class="mr-2"><i class="fas fa-user-plus fa-sm text-primary-400"></i></span> Nova Inscrição/Matrícula
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.courseClasseGradeStudentSchoolyear.list') ? 'active' : '' }}"
                    href="{{ route('admin.courseClasseGradeStudentSchoolyear.list') }}">Alunos Matriculados</a>
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.renewal.index') }}">
                    <span class="mr-2"><i class="fas fa-sync-alt fa-sm text-primary-400"></i></span> Confirmação de Alunos
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.renewal.confirmed') ? 'active' : '' }}"
                    href="{{ route('admin.renewal.confirmed') }}">Alunos Confirmados</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Instituição -->
    <div class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-2">
        <span>Instituição</span>
        <i class="fas fa-school text-gray-400"></i>
    </div>

    @php $isAdmin = auth()->user()->hasRole('admin'); @endphp

    <!-- Ano Letivo -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseYear"
            aria-expanded="true" aria-controls="collapseYear">
            <i class="fas fa-calendar-alt fa-fw mr-1"></i>
            <span>Ano Letivo</span>
        </a>
        <div id="collapseYear" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.schoolyear.list') }}">
                    <span class="mr-2"><i class="fas fa-calendar-alt fa-sm text-primary-400"></i></span> Anos Lectivos
                </a>
            </div>
        </div>
    </li>

    <!-- Cursos -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="true" aria-controls="collapseFour">
            <i class="fas fa-graduation-cap fa-fw mr-1"></i>
            <span>Cursos</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                @if($isAdmin)
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.course.create') }}">
                    <span class="mr-2"><i class="fas fa-book-open fa-sm text-primary-400"></i></span> Adicionar Curso
                </a>
                @endif
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.course.list') }}">
                    <span class="mr-2"><i class="fas fa-graduation-cap fa-sm text-primary-400"></i></span> Cursos Disponíveis
                </a>
            </div>
        </div>
    </li>

    <!-- Turmas -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapsetree"
            aria-expanded="true" aria-controls="collapsetree">
            <i class="fas fa-users fa-fw mr-1"></i>
            <span>Turmas</span>
        </a>
        <div id="collapsetree" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                @if($isAdmin)
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.classe.create') }}">
                    <span class="mr-2"><i class="fas fa-user-friends fa-sm text-primary-400"></i></span> Criar Turma
                </a>
                @endif
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.classe.list') }}">
                    <span class="mr-2"><i class="fas fa-users fa-sm text-primary-400"></i></span> Turmas Disponíveis
                </a>
            </div>
        </div>
    </li>

    <!-- Classes (Níveis) -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseFive"
            aria-expanded="true" aria-controls="collapseFive">
            <i class="fas fa-layer-group fa-fw mr-1"></i>
            <span>Classes</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                @if($isAdmin)
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.grade.create') }}">
                    <span class="mr-2"><i class="fas fa-plus-square fa-sm text-primary-400"></i></span> Adicionar Classe
                </a>
                @endif
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.grade.list') }}">
                    <span class="mr-2"><i class="fas fa-layer-group fa-sm text-primary-400"></i></span> Classes Disponíveis
                </a>
            </div>
        </div>
    </li>

    <!-- Transporte -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseTransport"
            aria-expanded="true" aria-controls="collapseTransport">
            <i class="fas fa-bus fa-fw mr-1"></i>
            <span>Transporte</span>
        </a>
        <div id="collapseTransport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                @if($isAdmin)
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.transport.create') }}">
                    <span class="mr-2"><i class="fas fa-bus-alt fa-sm text-primary-400"></i></span> Adicionar Transporte
                </a>
                @endif
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.transport.list') }}">
                    <span class="mr-2"><i class="fas fa-shuttle-van fa-sm text-primary-400"></i></span> Frota de Transportes
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('Secretaria'))
    <!-- Pagamentos -->
    <div class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-2">
        <span>Pagamentos</span>
        <i class="fas fa-money-bill-wave text-gray-400"></i>
    </div>

    <!-- Propinas -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseTuition"
            aria-expanded="true" aria-controls="collapseTuition">
            <i class="fas fa-file-invoice-dollar fa-fw mr-1"></i>
            <span>Propinas</span>
        </a>
        <div id="collapseTuition" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.tuition.create') }}">
                    <span class="mr-2"><i class="fas fa-hand-holding-usd fa-sm text-primary-400"></i></span> Realizar Pagamento
                </a>
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.tuition.list') }}">
                    <span class="mr-2"><i class="fas fa-money-check-alt fa-sm text-primary-400"></i></span> Histórico de Pagamentos
                </a>
            </div>
        </div>
    </li>

    <!-- Transporte -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseTransportPay"
            aria-expanded="true" aria-controls="collapseTransportPay">
            <i class="fas fa-bus fa-fw mr-1"></i>
            <span>Pagamento de Transporte</span>
        </a>
        <div id="collapseTransportPay" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.transportPay.create') }}">
                    <span class="mr-2"><i class="fas fa-ticket-alt fa-sm text-primary-400"></i></span> Realizar Pagamento
                </a>
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.transportPay.list') }}">
                    <span class="mr-2"><i class="fas fa-list-ul fa-sm text-primary-400"></i></span> Histórico de Pagamentos
                </a>
            </div>
        </div>
    </li>

    <!-- Uniformes -->
    <li class="nav-item">
        <a class="nav-link collapsed py-2" href="#" data-toggle="collapse" data-target="#collapseUniformPay"
            aria-expanded="true" aria-controls="collapseUniformPay">
            <i class="fas fa-tshirt fa-fw mr-1"></i>
            <span>Pagamento de Uniforme</span>
        </a>
        <div id="collapseUniformPay" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm mx-4">
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.uniformPay.create') }}">
                    <span class="mr-2"><i class="fas fa-plus fa-sm text-primary-400"></i></span> Realizar Pagamento
                </a>
                <a class="collapse-item d-flex align-items-center" href="{{ route('admin.uniformPay.list') }}">
                    <span class="mr-2"><i class="fas fa-list-ul fa-sm text-primary-400"></i></span> Histórico de Pagamentos
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link py-2" href="/pdf/studentPayments">
            <i class="fas fa-file-invoice fa-fw mr-1"></i>
            <span>Relatório Geral de Pagamentos</span>
        </a>
    </li>
    @endif

    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
</ul>
<!-- End of Sidebar -->
