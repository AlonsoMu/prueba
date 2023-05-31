<?php

require_once '../models/Biblioteca.php';
$biblioteca = new Biblioteca();

if(isset($_GET['operacion'])){ 

    //CONTROLADOR VISTA ADMIN

    if($_GET['operacion']=='listarLibros'){
        echo json_encode($biblioteca->listarLibros());
    }

    if($_GET['operacion'] == 'eliminarLibro'){
        $biblioteca->eliminarLibro($_GET['idbook']);
    }
    
    if ($_GET['operacion'] == 'getLibro'){
        echo json_encode($biblioteca->getLibro($_GET['idbook']));   
    }
    
    if($_GET['operacion'] == 'actualizarLibro'){
        $datosSolicitados = [
            "idbook"                => $_GET['idbook'],
            "idcategorie"           => $_GET['idcategorie'],
            "idsubcategorie"        => $_GET['idsubcategorie'],
            "amount"                => $_GET['amount'],
            "descriptions"          => $_GET['descriptions'],
            "author"                => $_GET['author'],
            "state"                 => $_GET['state'],
            "locationresponsible"   => $_GET['locationresponsible']
        ];
        
        $biblioteca->actualizarLibro($datosSolicitados);
    }
    
    if ($_GET['operacion'] == 'getBinarios'){
        echo json_encode($biblioteca->getBinarios($_GET['idbook']));   
    }
    
    //CONTROLADOR VISTA PRINCIPAL
    if($_GET['operacion']=='listarVistaLibros'){
        echo json_encode($biblioteca->listarVistaLibros());
    }

    if ($_GET['operacion'] == 'VistaResumen'){
        echo json_encode($biblioteca->VistaResumen($_GET['idbook']));   
    }

    if ($_GET['operacion'] == 'VistaSubcategoria'){
        echo json_encode($biblioteca->VistaSubcategoria($_GET['idsubcategorie']));   
    }

    if ($_GET['operacion'] == 'Buscarlibros'){
        echo json_encode($biblioteca->Buscarlibros($_GET['type'], $_GET['look']));   
    }

    //CONTROLADOR VISTA ZOCIAL

    if ($_GET['operacion'] == 'listarComentarios'){
        echo json_encode($biblioteca->listarComentarios($_GET['idbook']));
    }

    if($_GET['operacion'] == 'registrarComentario'){

        $datosSolicitados = [
            "idbook"        => $_GET['idbook'],
            "idusers"       => $_GET['idusers'],
            "commentary"    => $_GET['commentary'],
            "score"         => $_GET['score']
        ];
        
        $biblioteca->registrarComentario($datosSolicitados);
    }

    
}

//Operación POST

if (isset($_POST['operacion'])){
    //CONTROLADOR VISTA ADMIN
    if($_POST['operacion'] == 'registrarLibros'){
            
        $datosSolicitados = [
            "idcategorie"           => $_POST['idcategorie'],
            "idsubcategorie"        => $_POST['idsubcategorie'],
            "amount"                => $_POST['amount'],
            "descriptions"          => $_POST['descriptions'],
            "author"                => $_POST['author'],
            "state"                 => $_POST['state'],
            "locationresponsible"   => $_POST['locationresponsible'],
            "url"                   => "",
            "frontpage"             => ""
        ];

        
        //Verificando si el usuario envio el archivo
        if(isset($_FILES['frontpage'])){
            //Carpeta
            $rutaDestino = "../views/frontpage/";

            //Fecha y hora
            $fechaActual = date("c"); // c = complete (fecha + hora)

            //Encriptando fecha y hora
            $nombreArchivo = sha1($fechaActual).".jpg";

            //Ruta final
            $rutaDestino .= $nombreArchivo;

            if (move_uploaded_file($_FILES['frontpage']['tmp_name'], $rutaDestino)){
            // Se logro subir el archivo
            //Acciones por definir
            $datosSolicitados["frontpage"] = $nombreArchivo;
            
            }//fin move_uploaded

        }//fin de isset

        if(isset($_FILES['url'])){
            //Carpeta
            $rutaDestino = "../views/PDF/";

            //Fecha y hora
            $fechaActual = date("c"); // c = complete (fecha + hora)

            //Encriptando fecha y hora
            $nombreArchivo = sha1($fechaActual).".pdf";

            //Ruta final
            $rutaDestino .= $nombreArchivo;

            if (move_uploaded_file($_FILES['url']['tmp_name'], $rutaDestino)){
            // Se logro subir el archivo
            //Acciones por definir
            $datosSolicitados["url"] = $nombreArchivo;
            
            }//fin move_uploaded

        }//fin de isset
        
        $biblioteca->registrarLibros($datosSolicitados);
    }
    
}


?>