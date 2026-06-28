@extends('layouts.merge.dashboard')
@section('title', 'Histórico de Pagamentos de Propina')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Histórico de Pagamentos de Propina</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.tuition.list') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="schoolyear">Ano Lectivo</label>
                            <select name="schoolyear" id="schoolyear" class="form-control">
                                <option value="">Todos os Anos Lectivos</option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->id }}" {{ ($filterSchoolyear ?? \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) == $schoolyear->id ? 'selected' : '' }}>
                                        {{ $schoolyear->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="course">Curso</label>
                            <select name="course" id="course" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ $filterCourse == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="class">Turma</label>
                            <select name="class" id="class" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ $filterClass == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="grade">Classe</label>
                            <select name="grade" id="grade" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ $filterGrade == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="month">Mês</label>
                            <select name="month" id="month" class="form-control">
                                <option value="">Todos</option>
                                @php $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']; @endphp
                                @foreach ($meses as $mes)
                                    <option value="{{ $mes }}" {{ $filterMonth == $mes ? 'selected' : '' }}>
                                        {{ $mes }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state">Estado</label>
                            <select name="state" id="state" class="form-control">
                                <option value="">Todos</option>
                                <option value="Pago" {{ $filterState == 'Pago' ? 'selected' : '' }}>Pago</option>
                                <option value="Pendente" {{ $filterState == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="Cancelado" {{ $filterState == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter fa-sm"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.tuition.list') }}" class="btn btn-secondary">
                                <i class="fas fa-undo fa-sm"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h6 class="m-0 font-weight-bold text-primary">Registros de Pagamentos</h6>
                    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        data-toggle="modal" data-target="#myModal">
                        <i class="fas fa-file-pdf fa-sm text-white-50"></i> Factura Geral
                    </button>
                </div>
                <div class="container">
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <form action="{{ route('pdf.tuition') }}" method="get" target="_blank">
                                @csrf
                                <div class="modal-content">
                                    @include('form.formPDF._formTuition.index')
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aluno</th>
                                <th>Nº de Processo</th>
                                <th>Curso</th>
                                <th>Turma</th>
                                <th>Classe</th>
                                <th>Ano lectivo</th>
                                <th>Mês</th>
                                <th>Valor</th>
                                <th>Multa</th>
                                <th>Estado</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($tuitions as $tuition)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $tuition->students ? $tuition->students->name : 'Não informado' }}</td>
                                    <td>{{ $tuition->students ? $tuition->students->nProcess : 'Não informado' }}</td>
                                    <td>{{ $tuition->course_name }}</td>
                                    <td>{{ $tuition->class_name }}</td>
                                    <td>{{ $tuition->grade_name }}</td>
                                    <td>{{ $tuition->schoolyears ? $tuition->schoolyears->name : 'Não informado' }}</td>
                                    <td>{{ $tuition->month }}</td>
                                    <td>{{ number_format($tuition->price, 2, ',', '.') }} AOA</td>
                                    <td>{{ $tuition->fine > 0 ? number_format($tuition->fine, 2, ',', '.') . ' AOA' : '—' }}</td>
                                    @if ($tuition->state == 'Pago')
                                        <td>
                                            <h5><span class="badge badge-success"> {{ $tuition->state }}</span></h5>
                                        </td>
                                    @elseif($tuition->state == 'Pendente')
                                        <td>
                                            <h5><span class="badge badge-warning"> {{ $tuition->state }}</span></h5>
                                        </td>
                                    @elseif($tuition->state == 'Cancelado')
                                        <td>
                                            <h5><span class="badge badge-danger"> {{ $tuition->state }}</span></h5>
                                        </td>
                                    @else
                                        <td>
                                            <h5><span class="badge badge-secondary"> {{ $tuition->state }}</span></h5>
                                        </td>
                                    @endif

                                    <td>
                                        <div class="dropdown mb-4">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu animated--fade-in"
                                                aria-labelledby="dropdownMenuButton">
                                                @if ($tuition->state == 'Pago')
                                                    <a class="dropdown-item"
                                                        href="{{ route('pdf.tuitionStudent', $tuition->id) }}"
                                                        target="_blank">
                                                        <i class="fas fa-file-pdf fa-sm text-primary-400 mr-2"></i>Factura
                                                    </a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.tuition.show', $tuition->id) }}">
                                                    <i class="fas fa-eye fa-sm text-primary-400 mr-2"></i>Detalhes
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.tuition.edit', $tuition->id) }}">
                                                    <i class="fas fa-edit fa-sm text-primary-400 mr-2"></i>Editar
                                                </a>
                                                <a class="dropdown-item delete-button" href="#"
                                                    data-based-id="{{ route('admin.tuition.destroy', $tuition->id) }}">
                                                    <i class="fas fa-trash fa-sm text-primary-400 mr-2"></i>Remover
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">Nenhum pagamento encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });

            $('.delete-button').click(function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('based-id');
                
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação não poderá ser revertida!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Excluído!',
                                    'O registro foi excluído com sucesso.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Erro!',
                                    'Ocorreu um erro ao tentar excluir o registro.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
