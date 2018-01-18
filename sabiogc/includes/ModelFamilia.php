<?php
require_once "ConnectDB.php";
class ModelFamilia 
{
	private $_mngDB;

	public function __construct() {
		try {
			$conexion = new ConnectDB();
			$this->_mngDB = $conexion->get_mngDB();
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}

	//Devuelve el número total de columnas
	public function numFamilias() {
		$result = false;
		try {
			$sql = 'SELECT COUNT(*) FROM `familias`';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchcolumn();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}
 
	//Función que devuelve todas las familias
	public function getFamilias() {
		$result = false;
		try {
			$sql = 'SELECT `familia` FROM `familias`';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para paginado
	public function getFamiliasPag($from_record_num, $records_per_page) {
		$result = false;
		try {
			$sql = 'SELECT `familia` FROM familias ORDER BY familia ASC LIMIT '.$from_record_num.' , '.$records_per_page.'';
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve una familia
	public function getFamilia($familia) { //Recibe como parámetro la clave primaria
		$result = false;
		try {
			$sql = 'SELECT * FROM `familias` WHERE `familia` = :familia';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('familia', $familia);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para eliminar una familia
	public function delFamilia($familia) {
		$result = false;
		try {
			$sql = 'DELETE FROM `familias` WHERE `familia` = :familia';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('familia', $familia);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para insertar una familia
	public function insFamilia($valores) { //Recibe como parámetro un array de valores
		try {
			$sql = 'INSERT INTO `familias`(`familia`) VALUES (:familia)';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('familia', $valores['familia']);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para editar una familia
	public function updFamilias($oldFamilia, $valores) {
		try {
			$newFamilia = $valores['familia'];
			$sql = 'UPDATE `familias` SET `familia` = :newfamilia WHERE `familias`.`familia` = :familia';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('newfamilia', $newFamilia);
			$query->bindParam('familia', $oldFamilia);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para la búsqueda de categorías
	public function buscarFamilias($patron) {
		$result = false;
		try {
			$sql = "SELECT `familia` FROM `familias` WHERE `familia` LIKE '%$patron%' ORDER BY `familia` ASC";
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

}