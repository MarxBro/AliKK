<?php

include ('verif.inc');

//Los mails incluyen la hora
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Comprobar que viene de Facebook y no de la "web"
$ip_visitante = getRealIpAddr();

// SOLO PARA DEBUGGEAR
// print_r($_POST);

if(isset($_POST["formSubmit"])){
    if(strcmp( $_POST["formSubmit"] , $_SESSION['secreto'] ) ){

/*  El formulario es asi :
//    $form_nombre_apellido   = $_POST['nombreapellido'];
//    $form_ciudad            = $_POST['ciudad'];
//    $form_e-mail            = $_POST['e-mail'];
//    $form_telefono          = $_POST['telefono'];
//    $form_propuesta         = $_POST['propuesta'];
*/ 
    
    $submitter_info_brute = array(
        $_POST['nombreapellido'],
        $_POST['ciudad'],
        $_POST['e-mail'],
        $_POST['telefono'],
        $_POST['propuesta']
    );
    $submitter_info = array_map("sano",$submitter_info_brute);
    $submitter_info_csv = $submitter_info[0] . ',' .  $submitter_info[1] . ',' .  $submitter_info[2] . ',' .  $submitter_info[3];


/* ------------	
   Chequeo de errores comunes.
   -------------------------------- */
   
    // Si hay algun elemento vacio, es que hubo un error validando la entrada.
    if ( in_array("",$submitter_info)){
        error_caca("Algun elemento del formulario es erroneo. Final no feliz.");
    } else {
        //NOMBRE
        if (!preg_match('/\w+/',$submitter_info[0])){
            error_caca('El Nombre ingresado es invalido. Intente nuevamente.');
        }
        //CORREO
        if (!filter_var($submitter_info[2], FILTER_VALIDATE_EMAIL)) {
            error_caca('El correo ingresado es invalido. Intente nuevamente.');
        }
        //TEL
        if (!preg_match('/\d+/',$submitter_info[3])){
            error_caca('El telefono ingresado es invalido. Intente nuevamente.');
        }
    }
    
    
    $tiempo_pa_formatear = time();
    $fecha_submitted = date("d-m-Y (H:i:s)", $tiempo_pa_formatear);

    // php tiene un error al verificar el body hash.
    // Hay que evitar "\r" y limitarse a usar "\n" unicamente.
    $contenido_pretty = 
        'Nombre y Apellido: ' . wordwrap($submitter_info[0],60,"\n") . "\n" .
        'Ciudad: ' .            wordwrap($submitter_info[1],60,"\n") . "\n" .
        'e-mail: ' .            wordwrap($submitter_info[2],60,"\n") . "\n" .
        'Telefono: ' .          wordwrap($submitter_info[3],60,"\n") . "\n" .
        'Fecha: ' .             wordwrap($fecha_submitted,60,"\n")   . "\n" .
        'Propuesta: ' .         wordwrap($submitter_info[4],60,"\n") . "\n" ;

    $csv_propuesta = "'" . preg_replace( "/\r|\n/", " ", $submitter_info[4] ) . "'";
    $contenido_ugly_csv_arr = array(
        $fecha_submitted, $ip_visitante,
        $submitter_info[0],
        $submitter_info[1],
        $submitter_info[2],
        $submitter_info[3],
        $csv_propuesta
    );
    $contenido_ugly_csv = join(',' , $contenido_ugly_csv_arr);
    
    mandar_mail_reporte_y_escribir_todo_en_el_LOG( $fecha_submitted, $contenido_pretty, $submitter_info[2] , $contenido_ugly_csv);
    
    } else {
        //ERROR de SESSION ID
        error_caca ('Hubo un error al procesar su propuesta. Intente nuevamente a través de Facebook, por favor.');
    }
}



/* -------------------------
         FUNCIONES
------------------------- */

function mandar_mail_reporte_y_escribir_todo_en_el_LOG( $fecha_hora , $mail_reporte_CONTENIDO , $mail_reporte_mail_del_submitter , $c_u_csv ){
    $mail_reporte_to      = '2015@somemail.com';	
    $mail_reporte_to_dbg  = 'alls@somemail.com';	
    $mail_reporte_subject = 'Nueva propuesta ciudadana: ' . $fecha_hora;
	$mail_reporte_message = $mail_reporte_CONTENIDO;
	$mail_reporte_headers = 'From: reportes@hipermegared.com.ar' . "\r\n" .
	    'X-Mailer: HiperMegaRed Systems' . "\r\n" .
	    'Return-path: ' . $mail_reporte_to . "\r\n" .
        'Content-Type: text/plain; charset=utf-8 ' . "\r\n";


    //Mandar correo a ellos
    mail($mail_reporte_to, $mail_reporte_subject, $mail_reporte_message, $mail_reporte_headers);

    //Mandar correo a mi para debuggear
    //mail($mail_reporte_to_dbg, $mail_reporte_subject, $mail_reporte_message, $mail_reporte_headers);

    // Escribir el feedback a un archivillo....
    $mail_logs = $c_u_csv . "\n";
    
    $fs_append = fopen("data/feedback.csv",'a');
    fwrite($fs_append, "$mail_logs");
    fclose($fs_append);

    //Mandar al visitante a la pag de feedback final.
    header("Location: gracias.php");
}

function error_caca($causa) {
    header("Location: error.php");
    die ("$causa");
}

function sano ($input){
    $output_sano = trim($input);
    $output_sano = stripslashes($output_sano);
    $output_sano = filter_var($output_sano,FILTER_SANITIZE_STRING);
    //$output_sano = htmlspecialchars($output_sano);
    if(filter_var($output_sano,FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>'/([\w\d\s-_()@,;."!?:]+)/')))){
        return $output_sano;
    } else {
        return '';
    }
}


function getRealIpAddr(){
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //to check ip is pass from proxy
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from share internet
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
