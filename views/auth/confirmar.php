<main class="auth">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="auth__descripcion">
        <p class="auth__descripcion--titulo"><?php echo $titulo ?></p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if(isset($alertas['exito'])) { ?>
        <a class="auth__boton" href="/login">Iniciar Sesión</a>
        <?php } ?>
    </div>
</main>