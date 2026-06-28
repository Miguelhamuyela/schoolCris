<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório de Transportes - Aluno: {{ $student->name }}</title>
    <style>
        body {
            font-family: arial, sans-serif;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            padding: 1.5rem;
            margin-bottom: 20px;
        }

        .student-info {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-canceled {
            color: red;
            font-weight: bold;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            color: #007bff;
        }
    </style>
</head>

<body>
    <header class="header">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
        </div>
        <div class="text-center">REPÚBLICA DE ANGOLA</div>
        <div class="text-center">MINISTÉRIO DA EDUCAÇÃO</div>
        <div class="text-center">SISTEMA DE GESTÃO ESCOLAR</div>
        <div class="text-center"><h4>RELATÓRIO INDIVIDUAL DE TRANSPORTE</h4></div>
        <div class="text-center">ANO LECTIVO: <b>{{ $schoolyear }}</b></div>
    </header>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
    </div>

    <main class="container">
        <div class="student-info">
            <div class="row">
                <div class="col-md-6">
                    <h5>DADOS DO ALUNO</h5>
                    <p><strong>Nome:</strong> {{ $student->name }}</p>
                    <p><strong>Nº de Processo:</strong> {{ $student->nProcess }}</p>
                    @if($student->dateBirth)
                    <p><strong>Data de Nascimento:</strong> {{ date('d/m/Y', strtotime($student->dateBirth)) }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <p><strong>Endereço:</strong> {{ $student->address ?? 'Não informado' }}</p>
                    <p><strong>Telefone:</strong> {{ $student->phone ?? 'Não informado' }}</p>
                    <p><strong>Email:</strong> {{ $student->email ?? 'Não informado' }}</p>
                </div>
            </div>
        </div>

        <h4 class="section-title">PAGAMENTO DO MÊS DE {{ $month }}</h4>
        
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Mês</th>
                    <th>Valor</th>
                    <th>Data de Pagamento</th>
                    <th>Estado</th>
                    <th>Rota</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @if(count($payment_history->where('month', $month)) > 0)
                    @foreach($payment_history->where('month', $month) as $payment)
                        <tr>
                            <td>{{ $payment->month }}</td>
                            <td>{!! number_format($payment->price, 2, ',', '.') . ' ' . 'kz' !!}</td>
                            <td>{{ $payment->created_at ? date('d/m/Y', strtotime($payment->created_at)) : 'N/A' }}</td>
                            <td>
                                <span class="status-{{ strtolower($payment->state) == 'pago' ? 'paid' : (strtolower($payment->state) == 'pendente' ? 'pending' : 'canceled') }}">
                                    {{ $payment->state }}
                                </span>
                            </td>
                            <td>{{ $payment->route ?? 'N/A' }}</td>
                            <td>{{ $payment->obs ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">Nenhum registro de pagamento encontrado para este mês.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <h4 class="section-title">HISTÓRICO ANUAL DE PAGAMENTOS</h4>
        
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Mês</th>
                    <th>Valor</th>
                    <th>Data de Pagamento</th>
                    <th>Estado</th>
                    <th>Rota</th>
                </tr>
            </thead>
            <tbody>
                @if(count($payment_history) > 0)
                    @foreach($payment_history as $payment)
                        <tr>
                            <td>{{ $payment->month }}</td>
                            <td>{!! number_format($payment->price, 2, ',', '.') . ' ' . 'kz' !!}</td>
                            <td>{{ $payment->created_at ? date('d/m/Y', strtotime($payment->created_at)) : 'N/A' }}</td>
                            <td>
                                <span class="status-{{ strtolower($payment->state) == 'pago' ? 'paid' : (strtolower($payment->state) == 'pendente' ? 'pending' : 'canceled') }}">
                                    {{ $payment->state }}
                                </span>
                            </td>
                            <td>{{ $payment->route ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Nenhum registro de pagamento encontrado.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="summary-box">
            <h5>RESUMO FINANCEIRO</h5>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Total Pago:</strong> {!! number_format($payment_history->where('state', 'Pago')->sum('price'), 2, ',', '.') . ' ' . 'kz' !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Total Pendente:</strong> {!! number_format($payment_history->where('state', 'Pendente')->sum('price'), 2, ',', '.') . ' ' . 'kz' !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Total Cancelado:</strong> {!! number_format($payment_history->where('state', 'Cancelado')->sum('price'), 2, ',', '.') . ' ' . 'kz' !!}</p>
                </div>
            </div>
            <p><strong>Meses Pagos:</strong> {{ implode(', ', $payment_history->where('state', 'Pago')->pluck('month')->toArray()) ?: 'Nenhum' }}</p>
            <p><strong>Meses Pendentes:</strong> {{ implode(', ', $payment_history->where('state', 'Pendente')->pluck('month')->toArray()) ?: 'Nenhum' }}</p>
        </div>
    </main>

    <hr class="my-4">
    <footer class="text-center mb-4">
        <p class="small">
            Documento gerado em {{ date('d/m/Y à\s H:i:s') }}<br>
            Este documento é válido como comprovativo de situação financeira do aluno.
        </p>
    </footer>
</body>
</html> 