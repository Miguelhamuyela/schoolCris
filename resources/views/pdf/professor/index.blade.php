

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Maticulados-{{ date('d/m/Y', strtotime(now())) }}</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        header {
            text-align: center;
            padding: 1.5rem;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="img">
            <img src="dashboard/assets/img/insignia.png" alt="">
        </div>
        <div class="text-center">DIOCESE DE BENGUELA</div>
        <div class="text-center"> SEMINÁRIO MAIOR DO BOM PASTOR</div>
        <div class="text-center">LISTA DE ALUNOS INSCRITOS DO ANO DE "{{ $schoolyear  }}"</div>
    </header>

    <main>
        <div class="main">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NOME</th>
                        <th>Nº de PROC</th>
                        <th>Nº B.I/PASSAPORTE</th>
                        <th>ANO LECTIVO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($professores as $item)
                        <tr>
                            <td scope="row">{{ $loop->index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->nProcess }}</td>
                            <td>{{ $item->nBi }}</td>
                            <td>{{ $item->schoolyear }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <hr class="pylarge bg-dark">
    <footer class="col-12 mt-2 text-center" id="footer">

        <small class="text-left text-dark">
            Documento Processado por Computador. <br>
        </small>

    </footer>


</body>

</html>
