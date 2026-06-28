<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pagamentos - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 10px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }
        .logo {
            max-width: 80px;
            display: block;
            margin: 0 auto 10px auto;
        }
        h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        h2 {
            font-size: 14px;
            margin: 5px 0;
        }
        .title {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            font-size: 9px;
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('dashboard/assets/img/logo-sge-small.png');
        $logoSrc = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp
    <div class="header">
        @if ($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo Instituto" class="logo">
        @endif
        <h1>Instituto Politécnico Privado Pitágoras de Samos</h1>
        <h2>LISTA DE PAGAMENTOS DE PROPINAS</h2>
        <div class="title">@if(!empty($schoolyear))Mês: <b>{{ $month }}</b> - Ano Lectivo: <b>{{ $schoolyear }}</b>@else Relatório Completo @endif</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Nº Processo</th>
                <th>Curso</th>
                <th>Classe</th>
                <th>Valor</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 0; @endphp
            @foreach ($tuitions as $item)
                @if (($pay == null && $item->state == "Pago") || 
                     ($cancel == null && $item->state == "Cancelado") || 
                     ($pending == null && $item->state == "Pendente"))
                    @continue
                @endif
                @php $counter++; @endphp
                @php
                    // Buscar a matrícula do aluno para o ano letivo selecionado
                    $registration = App\Models\CourseClasseGradeStudentSchoolyear::where('fk_students_id', $item->fk_students_id)
                        ->where('fk_schoolyears_id', $item->fk_schoolyears_id)
                        ->with('courses', 'grades')
                        ->first();
                @endphp
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $item->students ? $item->students->name : 'N/A' }}</td>
                    <td>{{ $item->students ? $item->students->nProcess : 'N/A' }}</td>
                    <td>{{ $registration && $registration->courses ? $registration->courses->name : 'N/A' }}</td>
                    <td>{{ $registration && $registration->grades ? $registration->grades->name : 'N/A' }}</td>
                    <td>{!! number_format($item->price, 2, ',', '.') . ' AOA' !!}</td>
                    <td>{{ $item->state }}</td>
                </tr>
                @if($counter >= 1000)
                    <tr>
                        <td colspan="7" style="text-align: center; font-style: italic;">
                            Relatório limitado a 1000 registros. Por favor, refine seus filtros para ver mais resultados.
                        </td>
                    </tr>
                    @break
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Instituto Politécnico Privado Pitágoras de Samos &copy; {{ date('Y') }}</p>
        <p>Documento processado por computador em {{ date('d/m/Y') }} às {{ date('H:i') }}</p>
    </div>
</body>

</html>
