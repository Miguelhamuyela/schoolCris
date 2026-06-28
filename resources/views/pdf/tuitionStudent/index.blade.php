<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Pagamento</title>
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
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header-logo {
            height: 80px;
            margin-bottom: 10px;
        }
        .header-linha1 {
            font-size: 11px;
            font-weight: bold;
        }
        .header-linha2 {
            font-size: 10px;
        }
        .header-titulo {
            font-size: 16px;
            font-weight: bold;
            margin-top: 8px;
        }
        .header-subtitulo {
            font-size: 13px;
            margin-top: 4px;
            color: #555;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-titulo {
            font-size: 13px;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 6px 8px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ccc;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
        }
        table.info td {
            padding: 4px 8px;
            border-bottom: 1px dashed #eee;
        }
        table.info .label {
            font-weight: bold;
            width: 35%;
        }
        table.info .value {
            width: 65%;
        }
        table.detalhes {
            width: 100%;
            border-collapse: collapse;
        }
        table.detalhes th, table.detalhes td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        table.detalhes th {
            background-color: #f2f2f2;
        }
        table.detalhes .total-label {
            text-align: right;
            font-weight: bold;
        }
        table.detalhes .total-value {
            font-weight: bold;
        }
        .observacoes {
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 11px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 100px;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $total = $tuition->price + ($tuition->fine ?? 0);
        $registration = App\Models\CourseClasseGradeStudentSchoolyear::with(['courses', 'classes', 'grades'])
            ->where('fk_students_id', $tuition->fk_students_id)
            ->where('fk_schoolyears_id', $tuition->fk_schoolyears_id)
            ->first();
    @endphp

    @php
        $logoPath = public_path('dashboard/assets/img/logo-sge-small.png');
        $logoSrc = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp
    <div class="container">
        <div class="header">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo" class="header-logo">
            @endif
            <div class="header-linha1">REPÚBLICA DE ANGOLA</div>
            <div class="header-linha2">MINISTÉRIO DA EDUCAÇÃO</div>
            <div class="header-titulo">INSTITUTO POLITECNICO PRIVADO PITAGORAS DE SAMOS</div>
            <div class="header-subtitulo">FACTURA DE PAGAMENTO DE PROPINA</div>
        </div>

        <div class="section">
            <div class="section-titulo">Informações da Factura</div>
            <table class="info">
                <tr><td class="label">Nº da Factura:</td><td class="value">{{ str_pad($tuition->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td class="label">Data de Emissão:</td><td class="value">{{ date('d/m/Y', strtotime($tuition->created_at)) }}</td></tr>
                <tr><td class="label">Estado:</td><td class="value">{{ $tuition->state }}</td></tr>
            </table>
        </div>

        <div class="section">
            <div class="section-titulo">Dados do Aluno</div>
            <table class="info">
                <tr><td class="label">Nome:</td><td class="value">{{ $tuition->students->name ?? 'N/A' }}</td></tr>
                <tr><td class="label">Nº de Processo:</td><td class="value">{{ $tuition->students->nProcess ?? 'N/A' }}</td></tr>
                <tr><td class="label">Nº do BI:</td><td class="value">{{ $tuition->students->nBi ?? 'N/A' }}</td></tr>
                <tr><td class="label">Curso:</td><td class="value">{{ $registration && $registration->courses ? $registration->courses->name : 'N/A' }}</td></tr>
                <tr><td class="label">Turma:</td><td class="value">{{ $registration && $registration->classes ? $registration->classes->name : 'N/A' }}</td></tr>
                <tr><td class="label">Classe:</td><td class="value">{{ $registration && $registration->grades ? $registration->grades->name : 'N/A' }}</td></tr>
                <tr><td class="label">Ano Lectivo:</td><td class="value">{{ $tuition->schoolyears->name ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <div class="section-titulo">Detalhes do Pagamento</div>
            <table class="detalhes">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Mês</th>
                        <th class="text-right">Valor (AOA)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pagamento de Propina</td>
                        <td>{{ $tuition->month }}</td>
                        <td class="text-right">{{ number_format($tuition->price, 2, ',', '.') }}</td>
                    </tr>
                    @if($tuition->fine > 0)
                    <tr>
                        <td>Multa por atraso</td>
                        <td>{{ $tuition->month }}</td>
                        <td class="text-right">{{ number_format($tuition->fine, 2, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="total-label">Total:</td>
                        <td class="total-value text-right">{{ number_format($total, 2, ',', '.') }} AOA</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="section">
            <div class="section-titulo">Observações</div>
            <div class="observacoes">
                {{ $tuition->details ?? 'Sem observações adicionais.' }}
            </div>
        </div>

        @if(isset($qrCode))
        <div class="qr-code">
            {!! $qrCode !!}
            <div style="font-size: 10px; margin-top: 5px;">Verifique a autenticidade da factura</div>
        </div>
        @endif

        <div class="footer">
            <p>{{ config('app.name') }} &copy; {{ date('Y') }} - Documento processado por computador</p>
            <p>Este documento é uma factura válida como comprovante de pagamento.</p>
        </div>
    </div>
</body>
</html>
