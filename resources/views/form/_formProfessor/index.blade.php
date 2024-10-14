

<div class="col-md-16 py-2">
    <label for="nProcess">Nº de Processo</label>
    <input class="form-control" type="number" name="nProcess" id="nProcess" placeholder="Digite o Nº de Processo"
        value="{{ isset($professor->nProcess) ? $professor->nProcess : $total + 1 }}" readonly>
</div>

<div class="row">
    <div class="col-md-4 py-2">
        <label for="title">Nome Completo</label>
        <input class="form-control" type="text" name="name" id="name" placeholder="Nome Completo" required
            value="{{ isset($professor->name) ? $professor->name : old('name') }}">
    </div>
    <div class="col-md-4 py-2">
        <label for="father">Nome do Pai</label>
        <input class="form-control" type="text" name="father" id="father" placeholder="Nome do Pai" required
            value="{{ isset($professor->father) ? $professor->father : old('father') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="mother">Nome da Mâe</label>
        <input class="form-control" type="text" name="mother" id="mother" placeholder="Nome da Mâe" required
            value="{{ isset($professor->mother) ? $professor->mother : old('mother') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="nBi">Nº do BI</label>
        <input class="form-control" type="text" name="nBi" id="nBi" placeholder="Nome da Mâe" required
            value="{{ isset($professor->nBi) ? $professor->nBi : old('nBi') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="dateBirth">Data de Nascimento</label>
        <input class="form-control" type="date" name="dateBirth" id="dateBirth" placeholder="" required
            value="{{ isset($professor->dateBirth) ? $professor->dateBirth : old('dateBirth') }}">
    </div>
    <div class="col-md-4 py-2">
        <label for="email">Endereço de E-mail</label>
        <input class="form-control" type="email" name="email" id="email" placeholder="Endereço de E-mail" required
            value="{{ isset($professor->email) ? $professor->email : old('email') }}">
    </div>


  <div class="col-md-4 py-2">
        <label for="contact">Contacto</label>
                <input class="form-control" type="text" name="contact" id="contact" placeholder="Digite o Contacto"
                    required value="{{ isset($professor->contact) ? $professor->contact : old('contact') }}">
    </div>

    <div class="col-md-4 py-2">
        <label for="contactAlter">Contacto Alternativo</label>
                <input class="form-control" type="text" name="contactAlter" id="contactAlter" placeholder="Contacto Alternativo" required
                    value="{{ isset($professor->contactAlter) ? $professor->contactAlter : old('contactAlter') }}">
    </div>
    <div class="col-md-4 py-2">
        <label for="schoolyear">Escolha o Ano lectivo</label>
        <select class="form-control" name="schoolyear" id="schoolyear" required>
            <option {{ isset($professor) ? '' : 'selected' }}></option>
            @foreach ($schoolyear as $item)
                <option value="{{ $item->name }}"
                    {{ isset($professor) && $professor->schoolyear == $item->name ? 'selected' : (old('schoolyear') == $item->name ? 'selected' : '') }}>
                    {{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 py-2"></div>

    <div class="col-md-6 py-3">
        <button type="submit"
            class="btn btn-md btn-primary shadow-sm text-end">{{ isset($professor) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>

</div>
