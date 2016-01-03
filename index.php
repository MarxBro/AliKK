<!DOCTYPE html>
<html>  

<head>
    <?php 
    include ('verif.inc'); 
    ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Propuestas Ciudadanas</title>
    <style>
        
        body{
            background: url("fondo.jpg") no-repeat;
            background-size: contain;
            margin: 0px;
            width: 810px;
            height: 700px;
            font-family: "Arial", sans-serif;
        }
        #formulario{
            margin-right: 40px;
            margin-top: 170px;
            width: 210px;
            float: right;
        }
        
        .elemento{
            width: 200px;
            border: 1px solid #C8CBC9;
            border-radius: 5px;
            background: #C8CBC9;
            padding: 5px;
            color:#000;
            margin-bottom: 10px;
        }
        .elemento::-webkit-input-placeholder { /* WebKit, Blink, Edge */
            color:    #606060;
        }
        .elemento:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
           color:    #606060;
           opacity:  1;
        }
        .elemento::-moz-placeholder { /* Mozilla Firefox 19+ */
           color:    #606060;
           opacity:  1;
        }
        .elemento:-ms-input-placeholder { /* Internet Explorer 10-11 */
           color:    #606060;
        }

        #formulario .button{
            background: url("enviar.jpg") no-repeat;
            width: 107px;
            height: 26px;
            
            float: right;
            border: 0px solid transparent;
            border-radius: 0px;
            background-color: #104482;
            color:transparent;
            font-size: 14px;
            padding:3px 10px;
        }
        textarea{
            resize: none;
        }
        #formulario .button:hover{
            cursor: pointer;
        }
    </style>
</head>

<body>
    <form id="formulario" method="post" action="submit.php">

        <input class="elemento" type="text" placeholder="Nombre y apellido" name="nombreapellido" required>
        
        <input class="elemento" type="text" placeholder="Ciudad" name="ciudad" required>

        <input class="elemento" type="text" placeholder="Correo electrónico" name="e-mail" required>

        <input class="elemento" type="text" placeholder="Teléfono" name="telefono" required>
        
        <textarea class="elemento" rows="13" cols="50" placeholder="Tu propuesta" name="propuesta" required></textarea>

        <?php 
        /* 
        <button type="submit" value="<?php echo $_SESSION['formhash'];?>" name="formSubmit">Enviar</button> 
        */ 
        ?>

        <button class="button" type="submit" value="<?php echo $_SESSION['secreto'];?>" name="formSubmit">ENVIAR</button>

    </form>

    </script>
</body>
</html>
