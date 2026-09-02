drop database supermercado if exists;
create database supermercado;
use supermercado;
create table fornecedor(
    id int auto_increment primary key,
    nome varchar(50) not null,
    cnpj varchar(14) not null unique,
    telefone varchar(20),
    email varchar(255),
    endereco varchar(255)
);
create table produto(
    id int auto_increment primary key,
    nome varchar(50) not null,
    descricao varchar(255),
    codigo varchar(8) not null unique,
    quantidade int not null,
    preco decimal(7,2) default 0,
    data_validade date not null,
    id_fornecedor int not null,
    foreign key (id_fornecedor) references fornecedor(id)
);
create table cliente(
    id int auto_increment primary key,
    nome varchar(255) not null,
    cpf varchar(11) not null unique,
    dtn date not null,
    telefone varchar(20),
    email varchar(255),
    endereco varchar(255)
);
create table funcionario(
    id int auto_increment primary key,
    nome varchar(255) not null,
    cpf varchar(11) not null unique,
    dtn date not null,
    telefone varchar(20),
    email varchar(255),
    endereco varchar(255),
    cargo varchar(50) not null,
    salario decimal(7,2) default 0,
    senha varchar(20) not null
);
create table venda(
    id int auto_increment primary key,
    id_produto int not null,
    id_funcionario int not null,
    id_cliente int not null,
    data_venda datetime not null,
    foreign key (id_produto) references produto(id),
    foreign key (id_funcionario) references funcionario(id),
    foreign key (id_cliente) references cliente(id)
);
create table venda_produto(
    id int auto_increment primary key,
    id_produto int not null,
    id_venda int not null,
    foreign key (id_produto) references produto(id),
    foreign key (id_venda) references venda(id)
);
    