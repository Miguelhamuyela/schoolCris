<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ date('d/m/Y', strtotime(now())) }}</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        header {
            text-align: center;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #444;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            height: 80px;
            width: auto;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            margin-top: 10px;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .report-subtitle {
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #444;
            font-size: 10px;
        }

        .student-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .student-info p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            @php
                $logoPath = public_path('dashboard/assets/img/logo-sge-small.png');
                $logoData = "";
                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            <img src="{{ $logoData }}" alt="Logo da Escola" style="width: 100px; height: auto;">
        </div>
        <div class="school-name">INSTITUTO POLITECNICO PRIVADO PITAGORAS DE SAMOS</div>
        <div class="report-title">@yield('report-title')</div>
        <div class="report-subtitle">@yield('report-subtitle')</div>
    </header>

    <div class="container-fluid">
        @yield('content')
    </div>

    <footer class="text-center">
        <small>
            Documento Processado por Computador.<br>
            {{ date('d/m/Y H:i:s') }} - {{ config('app.name') }}
        </small>
    </footer>
</body>

</html> 