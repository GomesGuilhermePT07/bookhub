CREATE DATABASE bookhubjb;

USE bookhubjb;

CREATE TABLE utilizadores(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    genero ENUM('m', 'f', 'o') NOT NULL,
    admin TINYINT(1) DEFAULT 0 NOT NULL
);

CREATE TABLE livros(
    cod_isbn VARCHAR(15) PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    edicao INT NOT NULL,
    autor VARCHAR(255) NOT NULL,
    numero_paginas INT NOT NULL,
    quantidade INT NOT NULL,
    resumo TEXT
);

CREATE TABLE carrinho(
    id_utilizador INT(11) NOT NULL,
    cod_isbn VARCHAR(15) NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    PRIMARY KEY (id_utilizador, cod_isbn),
    FOREIGN KEY (id_utilizador) REFERENCES utilizadores(id),
    FOREIGN KEY (cod_isbn) REFERENCES livros(cod_isbn)
);

