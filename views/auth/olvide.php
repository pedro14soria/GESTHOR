<main class="auth">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="auth__descripcion">
        <p class="auth__descripcion--titulo"><?php echo $titulo; ?></p>
        <?php require_once __DIR__ . '/../templates/alertas.php' ?>
        <form action="/olvide" method="POST" class="formulario">
            <p class="formulario__descripcion">Coloca tu email, asi te podemos enviar las instrcciones para poder rescuperar tu cuenta</p>
            <div class="formulario__campo">
                <label for="email" class="formulario__label">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" class="formulario__input"> 
            </div>
            <input type="submit" value="Enviar Instrucciones" class="formulario__submit">
        </form>

        <div class="acciones">
            <a href="/crear" class="acciones__enlace">Crear Cuenta</a>
            <a href="/login" class="acciones__enlace">Iniciar Sesión</a>
        </div>
    </div>
</main>