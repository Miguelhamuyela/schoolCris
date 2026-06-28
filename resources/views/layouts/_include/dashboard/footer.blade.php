<!-- Footer -->
</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Instituto Politécnico Privado Pitágoras de Samos &copy; {{ date('Y') }}</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Carregue primeiro os scripts do Bootstrap e do jQuery -->
<script src="/dashboard/assets/js/jquery-3.6.0.min.js"></script>
<script src="/dashboard/assets/js/jquery-ui.min.js"></script>
<script src="/dashboard/assets/js/moment.min.js"></script>
<script src='/dashboard/assets/js/pt-br.js'></script>

<script>
    $(function() {
        $('#calendar').fullCalendar({
            lang: 'pt-br'
        });
    });
</script>

<script src="/dashboard/assets/js/popper.min.js"></script>
<script src="/dashboard/assets/js/bootstrap.min.js"></script>
<script src="/dashboard/assets/js/fullcalendar.min.js"></script>

<!-- Depois, os plugins -->
<script src="/dashboard/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/dashboard/assets/vendor/chart.js/Chart.min.js"></script>

<!-- DataTables -->
<script src="/dashboard/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/dataTables.buttons.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/buttons.bootstrap4.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/jszip.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/pdfmake.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/vfs_fonts.js"></script>
<script src="/dashboard/assets/vendor/datatables/buttons.html5.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/buttons.print.min.js"></script>
<script src="/dashboard/assets/vendor/datatables/buttons.colVis.min.js"></script>

<script src="/dashboard/assets/js/sweetalert2.all.min.js"></script>
<!-- E, finalmente, os scripts personalizados -->
<script src="/dashboard/assets/js/demo/chart-area-demo.js"></script>
<script src="/dashboard/assets/js/demo/chart-pie-demo.js"></script>
<script src="/dashboard/assets/js/demo/datatables-demo.js"></script>
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
<script src="/dashboard/assets/js/jquery.validate.min.js"></script>
<!-- Dark Mode Script -->
<script src="/dashboard/assets/js/dark-mode.js"></script>
<!-- Custom Forms Script -->
<script src="/dashboard/assets/js/custom-forms.js"></script>

@stack('js')

</main>

@if (session('create'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Operação realizada com sucesso!',
            html: '<div class="swal-text-container"><p>Os dados foram cadastrados no sistema</p></div>',
            showConfirmButton: true,
            confirmButtonText: "{{ session('student_card_url') ? 'Abrir cartao' : 'Continuar' }}",
            confirmButtonColor: '#4e73df',
            showCancelButton: {{ session('student_card_url') ? 'true' : 'false' }},
            cancelButtonText: 'Continuar',
            cancelButtonColor: '#858796',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button',
                cancelButton: 'custom-swal-cancel-button'
            },
            backdrop: `
                rgba(38, 52, 86, 0.7)
            `,
            timer: 3000,
            timerProgressBar: true
        }).then((result) => {
            @if(session('student_card_url'))
                if (result.isConfirmed) {
                    window.open(@json(session('student_card_url')), '_blank');
                }
            @endif
        })
    </script>
@elseif(session('destroy'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Registro removido',
            html: '<div class="swal-text-container"><p>O registro foi excluído com sucesso</p></div>',
            showConfirmButton: true,
            confirmButtonText: 'Continuar',
            confirmButtonColor: '#4e73df',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button'
            },
            backdrop: `
                rgba(38, 52, 86, 0.7)
            `,
            timer: 3000,
            timerProgressBar: true
        })
    </script>
@elseif(session('error_password'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Senha incorreta',
            html: '<div class="swal-text-container"><p>A senha atual informada está incorreta</p></div>',
            showConfirmButton: true,
            confirmButtonText: 'Tentar novamente',
            confirmButtonColor: '#e74a3b',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button'
            }
        })
    </script>
@elseif(session('error_Repassword'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Senhas não coincidem',
            html: '<div class="swal-text-container"><p>As senhas informadas não são idênticas</p></div>',
            showConfirmButton: true,
            confirmButtonText: 'Tentar novamente',
            confirmButtonColor: '#e74a3b',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button'
            }
        })
    </script>
@elseif(session('edit'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Alterações salvas',
            html: '<div class="swal-text-container"><p>Todas as alterações foram salvas com sucesso</p></div>',
            showConfirmButton: true,
            confirmButtonText: 'Continuar',
            confirmButtonColor: '#4e73df',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button'
            },
            backdrop: `
                rgba(38, 52, 86, 0.7)
            `,
            timer: 3000,
            timerProgressBar: true
        })
    </script>
@elseif(session('create_image'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Imagens foram salvas com sucesso!',
            showConfirmButton: true
        })
    </script>
@elseif(session('erro_students'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este estudante já esta cadastrado nesta turma e curso!',
            showConfirmButton: true
        })
    </script>
@elseif(session('students_exist'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este Aluno já existe!',
            showConfirmButton: true
        })
    </script>
@elseif(session('courses_exist'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Existe um curso com este nome!',
            showConfirmButton: true
        })
    </script>
@elseif(session('documentation_not_exist'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Não existe nenhuma documentação deste transporte',
            showConfirmButton: true
        })
    </script>
@elseif(session('NoAuth'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Não tem autorização para visualizar esta página!',
            showConfirmButton: false,
            timer: 2500
        })
    </script>
@elseif(session('students_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este aluno está matriculado e não pode ser excluído!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('teachers_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este Professor está Admitido e não pode ser excluído!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('classes_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Esta Turma Tem associação e não pode ser excluída!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('propinas_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Esta Propina Tem associação e não pode ser excluída!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('transports_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este Pagamento de Transporte Tem associação e não pode ser excluída!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('bedroom_students_destroy_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Este Aluno ao Dormitório Tem associação e não pode ser excluída!',
            showConfirmButton: false,
            timer: 4500
        })
    </script>
@elseif(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true,
            timer: 6000
        })
    </script>
@endif

<script>
    $('.delete-button').click(function(event) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        event.preventDefault();
        var dadoId = $(this).data('based-id');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não poderá ser revertida!",
            icon: 'warning',
            iconColor: '#f6c23e',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
            cancelButtonColor: '#e74a3b',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            customClass: {
                container: 'custom-swal-container',
                popup: 'animated fadeInUp faster',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-html',
                confirmButton: 'custom-swal-confirm-button',
                cancelButton: 'custom-swal-cancel-button'
            },
            backdrop: `
                rgba(38, 52, 86, 0.7)
            `
        }).then((result) => {
            if (result.isConfirmed) {
                // Faz a requisição AJAX para o controlador com o ID do aluno
                $.ajax({
                    url: dadoId,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            title: 'Excluído!',
                            html: '<div class="swal-text-container"><p>O registro foi excluído com sucesso</p></div>',
                            icon: 'success',
                            confirmButtonColor: '#4e73df',
                            confirmButtonText: 'Continuar',
                            customClass: {
                                container: 'custom-swal-container',
                                popup: 'animated fadeInUp faster',
                                title: 'custom-swal-title',
                                htmlContainer: 'custom-swal-html',
                                confirmButton: 'custom-swal-confirm-button'
                            },
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            // Recarrega a página após a exclusão
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erro!',
                            html: '<div class="swal-text-container"><p>Ocorreu um erro ao excluir o registro</p></div>',
                            icon: 'error',
                            confirmButtonColor: '#e74a3b',
                            confirmButtonText: 'Entendi',
                            customClass: {
                                container: 'custom-swal-container',
                                popup: 'animated fadeInUp faster',
                                title: 'custom-swal-title',
                                htmlContainer: 'custom-swal-html',
                                confirmButton: 'custom-swal-confirm-button'
                            }
                        });
                    }
                });
            }
        });
    });
</script>

<!-- SCRIPTS -->
<!-- Global Required Scripts Start -->
<script></script>

<!-- Scripts personalizados para páginas específicas -->
@yield('scripts')

</body>

</html>
<!-- EndFooter -->
