<?php include_once __DIR__ . '/../templates/header.php' ?>
<div class="dashboard__contenido">
    <div class="dashboard__contenedor-boton">
        <button type="button" class="dashboard__boton" id="agregar-ingreso">&#43; Nuevo Ingreso</button>
    </div>
    <?php include_once __DIR__ . '/../templates/filtros.php' ?>

    <div id="modal-ingresos" class="modal modal--oculto">
        <div class="modal__contenido">
            <form id="form-ingresos" class="modal__form">
                <span class="modal__cerrar" id="cerrar-modal-ingresos">&times;</span>
                <h2 class="modal__descripcion">Agregar Ingreso</h2>
                <div class="modal__campo">
                    <label class="modal__label" for="descripcion-ingreso">Descripcion</label>
                    <input class="modal__input" type="text" id="descripcion-ingreso" name="descripcion-ingreso" required>
                </div>
                <div class="modal__campo">
                    <label class="modal__label" for="monto-ingreso">Monto</label>
                    <input class="modal__input" type="number" id="monto-ingreso" name="monto-ingreso" required>
                </div>
                <div class="modal__campo">
                    <label class="modal__label" for="fecha-ingreso">Fecha</label>
                    <input class="modal__input" type="date" id="fecha-ingreso" name="fecha-ingreso" required>
                </div>
                <div class="modal__campo">
                    <label for="forma-ingreso" class="modal__label">Formas de Pago</label>
                    <select name="forma-ingreso" id="forma-ingreso" class="modal__input">
                        <option selected value="">-Seleccionar-</option>
                        <?php foreach ($formas_pago as $forma) { ?>
                            <option value="<?php echo $forma->id; ?>"><?php echo $forma->nombre; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit" id="ingreso-submit" value="Agregar ingreso" class="modal__submit">
            </form>
        </div>
    </div>
    
    <div class="caja" id="listado-ingresos"></div> 
</div>
<?php include_once __DIR__ . '/../templates/footer.php' ?>
