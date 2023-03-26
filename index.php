<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'flight/Flight.php';
require 'mailTemplate.php';


Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=gustitruck','root',''));
// Flight::register('db', 'PDO', array('mysql:host=192.95.39.223;dbname=cargaint_gustitruck','cargaint_root','HHbt37Y37@#987'));

Flight::route('GET /inventario/@user', function ($user) {

        $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE `usuario` = '{$user}' and cantidad > 0");
        $sentence->execute();
        $data=$sentence->fetchAll();
        Flight::json($data);

});


Flight::route('GET /inventario/@user/@producto', function ($user, $producto) {

    

    $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE (usuario = '{$user}' and `producto` like '%{$producto}%' and cantidad > 0) or (usuario = '{$user}' and `descripcion` like '%{$producto}%' and cantidad > 0) or (usuario = '{$user}' and `ean13` like '%{$producto}%' and cantidad > 0)");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /clientes', function () {

    $sentence =Flight::db()->prepare("SELECT * FROM `clientes`");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /clientes/@id', function ($id) {

    $sentence =Flight::db()->prepare("SELECT * FROM `clientes` where `cliente` like '%{$id}%' or `descripcion` like '%{$id}%'");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('POST /pedidos', function () {

    $usuario = (Flight::request()->data->usuario);
    $tipo = (Flight::request()->data->tipo);
    $fechaEntrega = (Flight::request()->data->fechaEntrega);
    $formaPago = (Flight::request()->data->formaPago);
    $arq_id = (Flight::request()->data->arq_id);
    $cliente = (Flight::request()->data->cliente);
    $cliente_nombre = (Flight::request()->data->cliente_nombre);
    $anotaciones = (Flight::request()->data->anotaciones);
    $lineas = (Flight::request()->data->lineas);
    $facturado = (Flight::request()->data->facturado);

    $sql= 'INSERT INTO pedidos (usuario, tipo, arq_id, fecha_entrega, forma_pago, cliente, cliente_nombre, anotaciones, lineas, facturado) VALUES(?,?,?,?,?,?,?,?,?,?)';
    $sentence =Flight::db()->prepare($sql);
    
    $sentence->bindParam(1,$usuario);
    $sentence->bindParam(2,$tipo);
    $sentence->bindParam(3,$arq_id);
    $sentence->bindParam(4,$fechaEntrega);
    $sentence->bindParam(5,$formaPago);
    $sentence->bindParam(6,$cliente);
    $sentence->bindParam(7,$cliente_nombre);
    $sentence->bindParam(8,$anotaciones);
    $sentence->bindParam(9,$lineas);
    $sentence->bindParam(10,$facturado);
    
    $sentence->execute();

    $sentence =Flight::db()->prepare("SELECT IFNULL(max(pedido),0) as lastId FROM `pedidos`");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('POST /pedidodetalle', function () {

    $pedido = (Flight::request()->data->pedido);
    $cod_producto = (Flight::request()->data->cod_producto);
    $producto = (Flight::request()->data->producto);
    $cantidad = (Flight::request()->data->cantidad);
    $precio = (Flight::request()->data->precio);
    $total = (Flight::request()->data->total);

    $sql= 'INSERT INTO `detallepedidos` (`pedido`, `cod_producto`, `producto`, `cantidad`, `precio`, `total`) VALUES (?,?,?,?,?,?)';
    $sentence =Flight::db()->prepare($sql);
    
    $sentence->bindParam(1,$pedido);
    $sentence->bindParam(2,$cod_producto);
    $sentence->bindParam(3,$producto);
    $sentence->bindParam(4,$cantidad);
    $sentence->bindParam(5,$precio);
    $sentence->bindParam(6,$total);

    
    $sentence->execute();

});


Flight::route('GET /pedidos/@usuario/@arq_id', function ($usuario, $arq_id) {

    $sentence =Flight::db()->prepare("SELECT * FROM `pedidos` WHERE usuario='{$usuario}' and arq_id='{$arq_id}' order by 'pedido' desc");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /pedidodetalle/@pedido', function ($pedido) {

    $sentence =Flight::db()->prepare("SELECT * FROM `detallepedidos` WHERE pedido='{$pedido}'");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('POST /login', function () {

    $usuario = (Flight::request()->data->usuario);
    $contrasena = (Flight::request()->data->contrasena);

    $sql= "SELECT * FROM usuarios where usuario = '{$usuario}' and BINARY contrasena = '{$contrasena}'";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('POST /abrirContenedor', function () {

    $usuario = (Flight::request()->data->usuario);
    $timestampId = (Flight::request()->data->timestampId);

    $sql= "INSERT INTO arqueos(usuario, timestampId) values('{$usuario}', '{$timestampId}')";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    Flight::json($sentence);

});

Flight::route('GET /contenedorAprobado/@usuario', function ($usuario) {

    $sql= "SELECT * FROM arqueos where `usuario` = '{$usuario}' and `arqueo_aprobado`= false";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});



Flight::route('POST /cerrarContenedor', function () {

    

    $arq_id = (Flight::request()->data->arq_id);

    $sql= "UPDATE `arqueos` set `fecha_cierre` = CURRENT_TIMESTAMP where `arq_id` = '{$arq_id}'";
    
    $sentence =Flight::db()->prepare($sql);

    $sentence->execute();

    Flight::jsonp($sql);


});


Flight::route('GET /contenedores', function () {

    $sql= "SELECT * FROM arqueos";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /detalleContenedor/@arq_id', function ($arq_id) {

    $sql= "SELECT distinct a.fecha_apertura, a.fecha_cierre, p.usuario, p.arq_id, d.pedido, sum(d.cantidad) as cantidad, sum(d.total) as total FROM `pedidos` as p INNER JOIN `detallepedidos` as d on p.pedido = d.pedido INNER JOIN `arqueos` as a on p.arq_id = a.arq_id WHERE p.`arq_id` = {$arq_id} group by a.fecha_apertura, a.fecha_cierre, p.usuario, p.arq_id, d.pedido;";
    $sentence = Flight::db()->prepare($sql);

    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /contenedorAbierto/@usuario', function ($usuario) {

    $sql= "SELECT * FROM arqueos where `fecha_cierre` is null and `usuario`='{$usuario}'";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});



Flight::route('POST /inventarioreduce', function () {

    $cantidad = (Flight::request()->data->cantidad);
    $usuario = (Flight::request()->data->usuario);
    $cod_producto = (Flight::request()->data->cod_producto);

    $sql= "UPDATE `inventario` set `cantidad` = cantidad-{$cantidad} where usuario = '{$usuario}' and producto = '{$cod_producto}'";
    
    $sentence =Flight::db()->prepare($sql);

    $sentence->execute();

    Flight::jsonp($sql);

});



// Flight::route('PUT /inventarioajust', function () {

//     $cantidad = (Flight::request()->data->cantidad);
//     $usuario = (Flight::request()->data->usuario);
//     $cod_producto = (Flight::request()->data->cod_producto);

//     $sql= "UPDATE `inventario` set `cantidad` = ? where usuario = ? and producto = ?";
    
//     $sentence =Flight::db()->prepare($sql);

//     $sentence->bindParam(1,$cantidad);
//     $sentence->bindParam(2,$usuario);
//     $sentence->bindParam(3,$cod_producto);

//     $sentence->execute();
//     Flight::jsonp($cantidad);

// });

Flight::route('GET /email/@pedido', function ($pedido) {

        // $pedido = (Flight::request()->data->pedido);
        // $cliente = (Flight::request()->data->cliente);
        // $lineas = (Flight::request()->data->lineas);
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'wo41.wiroos.host';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'ingenieria@cargainternacional.cr';                     //SMTP username
            $mail->Password   = 'HHbt37Y37';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('ingenieria@cargainternacional.cr', 'GustiTruck');
            $mail->addAddress('gerardo.benavidesh@hotmail.com', 'Facturacion');     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                               //Set email format to HTML
            $mail->Subject = "Pedido [{$pedido}] generado en la WEB";
            $mail->Body    = getTemplate($pedido);
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            Flight::jsonp('Message has been sent');
        } catch (Exception $e) {
            Flight::jsonp(
                "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
            );
        }
});

Flight::before('json', function () {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
});


Flight::start();
