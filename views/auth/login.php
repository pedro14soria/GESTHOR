<main class="auth">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="auth__descripcion">
        <p class="auth__descripcion--titulo"><?php echo $titulo; ?></p>
        <?php require_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/login" method="POST" class="formulario">
            <div class="formulario__campo">
                <label for="email" class="formulario__label">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" class="formulario__input">
            </div>
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" class="formulario__input">
            </div>
            <input type="submit" value="Iniciar Sesion" class="formulario__submit">
        </form>

        <div class="acciones">
            <a href="/crear" class="acciones__enlace">Crear Cuenta</a>
            <a href="/olvide" class="acciones__enlace">¿Has olvidado la contraseña?</a>
        </div>
    </div>
</main>