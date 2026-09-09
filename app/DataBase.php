<?php

namespace App;

use PDO;
use PDOException;

class DataBase
{
    private const HOST = 'localhost';
    private const USER = 'root';
    private const PASSWORD = '123';
    private const DB = 'supermercado';
    private $connection;
    private $table;
    //metodo que constroi a classe
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }
    //metodo que cria uma conexão com o banco de dados
    public function setConnection()
    {
        $this->connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB, self::USER, self::PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    //metodo que insere dados no banco
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
    public function insert($array)
    {
        //extrair as chaves do array
        $fields = array_keys($array);
        //criar um array com valores = ?
        $binds = array_pad([], count($array), '?');
        //monta a query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';
        //executa a query
        $this->execute($query, array_values($array));
        return $this->connection->lastInsertId();
    }
    public function update($where, $array)
    {
        //extrair as chaves do array
        $fields = array_keys($array);
        //monta a query
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . ' WHERE ' . $where;
        //executa a query
        $this->execute($query, array_values($array));
        return true;
    }
    public function delete($where)
    {
        //monta a query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
        $this->execute($query);
        return true;
    }
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = strlen($where) ? 'ORDER BY ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';
        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;
        return $this->execute($query);
    }
}
$db = new DataBase('fornecedor');
$db->delete('id=1');
$f = $db->select(null, 'cnpj desc', 2, 'nome, cnpj')->fetchAll(PDO::FETCH_CLASS);
echo "<pre>";
print_r($f);
echo "</pre>";
