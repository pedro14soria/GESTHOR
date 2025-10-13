(function () {
    const nuevaCatBtn = document.querySelector("#agregar-categoria");
    let categorias = []

    if (nuevaCatBtn) {
        let modoEditar = false;
        let categoriaEditando = null;
        obtenerCategorias()
        nuevaCatBtn.addEventListener('click', function () {
            modoEditar = false;
            categoriaEditando = null;
            mostrarModal();
        });

        async function obtenerCategorias() {
            try {
                const url = '/api/categorias'
                const respuesta = await fetch(url)
                const resultado = await respuesta.json();

                categorias = resultado.categorias;
                mostrarCategorias()
            } catch (error) {
                console.log(error)
            }
        }
        function mostrarCategorias() {
            const contenedorCategorias = document.querySelector('#listado-categorias');
            if (contenedorCategorias) {
                if (categorias.length === 0) {
                    const textoNoCategorias = document.createElement('LI');
                    textoNoCategorias.textContent = "No hay Categorias"
                    textoNoCategorias.classList.add('categorias__no-categorias')

                    contenedorCategorias.appendChild(textoNoCategorias);
                    return;
                }

                limpiarCategorias()
                categorias.forEach(categoria => {
                    const contenedorCategoria = document.createElement('LI');
                    contenedorCategoria.dataset.categoriaId = categoria.id;
                    contenedorCategoria.classList.add('categoria')

                    const nombreCategoria = document.createElement('P')
                    nombreCategoria.textContent = categoria.nombre
                    nombreCategoria.classList.add('categoria__texto')

                    const opcionesDiv = document.createElement('DIV')
                    opcionesDiv.classList.add('categoria__opciones')

                    //Botones
                    const btnEditarCat = document.createElement('BUTTON')
                    btnEditarCat.classList.add('categoria__opciones__boton--editar', 'categoria__opciones__boton')
                    btnEditarCat.dataset.idCategoria = categoria.id
                    btnEditarCat.textContent = 'Editar'
                    btnEditarCat.ondblclick = function () {
                        modoEditar = true;
                        categoriaEditando = { ...categoria };
                        mostrarModal();
                    }
                    const btnEliminarCat = document.createElement('BUTTON')
                    btnEliminarCat.classList.add('categoria__opciones__boton--eliminar', 'categoria__opciones__boton')
                    btnEliminarCat.dataset.idCategoria = categoria.id
                    btnEliminarCat.textContent = 'Eliminar'
                    btnEliminarCat.ondblclick = function () {
                        confirmarEliminarCat({ ...categoria });
                    }

                    opcionesDiv.appendChild(btnEditarCat)
                    opcionesDiv.appendChild(btnEliminarCat)

                    contenedorCategoria.appendChild(nombreCategoria)
                    contenedorCategoria.appendChild(opcionesDiv)

                    contenedorCategorias.appendChild(contenedorCategoria)
                });
            }
        }

        function mostrarModal() {
            const modal = document.querySelector("#modal-categoria")
            if (modal) {
                modal.classList.remove("modal--oculto")

                // Reiniciar todos los valores del modal
                const descripcion = document.querySelector(".modal__descripcion")
                const nombre = document.querySelector('#nombre-categoria')
                const categoriaSumbit = document.querySelector("#categoria-submit")

                if (modoEditar && categoriaEditando) {
                    descripcion.textContent = "Editar Categoria"
                    nombre.value = categoriaEditando.nombre
                    categoriaSumbit.value = "Editar Categoria"
                } else {
                    descripcion.textContent = "Agregar Categoria"
                    nombre.value = ""
                    categoriaSumbit.value = "Agregar Categoria"
                }
            }
        }

        const cerrarBtn = document.querySelector("#cerrar-modal-categoria")
        if (cerrarBtn) {
            cerrarBtn.addEventListener("click", function (e) {
                const modal = document.querySelector("#modal-categoria")
                modal.classList.add("modal--oculto")
            })
        }

        const categoriaSumbit = document.querySelector("#categoria-submit")
        if (categoriaSumbit) {
            categoriaSumbit.addEventListener('click', function (e) {
                e.preventDefault();
                const categoriaNombre = document.querySelector("#nombre-categoria").value.trim();
                if (categoriaNombre === '') {
                    mostrarAlerta('El nombre es Obligatorio', 'error', document.querySelector(".modal__form h2"))
                    return;
                }
                if (modoEditar && categoriaEditando) {
                    categoriaEditando.nombre = categoriaNombre;
                    actualizarCategoria(categoriaEditando);
                } else {
                    agregarCategoria(categoriaNombre);
                }
            });
        }

        async function actualizarCategoria(cat) {
            const { id, nombre } = cat

            const datos = new FormData();
            datos.append('id', id)
            datos.append('nombre', nombre)

            try {
                const url = "/api/categorias/actualizar"

                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })
                const resultado = await respuesta.json()
                if (resultado.respuesta.tipo === "exito") {
                    Swal.fire(
                        resultado.respuesta.mensaje,
                        resultado.respuesta.mensaje,
                        'success'
                    )
                    const modal = document.querySelector("#modal-categoria")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    categorias = categorias.map((categoriaMemoria) => {
                        if (categoriaMemoria.id === id) {
                            categoriaMemoria.nombre = nombre;
                        }

                        return categoriaMemoria;
                    });
                    mostrarCategorias();
                }

            } catch (error) {
                console.log(error)
            }
        }


        async function agregarCategoria(categoriaNombre) {
            //Construir la peticion
            const datos = new FormData();
            datos.append('nombre', categoriaNombre);

            try {
                const url = '/api/categorias/categoria'
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })

                const resultado = await respuesta.json();
                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".modal__form h2"))

                if (resultado.tipo === "exito") {
                    const modal = document.querySelector("#modal-categoria")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    //Agregar el objeto de categoria al global de categorias
                    const catObj = {
                        id: String(resultado.id),
                        nombre: categoriaNombre,
                    }

                    categorias = [...categorias, catObj]
                    mostrarCategorias()
                }

            } catch (error) {
                console.log(error);
            }
        }
        function confirmarEliminarCat(cat) {
            Swal.fire({
                title: "¿Eliminar Categoria?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: 'No'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    eliminarCat(cat);
                }
            });
        }

        async function eliminarCat(cat) {
            const { id, nombre } = cat

            const datos = new FormData();
            datos.append('id', id)
            datos.append('nombre', nombre)

            try {
                const url = '/api/categorias/eliminar'
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })

                const resultado = await respuesta.json()
                if (resultado.resultado) {
                    Swal.fire(
                        'Eliminado!',
                        resultado.mensaje,
                        'success'
                    )

                    categorias = categorias.filter(categoriasMemoria => categoriasMemoria.id !== cat.id);
                    mostrarCategorias();
                }
            } catch (error) {
                console.log(error)
            }
        }

        function mostrarAlerta(mensaje, tipo, referencia) {
            const alertaPrevia = document.querySelector('.alerta')
            if (alertaPrevia) {
                alertaPrevia.remove()
            }

            const alerta = document.createElement('DIV')
            alerta.classList.add('alerta__' + tipo, 'alerta')
            alerta.textContent = mensaje
            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling)

            setTimeout(() => {
                alerta.remove()
            }, 5000);
        }
        
        function limpiarCategorias() {
            const listadoCategorias = document.querySelector('#listado-categorias')
            if (listadoCategorias) {
                while (listadoCategorias.firstChild) {
                    listadoCategorias.removeChild(listadoCategorias.firstChild)
                }
            }
        }
    }
})()