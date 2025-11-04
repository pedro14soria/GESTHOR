<?php include_once __DIR__ . '/../templates/header.php' ?>
<?php include_once __DIR__ . '/../templates/filtros.php' ?>
<main class="bloques">
    <div class="bloques__grid">
        <div class="bloque bloque--ingresos">
            <h3 class="bloque__heading">Total Ingresos</h3>
            <p class="bloque__texto--cantidad" id="total-ingresos"><?php echo '$ ' . round($ingresos, 2); ?></p>
        </div>
        <div class="bloque bloque--egresos">
            <h3 class="bloque__heading">Total Egresos</h3>
            <p class="bloque__texto--cantidad" id="total-egresos"><?php echo round($egresos, 2); ?></p>
        </div>
        <div class="bloque bloque--balance">
            <h3 class="bloque__heading">Balance</h3>
            <p class="bloque__texto--cantidad" id="balance"><?php echo round($balance, 2); ?></p>
        </div>
    </div>
</main>
<?php include_once __DIR__ . '/../templates/footer.php' ?>
