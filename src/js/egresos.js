(function () {
    const nuevoEgresoBtn = document.querySelector('#agregar-egreso');
    let egresos = []
    let filtrados = []

    if (nuevoEgresoBtn) {
        let modoEditar = false;
        let egresoEditando = null;
        let filtro = false
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
                filtrarEgresos(filtros);
            });
        }
        obtenerEgresos()

        nuevoEgresoBtn.addEventListener('click', function () {
            modoEditar = false
            egresoEditando = null
            mostrarModal();
        })

        function filtrarEgresos(filtros) {
            const {fecha_inicio, fecha_fin, forma_pago_id} = filtros
            filtro = true
            filtrados = egresos.filter(egreso => {
                cumpleFecha = true
                cumpleForma = true
                if (fecha_inicio !== '' && fecha_fin !== '') {
                    cumpleFecha = egreso.fecha >= fecha_inicio && egreso.fecha <= fecha_fin
                }
                if (forma_pago_id !== '') {
                    cumpleForma = String(egreso.forma) === String(forma_pago_id)
                }
                return cumpleFecha && cumpleForma
            })
            mostrarEgresos(filtro);
        }

        async function obtenerEgresos() {
            try {
                const url = '/api/egresos';
                const respuesta = await fetch(url);
                const resultado = await respuesta.json()

                egresos = resultado.egresos
                mostrarEgresos()
            } catch (error) {
                console.log(error)
            }
        }

        function mostrarEgresos(filtro) {
            const contenedorEgresos = document.querySelector('#listado-egresos')
            contenedorEgresos.innerHTML = '';
            const arrayEgresos = filtro ? filtrados : egresos;

            if (!egresos || egresos.length === 0) {
                const textoNoEgresos = document.createElement('P');
                textoNoEgresos.textContent = 'No Hay Egresos Registrados'
                textoNoEgresos.classList.add('egresos__no-egresos')
                contenedorEgresos.appendChild(textoNoEgresos)
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
                    <th class='tabla__th'>Categoría</th>
                    <th class='tabla__th'>Forma de Pago</th>
                    <th class='tabla__th'>Acciones</th>
                </tr>
            `
            tabla.appendChild(thead)
            const tbody = document.createElement('TBODY')
            tbody.classList.add('tabla__body')
            arrayEgresos.forEach(egreso => {
                const fila = document.createElement('TR');
                fila.classList.add('tabla__tr')
                fila.innerHTML = `
                    <td class='tabla__td'>${egreso.descripcion}</td>
                    <td class='tabla__td'>${egreso.monto}</td>
                    <td class='tabla__td'>${egreso.fecha}</td>
                    <td class='tabla__td'>${egreso.categoria}</td>
                    <td class='tabla__td'>${egreso.forma}</td>
                    <td class='tabla__td--acciones'>
                        <button class="tabla__accion tabla__accion--editar" data-id="${egreso.id}" data-descripcion="${egreso.descripcion}">Editar</button>
                        <button class="tabla__accion tabla__accion--eliminar" data-id="${egreso.id}" >Eliminar</button>
                    </td>
                `
                tbody.appendChild(fila)
            });
            tabla.appendChild(tbody)
            contenedorEgresos.appendChild(tabla)

            tabla.addEventListener('click', function (e) {
                if (e.target.classList.contains('tabla__accion--eliminar')) {
                    const id = e.target.dataset.id;
                    confirmarEliminarEgreso(id)
                }
                if (e.target.classList.contains('tabla__accion--editar')) {
                    modoEditar = true;
                    const id = e.target.dataset.id;
                    egresoEditando = egresos.find(e => String(e.id) === String(id));
                    if (!egresoEditando) {
                        window.location.reload();
                        return;
                    }
                    mostrarModal();
                }
            })
        }
        
        function mostrarModal() {
            const modal = document.querySelector("#modal-egresos")
            if (modal) {
                modal.classList.remove("modal--oculto")

                //Reiniciar todos los valores del modal
                const descripcion = document.querySelector(".modal__descripcion")
                const nombre = document.querySelector('#descripcion-egreso')
                const monto = document.querySelector('#monto-egreso')
                const fecha = document.querySelector('#fecha-egreso')
                const categoria = document.querySelector('#categoria-egreso')
                const forma = document.querySelector('#forma-egreso')
                const egresoSubmit = document.querySelector("#egreso-submit")
                
                if (modoEditar && egresoEditando) {
                    descripcion.textContent = "Editar Egreso"
                    nombre.value = egresoEditando.descripcion
                    monto.value = egresoEditando.monto
                    fecha.value = egresoEditando.fecha
                    // Buscar el índice del option cuyo value coincide con el valor guardado
                    for (var i = 0; i < categoria.options.length; i++) {
                        if (categoria.options[i].textContent == egresoEditando.categoria) {
                            categoria.selectedIndex = i;
                            break;
                        }
                    }
                    for (var j = 0; j < forma.options.length; j++) {
                        if (forma.options[j].textContent == egresoEditando.forma) {
                            forma.selectedIndex = j;
                            break;
                        }
                    }
                    egresoSubmit.value = "Editar Egreso"
                } else {
                    descripcion.textContent = "Agregar Egreso"
                    nombre.value = ""
                    monto.value = ""
                    fecha.value = ""
                    forma.selectedIndex = 0
                    categoria.selectedIndex = 0
                    egresoSubmit.value = "Agregar Egreso"
                }
            }
        }
        const cerrarBtn = document.querySelector("#cerrar-modal-egresos")
        if (cerrarBtn) {
            cerrarBtn.addEventListener("click", function (e) {
                const modal = document.querySelector("#modal-egresos")
                modal.classList.add("modal--oculto")
            })
        }

        const egresoSubmit = document.querySelector("#egreso-submit")
        if (egresoSubmit) {
            egresoSubmit.addEventListener('click', function (e) {
                e.preventDefault();
                validarEgreso();
            })
        }

        function validarEgreso() {
            const monto = document.querySelector("#monto-egreso").value.trim();
            const descripcion = document.querySelector("#descripcion-egreso").value.trim();
            const fecha = document.querySelector("#fecha-egreso").value.trim();
            const categoria = document.querySelector("#categoria-egreso").value.trim();
            const forma = document.querySelector("#forma-egreso").value.trim();

            //console.log(categoria)
            
            let errores = [];
            (monto === "" || isNaN(monto) || Number(monto) <= 0) && errores.push("Ingresa correctamente el monto");
            descripcion === "" && errores.push("La descripción es obligatoria");
            fecha === "" && errores.push("La fecha es obligatoria");
            categoria === "" && errores.push("La categoría es obligatoria");
            forma === "" && errores.push("La forma de pago es obligatoria");

            if (errores.length > 0) {
                mostrarAlerta(errores, "error", document.querySelector(".modal__form h2"));
                return;
            }
            if (modoEditar && egresoEditando) {
                egresoEditando.descripcion = descripcion;
                egresoEditando.monto = monto;
                egresoEditando.fecha = fecha;
                egresoEditando.categoria = categoria;
                egresoEditando.forma = forma;
                actualizarEgreso(egresoEditando);
            } else {
                agregarEgreso(monto, descripcion, fecha, categoria, forma);
            }
        }

        async function actualizarEgreso(egreso) {
            const {id, descripcion, monto, fecha, categoria, forma} = egreso;
            const datos = new FormData();
            datos.append('id', id);
            datos.append('descripcion', descripcion);
            datos.append('fecha', fecha);
            datos.append('monto', monto);
            datos.append('categoria_id', categoria);
            datos.append('forma_pago_id', forma);

            try {
                const url = '/api/egresos/actualizar'

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
                    const modal = document.querySelector("#modal-egresos")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    obtenerEgresos()
                    mostrarEgresos()
                }
            } catch (error) {
                console.log(error)
            }
        }


        async function agregarEgreso(monto, descripcion, fecha, categoria, forma) {
            const datos = new FormData();
            datos.append('descripcion', descripcion)
            datos.append('monto', monto)
            datos.append('fecha', fecha)
            datos.append('categoria_id', categoria)     
            datos.append('forma_pago_id', forma)
            
            try {
                const url = '/api/egresos/egreso'
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                })
                const resultado = await respuesta.json();
                mostrarAlerta(resultado.mensajes, resultado.tipo, document.querySelector(".modal__form h2"))
                
                if (resultado.tipo === "exito") {
                    const modal = document.querySelector("#modal-egresos")
                    setTimeout(() => {
                        modal.classList.add("modal--oculto")
                    }, 1000);

                    //Agregar el objeto de categoria al global de categorias
                    const egresoObj = {
                        id: String(resultado.id),
                        descripcion: descripcion,
                        monto: monto,
                        fecha: fecha,
                        categoria: categoria,
                        forma: forma
                    }

                    egresos = [egresoObj, ...egresos]
                    obtenerEgresos()
                    mostrarEgresos()
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

        function confirmarEliminarEgreso(idEgreso) {
            Swal.fire({
                title: "¿Eliminar Egreso?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: 'No'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    eliminarEgreso(idEgreso);
                }
            });
        }

        async function eliminarEgreso(idEgreso) {
            const datos = new FormData()
            datos.append('id', idEgreso)

            try {
                const url = '/api/egresos/eliminar'
                const respuesta = await fetch(url, {
                    method: 'post',
                    body: datos
                })
                
                const resultado = await respuesta.json()
                console.log(resultado, 'aca esta el resultado')
                if (resultado.resultado) {
                    Swal.fire(
                        'Elimnado!',
                        resultado.mensaje,
                        'success'
                    )
                    obtenerEgresos()
                    mostrarEgresos()
                }
            } catch (error) {
                console.log(error);
            }
        }
    }
})()