------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id       bigserial    PRIMARY KEY
  , nombre   varchar(255) NOT NULL UNIQUE
  , password varchar(60)  NOT NULL
);

INSERT INTO usuarios (nombre, password)
VALUES ('pepe', crypt('pepe', gen_salt('bf')));

DROP TABLE IF EXISTS zapatos CASCADE;

CREATE TABLE zapatos (
    id           bigserial    PRIMARY KEY
  , codigo       numeric(13)  NOT NULL UNIQUE
  , denominacion varchar(255) NOT NULL
  , precio       numeric(7,2)
  , CONSTRAINT ck_codigo_13_digitos CHECK (length(codigo::text) = 13)
);


INSERT INTO zapatos (codigo, denominacion, precio)
VALUES (1231231231231, 'Converse 24', 35.00)
     , (9999999999999, 'Nike 75', 49.99);

DROP TABLE IF EXISTS carritos CASCADE;

CREATE TABLE carritos (
    id         bigserial PRIMARY KEY
  , usuario_id bigint    NOT NULL REFERENCES usuarios (id)
  , zapato_id  bigint    NOT NULL REFERENCES zapatos (id)
  , cantidad   int       NOT NULL
  , CONSTRAINT ck_cantidad_no_negativa CHECK (cantidad >= 0)
);

INSERT INTO carritos (usuario_id, zapato_id, cantidad)
VALUES (1, 1, 4)
     , (1, 2, 2);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial    PRIMARY KEY
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
  , created_at timestamp(0) NOT NULL DEFAULT LOCALTIMESTAMP
);

CREATE INDEX idx_facturas_usuario_id ON facturas (usuario_id);

INSERT INTO facturas (usuario_id)
VALUES (1);

DROP TABLE IF EXISTS lineas CASCADE;

CREATE TABLE lineas (
    id         bigserial PRIMARY KEY
  , factura_id bigint    NOT NULL REFERENCES facturas (id)
  , zapato_id  bigint    NOT NULL REFERENCES zapatos (id)
  , cantidad   int       NOT NULL
  , CONSTRAINT ck_cantidad_no_negativa CHECK (cantidad >= 0)
);

CREATE INDEX idx_lineas_factura_id ON lineas (factura_id);

INSERT INTO lineas (factura_id, zapato_id, cantidad)
VALUES (1, 1, 2)
     , (1, 2, 1);
