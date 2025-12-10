<?php include_once __DIR__ . '/../templates/header.php' ?>

<div class="perfil">
    <h3 class="perfil__titulo">Cambiar Nombre y Email</h3>
    <?php
    $alertas = $alertasPerfil ?? []; 
    include __DIR__ . '/../templates/alertas.php' 
    ?>
    <form action="/dashboard/perfil" method="POST" class="formulario formulario--perfil">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input class="formulario__input" type="text" value="<?php echo $nombre;?>" name="nombre" placeholder="Tu Nombre">
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input class="formulario__input" type="email" value="<?php echo $email;?>" name="email" placeholder="Tu Email">
        </div>
        <input type="submit" value="Guardar Cambios" class="formulario__submit formulario__submit--perfil">
    </form>
</div>

<div class="perfil">
    <h3 class="perfil__titulo">Cambiar Contraseña</h3>
    <?php
    $alertas = $alertasContraseña ?? [];
    include __DIR__ . '/../templates/alertas.php' 
    ?>
    <form action="/dashboard/password" method="POST" class="formulario formulario--perfil">
        <div class="formulario__campo">
            <label class="formulario__label" for="password_actual">Password Actual</label>
            <input class="formulario__input" type="password" name="password_actual" placeholder="Password">
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="password_nuevo">Password Nuevo</label>
            <input class="formulario__input" type="password" name="password_nuevo" placeholder="Password">
        </div>
        <input type="submit" value="Cambiar Contraseña" class="formulario__submit formulario__submit--perfil">
    </form>
</div>

<?php include_once __DIR__ . '/../templates/footer.php' ?>
