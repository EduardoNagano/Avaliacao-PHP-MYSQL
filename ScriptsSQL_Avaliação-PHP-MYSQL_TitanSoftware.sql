CREATE DATABASE IF NOT EXISTS titan_software


CREATE TABLE IF NOT EXISTS tbl_usuario (
	id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(50) NOT NULL,
	senha VARCHAR(30) NOT NULL
	)


CREATE TABLE IF NOT EXISTS tbl_empresa (
	id_empresa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(40) NOT NULL
	)

CREATE TABLE IF NOT EXISTS tbl_funcionario (
	id_funcionario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(50) NOT NULL,
	cpf VARCHAR(11) NOT NULL,
	rg VARCHAR(20),
	email VARCHAR(30) NOT NULL,
	id_empresa INT UNSIGNED NOT NULL,
	data_cadastro DATE,
	salario DOUBLE(10,2),
	bonificacao DOUBLE(10,2),
	FOREIGN KEY (id_empresa) REFERENCES tbl_empresa(id_empresa)
	)

INSERT INTO tbl_usuario (login, senha)
	VALUES ('teste@gmail.com.br','1234')

UPDATE tbl_usuario SET senha=MD5('1234')
	WHERE login='teste@gmail.com.br'