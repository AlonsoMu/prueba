<?php

require_once "../models/Comentario.php";

if (isset($_GET['operacion'])) {
    $comentario = new Comentario();

    if ($_GET['operacion'] == 'listarComentario') {
        echo json_encode($comentario->mostrarCommentaries($_GET['idusers'], $_GET['accesslevel']));
    }
    
    if ($_GET['operacion'] == 'eliminarComentario') {
        $datosSolicitados = [
            "idcomentario"         => $_GET['idcomentario']
        ];
        $dataComentario = $comentario->eliminarComentario($datosSolicitados);

        echo json_encode($dataComentario);
    }

    if ($_GET['operacion'] == 'enviarComentario') {
        $datosSolicitados = [
            "idbook"            => $_GET['idbook'],
            "iduser"            => $_GET['iduser'],
            "commentary"        => $_GET['commentary'],
            "score"             => $_GET['score']
        ];
        $dataComentario = $comentario->enviarComentario($datosSolicitados);

        echo json_encode($dataComentario);
    }


    if ($_GET['operacion'] == 'obtenerComentario') {
        $idComentario = $_GET['idcomentario'];

        $comentarioSeleccionado = $comentario->obtenerComentario($idComentario);

        echo json_encode($comentarioSeleccionado);
    }

    if ($_GET['operacion'] == 'reporteComentario') {
        echo json_encode($comentario->reporteComentario($_GET['idbook'], $_GET['anio'], $_GET['mes'],$_GET['accesslevel']));
    }
}

?>
<?php
