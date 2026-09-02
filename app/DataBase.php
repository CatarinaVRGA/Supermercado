<?php

namespace App;

use PDO;
use PDOException;

class DataBase
{

    private const HOST = 'localhost';

    private const USER = 'root';

    private const PASSWORD = '123';

    private const DBNAME = 'supermercado';

    private $connection;

    private $table;
    //metodo que constroi classe
    public function __construct($table = null)
    {
        $this->table = $table;
    }
    //metodo que cria uma conexão com o banco dedados
    public function setConnection()
    {
        $connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASSWORD);
    }
    //metodo que insere dados no banco
    public function insert($array){
        //extrair as chaves do array
        $fields = array_keys($array);
        //criar um array com valores = ?
        $binds = array_pad([], count($array), '?');
        //monta a query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(', ', $fields) . ') 
        VALUES (' . implode(', ', $binds) . ')';
        //executa a query
        $this->execute($query, array_values($array));
        return $this->connection->lasInsertId();
       
    }
}
$db = new DataBase('fornecedor');
$db->insert([
    'nome' => 'Coca-Cola',
    'cnpj' => 'ABC123456789',
    'telefone' => '5427655',
    'email' => 'cocacola@email.com',
    'endereco' => 'Avenida das Américas, 456'
]);
