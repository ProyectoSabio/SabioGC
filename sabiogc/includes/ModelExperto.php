<?php
require_once "ConnectDB.php";
class ModelExperto
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


	//Función que devuelve todos los expertos
	public function getExpertos() {
		$result = false;
		try {
			$sql = "SELECT `id`, `nombre`, `usuario`, `password`, `email`, `estado`  FROM `expertos` WHERE `trash` = '0'";
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			if ($resultado) {
				$result = $this->mostrarExpertos($resultado);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve un experto
	public function getExperto($usuario) {
		$result = false;
		try {
			$sql = 'SELECT `id`, `nombre`, `usuario`, `password`, `email`, `estado` FROM `expertos` WHERE `usuario` = :usuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $usuario);
			$query->execute();
			$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			if ($resultado) {
				$result = $this->mostrarExpertos($resultado);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función que devuelve las categorías de un experto
	public function getCategoriaExperto($id) {
		$result = false;
		try {
			$sql = 'SELECT `categoria` FROM `expcategorias` WHERE `idExperto` = :id';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('id', $id);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para eliminar un experto
	public function delExperto($usuario) {
		$result = false;
		try {
			$sql = 'DELETE `expcategorias` FROM `expcategorias` INNER JOIN `expertos` ON `expcategorias`.`idExperto` = `expertos`.`id` WHERE `expertos`.`usuario` = :usuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $usuario);
			$result = $query->execute();
			$sql = 'DELETE FROM `expertos` WHERE `usuario` = :usuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $usuario);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para validar un experto
	public function validaExperto($usuario) {
		try {
			$valido = 1;
			$sql = 'UPDATE `expertos` SET `estado`=:valido WHERE `usuario` = :usuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $usuario);
			$query->bindParam('valido', $valido);

			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para insertar un experto
	public function insExperto($valores, $categorias) {
		$token = bin2hex(random_bytes(8));
		$caducidad = date("Y-m-d H:i:s", strtotime(tomorrow));
		try {
			$sql = 'SELECT COUNT(`usuario`) AS filU, COUNT(`email`) AS filE FROM `expertos` WHERE UPPER(`usuario`) = :usuario OR UPPER(`email`) = :email';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('usuario', $valores['usuario']);
			$query->bindParam('email', $valores['email']);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if ($result[0]['filU'] > 0 || $result[0]['filE'] > 0) {
				$result = "El usuario ya existe.";
			} else {
				$sql = 'INSERT INTO `expertos`(`nombre`,`usuario`,`password`,`email`,`token`,`caducidadToken`)
				VALUES (:nombre,:usuario,:password,:email,:token,:caducidadToken)';
				$query = $this->_mngDB->prepare($sql);
				$query->bindParam('nombre', $valores['nombre']);
				$query->bindParam('usuario', $valores['usuario']);
				$query->bindParam('password', $valores['password']);
				$query->bindParam('email', $valores['email']);
				$query->bindParam('token', $token);
				$query->bindParam('caducidadToken', $caducidad);

				$result = $query->execute();
				$this->asignarCategorias($valores['usuario'], $categorias);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	//Función para editar un experto
	public function updExperto($oldExperto, $valores, $categorias) {
		$token = bin2hex(random_bytes(8));
		$caducidad = date("Y-m-d H:i:s", strtotime(tomorrow));
		try {
			$sql = 'UPDATE `expertos` SET `nombre`=:nombre,`usuario`=:usuario,`password`=:password,`email`=:email WHERE `usuario` = :oldUsuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('nombre', $valores['nombre']);
			$query->bindParam('usuario', $valores['usuario']);
			$query->bindParam('password', $valores['password']);
			$query->bindParam('email', $valores['email']);
			$query->bindParam('oldUsuario', $oldExperto);
			$result = $query->execute();
			$this->asignarCategorias($valores['usuario'], $categorias);
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

	public function updExpertoToken($oldExperto) {
		$token = bin2hex(random_bytes(8));
		$caducidad = date("Y-m-d H:i:s", strtotime(tomorrow));
		try {
			$sql = 'UPDATE `expertos` SET `token`=:token,`caducidadToken`=:caducidadToken WHERE `usuario` = :oldUsuario';
			$query = $this->_mngDB->prepare($sql);
			$query->bindParam('token', $token);
			$query->bindParam('oldUsuario', $oldExperto);
			$query->bindParam('caducidadToken', $caducidad);
			$result = $query->execute();
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}
	//Función para asignar las categorías a un experto
	public function asignarCategorias($usuario, $categorias) {
		try {
			if (empty($categorias)) {
				$sql = 'SELECT `expcategorias`.`id` FROM `expcategorias` INNER JOIN `expertos`
				ON `expcategorias`.`idExperto` = `expertos`.`id` WHERE `expertos`.`usuario` = :usuario';
				$query = $this->_mngDB->prepare($sql);
				$query->bindParam('usuario', $usuario);
				$query->execute();
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				$sql = 'DELETE FROM `expcategorias` WHERE `id` = :id';
				$query = $this->_mngDB->prepare($sql);
				$query->bindParam('id', $result[0]['id']);
				$query->execute();
			} else {
				$categoriasExistentes = array();
				$sql = 'SELECT `expcategorias`.`idExperto`, `expcategorias`.`categoria` FROM
				`expcategorias` INNER JOIN `expertos` ON `expcategorias`.`idExperto` = `expertos`.`id`
				WHERE `expertos`.`usuario` = :usuario';
				$query = $this->_mngDB->prepare($sql);
				$query->bindParam('usuario', $usuario);
				$query->execute();
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result as $key => $value) {
					if (!in_array($value['categoria'], $categorias)) {
						$sql = 'DELETE FROM `expcategorias` WHERE `idExperto` = :id AND `categoria` = :categoria';
						$query = $this->_mngDB->prepare($sql);
						$query->bindParam('id', $value['idExperto']);
						$query->bindParam('categoria', $value['categoria']);
						$query->execute();
					} else {
						array_push($categoriasExistentes, $value['categoria']);
					}
				}
				foreach ($categorias as $value) {
					if (!in_array($value, $categoriasExistentes)) {
						$sql = 'SELECT `id` FROM `expertos` WHERE `usuario` = :usuario';
						$add = 'INSERT INTO `expcategorias`(`idExperto`,`categoria`) VALUES (('.$sql.'),:categoria)';
						$query = $this->_mngDB->prepare($add);
						$query->bindParam('usuario', $usuario);
						$query->bindParam('categoria', $value);
						$query->execute();
					}
				}
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}

	//Función que devuelve un experto
	public function mostrarExpertos($resultado) {
		try {
			$listaExpertos = array();
			$listaCategorias = array();
			foreach ($resultado as $key => $value) {
				$sql = 'SELECT `categoria` FROM `expcategorias` WHERE `idExperto` = :id';
				$query = $this->_mngDB->prepare($sql);
				$query->bindParam('id', $value['id']);
				$query->execute();
				$resultCategoria = $query->fetchAll(PDO::FETCH_ASSOC);
				foreach ($resultCategoria as $key1 => $value1) {
					array_push($listaCategorias, $value1['categoria']);
				}
				array_push($listaExpertos, array("id" => $value['id'], "nombre" => $value['nombre'], "usuario" => $value['usuario'], "password" => $value['password'], "email" => $value['email'], "estado" => $value['estado'], "categorias" => $listaCategorias));
				$listaCategorias = array();
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $listaExpertos;
	}

	//Función para la búsqueda de expertos
	public function buscarExpertos($patron) {
		$result = false;
		try {
			$sql = "SELECT `id`, `nombre`, `usuario`, `password`, `email`, `estado` FROM `expertos` WHERE `nombre` LIKE '%$patron%' ORDER BY `nombre` ASC";
			$query = $this->_mngDB->prepare($sql);
			$query->execute();
			$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			if ($resultado) {
				$result = $this->mostrarExpertos($resultado);
			}
			$this->_mngDB = null;
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return $result;
	}

}
