<?php

class MySQLiManager{

	private $link;

	public function __construct($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME)
	{
		$this->DB_HOST= $DB_HOST;
		$this->DB_USER= $DB_USER;
		$this->DB_PASS= $DB_PASS;
		$this->DB_NAME=$DB_NAME;
		$this->link = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

	}

	/**
	* __destruct
	*
	* @author Pabhoz
	*
	* Destruye el hilo y cierra la conexión.
	*
	*/
	function __destruct()
	{
		$thread_id = $this->link->thread_id;
		$this->link->kill($thread_id);
		$this->link->close();
	}

	/**
	* select
	*
	* @author Pabhoz
	*
	* retorna el resultado de una query de tipo SELECT a una base de datos
	* MySQL.
	*
	* @param String $attr Atributo a seleccionar de la tabla
	* @param String $table Tabla de la que se selecciona
	* @param String $where condicional (opcional) de la selección
	* 
	* @return Array<String> $response / Error
	*/
	function select($attr,$table,$where = ''){

		$where = ($where != '' ||  $where != null) ? "WHERE ".$where : '';
		$stmt = "SELECT ".$attr." FROM ".$table." ".$where.";";
		$result = $this->link->query($stmt) or die($this->link->error.__LINE__);
		if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $response[] = $row;
                    }
                    return $response;
		}

	}

	/**
	* insert
	*
	* @author Pabhoz
	*
	* inserta registros enviados en formato INSERT a una base
	* de datos MySQL
	*
	* @param String $table Tabla en la que se insertarán los datos
	* @param Array $values Arreglo de datos a insertar cuyo indice corresponde
	*					   al atributo en la base de datos.
	* @param String $where condicional (opcional) de la selección
	* @param Boolean $sanear Condicional que determina si debe ser saneada la cadena
	*
	* @return Boolean $response true/false
	*/
	function insert($table,$values,$where = '',$sanear = false){
                
                $columnas = null;
                $valores = null;
            
		foreach ($values as $key => $value) {
			$columnas.=$key.',';
			if( $sanear == true){
				$valores.='"'.ucwords(strtolower($value)).'",';
			}else{
				$valores.='"'.$value.'",';
			}
		}
		$columnas = substr($columnas, 0, -1);
		$valores = substr($valores, 0, -1);

		$stmt = "INSERT INTO ".$table." (".$columnas.") VALUES(".$valores.") ".$where.";";
		
                //$result = $this->link->query($stmt) or die($this->link->error.__LINE__);
                $result = $this->link->query($stmt) or die($this->link->error);
		
                if($result > 0) {
			$response = false;
		}
		else {
			$response = true;
		}

		return $response;

	}

	/**
	* update
	*
	* @author Pabhoz
	*
	* Actualiza registros en la tabla deseada mediante una String en formato
	* Update Query de MySQL
	*
	* @param String $table Tabla de la base de datos
	* @param Array<String> $values Valores ordenados en formato [attr] = value  
	* @param String $where Sentencia where
	*
	* @return Boolean $response
	*/
	function update($table,$values,$where){
            
		foreach ($values as $key => $value) {

			$valores .= $key.'="'.$value.'",';
		}
		$valores = substr($valores,0,strlen($valores)-1);
		$stmt = "UPDATE $table SET $valores WHERE $where";

		$result = $this->link->query($stmt) or die($this->link->error.__LINE__);
		if($result->num_rows > 0) {
			$response = false;
		}
		else {
			$response = true;
		}

		return $response;
	}

	/**
	* update
	*
	* @author Pabhoz
	*
	* Eliminar registros en la tabla deseada mediante una String en formato
	* Update Query de MySQL
	*
	* @param String $table Tabla de la base de datos
	* @param Array<String>/String $values Valores ordenados en formato [attr] = value
	*								      o en otro caso sentencia  
	* @param Boolean $complex Indica si se usará $values como string o como arreglo
	*
	* @return Boolean $response
	*/
	function delete($table,$values,$complex = false){

		if($complex){ $where = $values; }else{
			foreach ($values as $key => $value) {
				$where = $key.'="'.$value.'"';
			}
		}

		$stmt = 'DELETE FROM '.$table.' WHERE '.$where;
		$result = $this->link->query($stmt) or die($this->link->error.__LINE__);
		if($result->num_rows > 0) {
			$response = false;
		}
		else {
			$response = true;
		}

		return $response;

	}

	/**
	* update
	*
	* @author Pabhoz
	*
	* Checar la existencia de un registro en la base de datos
	*
	* @param String $what Atributo a checkear
	* @param String $table Tabla de la base de datos
	* @param Array<String>/String $values Valores ordenados en formato [attr] = value
	*								      o en otro caso sentencia  
	* @param Boolean $complex Indica si se usará $values como string o como arreglo
	*
	* @return Boolean $response
	*/
	function check($what,$table,$values,$complex = false){

		if($complex){ $where = $values; }else{
			foreach ($values as $key => $value) {
				$where = $key.'="'.$value.'"';
			}
		}

		$stmt = "SELECT ".$what." FROM ".$table." WHERE ".$where;
		$result = $this->link->query($stmt) or die($this->link->error.__LINE__);
		if($result->num_rows > 0) {
			$response = true;
		}
		else {
			$response = false;
		}

		return $response;

	}

}

?>