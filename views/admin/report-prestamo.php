<?php require_once 'permisos.php'; ?>

<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <!-- INICIO PERFIL -->

        <!-- FIN PERFIL -->
        <div class="container-fluid">
            <div class="container">
                <!-- Cabecera -->
                <!-- Botón de retroceso -->
                <div class="text-left">
                    <a href="javascript:history.back()" class="btn btn-primary btn-sm d-inline-block" role="button">
                        <i class="fas fa-chevron-left"></i>
                        &nbsp;Volver
                    </a>
                </div>
                <div class="d-grid gap-2 col-12 col-md-6 mx-auto">
                    <!-- Título oculto para pc y laptop -->
                    <div class="d-inline-block d-md-none text-center">
                        <h3 class="title-tablas2">Reportes</h3>
                    </div>
                    <!-- Título oculto para móvil y tablet -->
                    <div class="row mt-3 d-none d-md-inline-block text-center">
                        <h3 class="title-tablas">Módulo de Préstamos</h3>
                    </div>
                </div>
                <!-- Filtro -->
                <div class="row mt-2">
                    <div class="col-md-12">
                        <!-- Inicio Card -->
                        <div class="card">
                            <div class="card-header">Seleccione los filtros que desea aplicar:</div>
                            <!-- Inicio body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row tex-center">
                                            <div class="col">
                                                <select id="libro" class="form-select libro" multiple name="libro" required style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="month" name="fecha" id="fecha" class="form-control" value=" ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin body -->
                            <div class="card-footer text-muted text-center">
                                <button type="button" class="btn btn-danger mt-2" id="btnGenerarPDF">Generar PDF</button>
                            </div>
                        </div>
                        <!-- Fin Card -->
                    </div>
                </div>
                <!-- Datos - tabla -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="prestamo" class="table table-sm table-striped">
                                <colgroup>
                                    <col width="5%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="5%">
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Libro</th>
                                        <th>Usuario</th>
                                        <th>Cantidad</th>
                                        <th>Solicitud</th>
                                        <th>Recojo</th>
                                        <th>Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Asíncrono -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputFecha = document.querySelector('#fecha');
    const selectLibro = document.querySelector('#libro');
    const tablaPrestamo = document.querySelector("#prestamo tbody");
    let filtroPDF = -1;

    function alerta(textoMensaje = "") {
        Swal.fire({
            title: 'Comentarios',
            text: textoMensaje,
            icon: 'info',
            footer: 'Horacio Zeballos Gámez',
            timer: 2500,
            confirmButtonText: 'Aceptar'
        });
    }

    function listarLibros(){
        fetch(`../../controllers/biblioteca.controller.php?operacion=listarLibros`)
            .then(respuesta => respuesta.json())
            .then(datos => {
                datos.forEach(element=> {
                    const optionTag = document.createElement("option");
                    optionTag.value = element.idbook
                    optionTag.text = element.descriptions;
                    selectLibro.appendChild(optionTag);
                });
            });
    }

    function listarPrestamos() {
        const indiceLibro = parseInt(selectLibro.value);
        const indiceLibroSeleccionado = indiceLibro >= 1 ? indiceLibro : "0";
    
        // Obtener el valor del año y del mes
        var valorFecha = inputFecha.value;
        var anio = '';
        var mes = '';

        if (valorFecha) {
            anio = valorFecha.split("-")[0];
            mes = valorFecha.split("-")[1];
        }
        const parametros = new URLSearchParams();
        parametros.append("operacion","reportePrestamo");
        parametros.append("idbook", indiceLibroSeleccionado);
        parametros.append("anio",anio);
        parametros.append("mes",mes);
        console.log(parametros.toString());
        fetch(`../../controllers/prestamo.controller.php?${parametros}`)
        .then(respuesta => respuesta.text())
            .then(datos => {
                let i = 1;
                if(!datos || datos.length === 0){
                    tablaPrestamo.innerHTML = '<tr><td colspan="7">No ha seleccionado ningún libro</td></tr>';
                    filtroPDF = -1;
                }else{
                    registro = JSON.parse(datos);
                    tablaPrestamo.innerHTML=``;
                    filtroPDF = 1;

                    registro.forEach(element => {
                    const tableRow = `
                    <tr>
                        <td class='text-center'>${i++}</td>
                        <td>${element.descriptions}</td>
                        <td>${element.nombre_completo}</td>
                        <td class='text-center'>${element.amount}</td>
                        <td class='text-center'>${element.loan_date}</td>
                        <td class='text-center'>${element.return_date}</td>
                        <td class='text-center'>${element.registrationdate}</td>
                    </tr>
                    `;
                    tablaPrestamo.innerHTML += tableRow;

                    });
                }
            });
    }

    function generarPDF() {
        var valorFecha = inputFecha.value;
        var anio = '';
        var mes = '';

        if (valorFecha) {
            anio = valorFecha.split("-")[0];
            mes = valorFecha.split("-")[1];
        }

        if (selectLibro.value == 0) {
          alerta("Debes elegir un libro para poder crear el PDF");
        } else if (filtroPDF > 0) {
          const parametros = new URLSearchParams();
            parametros.append("idbook", selectLibro.value);
            parametros.append("anio",anio);
            parametros.append("mes",mes);
            parametros.append("titulo", selectLibro.options[selectLibro.selectedIndex].text);

          window.open(`../../reports/report-prestamo/reporte.php?${parametros}`, '_blank');
        } else {
          alerta("No existen datos disponibles para generar el PDF");
        }

    }

    $(selectLibro).on('change', listarPrestamos);
    inputFecha.addEventListener("change", listarPrestamos);
    btnGenerarPDF.addEventListener("click", generarPDF);
    listarLibros();
    listarPrestamos();
    // Select2
    $('.libro').select2({
        maximumSelectionLength: 1,
        placeholder: 'Seleccione: '
    });
</script>