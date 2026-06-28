<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Geral de Pagamentos - {{ $student->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', arial, sans-serif; font-size: 10pt; color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 16px; font-size: 9pt; }
        td, th { border: 1px solid #dee2e6; text-align: left; padding: 6px 8px; }
        th { background-color: #007bff; color: #fff; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .header { text-align: center; padding: 1rem 1.5rem; margin-bottom: 16px; border-bottom: 3px double #007bff; }
        .logo { margin-bottom: 8px; }
        .school-name { font-size: 14pt; font-weight: bold; color: #1a1a2e; margin: 4px 0; }
        .school-sub { font-size: 9pt; color: #666; margin: 2px 0; }
        .report-title { font-size: 13pt; font-weight: bold; color: #007bff; margin-top: 12px; margin-bottom: 4px; }
        .student-info { border: 1px solid #dee2e6; padding: 12px 15px; margin-bottom: 16px; background-color: #f8f9fa; border-radius: 4px; }
        .student-info p { margin: 3px 0; font-size: 9pt; }
        .summary-box { border: 1px solid #dee2e6; padding: 12px 15px; margin-top: 16px; background-color: #fff; border-radius: 4px; }
        .summary-box table { margin-bottom: 0; }
        .summary-box td { border: none; padding: 4px 8px; }
        .status-paid { color: #28a745; font-weight: bold; }
        .status-pending { color: #fd7e14; font-weight: bold; }
        .status-canceled { color: #dc3545; font-weight: bold; }
        .section-title { margin-top: 20px; margin-bottom: 8px; border-bottom: 2px solid #007bff; padding-bottom: 4px; color: #007bff; font-size: 11pt; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #dee2e6; font-size: 8pt; color: #999; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 8pt; color: #fff; }
        .badge-paid { background-color: #28a745; }
        .badge-pending { background-color: #fd7e14; }
        .badge-canceled { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('dashboard/assets/img/logo-sge-small.png'))) }}" alt="Logo" style="height:70px;">
        </div>
        <div class="school-name">REPÚBLICA DE ANGOLA</div>
        <div class="school-sub">MINISTÉRIO DA EDUCAÇÃO</div>
        <div class="school-sub">SISTEMA DE GESTÃO ESCOLAR</div>
        <div class="report-title">RELATÓRIO GERAL DE PAGAMENTOS</div>
        @if(isset($schoolyear))
            <p style="margin:4px 0;font-size:9pt;color:#666;"><strong>Ano Lectivo:</strong> {{ $schoolyear->name }}</p>
        @else
            <p style="margin:4px 0;font-size:9pt;color:#666;"><strong>Todos os Anos Lectivos</strong></p>
        @endif
    </div>

    <div class="student-info">
        <table style="border:none;margin:0;">
            <tr>
                <td style="border:none;width:50%;"><strong>Nome do Aluno:</strong><br>{{ $student->name }}</td>
                <td style="border:none;width:25%;"><strong>Nº de Processo:</strong><br>{{ $student->nProcess }}</td>
                <td style="border:none;width:25%;"><strong>Data de Nascimento:</strong><br>{{ $student->dateBirth ? date('d/m/Y', strtotime($student->dateBirth)) : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if($tuitions->count() > 0)
        <div class="section-title">Propinas</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:25%;">Mês</th>
                    <th style="width:20%;">Valor</th>
                    <th style="width:15%;">Multa</th>
                    <th style="width:20%;">Total</th>
                    <th style="width:15%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tuitions as $t)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $t->month }}</td>
                        <td>{{ number_format($t->price, 2, ',', '.') }} KZ</td>
                        <td>{{ number_format($t->fine ?? 0, 2, ',', '.') }} KZ</td>
                        <td>{{ number_format($t->price + ($t->fine ?? 0), 2, ',', '.') }} KZ</td>
                        <td>
                            <span class="badge
                                @if($t->state == 'Pago') badge-paid
                                @elseif($t->state == 'Pendente') badge-pending
                                @elseif($t->state == 'Cancelado') badge-canceled
                                @endif">
                                {{ $t->state }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($transportPays->count() > 0)
        <div class="section-title">Pagamentos de Transporte</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:40%;">Mês</th>
                    <th style="width:30%;">Valor</th>
                    <th style="width:25%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transportPays as $tp)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $tp->month }}</td>
                        <td>{{ number_format($tp->price, 2, ',', '.') }} KZ</td>
                        <td>
                            <span class="badge
                                @if($tp->state == 'Pago') badge-paid
                                @elseif($tp->state == 'Pendente') badge-pending
                                @elseif($tp->state == 'Cancelado') badge-canceled
                                @endif">
                                {{ $tp->state }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($uniformPays->count() > 0)
        <div class="section-title">Pagamentos de Uniformes</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:50%;">Valor</th>
                    <th style="width:45%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($uniformPays as $up)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ number_format($up->price, 2, ',', '.') }} KZ</td>
                        <td>
                            <span class="badge
                                @if($up->state == 'Pago') badge-paid
                                @elseif($up->state == 'Pendente') badge-pending
                                @elseif($up->state == 'Cancelado') badge-canceled
                                @endif">
                                {{ $up->state }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($tuitions->count() == 0 && $transportPays->count() == 0 && $uniformPays->count() == 0)
        <div style="text-align:center;padding:30px;color:#999;">
            <p>Nenhum pagamento encontrado para este aluno.</p>
        </div>
    @endif

    <div class="summary-box">
        <h5 style="margin-top:0;color:#007bff;border-bottom:1px solid #dee2e6;padding-bottom:8px;">Resumo Financeiro</h5>
        <table>
            <tr>
                <td style="width:50%;"><strong>Total Pago:</strong></td>
                <td style="width:50%;" class="status-paid">{{ number_format($totalPaid, 2, ',', '.') }} KZ</td>
            </tr>
            <tr>
                <td><strong>Total Pendente:</strong></td>
                <td class="status-pending">{{ number_format($totalPending, 2, ',', '.') }} KZ</td>
            </tr>
            <tr>
                <td><strong>Total Geral:</strong></td>
                <td>{{ number_format($totalPaid + $totalPending, 2, ',', '.') }} KZ</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Documento processado por computador. | Data de emissão: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
