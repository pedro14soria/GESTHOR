<div class="filtros">
    <form id="filtro-form" class="formulario--filtro formulario">
        <div class="formulario__campo formulario__campo--filtro">
            <label class="formulario__label formulario__label--filtro" for="fecha-inicio">Desde:</label>
            <input class="formulario__input" type="date" id="fecha-inicio" name="fecha_inicio">
        </div>
        <div class="formulario__campo formulario__campo--filtro">
            <label class="formulario__label formulario__label--filtro" for="fecha-fin">Hasta:</label>
            <input class="formulario__input formulario__input--filtro" type="date" id="fecha-fin" name="fecha_fin">
        </div>
        <div class="formulario__campo formulario__campo--filtro">
            <label class="formulario__label formulario__label--filtro" for="forma-pago">Forma de pago:</label>
            <select class="formulario__input formulario__input--filtro" id="forma-pago" name="forma_pago_id">
                <option value=""></option>
                <?php foreach ($formas_pago as $forma) { ?>
                    <option value="<?php echo $forma->id; ?>"><?php echo $forma->nombre; ?></option>
                <?php } ?>
            </select>
        </div>
        <button class="formulario__submit formulario__submit--filtro" type="button" id="aplicar-filtros">Filtrar</button>
    </form>
</div>