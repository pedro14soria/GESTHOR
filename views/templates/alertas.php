<?php 
foreach ($alertas as $key => $alerta) {
    foreach($alerta as $mensaje) {
?>
    <div class="alerta alerta__<?php echo $key; ?>">
        <p><?php echo $mensaje; ?></p>
    </div>
<?php 
    }
}
?>

