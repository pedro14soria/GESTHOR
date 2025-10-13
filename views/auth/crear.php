<main class="auth">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="auth__descripcion">
        <p class="auth__descripcion--titulo"><?php echo $titulo; ?></p>
        <?php require_once __DIR__ . '/../templates/alertas.php' ?>
        <form action="/crear" method="POST" class="formulario">
            <div class="formulario__campo">
                <label class="formulario__label" for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo $usuario->nombre; ?>" class="formulario__input">
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" value="<?php echo $usuario->email; ?>" class="formulario__input">
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" class="formulario__input">
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="password2">Repite tu Password</label>
                <input type="password" id="password2" placeholder="Repite tu Password" name="password2" class="formulario__input">
            </div>
            <input type="submit" value="Crear Cuenta" class="formulario__submit">
        </form>

        <div class="acciones">
            <a href="/login" class="acciones__enlace">Iniciar Sesión</a>
            <a href="/olvide" class="acciones__enlace">¿Has olvidado la contraseña?</a>
        </div>
    </div>
</main>