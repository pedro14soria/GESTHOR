<aside class="sidebar">
    <div class="sidebar__contenedor">
        <h2 class="sidebar__contenedor__logo">GESTHOR</h2>
        <div class="sidebar__contenedor__menu-cerrar">
            <p id="cerrar-menu" class="mobile__menu__icono">&#9776;</p>
        </div>
    </div>
    <nav class="sidebar__nav">
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Inicio') ? 'activo' : ''; ?>" href="/dashboard/inicio">Inicio</a>
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Ingresos') ? 'activo' : ''; ?>" href="/dashboard/ingresos">Ingresos</a>
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Egresos') ? 'activo' : ''; ?>" href="/dashboard/egresos">Egresos</a>
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Categorias') ? 'activo' : ''; ?>" href="/dashboard/categorias">Categorias</a>
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Formas de Pago') ? 'activo' : ''; ?>" href="/dashboard/formas">Formas de Pago</a>
        <a class="sidebar__enlace sidebar__enlace--<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="sidebar__cerrar-sesion">
        <a href="/logout" class="sidebar__cerrar-sesion__enlace">Cerrar Sesión</a>
    </div>
</aside>