<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'mailTemplate.php';

require 'flight/Flight.php';

// Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=gustitruck','root',''));
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=gustitruck','root',''));

Flight::before('start', function(){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
});



Flight::route('OPTIONS *', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
  });

// TODO: encriptar passwords

Flight::route('GET /inventario/@user', function ($user) {

        $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE `usuario` = '{$user}' and cantidad > 0");
        $sentence->execute();
        $data=$sentence->fetchAll();
        Flight::json($data);

});

Flight::route('GET /inventario/', function () {

    $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE cantidad > 0");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /reporteventas/', function () {

    //Vendedor, Descripcion ,  fecha, cliente

    $sentence =Flight::db()->prepare("SELECT 
        p.arq_id, 
        p.fecha_creacion, 
        p.usuario, 
        p.cliente, 
        p.cliente_nombre, 
        d.pedido,
        CASE 
            WHEN p.facturado = 1 THEN 'facturado'
            ELSE 'sin facturar'
        END AS estado_facturacion,
        d.cod_producto, 
        d.producto, 
        REPLACE(d.cantidad, '.', ',') AS cantidad, 
        REPLACE(d.precio, '.', ',') AS precio, 
        REPLACE(d.total, '.', ',') AS total,
    FROM 
        `detallepedidos` AS d
    INNER JOIN 
        `pedidos` AS p 
    ON 
        d.pedido = p.pedido;
    ");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /users', function () {

    $sentence =Flight::db()->prepare("SELECT u.usuario, u.nombre, u.primer_apellido, u.segundo_apellido, r.description as role FROM `usuarios` as u inner join `roles` as r on u.role = r.id_role");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('DELETE /deleteuser/@id', function ($id) {

    $sentence =Flight::db()->prepare("UPDATE usuarios set estado = 0 where usuario='{$id}'");
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => "Error al eliminar al usuario '{$id}
        '");
        Flight::halt(400, json_encode($error));
    }

    Flight::halt(200, json_encode(array('message' => 'Usuario eliminado correctamente')));


});

Flight::route('DELETE /deleteInvUser/@username', function ($username) {

    $sentence =Flight::db()->prepare("DELETE from `inventario` where `usuario`='{$username}'");
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => "Error al eliminar al inventario de '{$username}'");
        Flight::halt(400, json_encode($error));
    }

    Flight::halt(200, json_encode(array('message' => 'Inventario eliminado correctamente')));


});



Flight::route('DELETE /deleteAllInv', function () {

    $sentence =Flight::db()->prepare("DELETE FROM `inventario`");
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => "Error al eliminar el inventario");
        Flight::halt(400, json_encode($error));
    }

    Flight::halt(200, json_encode(array('message' => 'Inventario eliminado correctamente')));

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


Flight::route('PUT /pedidos', function () {

    $pedido = (Flight::request()->data->pedido);

    $sql= "UPDATE pedidos SET facturado = 1 where pedido = '{$pedido}'";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();

    Flight::jsonp('contenedor cerrado');
});


Flight::route('GET /pedidos/@usuario/@arq_id', function ($usuario, $arq_id) {

    $sentence =Flight::db()->prepare("SELECT * FROM `pedidos` WHERE usuario='{$usuario}' and arq_id='{$arq_id}' order by 'pedido' desc");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /pedidos/@facturado', function ($facturado) {

    $sentence =Flight::db()->prepare("SELECT * FROM `pedidos` WHERE facturado='{$facturado}' order by 'fecha_creacion' desc");
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

    $sql= "SELECT u.usuario, u.nombre, u.primer_apellido, u.segundo_apellido, u.role, r.ability FROM usuarios as u inner JOIN roles as r on u.role = r.id_role where usuario = '{$usuario}' and BINARY contrasena = '{$contrasena}'";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => 'You are not authorized to access this resource');
        Flight::halt(403, json_encode($error));
    }

    $data=$sentence->fetchAll();
    Flight::halt(200, json_encode($data));
});



Flight::route('POST /changePassword', function () {

    $usuario = (Flight::request()->data->usuario);
    $contrasena = (Flight::request()->data->contrasena);

    $sql= "UPDATE usuarios set contrasena = '{$contrasena}' where usuario = '{$usuario}'";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => 'An error ocurred when thy to change password');
        Flight::halt(403, json_encode($error));
    }

    $data = array('message' => 'Updated successful');
    Flight::halt(200, json_encode($data));
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



Flight::route('POST /solicitudCliente', function () {

    $cliente = (Flight::request()->data->cliente);
    $descripcion = (Flight::request()->data->descripcion);
    $id = (Flight::request()->data->id);
    $provincia = (Flight::request()->data->provincia);
    $canton = (Flight::request()->data->canton);
    $distrito = (Flight::request()->data->distrito);
    $direccion = (Flight::request()->data->direccion);
    $solicitante = (Flight::request()->data->solicitante);
    $email = (Flight::request()->data->email);

    $sql= "INSERT INTO solicitudClientes(cliente, descripcion, id, provincia, canton, distrito, direccion, email, usuario) values('{$cliente}', '{$descripcion}','{$id}','{$provincia}','{$canton}','{$distrito}','{$direccion}','{$email}','{$solicitante}')";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    Flight::jsonp('Solicitu creada');

});

Flight::route('POST /agregarCliente', function () {

    $cliente = (Flight::request()->data->cliente);
    $descripcion = (Flight::request()->data->descripcion);


    $sql= "INSERT INTO clientes (cliente, descripcion) VALUES ('{$cliente}', '{$descripcion}')";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    Flight::jsonp('Cliente agregado correctamente');

});

Flight::route('POST /cerrarContenedor', function () {

    

    $arq_id = (Flight::request()->data->arq_id);

    $sql= "UPDATE `arqueos` set `fecha_cierre` = CURRENT_TIMESTAMP where `arq_id` = '{$arq_id}'";
    
    $sentence =Flight::db()->prepare($sql);

    $sentence->execute();

    Flight::jsonp($sql);


});




Flight::route('POST /crearCliente', function () {

    
    $clientId = (Flight::request()->data->clientId);
    $usuario = (Flight::request()->data->usuario);
    $id = (Flight::request()->data->id);

    $sql= "UPDATE `solicitudClientes` set `fecha_creacion` = CURRENT_TIMESTAMP, `creador`='{$usuario}', creado = 1 where `cliente` = '{$id}';UPDATE `clientes` set `cliente` = '{$clientId}', `create_time` = CURRENT_TIMESTAMP where `cliente` = '{$id}'; UPDATE `pedidos` set cliente = '{$clientId}' where cliente = '{$id}'";
    
    $sentence =Flight::db()->prepare($sql);

    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => 'error al crear');
        Flight::halt(400, $sql);
    }

    // $data = array('rowsAffected' => $sentence->rowCount());
    // Flight::halt(200, json_encode($data));
    Flight::halt(200, jsonp($sql));

    Flight::jsonp($sql);

});



Flight::route('POST /crearUsuario', function () {

    $name = (Flight::request()->data->name);
    $username = (Flight::request()->data->username);
    $flastName = (Flight::request()->data->flastName);
    $slastName = (Flight::request()->data->slastName);
    $password = (Flight::request()->data->password);
    $role = (Flight::request()->data->role);


    $sql= "INSERT INTO `usuarios` (usuario, nombre, primer_apellido, segundo_apellido, contrasena, role) values ('{$username}','{$name}','{$flastName}','{$slastName}','{$password}','{$role}')";
    
    try {

        $sentence =Flight::db()->prepare($sql);

        $sentence->execute();

        if (!$sentence->rowCount() > 0) {
            $error = array('message' => 'error al crear');
            Flight::halt(400, $error);
        }

        $data = array('rowsAffected' => $sentence->rowCount());
        Flight::halt(200, json_encode($data));

    } catch (PDOException $e) {
        Flight::halt(400, json_encode($e));
    }
});


Flight::route('GET /contenedores', function () {

    $sql= "SELECT * FROM arqueos";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /contenedores/@arq_state', function ($arq_state) {

    $sql= "SELECT * FROM arqueos where `arqueo_aprobado`={$arq_state}";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('PUT /contenedores', function () {

    $arq_id = (Flight::request()->data->arq_id);
    $auditor = (Flight::request()->data->auditor);
    $comentarios = (Flight::request()->data->comentarios);

    $sql= "UPDATE arqueos set arqueo_aprobado = 1, auditor= '{$auditor}', comentarios ='{$comentarios}', fecha_aprobacion = CURRENT_TIMESTAMP where arq_id ={$arq_id}";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();

    Flight::jsonp('contenedor cerrado');
});

Flight::route('GET /detalleContenedor/@arq_id', function ($arq_id) {

    $sql= "SELECT distinct a.fecha_apertura, a.fecha_cierre, p.usuario, p.arq_id, d.pedido, sum(d.cantidad) as cantidad, sum(d.total) as total FROM `pedidos` as p INNER JOIN `detallepedidos` as d on p.pedido = d.pedido INNER JOIN `arqueos` as a on p.arq_id = a.arq_id WHERE p.`arq_id` = {$arq_id} group by a.fecha_apertura, a.fecha_cierre, p.usuario, p.arq_id, d.pedido;";
    $sentence = Flight::db()->prepare($sql);

    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /detalleCierreContenedor/@arq_id', function ($arq_id) {

    $sql= "SELECT a.`usuario`, a.`fecha_apertura`, a.`fecha_cierre`, p.forma_pago, count(distinct p.pedido) as pedidos, sum(d.total) as total FROM `arqueos` as a inner join pedidos as p on p.arq_id = a.arq_id inner join detallepedidos as d on p.pedido = d.pedido WHERE a.`arq_id` = {$arq_id} group by  a.`usuario`, a.`fecha_apertura`, a.`fecha_cierre`, p.forma_pago";
    $sentence = Flight::db()->prepare($sql);

    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});




Flight::route('GET /contenedorInfo/@arq_id', function ($arq_id) {

    $sql= "SELECT * FROM `arqueos` WHERE `arq_id` = '{$arq_id}'";
    $sentence = Flight::db()->prepare($sql);

    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /contenedorAbierto/@usuario', function ($usuario) {

    $sql= "SELECT * FROM arqueos where `fecha_cierre` is null and `usuario`='{$usuario}' order by `arq_id` desc LIMIT 1";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});



Flight::route('POST /inventoryload', function () {

      //Retrieve the JSON data from the POST request
      $json_data = Flight::request()->getBody();

      // Decode the JSON data into an associative array
      $data = json_decode($json_data, true);
      $sql ="";
    // Loop through the array of associative arrays
    foreach ($data as $row) {

        $usuario = trim($row['Usuario']);
        $producto = trim($row['Producto']);
        $descripcion = $row['Descripcion'];
        $ean13 = trim($row['EAN13']);
        $precio = $row['Precio'];
        $cantidad = $row['Cantidad'];

        $sql = "SELECT * FROM inventario where usuario = '{$usuario}' and producto = '{$producto}'";

        $sentence = Flight::db()->prepare($sql);

        $sentence->execute();


        if (!$sentence->rowCount() > 0) {

            $sql = "INSERT INTO inventario (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`) values ('{$usuario}', '{$producto}','{$descripcion}', '{$ean13}', '{$precio}', '{$cantidad}')";
        }else{
            $sql = "UPDATE inventario set ean13 = '{$ean13}', precio='{$precio}', cantidad = cantidad+{$cantidad}, creado = CURRENT_TIMESTAMP where usuario = '{$usuario}' and producto = '{$producto}'";
        }

        $sentence = Flight::db()->prepare($sql);
        $sentence->execute();

    }
    Flight::jsonp('Ingreso exitoso');
});


Flight::route('POST /cargaClientes', function () {

    //Retrieve the JSON data from the POST request
    $json_data = Flight::request()->getBody();

    // Decode the JSON data into an associative array
    $data = json_decode($json_data, true);
    $sql = 'DELETE FROM clientes; INSERT INTO clientes (`cliente`, `descripcion`) VALUES';
    $count = true;

  // Loop through the array of associative arrays
  foreach ($data as $row) {

      $Cliente = $row['Cliente'];
      $Nombre = $row['Nombre'];

      if ($count) {
        $sql=$sql."('{$Cliente}','{$Nombre}')";
        $count=false;
      }else{
        $sql=$sql.",('{$Cliente}','{$Nombre}')";
      }
  }

    $sentence = Flight::db()->prepare($sql);
    $sentence->execute();

    if (!$sentence->rowCount() > 0) {
        $error = array('message' => 'You are not authorized to access this resource');
        Flight::halt(400, json_encode($error));
    }

    $data=$sentence->fetchAll();
    $data = array('rowsAffected' => $sentence->rowCount());
    Flight::halt(200, json_encode($data));

});


Flight::route('GET /clientes', function () {

    $sql= "SELECT * FROM clientes";
    $sentence = Flight::db()->prepare($sql);
    
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});


Flight::route('GET /newClients', function () {

    $sql= "SELECT * FROM solicitudClientes where creado=0";
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


Flight::route('GET /email/@pedido', function ($pedido) {

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
 //Optional name

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


Flight::start();
