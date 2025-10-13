<?php include_once __DIR__ . '/../templates/header.php' ?>
<div class="dashboard__contenido">
    <div class="dashboard__contenedor-boton">
        <button type="button" class="dashboard__boton" id="agregar-formas">&#43; Nueva Forma de Pago</button>
    </div>

    <div id="modal-forma" class="modal modal--oculto">
        <div class="modal__contenido">
            <form id="form-forma" class="modal__form">
                <span class="modal__cerrar" id="cerrar-modal-forma">&times;</span>
                <h2 class="modal__descripcion">Agregar Forma de Pago</h2>
                <div class="modal__campo">
                    <label class="modal__label" for="nombre-forma">Nombre</label>
                    <input class="modal__input" type="text" id="nombre-forma" name="nombre-forma" required>
                </div>
                <input type="submit" id="forma-submit" value="Agregar Forma de Pago" class="modal__submit">
            </form>
        </div>
    </div>
    
    <ul class="formas" id="listado-formas"></ul>
</div>
<?php include_once __DIR__ . '/../templates/footer.php' ?>
