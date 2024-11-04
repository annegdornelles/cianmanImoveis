create database cianman;

use cianman;

create table clientes(
   cpf varchar(14) not null,
   nome varchar(50),
   cep varchar(8),
   email varchar(50),
   dataNasc date,
   senha varchar(20),
   telefone varchar(17),
   primary key(cpf));
   
   create table funcionarios(
      id int auto_increment not null,
      cpf varchar(14),
      nome varchar(50),
      email varchar(50),
      senha varchar(20),
      telefone varchar(17),
      salario float,
      numImoveisResponsavel int,
      primary key(id));
      
	create table corretores(
       creci varchar(5), 
       funcionariosId int,
       primary key(creci),
       FOREIGN KEY (funcionariosId) REFERENCES funcionarios(id));
       
	create table secretarios(
       id int auto_increment,
       escolaridade varchar(50),
       funcionariosId int,
       primary key(id),
	   FOREIGN KEY (funcionariosId) REFERENCES funcionarios(id));
       
	create table venda(
       clientesCpf varchar(14),
       funcionariosId int,
       id int auto_increment,
       valor float,
       dataVenda date,
       imovelId int,
       primary key (id),
       foreign key(clientesCpf) references clientes(cpf),
       foreign key(funcionariosId) references funcionarios(id));
       
	 create table imoveis(
        id int auto_increment,
        clientesCpf varchar(14),
        funcionariosId int,
        cep varchar(9),
        tamanho int,
        valor float,
        numQuartos int,
        bairro varchar(50),
        cidade varchar(20),
        estado varchar(30),
        numCasa int,
        logradouro varchar(50),
        primary key(id),
        foreign key(clientesCpf) references clientes(id),
        foreign key(funcionariosId) references funcionarios(id));
        
      create table proprietarios(
         cpf varchar(14),
         funcionariosId int,
         nome varchar(50),
         email varchar(50),
         telefone varchar(17),
         primary key(cpf),
         foreign key(funcionariosId) references funcionarios(id));
       
       
