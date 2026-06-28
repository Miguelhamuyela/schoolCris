<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <title>Cartao de Estudante</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            color: #1e293b;
        }

        .page-break {
            page-break-after: always;
        }

        .page-wrap {
            width: 21cm;
            height: 29.7cm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ==================== CARTÃO ==================== */
        .card {
            width: 8.5cm;
            height: 5.4cm;
            background: #ffffff;
            border: 1.5px solid #FF8000;
            border-left: 5px solid #FF8000;
            position: relative;
        }

        /* ==================== FRENTE ==================== */
        .card-front {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        /* --- HEADER --- */
        .card-front .hdr {
            height: 0.8cm;
            background: #ffffff;
        }

        .card-front .hdr td {
            padding: 3px 10px 3px 14px;
            border-bottom: 2px solid #FF8000;
        }

        .hdr-logo {
            width: 0.5cm;
        }

        .hdr-title {
            font-size: 6.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
        }

        .hdr-sub {
            font-size: 5px;
            color: #FF8000;
            font-weight: 600;
            text-transform: uppercase;
        }

        .hdr-badge {
            font-size: 7px;
            font-weight: 700;
            color: #ffffff;
            background: #FF8000;
            padding: 2px 6px;
        }

        /* --- BODY --- */
        .card-front .body-row {
            height: 3.85cm;
            vertical-align: top;
        }

        .card-front .body-row td {
            padding: 5px 8px 4px 14px;
        }

        .photo-cell {
            width: 2.2cm;
            text-align: center;
            vertical-align: top;
        }

        .photo-frame {
            width: 1.85cm;
            height: 2.35cm;
            margin: 2px auto 0;
            border: 1.5px solid #d1d5db;
            background: #f1f5f9;
        }

        .photo-frame img {
            display: block;
        }

        .photo-frame .no-photo {
            font-size: 5.5px;
            color: #94a3b8;
            font-weight: 700;
            text-align: center;
            padding-top: 0.85cm;
        }

        .photo-label {
            font-size: 5px;
            color: #94a3b8;
            text-transform: uppercase;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .info-cell {
            vertical-align: top;
            padding-left: 6px;
        }

        .student-name {
            font-size: 7.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .info-table .il {
            font-size: 5px;
            color: #64748b;
            text-transform: uppercase;
            white-space: nowrap;
            width: 1%;
        }

        .info-table .isep {
            font-size: 5px;
            color: #cbd5e1;
            padding: 0 3px;
            width: 8px;
        }

        .info-table .iv {
            font-size: 6.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .info-table .iv-hl {
            color: #FF8000;
            font-weight: 700;
        }

        /* --- FOOTER --- */
        .card-front .ftr td {
            padding: 2px 10px 2px 14px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 4.8px;
            color: #94a3b8;
        }

        .ftr-id {
            font-weight: 600;
            color: #FF8000;
            text-align: right;
        }

        /* ==================== VERSO ==================== */
        .card-back {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .card-back .body-cell {
            text-align: center;
            vertical-align: middle;
        }

        .back-logo {
            max-width: 2.8cm;
            max-height: 1.3cm;
            margin-bottom: 4px;
        }

        .back-school {
            font-size: 7px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .back-info {
            font-size: 5.5px;
            color: #475569;
            line-height: 1.5;
        }

        .card-back .ftr-cell {
            padding: 4px 10px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 5px;
            color: #94a3b8;
        }

        .ftr-brand {
            font-weight: 600;
            color: #FF8000;
        }
    </style>
</head>

<body>
    @php
        $student = $registration->students;
        $studentName = mb_strtoupper($student->name ?? 'SEM NOME');
        $courseName = $registration->courses->name ?? 'N/A';
        $className = $registration->grades->name ?? 'N/A';
        $turmaName = $registration->classes->name ?? 'N/A';
        $periodName = $registration->season ?? 'N/A';
        $yearName = $registration->schoolyears->name ?? 'N/A';
        $nProcess = $student->nProcess ?? '---';
        $nBi = $student->nBi ?? '---';
        $dateBirth = !empty($student->dateBirth) ? date('d/m/Y', strtotime($student->dateBirth)) : '---';

        $instName = 'Instituto Politecnico Privado Pitagoras de Samos';
        $instShort = 'IPPP de Samos';
    @endphp

    <!-- ==================== FRENTE ==================== -->
    <div class="page-wrap page-break">
        <div class="card">
            <table class="card-front">
                <tr class="hdr">
                    <td>
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td width="36" style="vertical-align:middle;">
                                    @if ($logoSrc)
                                        <img src="{{ $logoSrc }}" alt="Logo" class="hdr-logo">
                                    @endif
                                </td>
                                <td style="vertical-align:middle;">
                                    <div class="hdr-title">{{ $instName }}</div>
                                    <div class="hdr-sub">Cartao de Estudante</div>
                                </td>
                                <td style="text-align:right; vertical-align:middle;">
                                    <span class="hdr-badge">ESTUDANTE</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="body-row">
                    <td>
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td class="photo-cell">
                                    <div class="photo-frame">
                                        @if ($studentPhotoSrc)
                                            <img src="{{ $studentPhotoSrc }}" alt="Foto" style="width:1.85cm; height:2.35cm;">
                                        @else
                                            <div class="no-photo">SEM<br>FOTO</div>
                                        @endif
                                    </div>
                                    <div class="photo-label">{{ $studentName }}</div>
                                </td>
                                <td class="info-cell">
                                    <div class="student-name">{{ $studentName }}</div>
                                    <table class="info-table">
                                        <tr>
                                            <td class="il">Nº Processo</td>
                                            <td class="isep">:</td>
                                            <td class="iv iv-hl">{{ $nProcess }}</td>
                                        </tr>
                                        <tr>
                                            <td class="il">Curso</td>
                                            <td class="isep">:</td>
                                            <td class="iv">{{ $courseName }}</td>
                                        </tr>
                                        <tr>
                                            <td class="il">Classe</td>
                                            <td class="isep">:</td>
                                            <td class="iv">{{ $className }} · {{ $turmaName }}</td>
                                        </tr>
                                        <tr>
                                            <td class="il">Periodo</td>
                                            <td class="isep">:</td>
                                            <td class="iv">{{ $periodName }}</td>
                                        </tr>
                                        <tr>
                                            <td class="il">Ano Lectivo</td>
                                            <td class="isep">:</td>
                                            <td class="iv">{{ $yearName }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="ftr">
                    <td>
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="font-size:4.8px; color:#94a3b8;">
                                    Nasc: {{ $dateBirth }} &nbsp;|&nbsp; BI: {{ $nBi }}
                                </td>
                                <td class="ftr-id">#{{ $nProcess }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ==================== VERSO ==================== -->
    <div class="page-wrap">
        <div class="card">
            <table class="card-back">
                <tr>
                    <td class="body-cell">
                        @if ($logoSrc)
                            <img src="{{ $logoSrc }}" alt="Logo" class="back-logo">
                        @endif
                        <div class="back-school">{{ $instName }}</div>
                        <div class="back-info">
                            Republica de Angola<br>
                            Ministerio da Educacao<br>
                            <br>
                            <strong>{{ $studentName }}</strong><br>
                            Processo: {{ $nProcess }}<br>
                            {{ $courseName }} · {{ $className }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="ftr-cell">
                        Documento emitido pelo <span class="ftr-brand">{{ $instShort }}</span> &mdash; {{ date('Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>