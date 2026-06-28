<div class="row">
    <div class="col-md-6 py-2">
        <label for="fk_teachers_id">Escolha Professor</label>
        <select class="form-control" name="fk_teachers_id" id="fk_teachers_id">
            <option {{ isset($teacherSubjectClasseRuleSchoolyear) ? '' : 'selected'}}></option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ (isset($teacherSubjectClasseRuleSchoolyear) && $teacherSubjectClasseRuleSchoolyear->teachers->id == $teacher->id ) ? 'selected' :  ((old('fk_teachers_id') == $teacher->id) ? 'selected' : '')  }}>{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 py-2">
        <label>Ano Lectivo</label>
        <p class="form-control-plaintext">{{ \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}</p>
        <input type="hidden" name="fk_schoolyears_id" value="{{ old('fk_schoolyears_id', isset($teacherSubjectClasseRuleSchoolyear) ? $teacherSubjectClasseRuleSchoolyear->fk_schoolyears_id : \App\Helpers\SchoolYearHelper::getCurrentSchoolYearId()) }}">
    </div>
    <div class="col-md-6 py-2">
        <label for="season">Escolha o turno</label>
        <select class="form-control" name="season" id="season">
            <option></option>
            <option value="Manhã" {{ isset($teacherSubjectClasseRuleSchoolyear) && $teacherSubjectClasseRuleSchoolyear->season == 'Manhã' ? 'selected' : ((old('season') == 'Manhã') ? 'selected' : '') }}>Manhã</option>
            <option value="tarde" {{ isset($teacherSubjectClasseRuleSchoolyear) && $teacherSubjectClasseRuleSchoolyear->season == 'tarde' ? 'selected' : ((old('season') == 'tarde') ? 'selected' : '') }}>tarde</option>
            <option value="Noite" {{ isset($teacherSubjectClasseRuleSchoolyear) && $teacherSubjectClasseRuleSchoolyear->season == 'Noite' ? 'selected' : ((old('season') == 'Noite') ? 'selected' : '') }}>Noite</option>
        </select>
    </div>

    <div class="col-md-6 py-3">
        <button type="submit" class="btn btn-md btn-primary shadow-sm text-end">{{ isset($teacherSubjectClasseRuleSchoolyear) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>
</div>

