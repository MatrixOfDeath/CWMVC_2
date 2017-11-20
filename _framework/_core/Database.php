<?php 

namespace _Core;

class Database
{
private $pdo;	
private $host = '127.0.0.1';
private $user = 'root';
private $pass = 'root';
private $db ='cw16_mvc';

private static $_instance =false;

public static function instance()
	{
		if(!self::$_instance){
			self::$_instance = new Database();
		}
		return self::$_instance;
	}

	function __construct()
	{
		try{
		    $this->pdo = new \PDO("mysql:host=".$this->host.";dbname=".$this->db,$this->user,$this->pass);
		}catch (PDOExcption $e)
		{
			echo 'Connection failed :' . $e->getMessage();
		}
		
	}

	public function insert($className,$data)
	{
		unset ($data->id);
		$table = $this->stripNameSpace($className);
		
		$bindParam = $this->join(",",$this->makeBindParam($data));
		
		$fields = $this->join(",",$this->makeFields($data));
		$statement = $this->pdo->prepare("INSERT INTO `$table` ($fields) VALUES ($bindParam);");

		return $this->bindStatement($data,$statement)->execute() ? $this->pdo->lastInsertId() : -1;
	}

	private function stripNameSpace($className){
		return substr(strchr($className,"\\"), 1);
	}

	private function join($glue,$data)
	{
		return join($glue,$data);
	}

	private function makeBindParam($data)
	{
		$bindParam =[];
		foreach ($data as $key => $value) {
			$bindParam[] =":$key";
		}
		return $bindParam;
	}

	private function makeFields($data) 
	{
		$fields =[];
		foreach ($data as $key => $value) {
			$fields[] ="`$key`";
		}

		return $fields;
	}

	private function bindStatement($data, $statement){
		$data = (object)$data;
		foreach ($data as $key => $value) {
            $statement->bindParam(":$key",$data->$key);
		}
		return $statement;
	}

	private function makeWhere($data)
	{ 
		$whereParam = [];
		foreach ($data as $key => $value) {
			$whereParam[] ="`$key` = :$key";
		}
		return $whereParam;
	}
	

	public function select($className,$data)
	{	
		$where = $data ? $this->join(" AND ",$this->makeWhere($data)) : '1';
		$table = $this->stripNameSpace($className);
		$statement = $this->pdo->prepare("SELECT * FROM `$table` Where $where");
		$statement = $this->bindStatement($data,$statement);
		$statement->setFetchMode(\PDO::FETCH_CLASS, "\Models\\".$table);
		$statement->execute();
		return $statement->rowCount() > 1 ? $statement->fetchAll() : $statement->fetch();
	}

	public function update($className,$data,$where = false)
	{
		if(!$where)
		{
			$where ="`id` = " .$data->id;
		}
		else {
            $where = $this->join(" AND " ,$this->makeWhere($where));
		}
        $table = $this->stripNameSpace($className);
        $fields =$this->join(",",$this->makeWhere($data));
        $statement = $this->pdo->prepare("UPDATE `$table` SET $fields WHERE $where;");

		return $this->bindStatement($data,$statement)->execute();
	}

	public function delete($className,$data,$where = false)
	{

		$table = $this->stripNameSpace($className);
		if(!$where)
		{
			$where ="`id` = :id";
			$data =['id' => $data->id];
		}
		else {$where = $this->join(" AND " ,$this->makeWhere($where));}
		$statement =$this->pdo->prepare("DELETE FROM `$table` WHERE $where");
		return $this->bindStatement($data,$statement)->execute();

	}


}