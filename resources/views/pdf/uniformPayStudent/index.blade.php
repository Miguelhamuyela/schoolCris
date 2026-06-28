<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Pagamento de Uniforme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0;
        }
        .info-block {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-value {
            width: 60%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ public_path('dashboard/assets/img/logo-sge-small.png') }}" alt="Logo da Escola" style="height: 80px;">
            </div>
            <div class="text-center">REPÚBLICA DE ANGOLA</div>
            <div class="text-center">MINISTÉRIO DA EDUCAÇÃO</div>
            <div class="text-center">SISTEMA DE GESTÃO ESCOLAR</div>
            <div class="title">Instituto Politécnico Privado Pitágoras de Samos</div>
            <div class="subtitle">Factura de Pagamento de Uniforme</div>
        </div>

        <div class="info-block">
            <div class="subtitle">Informações da Factura</div>
            <div class="info-row">
                <div class="info-label">Nº da Factura:</div>
                <div class="info-value">{{ $uniformPay->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data de Emissão:</div>
                <div class="info-value">{{ date('d/m/Y', strtotime($uniformPay->created_at)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">{{ $uniformPay->state }}</div>
            </div>
        </div>

        <div class="info-block">
            <div class="subtitle">Dados do Aluno</div>
            <div class="info-row">
                <div class="info-label">Nome:</div>
                <div class="info-value">{{ $uniformPay->students->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nº de Processo:</div>
                <div class="info-value">{{ $uniformPay->students->nProcess }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ano Lectivo:</div>
                <div class="info-value">{{ $uniformPay->schoolyears->name }}</div>
            </div>
        </div>

        <div class="info-block">
            <div class="subtitle">Detalhes do Pagamento</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pagamento de Uniforme</td>
                        <td>{{ number_format($uniformPay->price, 2, ',', '.') }} kz</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total:</th>
                        <td>{{ number_format($uniformPay->price, 2, ',', '.') }} kz</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(isset($qrCode))
        <div class="qr-code">
            {!! $qrCode !!}
            <div>Verifique a autenticidade da factura</div>
        </div>
        @endif

        <div class="footer">
            <p>Instituto Politécnico Privado Pitágoras de Samos &copy; {{ date('Y') }}</p>
            <p>Este documento é uma factura válida como comprovante de pagamento.</p>
        </div>
    </div>
</body>
</html>
