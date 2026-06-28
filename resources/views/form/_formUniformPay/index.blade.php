<div class="row">
    <div class="col-md-8 py-2">
        <div class="row">
            <div class="col-md-12" style="position:relative">
                <label for="search_student">Pesquisar Aluno (nome ou n.º de processo)</label>
                <input class="form-control" type="text" id="search_student"
                    placeholder="Digite o nome ou n.º de processo do aluno"
                    value="{{ isset($uniformPay) ? $uniformPay->students->name . ' (' . $uniformPay->students->nProcess . ')' : '' }}">
                <input type="hidden" name="fk_students_id" id="fk_students_id"
                    value="{{ isset($uniformPay) ? $uniformPay->fk_students_id : old('fk_students_id') }}">
                <small class="text-muted">Digite o nome ou n.º de processo e seleccione o aluno da lista.</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 py-2">
        <label>Ano Lectivo</label>
        <p class="form-control-plaintext">{{ \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}</p>
        <input type="hidden" name="fk_schoolyears_id" value="{{ old('fk_schoolyears_id', isset($uniformPay) ? $uniformPay->fk_schoolyears_id : \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="state">Escolha o Estado</label>
        <select class="form-control" name="state" id="state" required>
            <option value=""></option>
            @php
                $states = ['Pago', 'Pendente', 'Cancelado'];
                $selectedState = isset($uniformPay) ? $uniformPay->state : old('state');
            @endphp
            @foreach ($states as $state)
                <option value="{{ $state }}" {{ $selectedState == $state ? 'selected' : '' }}>
                    {{ $state }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 py-2">
        <label for="price">Escolha o Preço</label>
        <select class="form-control" name="price" id="price" required>
            <option value=""></option>
            @php
                $prices = [
                    '10000' => '10.000kz',
                    '15000' => '15.000kz',
                    '25000' => '25.000kz',
                    '30000' => '30.000kz'
                ];
                $selectedPrice = isset($uniformPay) ? $uniformPay->price : old('price');
            @endphp
            @foreach($prices as $value => $label)
                <option value="{{ $value }}" {{ $selectedPrice == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 py-3">
        <button type="submit"
            class="btn btn-md btn-primary shadow-sm text-end">{{ isset($uniformPay) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>
</div>

<script>
    var studentList = [
        @foreach ($registrations as $item)
            @if ($item->students)
                { id: {{ $item->students->id }}, name: '{{ str_replace("'", "\\'", $item->students->name) }}', nProcess: '{{ $item->students->nProcess }}' },
            @endif
        @endforeach
    ];

    $(document).ready(function() {
        var searchInput = $('#search_student');
        var studentIdInput = $('#fk_students_id');
        var suggestions = $('<div class="list-group" style="position:absolute;z-index:1000;max-height:200px;overflow-y:auto;width:100%"></div>').insertAfter(searchInput);

        searchInput.on('input', function() {
            var val = $(this).val().toLowerCase().trim();
            suggestions.empty().hide();

            if (val.length < 1) {
                studentIdInput.val('');
                return;
            }

            var matches = studentList.filter(function(s) {
                return s.name.toLowerCase().includes(val) || s.nProcess.toLowerCase().includes(val);
            });

            if (matches.length === 0) {
                suggestions.append('<a class="list-group-item list-group-item-action disabled">Nenhum aluno encontrado</a>').show();
                return;
            }

            matches.forEach(function(s) {
                var item = $('<a class="list-group-item list-group-item-action" href="#"></a>')
                    .text(s.name + ' (' + s.nProcess + ')')
                    .on('click', function(e) {
                        e.preventDefault();
                        studentIdInput.val(s.id);
                        searchInput.val(s.name + ' (' + s.nProcess + ')');
                        suggestions.empty().hide();
                    });
                suggestions.append(item);
            });
            suggestions.show();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search_student, .list-group').length) {
                suggestions.hide();
            }
        });
    });
</script>
