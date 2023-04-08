drop database gustitruck;
create database gustitruck;

use gustitruck;

CREATE TABLE inventario (
    usuario VARCHAR(30) not null,
    producto VARCHAR(30) not null,
    descripcion varchar(50) not null,
    ean13 VARCHAR(13) not null,
    precio FLOAT not null,
    cantidad INT not null,
    creado DATETIME not null DEFAULT CURRENT_TIMESTAMP,
    primary key(usuario, producto, creado)
);



CREATE TABLE clientes(  
    cliente VARCHAR(30) NOT NULL PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL,
    create_time DATETIME DEFAULT  CURRENT_TIMESTAMP
);


CREATE TABLE usuarios
(
    usuario VARCHAR(30) PRIMARY key,
    nombre NVARCHAR(30),
    primer_apellido NVARCHAR(30),
    segundo_apellido NVARCHAR(30),
    contrasena NVARCHAR(200),
    role int
);

Create Table roles
(
    id_role int not null PRIMARY KEY,
    description nvarchar(200),
    ability JSON not null
);

insert into usuarios (usuario, nombre, primer_apellido, segundo_apellido, contrasena) values('abenavides', 'Antonio', 'Benavides', 'Hernandez', 'ogaitnas');

CREATE TABLE pedidos(
    usuario VARCHAR(30) NOT NULL, 
    pedido int not null primary key AUTO_INCREMENT,
    arq_id int not null,
    tipo NVARCHAR(20) not NULL, 
    fecha_entrega DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    forma_pago NVARCHAR(30) not null,
    cliente VARCHAR(30) NOT NULL,
    cliente_nombre VARCHAR(50) NOT NULL,
    anotaciones NVARCHAR(200),
    lineas int,
    facturado BOOLEAN DEFAULT FALSE
);


CREATE TABLE detallepedidos(
    pedido int not null,
    cod_producto VARCHAR(30) NOT NULL,
    producto VARCHAR(50) NOT NULL,
    cantidad INT NOT NULL,
    precio FLOAT not null,
    total FLOAT NOT NULL
);

create table arqueos(
    arq_id int not null primary key AUTO_INCREMENT,
    timestampId VARCHAR(30)not null,
    usuario varchar(30) not null,
    fecha_apertura DATETIME not null DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME,
    arqueo_aprobado BOOLEAN DEFAULT FALSE,
    auditor varchar(30) null,
    fecha_aprobacion DATETIME null,
    comentarios VARCHAR(200) null,

);

CREATE TABLE solicitudClientes (
    cliente VARCHAR(30) NOT NULL PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL,
    id VARCHAR(50) NOT NULL,
    provincia VARCHAR(50) NOT NULL,
    canton VARCHAR(50) NOT NULL,
    distrito VARCHAR(50) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    creado boolean DEFAULT FALSE,
    create_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    creador_cod varchar(30) null,
    fecha_creacion DATETIME null
);

-- Llenar tabla de cliente

insert into clientes (cliente, descripcion)values('0010000000001','CLIENTE DE CONTADO');
insert into clientes (cliente, descripcion)values('0010000000167','JUAN DIEGO ROJAS ARAYA');
insert into clientes (cliente, descripcion)values('0010000000174','IVAN ADOLFO CARPIO BRENES');
insert into clientes (cliente, descripcion)values('0010000000199','EXCLUSIVE & LUXURY IMPORTS S.A');
insert into clientes (cliente, descripcion)values('0010000000201','ERICK GRANADOS BRENES');
insert into clientes (cliente, descripcion)values('0010000000202','ALIMENTOS Y COMERCIALIZADORA DGC S.A');
insert into clientes (cliente, descripcion)values('0010000000203','3101808687 S.A');
insert into clientes (cliente, descripcion)values('0010000000204','MARCO ANTONIO ARIAS FERNANDEZ');
insert into clientes (cliente, descripcion)values('0010000000205','FUFO´S FAST FOOD S.A');
insert into clientes (cliente, descripcion)values('0010000000209','SANDRA MONTES GUZMAN');
insert into clientes (cliente, descripcion)values('0010000000210','DISTRIBUIDORA EVENCIO RODRIGUEZ S.A');
insert into clientes (cliente, descripcion)values('0010000000211','ELIZABETH MORA OVARES');
insert into clientes (cliente, descripcion)values('0010000000214','INVERSIONES JOEPAU DEL CARIBE S.A');
insert into clientes (cliente, descripcion)values('0010000000215','CARLOS EDUARDO CISNEROS GODINEZ');
insert into clientes (cliente, descripcion)values('0010000000217','GUICENIA VALVERDE ZAMORA');
insert into clientes (cliente, descripcion)values('0010000000218','S.R LOGISTICA SRL ( STERA COMERCIAL)');
insert into clientes (cliente, descripcion)values('0010000000219','LAURA APUY MONGE');
insert into clientes (cliente, descripcion)values('0010000000221','CASA PROVEEDORA PHILLIPS S.A.');
insert into clientes (cliente, descripcion)values('0010000000223','DISTRIBUIDORA LA B.D.S.SRL');
insert into clientes (cliente, descripcion)values('0010000000225','VERDE ROJO AZUL NUEVE OCHO SIETE S.R.LTD');
insert into clientes (cliente, descripcion)values('0010000000227','DANIEL RAMIREZ SANCHO');
insert into clientes (cliente, descripcion)values('0010000000228','COMARKA HBH SOCIEDAD ANONIMA');
insert into clientes (cliente, descripcion)values('0010000000229','EDUARDO ARIAS NAVARRO');
insert into clientes (cliente, descripcion)values('0010000000231','DEPORTES MATA DE COSTA RICA MYM S.A');
insert into clientes (cliente, descripcion)values('0010000000237','DISTRIBUIDORA JOLUSO LIMITADA');
insert into clientes (cliente, descripcion)values('0010000000241','FRITURAS LA VICTORIA');
insert into clientes (cliente, descripcion)values('0010000000248','COUNTRY HOUSE  LIBERIA');
insert into clientes (cliente, descripcion)values('0010000000249','COUNTRY HOUSE SANTA CRUZ');
insert into clientes (cliente, descripcion)values('0010000000250','COUNTRY HOUSE NICOYA');
insert into clientes (cliente, descripcion)values('0010000000251','DISTRIBUIDORA VEZU VENEGAS ZUÑIGA');
insert into clientes (cliente, descripcion)values('0010000000254','DISTRIBUCION CARYBE COMERCIALS.A');
insert into clientes (cliente, descripcion)values('0010000000255','MUNDO VENDING CR');
insert into clientes (cliente, descripcion)values('0010000000141','SUPER EL BUEN PRECIO');
insert into clientes (cliente, descripcion)values('0010000000166','JOSE ANDRES NIÑO RIVERA');
insert into clientes (cliente, descripcion)values('0010000000168','RANDALL VALERIO SOTO');
insert into clientes (cliente, descripcion)values('0010000000189','DANIEL MAURICIO CHINCHILLA GONZALEZ');
insert into clientes (cliente, descripcion)values('0010000000200','YAJAIRA CORDOBA MURILLO');
insert into clientes (cliente, descripcion)values('0010000000206','GERARDO LEDEZMA CASTILLO');
insert into clientes (cliente, descripcion)values('0010000000216','RAFAEL ANGEL GUERRERO ROMERO');
insert into clientes (cliente, descripcion)values('0010000000222','WILLIAM BOTERO GOMEZ');
insert into clientes (cliente, descripcion)values('0010000000224','ERICK ALBERTO VARGAS CASCANTE');
insert into clientes (cliente, descripcion)values('0010000000226','MALUQUER DE CENTROAMERICA S.A');
insert into clientes (cliente, descripcion)values('0010000000252','GILBERTO ALONSO ROJAS RAMIREZ');
insert into clientes (cliente, descripcion)values('0010000000253','ALFONSO JIMENEZ VEGA');
insert into clientes (cliente, descripcion)values('0010000000258','ASEMALUQUER');
insert into clientes (cliente, descripcion)values('0010000000259','JOHAN VIACHICA QUESADA');
insert into clientes (cliente, descripcion)values('0010000000260','FABIAN A ANCHIA MURILLO');
insert into clientes (cliente, descripcion)values('0010000000261','ALEXANDRA GRANADOS ESQUIVEL');
insert into clientes (cliente, descripcion)values('0010000000262','SUPER SAN JOSE');
insert into clientes (cliente, descripcion)values('0010000000263','SUPER SAN BUENA VISTA');
insert into clientes (cliente, descripcion)values('0010000000264','SUPER Y LICORERA EL NORTE');
insert into clientes (cliente, descripcion)values('0010000000265','MINI SUPER SANTA LUCIA');
insert into clientes (cliente, descripcion)values('0010000000266','SUPER Y LICORERA LA GRUTA');







--Agregando inventario


INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'ARC20-1', 'AROS DE CEBOLLA 14 GR', '7443014060129', '1800', '166',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'ARC350-1', 'AROS DE CEBOLLA 350 GRAMOS', '7443014060937', '700', '16',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'ARC60-1', 'AROS DE CEBOLLA 60 G', '7443014060586', '300', '179',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'CHP18', 'CHICHARRON 14 GR', '7443014061309', '1800', '198',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'CHP60-1', 'CHICHARRON  60 G', '7443014060739', '600', '440',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'CPKA8', 'COMBO 4 EN 1 PELLETS 60GR', '7443014061422', '1000', '4',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'FS18', 'FIRE STICK 14 GR', '7443014061330', '1800', '105',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'FS60', 'FIRE STICK 60 GR', '7443014060685', '300', '215',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'KRMIX18', 'KRAZY MIX 14 GR', '7443014061316', '1800', '40',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'KRMIX300', 'KRAZY MIX 300 GRAMOS', '7443014060920', '630', '25',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'KRMIX60', 'KRAZY MIX 60 GRAMOS', '7443014060746', '250', '93',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'NAE200', 'NACHO  200 GRAMOS BBQ', '7443014060203', '450', '184',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE120-1', 'PAPAS 120 GRAMOS NATURAL', '7443014060241', '600', '328',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE120-1PACK', 'COMBO TWO PACK  PAPAS 120 GRAMOS', '7443014061019', '1245', '2',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE120-6PACK    ', 'COMBO TWO PACK  PAPAS 120 GR TRAD / CEBOLLA', '7443014060975', '1245', '0',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE120-6', 'PAPAS  120 GRAMOS CREMA CEBOLLA', '7443014060197', '600', '334',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE120-7', 'PAPAS  120 GRAMOS  MOSTAZA MIEL', '7443014060258', '600', '419',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE180', 'PAPA PALITO 180 GR', '7443014060814', '890', '265',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE180-1', 'PAPAS  180 GRAMOS NATURAL', '7443014060166', '890', '347',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE180-6', 'PAPAS 180 GRAMOS CREMA CEBOLLA', '7443014060135', '890', '503',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE180-7', 'PAPAS 180 GRAMOS MOSTAZA MIEL', '7443014060159', '890', '224',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE18-1', 'PAPAS 14 GRAMOS NATURAL', '7443014061262', '1800', '448',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE18-6', 'PAPAS  14 GRAMOS CREMA CEBOLLA', '7443014061279', '1800', '811',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE18-7', 'PAPAS  14 GRAMOS MOSTAZA MIEL', '7443014061286', '1800', '297',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE250-1', 'PAPAS  250 GRAMOS TRADICIONAL', '7443014060814', '1200', '608',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE25-6', 'PAPAS  ONDULADAS 25 GRAMOS CREMA CEBOLLA', '7443014061347', '1930', '144',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE25-7', 'PAPAS ONDULADAS  25 GRAMOS MOSTAZA MIEL', '7443014061361', '1930', '32',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE25-8', 'PAPAS ONDULADAS  25 GRAMOS AJO ROSTIZADO', '7443014061408', '1930', '80',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE25-9', 'PAPAS ONDULADAS  25 GRAMOS MAYONESA', '7443014061354', '1930', '16',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE500-1', 'PAPAS  500 GRAMOS NATURAL', '7443014060210', '2300', '627',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-1', 'PAPAS 75 GRAMOS SABOR TRADICIONAL', '7443014060647', '370', '1007',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-1PACK ', 'COMBO TWO PACK  PAPAS 75 GRAMOS', '7443014061040', '735', '0',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-2PACK ', 'COMBO TWO PACK  PAPAS 75 GRAMOS TRADI / CEBOLLA ', '7443014060968', '735', '0',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-6PACK', 'COMBO TWO PACK  PAPAS 75 GRAMOS CEBOLLA ', '7443014061057', '735', '0',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-6', 'PAPAS 75 GRAMOS SABOR CREMA CEBOLLA', '7443014060661', '37', '1520',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-7', 'PAPAS 75 GRAMOS SABOR MOSTAZA MIEL', '7443014060654', '370', '1859',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAE75-8', 'PAPAS 75 GRAMOS SABOR POLLO', '7443014060647', '370', '1083',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PAEK', 'PAPAS POR KILO', '7443014060401', '4500', '44',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLE500-1', 'PLATANO 500 GRAMOS NATURAL', '7443014060302', '1250', '95',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLE50-1', 'PLATANO NATURAL 50 GR', '7443014061675', '5100', '3',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLE50-3', 'PLATANO QUESO 50 GR', '7443014061637', '5100', '9',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLE50-5', 'PLATANO JALAPEÑO 50 GR', '7443014061620', '5100', '3',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLE90-1', 'PLATANO  90 GRAMOS NATURAL', '7443014060784', '250', '1227',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLQ20-1', 'PALITO DE QUESO 14 GRAMOS', '7443014061323', '1800', '15',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'PLQ60-1', 'PALITOS DE QUESO 60 GRAMOS', '7443014060593', '250', '158',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'TOE220', 'TORTILLA 220 GRAMOS BBQ', '7443014060173', '450', '249',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'YUE100-3', 'YUCA 100 GRAMOS  BBQ', '7443014060081', '275', '95',  current_timestamp());
INSERT INTO `inventario` (`usuario`, `producto`, `descripcion`, `ean13`, `precio`, `cantidad`, `creado`) VALUES ('abenavides', 'YUE500-1', 'YUCA  500 GRAMOS NATURAL', '7443014060449', '1600', '14',  current_timestamp());