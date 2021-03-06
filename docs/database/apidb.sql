DROP DATABASE apidb;

CREATE DATABASE apidb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_bin;

USE apidb;

CREATE TABLE token (id INT UNSIGNED NOT NULL AUTO_INCREMENT, content CHAR(32) NOT NULL, PRIMARY KEY(id)) ENGINE=InnoDB;

CREATE TABLE categoria (id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, nome VARCHAR(60) NOT NULL, PRIMARY KEY(id)) ENGINE=InnoDB;

CREATE TABLE produto (id INT UNSIGNED NOT NULL AUTO_INCREMENT, categoria_id SMALLINT NOT NULL REFERENCES categoria(id), nome VARCHAR(60) NOT NULL, preco NUMERIC(9,2), PRIMARY KEY(id)) ENGINE=InnoDB;

