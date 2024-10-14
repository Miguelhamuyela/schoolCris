<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Professores admitidos-{{ date('d/m/Y', strtotime(now())) }}</title>
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
        <div class="text-center">REPÚBLICA DE ANGOLA</div>
        <div class="text-center">MINISTÉRIO DA EDUCAÇÃO</div>
        <div class="text-center">SISTEMA DE GESTÃO ESCOLAR</div>
        <div class="text-center">LISTA DE PROFESSORES ADMITIDOS</div>
    </header>

    <main>
        <div class="main">
            @if ($teacherSchoolYear->count() <= 0)
            <hr>
                <div class="text-center">  Não Existe nenhum professor cadastrado neste ano lectivo</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Professor</th>
                            <th>Cargo</th>
                            <th>Turma</th>
                            <th>Disciplina</th>
                            <th>Ano Lectivo</th>
                            <th>Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacherSchoolYear as $item)
                            <tr>
                                <td scope="row">{{ $loop->index + 1 }}</td>
                                <td>{{ $item->teachers->name }}</td>
                                <td>{{ $item->rules->name }}</td>
                                <td>{{ $item->classes->name }}</td>
                                <td>{{ $item->subjects->name }}</td>
                                <td>{{ $item->schoolyears->name }}</td>
                                <td>{{ $item->season }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

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
