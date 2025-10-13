(function () {
    const mobileMenuBtn = document.querySelector("#mobile-menu")
    const cerrarMenuBtn = document.querySelector("#cerrar-menu")
    const sidebar = document.querySelector(".sidebar")
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar__mostrar')
        })
    }
    if (cerrarMenuBtn) {
        cerrarMenuBtn.addEventListener('click', function () {
            sidebar.classList.add('sidebar__ocultar')
            setTimeout(() => {
                sidebar.classList.remove('sidebar__mostrar')
                sidebar.classList.remove('sidebar__ocultar')
            }, 500);
        })
    }
    window.addEventListener('resize', function() {
        const anchoPantalla = document.body.clientWidth;
        if (anchoPantalla >= 768) {
            sidebar.classList.remove('sidebar__mostrar')
        }
    })
})()