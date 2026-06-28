<div class="row">
    <div class="col-md-12 py-2">
        <label for="title">Cargo</label>
        <input class="form-control" type="text" name="name" id="name" placeholder="Digite o Cargo"
            required value="{{ isset($rule->name) ? $rule->name : old('name') }}">
    </div>
    <div class="col-md-12 py-2 details-container with-margin">
        <label for="details">Detalhes do Cargo</label>
        <textarea class="form-control" name="details" id="details" cols="30" rows="5" 
            placeholder="Descreva as responsabilidades, habilidades necessárias e funções deste cargo">{{ isset($rule->details) ? $rule->details : old('details') }}</textarea>
        <span class="char-counter"><i class="fas fa-info-circle"></i> Especifique as funções e responsabilidades</span>
    </div>
    <div class="col-md-6 py-3">
        <button type="submit"
            class="btn btn-md btn-primary shadow-sm text-end">{{ isset($rule) ? 'Atualizar' : 'Cadastrar' }}</button>
    </div>

</div>
