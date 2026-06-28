<div class="row">
    <div class="col-md-8 py-2">
        <div class="row">

            <div class="col-md-4">
                <label for="nProcess">Escolha Nº de Processo</label>
                <input class="form-control" type="text" name="nProcess" id="nProcess" placeholder="Nº de Processo"
                    value="{{ old('nProcess') }}">
            </div>
            <div class="col-md-8">
                <label for="fk_students_id">Escolha Aluno</label>
                <select class="form-control" name="fk_students_id" id="fk_students_id" required>
                    <option {{ isset($tuition) ? '' : 'selected' }}></option>
                    @foreach ($registrations as $item)


                    <option value="{{ isset($item->students) ? $item->students->id : '' }}" {{ isset($tuition->students) && isset($item->students) && $tuition->students->id == $item->students->id ? 'selected' : (old('fk_students_id') == (isset($item->students) ? $item->students->id : '') ? 'selected' : '') }}> {{ isset($item->students) ? $item->students->name : 'Não informado' }} </option>


                        {{--  <option value="{{ $item->id }}"
                            {{ isset($tuition) && $tuition->students->id == $item->students->id ? 'selected' : (old('fk_students_id') == $item->students->id ? 'selected' : '') }}>
                            {{ $item->students->name }}</option>  --}}
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 text-muted">Poderá escolher pelo nome ou pelo nº de processo, as duas opções são
                válidas.</div>
        </div>
    </div>

    <div class="col-md-4 py-2">
        <label>Ano Lectivo</label>
        <p class="form-control-plaintext">{{ $schoolyearName ?? \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}</p>
        <input type="hidden" name="fk_schoolyears_id" value="{{ old('fk_schoolyears_id', isset($tuition) ? $tuition->fk_schoolyears_id : \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="month">Escolha o Mês</label>

        <select class="form-control" name="month" id="month" required>
            <option {{ isset($tuition) ? '' : 'selected' }}></option>
            @php
                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                $selectedMonth = isset($tuition) ? $tuition->month : old('month');
            @endphp

            @foreach ($meses as $mes)
                <option value="{{ $mes }}" {{ $selectedMonth == $mes ? 'selected' : '' }}>
                    {{ $mes }}
                </option>
            @endforeach
        </select>

    </div>

    <div class="col-md-4 py-2">
        <label for="state">Escolha o Estado</label>
        <select class="form-control" name="state" id="state" required>
            <option {{ isset($tuition) ? '' : 'selected' }}></option>
            @php
                $states = ['Pago', 'Pendente', 'Cancelado'];
                $selectedState = isset($tuition) ? $tuition->state : old('state');
            @endphp

            @foreach ($states as $state)
                <option value="{{ $state }}" {{ $selectedState == $state ? 'selected' : '' }}>
                    {{ $state }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 py-2">
        <label for="price">Valor da Propina</label>
        <select class="form-control" name="price" id="price" required>
            <option {{ isset($tuition) ? '' : 'selected' }} value="">Selecione o valor</option>
            @php
                $precos = ['27000', '30000', '32000', '36000', '40000', '60000'];
                $selectedPrice = isset($tuition) ? $tuition->price : old('price');
            @endphp
            @foreach ($precos as $preco)
                <option value="{{ $preco }}" {{ $selectedPrice == $preco ? 'selected' : '' }}>
                    {{ number_format($preco, 2, ',', '.') }} Kz
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 py-2">
        <label for="fine">Multa</label>
        <select class="form-control" name="fine" id="fine" required>
            <option {{ !isset($tuition) || (isset($tuition) && $tuition->fine == 0) ? 'selected' : '' }} value="0">Sem Multa</option>
            <option value="6000" {{ (isset($tuition) && $tuition->fine == 6000) || old('fine') == '6000' ? 'selected' : '' }}>
                6.000,00 Kz
            </option>
        </select>
    </div>


    <div class="col-md-4 py-3">
        <button type="submit"
            class="btn btn-md btn-primary shadow-sm text-end">{{ isset($tuition) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>
</div>

<script>
    var students = []; // variável global para armazenar os dados do select

    $(document).ready(function() {
        // Salva os dados do select na variável global
        $('#fk_students_id option').each(function() {
            students.push({
                value: $(this).val(),
                text: $(this).text()
            });
        });
    });

    // Preencher o nome

    $('#nProcess').on('input', function() {
        var nProcess = $(this).val();

        if (nProcess === '') { // Se o input estiver vazio, usa os dados armazenados na variável global
            $('#fk_students_id').empty();
            $.each(students, function(key, value) {
                $('#fk_students_id').append($('<option>').text(value.text).attr('value', value.value));
            });
        } else {
            $.ajax({
                url: '/get/registration/' + nProcess,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    
                    $('#fk_students_id').empty();
                    if (data) {
                        $('#fk_students_id').append($('<option>').text(data.name).attr('value', data
                            .id));
                    } else {
                        $('#fk_students_id').append($('<option>').text('Nenhum aluno encontrado neste processo')
                            .attr('value', ''));
                    }
                }
            });
        }
    });

    // Preencher o id

    $('#fk_students_id').on('change', function() {
        var student = $(this).val();

        if (student === '') {
            $('#nProcess').empty();
            $.each(students, function(key, value) {
                $('#nProcess').append($('<input>').text(value.text).attr('value', value.value));
            });
        } else {
            $.ajax({
                url: '/get/nprocess/' + student,
                type: 'get',
                datatype: 'json',
                success: function(data) {
                    console.log(data);
                    $('#nProcess').empty();

                    $('#nProcess').val(data);
                }
            });
        }
    });
</script>
