<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/Navbar-Right-Links-Dark-icons.css?h=6374f842801eca4c964d319ee1808973">
    <link rel="stylesheet" href="../assets/css/Sidebar-navbar.css?h=dbde5f7cd08c3af294ce34870a0e649f">
    <link rel="stylesheet" href="../assets/css/Sidebar.css?h=221a6cfc6c7eea8872b679d3e5f73dc4">
    <!-- LightBox -->
    <link rel="stylesheet" href="../vendor/lightbox/css/lightbox.min.css">
    <link rel="shortcut icon" href="../assets/img/favicon.ico" />
</head>

<body>
    <!-- navbar -->
    <?php include './navbar.php'; ?>

    <div class="container" style="margin-top: 48px;padding-right: 0px;padding-left: 0px;margin-bottom: 53px;">
        <!-- Resumen de los libros -->
        <div class="row col-xl-12 resumen">
            <a href="./index.php"><i class="fa-solid fa-arrow-left"></i>Volver</a>
        </div>
    </div>
    <hr>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header  text-center">
                        <h3>Comentarios</h3>
                    </div>
                    <div class="datos">

                    </div>

                    <!-- Agregar comentarios -->
                    <div class="card-footer text-muted" id="Comentario">
                        <form action="" autocomplete="off" id="form-comentario">
                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label for="comentario">Escribe un comentario:</label>
                                    <textarea class="form-control" name="" id="comentario"></textarea>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Puntuación</label>
                                    <div id="stars">
                                        <i class="far fa-star" id="star" data-rating="1"></i>
                                        <i class="far fa-star" id="star" data-rating="2"></i>
                                        <i class="far fa-star" id="star" data-rating="3"></i>
                                        <i class="far fa-star" id="star" data-rating="4"></i>
                                        <i class="far fa-star" id="star" data-rating="5"></i>
                                        <input type="hidden" name="rating" id="rating">
                                    </div>
                                    <p class="text-muted" id="rating-text"></p>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="enviar" class="btn btn-primary mt-5">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- FOOTER -->
    <footer class="text-white bg-dark">
        <div class="container text-center py-4 py-lg-5">

            <p class="text-white-50 mb-0">Copyright © 2023 By IA TECH</p>
        </div>
    </footer>


    <a id="scroll-top" href="#" class="btn btn-primary btn-scroll-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- JQUERY -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- BOOTSTRAP-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
    <!-- FONTWASOME -->
    <script src="https://kit.fontawesome.com/9b57fc34f2.js" crossorigin="anonymous"></script>
    <!-- Lightbox -->
    <script src="../vendor/lightbox/js/lightbox.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mis funciones y eventos javascript -->
    <script>
        $(document).ready(function() {

            idusuario = <?php echo $idusers ?>;
            idbook2 = <?php echo $_GET["resumen"]; ?>;

            $(window).scroll(function() {
                if ($(this).scrollTop() > 200) {
                    $('#scroll-top').addClass('active');
                } else {
                    $('#scroll-top').removeClass('active');
                }
            });

            $('#scroll-top').click(function(event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            });



            const url = new URL(window.location.href);
            const idlibro = url.searchParams.get("resumen");
            let estrellas = 0;

            function alertar(textoMensaje = "", icono = "") {
                Swal.fire({
                    title: 'Comentarios',
                    text: textoMensaje,
                    icon: icono,
                    footer: 'Horacio Zeballos Gámez',
                    timer: 900,
                    confirmButtonText: 'Aceptar'
                });
            }

            // Verificar si idusuario es igual a -1
            if (idusuario == -1) {
                // Remplazar el contenido del formulario
                var form = document.getElementById('form-comentario');
                form.innerHTML = `
                    <p class='text-center'>Debes iniciar sesion para registrar un comentario.</p>
                    <a href="./login.php" class="btn btn-primary d-flex justify-content-center align-items-center mx-auto w-25" role="button">Click Aquí</a>
                `;
            }


            $(document).on('click', '#star', function(event) {
                var dataID = $(this).data('rating')
                estrellas = dataID;
            })

            function enviarComenatrio() {
                let comentario = $("#comentario").val()
                const stars = document.querySelectorAll('#stars i');
                if (comentario == "") {
                    alertar("El comentario no puede estar vacio", "warning");
                    stars.forEach(star => star.classList.remove('fas'));
                    stars.forEach(star => star.classList.add('far'));
                    $("#rating-text").text("");
                    estrellas = 0
                } else {

                    $.ajax({
                        url: '../controllers/comentario.controller.php',
                        type: 'GET',
                        data: {
                            'operacion': 'enviarComentario',
                            'idbook': idlibro,
                            'iduser': idusuario,
                            'commentary': comentario,
                            'score': estrellas
                        },
                        success: function(result) {
                            alertar("Tu comentario fue registrado exitosamente", "success");
                            $("#form-comentario")[0].reset();
                            stars.forEach(star => star.classList.remove('fas'));
                            stars.forEach(star => star.classList.add('far'));
                            $("#rating-text").text("");
                            estrellas = 0
                            listarComentarios()
                        }
                    })
                }
            }

            $("#enviar").click(enviarComenatrio);


            function VistaResumen() {
                $.ajax({
                    url: '../controllers/biblioteca.controller.php',
                    type: 'GET',
                    data: {
                        'operacion': 'VistaResumen',
                        'idbook': idbook2
                    },
                    success: function(result) {
                        let registros = JSON.parse(result);
                        let nuevaFila = `
          <div class="row">
            <div class="col-md-6 col-sm-12 p-1" style="margin-right: 10px; margin-bottom: 10px;">
              <h5 class="text-center">${registros['descriptions']}</h5>
              <div class="text-center">
                <img src="frontpage/${registros['frontpage'] || 'noimagen.png'}" width="293" height="452">
              </div>
            </div>
            <div class="col-md-5 col-sm-12 p-1" style="margin-left: 10px; margin-bottom: 10px;">
              <p style="margin-top: 40px;margin-bottom: 0px;"> Autor: ${registros['author']}</p>
              <p>Libros disponibles: ${registros['amount']}</p>
              <p class="text-justify" style="margin-bottom: 61px;margin-top: 30px;">
                <span style="color: rgb(34, 34, 34);">${registros['summary'] || 'Resumen no disponible'}</span>
              </p>
              <div class="text-center">
                <div class="btn-group" role="group">
                  <a href="PDF/${registros['url'] || 'sin-pdf.png'}" download="${registros['descriptions']}.pdf" class="btn btn-warning mr-3" type="button">Descargar <i class="fa-solid fa-download"></i></a>
                  <a href='./prestamos.view.php?prestamo=${registros['idbook']}' class="btn btn-primary prestamos"  type="button">Prestamo <i class="fa-solid fa-book-open"></i></a>
                </div>
              </div>
            </div>
          </div>
        `;

                        $(".resumen").append(nuevaFila);

                        // Verificar si el idusuario es igual a -1
                        if (idusuario === -1) {
                            $(".prestamos").attr("href", "login.php");
                        }

                        // Verificar si el stock es igual a 0
                        if (registros['amount'] == 0) {
                            Swal.fire({
                                title: 'No hay libros disponibles',
                                text: 'Lo sentimos, actualmente no hay libros disponibles de este título.',
                                icon: 'info',
                                footer: '<strong>Horacio Zeballos Gámez</strong>',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            }

            function listarComentarios() {
                $.ajax({
                    url: '../controllers/biblioteca.controller.php',
                    type: 'GET',
                    data: {
                        'operacion': 'listarComentarios',
                        'idbook': idbook2
                    },
                    success: function(result) {
                        let registros = JSON.parse(result);
                        $(".datos").html("");
                        let nuevaFila2 = ``;

                        registros.forEach(registro => {
                            let comentario = registro['commentary'];
                            let comentarioPreview = comentario.length > 100 ? comentario.substring(0, 100) + '...' : comentario;
                            let estrellasHTML = generarEstrellasHTML(registro['score']);

                            nuevaFila2 = `
                    <div class="card-body" style="border-color: black">
                        <h5 class="card-title">${registro['Usuario']}</h5>
                        <p class="card-text">${comentarioPreview}</p>
                        <div class="estrellas">${estrellasHTML}</div>
                        <p class="card-text">${registro['commentary_date']}</p>
                    </div>
                    <hr style=" border: none; height: 0.5px; background-color: #a6d6e3; margin: 10px 0;">
                `;
                            $(".datos").append(nuevaFila2);
                        });
                    }
                });
            }

            function generarEstrellasHTML(score) {
                // Inicializamos la variable
                // La variable estrellasHTML es una cadena que contiene codigo HTML para la clasificacion
                let estrellasHTML = '';
                // Recorre en bucle los numero 1a5
                for (let i = 1; i <= 5; i++) {
                    // Si el numero actual es menor o igual a la puntuacion cambia a amarillo
                    if (i <= score) {
                        estrellasHTML += '<i class="fas fa-star" style="color: #ffc800;"></i>';
                        // Caso contrario se cambia a otro color
                    } else {
                        estrellasHTML += '<i class="fas fa-star" style="color: #a29e90;"></i>';
                    }
                }
                return estrellasHTML;
            }



            $('#stars i').click(function() {
                var rating = $(this).data('rating');
                $('#rating').val(rating);
                $('#stars i').removeClass('fas').addClass('far');
                $(this).prevAll().addBack().removeClass('far').addClass('fas');
                $('#rating-text').text(rating + ' estrella(s).');
            });



            //Funciones de carga automatica
            VistaResumen();
            listarComentarios();
        });
    </script>
</body>

</html>