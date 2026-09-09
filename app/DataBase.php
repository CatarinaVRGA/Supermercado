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
        $this->setConnection();
    }
    //metodo que cria uma conexão com o banco dedados
    public function setConnection()
    {
        $this->connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function execute($query, $values = null)
    {
        try {
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            $statement = $this->connection->prepare($query);
            $statement->execute($values);
            return $statement;
        } catch (PDOException $e) {
            die('ERRO: ' . $e);
        }
    }

    //metodo que insere dados no banco
    public function insert($array)
    {
        //extrair as chaves do array
        $fields = array_keys($array);
        //criar um array com valores = ?
        $binds = array_pad([], count($array), '?');
        //monta a query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(', ', $fields) . ') VALUES(' . implode(', ', $binds) . ')';
        //executa a query
        $this->execute($query, array_values($array));
        return $this->connection->lastInsertId();
    }

    public function update($where, $array){
         //extrair as chaves do array
        $fields = array_keys($array);
        //monta a query
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?, ', $fields) . '=? WHERE ' . $where;
        //executa a query
        $this->execute($query, array_values($array));
        return true;
    }

    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
        $this->execute($query);
        return true;
    }

    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = is_string($where) && $where !== '' ? ' where ' . $where : '';
        $order = is_string($order) && $order !== '' ? ' order by ' . $order : '';
        $limit = is_string($limit) && $limit !== '' ? ' limit ' . $limit : '';
        $query = 'select ' . $fields . ' from ' . $this->table . $where . $order . $limit;
        return $this->execute($query);
    }



}

$db = new DataBase('fornecedor');
$db->delete('nome = "Coca-Cola"');
$f = $db->select()->fetchAll(PDO::FETCH_ASSOC);
$db->insert([
    'nome' => 'Coca-Cola',
    'cnpj' => '12345678444',
    'telefone' => '5427655',
    'email' => 'cocacola@email.com',
    'endereco' => 'Avenida das Américas, 456'
]);
$db->select('nome = "Coca-Cola"');
$f = $db->select()->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($f);
echo "</pre>";

