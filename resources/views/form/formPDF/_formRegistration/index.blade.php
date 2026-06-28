<div class="modal-header">
    <h4 class="modal-title">Imprimir Lista</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <p class="mb-3">Selecione pelo menos um filtro para gerar o relatório. Todos os campos são opcionais.</p>

    <div class="row">
        <div class="col-md-6 py-2">
            <label for="schoolyear">Ano Letivo (opcional)</label>
            <select class="form-control" name="schoolyear" id="schoolyear">
                <option value=""></option>
                @foreach ($schoolyears as $schoolyear)
                    <option value="{{ $schoolyear->name }}" {{ $schoolyear->is_current ? 'selected' : '' }}>{{ $schoolyear->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 py-2">
            <label for="classe">Turma (opcional)</label>
            <select class="form-control" name="classe" id="classe">
                <option value=""></option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->name }}">{{ $classe->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 py-2">
            <label for="course">Curso (opcional)</label>
            <select class="form-control" name="course" id="course">
                <option value=""></option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 py-2">
            <label for="grade">Classe (opcional)</label>
            <select class="form-control" name="grade" id="grade">
                <option value=""></option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <button type="submit" class="btn btn-primary" id="printButton">Imprimir</button>
</div>
