<?php include_once __DIR__ . '/../templates/header.php' ?>
<div class="dashboard__contenido">
    <div class="dashboard__contenedor-boton">
        <button type="button" class="dashboard__boton" id="agregar-egreso">&#43; Nuevo Egreso</button>
    </div>
    <?php include_once __DIR__ . '/../templates/filtros.php' ?>

    <div id="modal-egresos" class="modal modal--oculto">
        <div class="modal__contenido">
            <form id="form-egresos" class="modal__form">
                <span class="modal__cerrar" id="cerrar-modal-egresos">&times;</span>
                <h2 class="modal__descripcion">Agregar Egreso</h2>
                <div class="modal__campo">
                    <label class="modal__label" for="descripcion-egreso">Descripcion</label>
                    <input class="modal__input" type="text" id="descripcion-egreso" name="descripcion-egreso" required>
                </div>
                <div class="modal__campo">
                    <label class="modal__label" for="monto-egreso">Monto</label>
                    <input class="modal__input" type="number" id="monto-egreso" name="monto-egreso" required>
                </div>
                <div class="modal__campo">
                    <label class="modal__label" for="fecha-egreso">Fecha</label>
                    <input class="modal__input" type="date" id="fecha-egreso" name="fecha-egreso" required>
                </div>
                <div class="modal__campo">
                    <label for="categoria-egreso" class="modal__label">Categoria</label>
                    <select name="categoria-egreso" id="categoria-egreso" class="modal__input">
                        <option value="">-Seleccionar-</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria->id; ?>"><?php echo $categoria->nombre; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal__campo">
                    <label for="forma-egreso" class="modal__label">Formas de Pago</label>
                    <select name="forma-egreso" id="forma-egreso" class="modal__input">
                        <option selected value="">-Seleccionar-</option>
                        <?php foreach ($formas_pago as $forma) { ?>
                            <option value="<?php echo $forma->id; ?>"><?php echo $forma->nombre; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit" id="egreso-submit" value="Agregar Egreso" class="modal__submit">
            </form>
        </div>
    </div>
    
    <div class="caja" id="listado-egresos"></div> 
</div>
<?php include_once __DIR__ . '/../templates/footer.php' ?>
