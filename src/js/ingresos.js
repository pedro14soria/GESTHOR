(function () {
    const nuevoIngresoBtn = document.querySelector('#agregar-ingreso');
    let ingresos = []
    let filtrados = []
    if (nuevoIngresoBtn) {
        let modoEditar = false;
        let ingresoEditando = null;
        let filtro =  false;
        
        const filtroForm = document.getElementById('filtro-form');
        if (filtroForm) {
            document.getElementById('aplicar-filtros').addEventListener('click', function() {
                filtrados = []
                select = document.getElementById('forma-pago')
                const filtros = {
                    fecha_inicio: document.getElementById('fecha-inicio').value,
                    fecha_fin: document.getElementById('fecha-fin').value,
                    forma_pago_id: select.options[select.selectedIndex].textContent
                };
                filtrarIngresos(filtros);
            });
        }

        obtenerIngresos()
        nuevoIngresoBtn.addEventListener('click', function () {
            modoEditar = false
            ingresoEditando = null
            mostrarModal();
        })

        function filtrarIngresos(filtros) {
            const {fecha_inicio, fecha_fin, forma_pago_id} = filtros
            filtro = true
            filtrados = ingresos.filter(ingreso => {
                cumpleFecha = true
                cumpleForma = true
                if (fecha_inicio !== '' && fecha_fin !== '') {
                    cumpleFecha = ingreso.fecha >= fecha_inicio && ingreso.fecha <= fecha_fin
                }
                if (forma_pago_id !== '') {
                    cumpleForma = String(ingreso.forma) === String(forma_pago_id)
                }
                return cumpleFecha && cumpleForma
            })
            mostrarIngresos(filtro);
        }

        async function obtenerIngresos() {
            try {
                const url = '/api/ingresos';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json()

                ingresos = resultado.ingresos
                mostrarIngresos()
            } catch (error) {
                console.log(error)
            }
        }

        function mostrarIngresos(filtro) {
            const contenedorIngresos = document.querySelector('#listado-ingresos')
            contenedorIngresos.innerHTML = '';
            const arrayIngresos = filtro ? filtrados : ingresos;
            if (!ingresos || ingresos.length === 0) {
                const textoNoIngresos = document.createElement('P');
                textoNoIngresos.textContent = 'No Hay Ingresos Registrados'
                textoNoIngresos.classList.add('ingresos__no-ingresos')
                contenedorIngresos.appendChild(textoNoIngresos)
                return
            }

            const tabla = document.createElement('TABLE')
            tabla.classList.add('tabla');

            const thead = document.createElement('THEAD')
            thead.classList.add('tabla__head')
            thead.innerHTML = `
                <tr class='tabla__tr'>
                    <th class='tabla__th'>Descripción</th>
                    <th class='tabla__th'>Monto</th>
                    <th class='tabla__th'>Fecha</th>
                    <th class='tabla__th'>Forma de Pago</th>
                    <th class='tabla__th'>Acciones</th>
                </tr>
            `
            tabla.appendChild(thead)
            const tbody = document.createElement('TBODY')
            tbody.classList.add('tabla__body')
            arrayIngresos.forEach(ingreso => {
                const fila = document.createElement('TR');
                fila.classList.add('tabla__tr')
                fila.innerHTML = `
                    <td class='tabla__td'>${ingreso.descripcion}</td>
                    <td class='tabla__td'>${ingreso.monto}</td>
                    <td class='tabla__td'>${ingreso.fecha}</td>
                    <td class='tabla__td'>${ingreso.forma}</td>
                    <td class='tabla__td--acciones'>
                        <button class="tabla__accion tabla__accion--editar" data-id="${ingreso.id}" data-descripcion="${ingreso.descripcion}">Editar</button>
                        <button class="tabla__accion tabla__accion--eliminar" data-id="${ingreso.id}" >Eliminar</button>
                    </td>
                `
                tbody.appendChild(fila)
            });
            tabla.appendChild(tbody)
            contenedorIngresos.appendChild(tabla)

            tabla.addEventListener('click', function (e) {
                if (e.target.classList.contains('tabla__accion--eliminar')) {
                    const id = e.target.dataset.id;
                    confirmarEliminarIngreso(id)
                }
                if (e.target.classList.contains('tabla__accion--editar')) {
                    modoEditar = true;
                    const id = e.target.dataset.id;
                    ingresoEditando = ingresos.find(e => String(e.id) === String(id));
                    if (!ingresoEditando) {
                        window.location.reload();
                        return;
                    }
                    mostrarModal();
                }
            })
        }
        
        function mostrarModal() {
            const modal = document.querySelector("#modal-ingresos")
            if (modal) {
                modal.classList.remove("modal--oculto")

                //Reiniciar todos los valores del modal
                const descripcion = document.querySelector(".modal__descripcion")
                const nombre = document.querySelector('#descripcion-ingreso')
                const monto = document.querySelector('#monto-ingreso')
                const fecha = document.querySelector('#fecha-ingreso')
                const forma = document.querySelector('#forma-ingreso')
                const ingresoSubmit = document.querySelector("#ingreso-submit")
                
                if (modoEditar && ingresoEditando) {
                    descripcion.textContent = "Editar Ingreso"
                    nombre.value = ingresoEditando.descripcion
                    monto.value = ingresoEditando.monto
                    fecha.value = ingresoEditando.fecha
                    // Buscar el índice del option cuyo value coincide con el valor guardado
                    for (var j = 0; j < forma.options.length; j++) {
                        if (forma.options[j].textContent == ingresoEditando.forma) {
                            forma.selectedIndex = j;
                            break;
                        }
                    }
                    ingresoSubmit.value = "Editar Ingreso"
                } else {
                    descripcion.textContent = "Agregar Ingreso"
                    nombre.value = ""
                    monto.value = ""
                    fecha.value = ""
                    forma.selectedIndex = 0
                    ingresoSubmit.value = "Agregar Ingreso"
                }
            }
        }
        const cerrarBtn = document.querySelector("#cerrar-modal-ingresos")
        if (cerrarBtn) {
            cerrarBtn.addEventListener("click", function (e) {
                const modal = document.querySelector("#modal-ingresos")
                modal.classList.add("modal--oculto")
            })
        }

        const ingresoSubmit = document.querySelector("#ingreso-submit")
        if (ingresoSubmit) {
            ingresoSubmit.addEventListener('click', function (e) {
                e.preventDefault();
                validarIngreso();
            })
        }

        function validarIngreso() {
            const monto = document.querySelector("#monto-ingreso").value.trim();
            const descripcion = document.querySelector("#descripcion-ingreso").value.trim();
            const fecha = document.querySelector("#fecha-ingreso").value.trim();
            const forma = document.querySelector("#forma-ingreso").value.trim();

            
            let errores = [];
            (monto === "" || isNaN(monto) || Number(monto) <= 0) && errores.push("Ingresa correctamente el monto");
            descripcion === "" && errores.push("La descripción es obligatoria");
            fecha === "" && errores.push("La fecha es obligatoria");
            forma === "" && errores.push("La forma de pago es obligatoria");

            if (errores.length > 0) {
                mostrarAlerta(errores, "error", document.querySelector(".modal__form h2"));
                return;
            }
            if (modoEditar && ingresoEditando) {
                ingresoEditando.descripcion = descripcion;
                ingresoEditando.monto = monto;
                ingresoEditando.fecha = fecha;
                ingresoEditando.forma = forma;
                actualizarIngreso(ingresoEditando);
            } else {
                agregarIngreso(monto, descripcion, fecha, forma);
            }
        }

        async function actualizarIngreso(ingreso) {
            const {id, descripcion, monto, fecha, forma} = ingreso;
            const datos = new FormData();
            datos.append('id', id);
            datos.append('descripcion', descripcion);
            datos.append('fecha', fecha);
            datos.append('monto', monto);
            datos.append('forma_pago_id', forma);

            try {
                const url = '/api/ingresos/actualizar'

                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })
                const resultado = await respuesta.json()
                mostrarAlerta(resultado.mensajes, resultado.tipo, document.querySelector(".modal__form h2"))
                
                if (resultado.tipo === 'exito') {
                    Swal.fire(
                        'Actuliazado!!',
                        resultado.mensajes,
                        'success'
                    )
                    const modal = document.querySelector("#modal-ingresos")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    obtenerIngresos()
                    mostrarIngresos()
                }
            } catch (error) {
                console.log(error)
            }
        }


        async function agregarIngreso(monto, descripcion, fecha, forma) {
            const datos = new FormData();
            datos.append('descripcion', descripcion)
            datos.append('monto', monto)
            datos.append('fecha', fecha)    
            datos.append('forma_pago_id', forma)
            
            try {
                const url = '/api/ingresos/ingreso'
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })
                const resultado = await respuesta.json();
                mostrarAlerta(resultado.mensajes, resultado.tipo, document.querySelector(".modal__form h2"))
                
                if (resultado.tipo === "exito") {
                    const modal = document.querySelector("#modal-ingresos")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    //Agregar el objeto de categoria al global de categorias
                    const ingresoObj = {
                        id: String(resultado.id),
                        descripcion: descripcion,
                        monto: monto,
                        fecha: fecha,
                        forma: forma
                    }

                    ingresos = [ingresoObj, ...ingresos]
                    obtenerIngresos()
                    mostrarIngresos()
                }
            } catch (error) {
                console.log(error);
            }

        }

        function mostrarAlerta(mensajes, tipo, referencia) {
            // Elimina alertas previas
            const alertaPrevia = document.querySelectorAll('.alerta');
            alertaPrevia.forEach(alerta => alerta.remove());

            // Muestra cada mensaje en un div separado
            if (!Array.isArray(mensajes)) mensajes = [mensajes];
            mensajes.forEach(msg => {
                const alerta = document.createElement("DIV");
                alerta.classList.add("alerta__" + tipo, "alerta");
                alerta.textContent = msg;
                referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
                setTimeout(() => {
                    alerta.remove();
                }, 3000);
            });
        }

        function confirmarEliminarIngreso(idIngreso) {
            Swal.fire({
                title: "¿Eliminar Ingreso?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: 'No'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    eliminarEgreso(idIngreso);
                }
            });
        }

        async function eliminarEgreso(idEgreso) {
            const datos = new FormData()
            datos.append('id', idEgreso)

            try {
                const url = '/api/ingresos/eliminar'
                const respuesta = await fetch(url, {
                    method: 'post',
                    body: datos
                })
                
                const resultado = await respuesta.json()
                
                if (resultado.resultado) {
                    Swal.fire(
                        'Elimnado!',
                        resultado.mensaje,
                        'success'
                    )
                    obtenerIngresos()
                    mostrarIngresos()
                }
            } catch (error) {
                console.log(error);
            }
        }
    }
})()