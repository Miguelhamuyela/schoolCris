{{--  Original  --}}



<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-mcw98/sfnge8fjt3gxweongsv7zt27nxfoaoapmym81iuxopkfojwj8erdknlpmo" crossorigin="anonymous">
    
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Recibo de Transporte - {{ date('d-m-Y') }}</title>

    <style>
        header {
            text-align: center;
            padding: 1.5rem;
        }

        #footer {
            padding-top: 10px;
            padding-bottom: 0px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body style='height:auto; width:100%; background: url("dashboard/images/digital.canvas.png") no-repeat center;'>
    <header class="header">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
        </div>
        <div class="text-center">REPÚBLICA DE ANGOLA</div>
        <div class="text-center">MINISTÉRIO DA EDUCAÇÃO</div>
        <div class="text-center">SISTEMA DE GESTÃO ESCOLAR</div>
        <div class="text-center">RECIBO DE PAGAMENTO DE TRANSPORTE</div>
        <div class="text-center">ANO LECTIVO "{{ isset($transportPayStudent->schoolyears) ? $transportPayStudent->schoolyears->name : 'N/A' }}"</div>
    </header>

    <section class="">

        <div class="container-fluid">
            <div class="row">

                <div class="col-12 mt-2">
                    <h5 class=""><b>Informações do Aluno </b> </h5>
                    <hr>
                </div>

                <div style="" class="col-12 mb-5">

                    <div style="display: inline-block;" class="mr-4">
                        <b>Nome do Aluno</b>
                        <p>{{ isset($transportPayStudent->students) ? $transportPayStudent->students->name : 'N/A' }}</p>

                        <b>Nº de Processo</b>
                        <p>{{ isset($transportPayStudent->students) ? $transportPayStudent->students->nProcess : 'N/A' }}</p>

                    </div>

                </div>

                <div class="col-12 mt-2">
                    <h5 class=""><b>Informações de Pagamento de Transporte </b> </h5>
                    <hr>
                </div>
                <div class="col-12">

                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Ano Lectivo</th>
                                <th>Mês</th>
                                <th>Valor pago</th>
                                <th>Estado do Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ isset($transportPayStudent->schoolyears) ? $transportPayStudent->schoolyears->name : 'N/A' }}</td>
                                <td>{{ $transportPayStudent->month }}</td>
                                <td>{!! number_format($transportPayStudent->price, 2, ',', '.') . ' ' . 'kz' !!}</td>
                                <td>{{ $transportPayStudent->state }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </section>

    <hr class="pylarge bg-dark">
    <footer class="col-12 mt-2 text-center" id="footer">

        <small class="text-left text-dark">
            Documento processado por computador. <br>
        </small>

    </footer>
</body>

</html>
