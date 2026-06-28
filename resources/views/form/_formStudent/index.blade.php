<div class="col-md-16 py-2">
    @if(isset($student) && $student->nProcess)
        {{-- Edição: mostrar o nProcess atual como somente leitura --}}
        <label for="nProcess">Nº de Processo</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
            </div>
            <input class="form-control font-weight-bold" type="text"
                value="{{ $student->nProcess }}" readonly
                style="background:#f8f9fc; color:#4e73df; font-size:1.1rem; letter-spacing:1px;">
        </div>
        <small class="text-muted">O Nº de Processo é atribuído automaticamente e não pode ser alterado.</small>
    @else
        {{-- Criação: aviso que será atribuído automaticamente --}}
        <div class="alert alert-info d-flex align-items-center mb-0" role="alert">
            <i class="fas fa-info-circle mr-2 fa-lg"></i>
            <span>
                <strong>Nº de Processo</strong> — será atribuído automaticamente no momento da matrícula, com base no <strong>Curso</strong> e <strong>Classe</strong> selecionados abaixo.<br>
                <small>Ex: Informática + 10ª → <strong>A1</strong>, <strong>A2</strong>... · Gestão + 10ª → <strong>B1</strong>, <strong>B2</strong>...</small>
            </span>
        </div>
    @endif
</div>


<div class="row">
    <div class="col-md-4 py-2">
        <label for="title">Nome Completo</label>
        <input class="form-control" type="text" name="name" id="name" placeholder="Nome Completo" required
            value="{{ isset($student->name) ? $student->name : old('name') }}">
    </div>
    <div class="col-md-4 py-2">
        <label for="father">Nome do Pai</label>
        <input class="form-control" type="text" name="father" id="father" placeholder="Nome do Pai" required
            value="{{ isset($student->father) ? $student->father : old('father') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="mother">Nome da Mãe</label>
        <input class="form-control" type="text" name="mother" id="mother" placeholder="Nome da Mãe" required
            value="{{ isset($student->mother) ? $student->mother : old('mother') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="nBi">Nº do BI</label>
        <input class="form-control" type="text" name="nBi" id="nBi" placeholder="Nº do BI" required
            value="{{ isset($student->nBi) ? $student->nBi : old('nBi') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="dateBirth">Data de Nascimento</label>
        <input class="form-control" type="date" name="dateBirth" id="dateBirth" placeholder="" required
            value="{{ isset($student->dateBirth) ? $student->dateBirth : old('dateBirth') }}">
    </div>
    <div class="col-md-4 py-2">
        <label for="email">Endereço de E-mail</label>
        <input class="form-control" type="email" name="email" id="email" placeholder="Endereço de E-mail" required
            value="{{ isset($student->email) ? $student->email : old('email') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="contact">Contacto</label>
                <input class="form-control" type="text" name="contact" id="contact" placeholder="Digite o Contacto"
                    required value="{{ isset($student->contact) ? $student->contact : old('contact') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="contactAlter">Contacto Alternativo</label>
                <input class="form-control" type="text" name="contactAlter" id="contactAlter" placeholder="Contacto Alternativo" required
                    value="{{ isset($student->contactAlter) ? $student->contactAlter : old('contactAlter') }}">
    </div>
    <div class="col-md-6 py-2">
        <label>Ano Lectivo</label>
        <p class="form-control-plaintext">{{ \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName() }}</p>
        <input type="hidden" name="schoolyear" value="{{ old('schoolyear', isset($student) ? $student->schoolyear : \App\Helpers\SchoolYearHelper::getCurrentSchoolYearName()) }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="image">Foto do Aluno (opcional)</label>
        <input class="form-control" type="file" name="image" id="image" accept="image/*">
        <small class="form-text text-muted">
            Tamanho máximo: 2MB. Formatos aceitos: JPEG, PNG, GIF.
        </small>
        @if(isset($student->image))
            <div class="mt-2">
                <img src="{{ route('admin.student.image', $student->image) }}" alt="Foto atual" class="img-thumbnail" style="width: 96px; height: 120px; object-fit: cover;">
            </div>
        @endif
    </div>

    <div class="col-md-6 py-2"></div>

    <div class="col-md-6 py-3">
        <button type="submit"
            class="btn btn-md btn-primary shadow-sm text-end">{{ isset($student) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>

</div>
