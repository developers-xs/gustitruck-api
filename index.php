<?php

require 'flight/Flight.php';


Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=gustitruck','root',''));
// Flight::register('db', 'PDO', array('mysql:host=192.95.39.223;dbname=cargaint_gustitruck','cargaint_root','HHbt37Y37@#987'));

Flight::route('GET /inventario/@user', function ($user) {

        $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE `usuario` = '{$user}'");
        $sentence->execute();
        $data=$sentence->fetchAll();
        Flight::json($data);

});


Flight::route('GET /inventario/@user/@producto', function ($user, $producto) {

    $sentence =Flight::db()->prepare("SELECT * FROM `inventario` WHERE `usuario` = '{$user}' and `producto` like '%{$producto}%' or `descripcion` like '%{$producto}%' or `ean13` like '%{$producto}%' ");
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
    $cliente = (Flight::request()->data->cliente);
    $cliente_nombre = (Flight::request()->data->cliente_nombre);
    $anotaciones = (Flight::request()->data->anotaciones);
    $lineas = (Flight::request()->data->lineas);
    $facturado = (Flight::request()->data->facturado);

    $sql= 'INSERT INTO pedidos (usuario, tipo, fecha_entrega, forma_pago, cliente, cliente_nombre, anotaciones, lineas, facturado) VALUES(?,?,?,?,?,?,?,?,?)';
    $sentence =Flight::db()->prepare($sql);
    
    $sentence->bindParam(1,$usuario);
    $sentence->bindParam(2,$tipo);
    $sentence->bindParam(3,$fechaEntrega);
    $sentence->bindParam(4,$formaPago);
    $sentence->bindParam(5,$cliente);
    $sentence->bindParam(6,$cliente_nombre);
    $sentence->bindParam(7,$anotaciones);
    $sentence->bindParam(8,$lineas);
    $sentence->bindParam(9,$facturado);
    
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


Flight::route('GET /pedidos', function () {

    $sentence =Flight::db()->prepare("SELECT * FROM `pedidos`");
    $sentence->execute();
    $data=$sentence->fetchAll();
    Flight::json($data);

});

Flight::route('GET /pedidodetalle', function () {

    $sentence =Flight::db()->prepare("SELECT * FROM `detallepedidos`");
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

Flight::start();
