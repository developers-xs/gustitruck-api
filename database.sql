
CREATE TABLE inventario (
    usuario VARCHAR(30) not null,
    producto VARCHAR(30) not null,
    descripcion varchar(50) not null,
    ean13 VARCHAR(13) not null,
    precio FLOAT not null,
    cantidad INT not null,
    creado DATETIME not null DEFAULT CURRENT_TIMESTAMP,
    primary key(usuario, producto, creado)
)


INSERT INTO inventario (
    usuario, producto, descripcion, ean13, precio, cantidad)
          VALUES
          ('abenavides', 's', 'asd', 'asd', 700, 900)