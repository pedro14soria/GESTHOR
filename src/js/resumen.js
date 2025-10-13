async function obtenerResumen(filtros = {}) {
    const bloqueIngresos = document.querySelector(".bloque--ingresos")
    if (bloqueIngresos) {
        const params = new URLSearchParams(filtros).toString();
        const url = `/api/inicio?${params}`;
        try {
            const respuesta = await fetch(url);
            const data = await respuesta.json();
            // Actualiza el DOM con los datos recibidos
            document.getElementById('total-ingresos').textContent ='$ ' + data.ingresos;
            document.getElementById('total-egresos').textContent ='$ ' + data.egresos;
            document.getElementById('balance').textContent ='$ ' + data.balance;
        } catch (error) {
            console.error('Error al obtener el resumen:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    obtenerResumen();

    const filtroForm = document.getElementById('filtro-form');
    if (filtroForm) {
        document.getElementById('aplicar-filtros').addEventListener('click', function() {
            const filtros = {
                fecha_inicio: document.getElementById('fecha-inicio').value,
                fecha_fin: document.getElementById('fecha-fin').value,
            };
            obtenerResumen(filtros);
        });
    }
});
