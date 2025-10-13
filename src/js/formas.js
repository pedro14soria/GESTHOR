(function () {
    const nuevaFormaBtn = document.querySelector("#agregar-formas");
    let formas = []

    if (nuevaFormaBtn) {
        let modoEditar = false;
        let FormaEditando = null;
        obtenerFormas()
        nuevaFormaBtn.addEventListener('click', function () {
            modoEditar = false;
            FormaEditando = null;
            mostrarModal();
        });

        async function obtenerFormas() {
            try {
                const url = '/api/formas'
                const respuesta = await fetch(url)
                const resultado = await respuesta.json();

                formas = resultado.formas;
                mostrarFormas()
            } catch (error) {
                console.log(error)
            }
        }
        function mostrarFormas() {
            const contenedorFormas = document.querySelector('#listado-formas');
            if (contenedorFormas) {
                if (formas.length === 0) {
                    const textoNoFormas = document.createElement('LI');
                    textoNoFormas.textContent = "No hay Formas de Pago"
                    textoNoFormas.classList.add('formas__no-formas')

                    contenedorFormas.appendChild(textoNoFormas);
                    return;
                }

                limpiarFormas()
                formas.forEach(forma => {
                    const contenedorForma = document.createElement('LI');
                    contenedorForma.dataset.formaId = forma.id;
                    contenedorForma.classList.add('forma')

                    const nombreForma = document.createElement('P')
                    nombreForma.textContent = forma.nombre
                    nombreForma.classList.add('forma__texto')

                    const opcionesDiv = document.createElement('DIV')
                    opcionesDiv.classList.add('forma__opciones')

                    //Botones
                    const btnEditarForma = document.createElement('BUTTON')
                    btnEditarForma.classList.add('forma__opciones__boton--editar', 'forma__opciones__boton')
                    btnEditarForma.dataset.idForma = forma.id
                    btnEditarForma.textContent = 'Editar'
                    btnEditarForma.ondblclick = function () {
                        modoEditar = true;
                        FormaEditando = { ...forma };
                        mostrarModal();
                    }
                    const btnEliminarForma = document.createElement('BUTTON')
                    btnEliminarForma.classList.add('categoria__opciones__boton--eliminar', 'categoria__opciones__boton')
                    btnEliminarForma.dataset.idForma = forma.id
                    btnEliminarForma.textContent = 'Eliminar'
                    btnEliminarForma.ondblclick = function () {
                        confirmarEliminarForma({ ...forma });
                    }

                    opcionesDiv.appendChild(btnEditarForma)
                    opcionesDiv.appendChild(btnEliminarForma)

                    contenedorForma.appendChild(nombreForma)
                    contenedorForma.appendChild(opcionesDiv)

                    contenedorFormas.appendChild(contenedorForma)
                });
            }
        }

        function mostrarModal() {
            const modal = document.querySelector("#modal-forma")
            if (modal) {
                modal.classList.remove("modal--oculto")

                // Reiniciar todos los valores del modal
                const descripcion = document.querySelector(".modal__descripcion")
                const nombre = document.querySelector('#nombre-forma')
                const formaSumbit = document.querySelector("#forma-submit")

                if (modoEditar && FormaEditando) {
                    descripcion.textContent = "Editar Forma de Pago"
                    nombre.value = FormaEditando.nombre
                    formaSumbit.value = "Editar Forma de Pago"
                } else {
                    descripcion.textContent = "Agregar Forma de Pago"
                    nombre.value = ""
                    formaSumbit.value = "Agregar Forma de Pago"
                }
            }
        }

        const cerrarBtn = document.querySelector("#cerrar-modal-forma")
        if (cerrarBtn) {
            cerrarBtn.addEventListener("click", function (e) {
                const modal = document.querySelector("#modal-forma")
                modal.classList.add("modal--oculto")
            })
        }

        const formaSumbit = document.querySelector("#forma-submit")
        if (formaSumbit) {
            formaSumbit.addEventListener('click', function (e) {
                e.preventDefault();
                const formaNombre = document.querySelector("#nombre-forma").value.trim();
                if (formaNombre === '') {
                    mostrarAlerta('El nombre es Obligatorio', 'error', document.querySelector(".modal__form h2"))
                    return;
                }
                if (modoEditar && FormaEditando) {
                    FormaEditando.nombre = formaNombre;
                    actualizarForma(FormaEditando);
                } else {
                    agregarForma(formaNombre);
                }
            });
        }

        async function actualizarForma(forma) {
            const { id, nombre } = forma

            const datos = new FormData();
            datos.append('id', id)
            datos.append('nombre', nombre)

            try {
                const url = "/api/formas/actualizar"

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
                    const modal = document.querySelector("#modal-forma")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    formas = formas.map((formaMemoria) => {
                        if (formaMemoria.id === id) {
                            formaMemoria.nombre = nombre;
                        }

                        return formaMemoria;
                    });
                    mostrarFormas();
                }

            } catch (error) {
                console.log(error)
            }
        }


        async function agregarForma(formaNombre) {
            //Construir la peticion
            const datos = new FormData();
            datos.append('nombre', formaNombre);

            try {
                const url = '/api/formas/forma'
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })

                const resultado = await respuesta.json();
                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".modal__form h2"))

                if (resultado.tipo === "exito") {
                    const modal = document.querySelector("#modal-forma")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    //Agregar el objeto de categoria al global de categorias
                    const formaObj = {
                        id: String(resultado.id),
                        nombre: formaNombre,
                    }

                    formas = [...formas, formaObj]
                    mostrarFormas()
                }

            } catch (error) {
                console.log(error);
            }
        }
        function confirmarEliminarForma(forma) {
            Swal.fire({
                title: "¿Eliminar Forma de Pago?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: 'No'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    eliminarForma(forma);
                }
            });
        }

        async function eliminarForma(forma) {
            const { id, nombre } = forma

            const datos = new FormData();
            datos.append('id', id)
            datos.append('nombre', nombre)

            try {
                const url = '/api/formas/eliminar'
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

                    formas = formas.filter(formasMemoria => formasMemoria.id !== forma.id);
                    mostrarFormas();
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
        function limpiarFormas() {
            const listadoFormas = document.querySelector('#listado-formas')
            if (listadoFormas) {
                while (listadoFormas.firstChild) {
                    listadoFormas.removeChild(listadoFormas.firstChild)
                }
            }
        }
    }
})()