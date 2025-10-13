<main class="auth">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="auth__descripcion">
        <p class="auth__descripcion--titulo"><?php echo $titulo; ?></p>
        <?php require_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if($token_valido) { ?>
        <form method="POST" class="formulario">
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password" class="formulario__input">
            </div>
            <input class="formulario__submit" type="submit" value="Cambiar Password" class="boton">
        </form>
        <?php } ?>
    </div>
</main>