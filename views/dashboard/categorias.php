<?php include_once __DIR__ . '/../templates/header.php' ?>
<div class="dashboard__contenido">
    <div class="dashboard__contenedor-boton">
        <button type="button" class="dashboard__boton" id="agregar-categoria">&#43; Nueva Categoria</button>
    </div>

    <div id="modal-categoria" class="modal modal--oculto">
        <div class="modal__contenido">
            <form id="form-categoria" class="modal__form">
                <span class="modal__cerrar" id="cerrar-modal-categoria">&times;</span>
                <h2 class="modal__descripcion">Agregar Categoria</h2>
                <div class="modal__campo">
                    <label class="modal__label" for="nombre-categoria">Nombre</label>
                    <input class="modal__input" type="text" id="nombre-categoria" name="nombre-categoria" required>
                </div>
                <input type="submit" id="categoria-submit" value="Agregar Categoria" class="modal__submit">
            </form>
        </div>
    </div>
    
    <ul class="categorias" id="listado-categorias"></ul>
</div>
<?php include_once __DIR__ . '/../templates/footer.php' ?>
