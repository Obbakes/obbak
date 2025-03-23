<?php
	if ( ! defined('BASEPATH'))
		exit('No direct script access allowed');

	class Agencias_model extends CI_Model {
 		public function __construct(){
  			parent::__construct();
			$this->load->library('email');
 		}
		
		/**
		 * Modifica el acceso a la plataforma NLM de las agencias especificadas
		 * 
		 * @param array $accesos Acceso a modificar: posicion 2k id de la agencia, posicion 2k+1 acceso final
		 */
		public function cambiarAccesoAgencias($accesos){
			$usuarios_email = array();

			if(!empty($accesos)){
				$aux = array();

				foreach($accesos as $id_usuario => $acceso){
					if($acceso == 0)
						$aux[] = $id_usuario;
				}

				$this->db->select('age.email');
				$this->db->from('usuarios usu');
				$this->db->join('agencias age', 'usu.id_usuario = age.id_usuario', 'left');
				$this->db->where_in('usu.id_usuario', $aux);
				$this->db->where('usu.estado', 1);
				$query = $this->db->get();

				$usuarios_email = $query->result();

				foreach($accesos as $id_usuario => $acceso){
					$this->db->where('id_usuario', $id_usuario);
					$this->db->set('estado', $acceso);
					$this->db->update('usuarios');
				}
			}

			return $usuarios_email;
		}
		
		/**
		 * Obtiene los datos de la agencia especificada
		 * 
		 * @param integer $id_agencia Id de la agencia a obtener
		 * @return resultSet Datos de la agencia obtenida
		 */
		public function getAgencia($id_agencia){
			$this->db->select('age.id_usuario, age.id_agencia, age.nombre, age.email, age.telefono, age.cp, age.poblacion, age.direccion, age.porcentaje, age.cif', false);
			$this->db->select('usu.estado, usu.fecha_registro, age.fecha_alta', false);
			$this->db->from('agencias age');
			$this->db->join('usuarios usu', 'age.id_usuario = usu.id_usuario', 'left');
			$this->db->where('age.id_agencia', $id_agencia);
			$query = $this->db->get();

			if($query->num_rows() == 0)
				return false;

			return $query->row();
		}
 		
 		/**
 		 * Obtiene el listado de las agencias
 		 * 
 		 * @return array Listado de agencia obtenidas
 		 */
 		public function getAgencias(){
 			$this->db->select('id_agencia, nombre');
 			$this->db->from('agencias');
 			$this->db->order_by('nombre', 'asc');
 			$query = $this->db->get();

 			return $query->result();
 		}
 		
 		/**
 		 * Obtiene el listado de agencias resultante del filtro especificado, asociada al area de administrador
 		 * 
 		 * @param array $filtro Opciones de filtrado: (estado, agencia, pagina, datosPorPagina)
 		 * @return array Listado de agencias obtenidas
 		 */
		public function getAgenciasAdmin($filtro) {
			$this->db->select('SQL_CALC_FOUND_ROWS age.id_agencia, age.id_usuario, age.nombre, age.email, usu.fecha_registro, usu.estado', false);
			$this->db->select('(SELECT COUNT(cli.id_cliente) FROM clientes cli WHERE cli.id_agencia = age.id_agencia) num_anunciantes', false);
			$this->db->from('agencias age');
			$this->db->join('usuarios usu', 'usu.id_usuario = age.id_usuario', 'left');

			if(!empty($filtro['estado']) AND $filtro['estado'] != 'todos'){
				if ($filtro['estado'] == 'activo')
					$this->db->where('usu.estado', '0');
				else if ($filtro['estado'] == 'pendiente')
					$this->db->where('usu.estado', '1');
				else
					$this->db->where('usu.estado', '2');
			}

			if(!empty($filtro['agencia'])){
				$this->db->where("(age.nombre LIKE '%" . $filtro['agencia'] . "%')");
			}

			$this->db->where('tipo_usuario', 'agencia');
			$this->db->order_by('nombre', 'asc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();

			$aRet = $query->result();

			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			//$this->db->from('eventos eve');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;

			return $aRet;
		}
		
		/**
		 * Obtiene el numero totl de agencias obtenidas en la funcion getAgenciasAdmin
		 * 
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getAgenciasAdmin o array(estado, agencia)
		 * @return array Listado de agencias obtenidas
		 */
		public function getNumAgenciasAdmin($filtro = false){
			if($filtro !== false){
				$this->db->select('COUNT(age.id_agencia) AS numAgencias');
				$this->db->from('agencias age');
				$this->db->join('usuarios usu', 'usu.id_usuario = age.id_usuario', 'left');

				if(!empty($filtro['estado']) AND $filtro['estado'] != 'todos'){
					if ($filtro['estado'] == 'activo')
						$this->db->where('usu.estado', '0');
					else if ($filtro['estado'] == 'pendiente')
						$this->db->where('usu.estado', '1');
					else
						$this->db->where('usu.estado', '2');
				}

				if(!empty($filtro['agencia'])){
					$this->db->where("(cli.nombre LIKE '%" . $filtro['agencia'] . "%')");
				}

				$this->db->where('tipo_usuario', 'agencia');
				$query = $this->db->get();
				$oResultado = $query->row();

				$this->iNumFilas = $oResultado->numAgencias;
			}

			return $this->iNumFilas;
		}
 		
 		/**
 		 * Inserta una agencia
 		 * 
 		 * @param array $datos_agencia Datos de la agencia a insertar
 		 * @return integer Id de la agencia insertada
 		 */
 		public function insertAgencia($datos_agencia){
 			$this->db->insert('agencias', $datos_agencia);

 			return $this->db->insert_id();
 		}
		
		/**
		 * Actualiza la agencia especificada
		 * 
		 * @param integer $id_agencia Id de la agencia a actualizar
		 * @param array $datos_agencia Datos a actualizar de la agencia
		 */
		public function updateAgencia($id_agencia, $datos_agencia){
			$this->db->where('id_agencia', $id_agencia);
			$this->db->update('agencias', $datos_agencia);

 			if(!empty($datos_agencia['email'])){
 				$this->db->set('nick', $datos_agencia['email']);
 				$this->db->where('usu.id_usuario = age.id_usuario');
 				$this->db->where('age.id_agencia', $id_agencia);
 				$this->db->update('usuarios usu, agencias age');
 			}
		}
	}