<?php
	if ( ! defined('BASEPATH'))
		exit('No direct script access allowed');

	class Gestores_model extends CI_Model {
		private $iNumFilas = 0;

		public function __construct(){
			parent::__construct();
		}

		/**
		 * Modifica los acceso de los usuarios especificados a la plataforma NLM
		 *
		 * @param array $cambios Cambios a llevar a cabo: posicion 2k id del usuario, posicion 2k+1 estado final del acceso
		 */
		public function cambiarAccesoGestores($accesos){
			$usuarios_email = array();

			if(!empty($accesos)){
				$aux = array();

				foreach($accesos as $id_usuario => $acceso){
					if($acceso == 0)
						$aux[] = $id_usuario;
				}

				if(!empty($aux)){ //Sólo vamos a devolver los usuarios que su estado estaba a 1 y se pasado a 0 (aceptado)
					$this->db->select('ges.email as email, ges.nombre as nombre');
					$this->db->from('usuarios usu');
					$this->db->join('gestores ges', 'usu.id_usuario = ges.id_usuario', 'left');
					$this->db->where_in('usu.id_usuario', $aux);
					$this->db->where('usu.estado', 1);
					$query = $this->db->get();

					$usuarios_email = $query->result();
				}

				foreach($accesos as $id_usuario => $acceso){
					$this->db->where('id_usuario', $id_usuario);
					$this->db->set('estado', $acceso);
					$this->db->update('usuarios');
				}
			}

			return $usuarios_email;
		}
                
                /**
		 * Modifica los permisos del anunciante especificado para los medios, realizada por una agencia
		 *
		 * @param integer $id_gestor Id del anunciante para el que modificar los permisos
		 * @param array $cambios Cambios a llevar a cabo: posicion 2k id del medio, posicion 2k+1 estado final del permiso
		 */
		public function cambiarPermisosGestorMedio($id_gestor, $cambios){
			$this->db->select('per.id_medio, IFNULL(per.estado, -1) estado', false);
			$this->db->from('permisos_gestor_medio per');
			$this->db->where('per.id_gestor', $id_gestor);
			$query = $this->db->get();

			$permisos = $query->result();

			if(!empty($cambios)){
				foreach($cambios as $medio => $nuevo_est){
					$accion = 'create';
					$valor = $nuevo_est;

					foreach($permisos as $permiso){
						if($permiso->id_medio == $medio){
							$accion = '';
							$valor = '';

                                                        /**
                                                         * $nuevo_est es el valor nuevo que le hemos aplicado
                                                         * $permiso->estado es el valor que tenía en la Base de datos
                                                         * Case -1 No existe el registro en la BD
                                                         * Case 0  Está Pendiente en la BD
                                                         * Case 1  Está NO Autorizado en la BD
                                                         * Case 2  Está Autorizado en la BD
                                                         * Case 3  Está Sin Asignar en la BD
                                                         **/
							if($nuevo_est == 0){
								switch($permiso->estado){
									case -1:
										break;
									case 0:
										break;
									case 1:
									case 2:
										$accion = 'delete';
										break;
								}
							}
							else if($nuevo_est == 1){
								switch($permiso->estado){
									case -1:
										$accion = 'create';
										$valor = 1;
										break;
									case 0:
									case 2:
										$accion = 'update';
										$valor = 1;
										break;
									case 1:
										break;
									case 3:
										$accion = 'update';
										$valor = 1;
										break;
								}
							}
							else if ($nuevo_est == 2){
								switch($permiso->estado){
									case -1:
										$accion = 'create';
										$valor = 2;
										break;
									case 0:
									case 1:
										$accion = 'update';
										$valor = 2;
										break;
									case 2:
										break;
                                                                        case 3:
										$accion = 'update';
										$valor = 2;
										break;
								}
							}else{                                                            
								switch($permiso->estado){
									case -1:
										break;
									case 0:
										break;
									case 1:
                                                                                $accion = 'delete';
										break;
									case 2:
										$accion = 'delete';
										break;
								}
                                                        }

							break;
						}
					}

					if($accion == 'create' && $valor != 0){
						$datos = array(
							'id_gestor' => $id_gestor,
							'id_medio' => $medio,
							'estado' => $valor
						);

						$this->db->insert('permisos_gestor_medio', $datos);
					}
					else if($accion == 'update'){
						$this->db->set('estado', $valor);
						$this->db->where('id_gestor', $id_gestor);
						$this->db->where('id_medio', $medio);
						$this->db->update('permisos_gestor_medio');
					}
					else if($accion == 'delete'){
						$this->db->where('id_gestor', $id_gestor);
						$this->db->where('id_medio', $medio);
						$this->db->delete('permisos_gestor_medio');
					}
				}
			}
		}
                
                /**
		 * Dar de alta o baja los gestores de medios especificados
		 *
		 * @param array $altas Array de altas y bajas a llevar a cabo: posicion 2k Id del medio, posicion 2k+1 (1 alta, 0 baja)
		 */
		function darAltaGestores($altas){
			if(!empty($altas)){
                $this->load->model('medios_model');
				foreach($altas as $id_medio => $alta){
					if($alta === '0'){ //Si se ha seleccionado la baja del medio
						$this->db->where('id_medio', $id_medio);
						$this->db->where('fecha_baja IS NULL');
						$this->db->set('fecha_baja', date('Y-m-d H:i:s'));
                        $this->db->update('medios');
                                                
                        //Desactivamos el usuario
                        $medio = $this->medios_model->getMedio($id_medio);
                        $this->db->where('id_usuario', $medio->id_usuario);
                        $this->db->where('estado = 0');
                        $this->db->set('estado', 3);
                        $this->db->update('usuarios');
                                                
					}
					else{
						$this->db->where('id_medio', $id_medio);
						$this->db->where('fecha_baja IS NOT NULL');
						$this->db->set('fecha_baja', NULL);
                        $this->db->set('fecha_alta', date('Y-m-d H:i:s'));
                        $this->db->update('medios');
                                                
                        //Reactivamos el usuario
                        $medio = $this->medios_model->getMedio($id_medio);
                        $this->db->where('id_usuario', $medio->id_usuario);
                        $this->db->where('estado <> 0');
                        $this->db->set('estado', 0);
                        $this->db->update('usuarios');
					}

					
				}
			}
		}

		/**
		 * Obtiene los datos del gestor de medios especificado
		 * 
		 * @param integer $id_gestor Id de la agencia a obtener
		 * @return resultSet Datos del gestor de medios obtenido
		 */
		public function getGestor($id_gestor){
			$this->db->select('ges.id_usuario, ges.id_gestor, ges.nombre, ges.email', false);
			$this->db->select('usu.estado, usu.nick, usu.fecha_registro', false);
			$this->db->from('gestores ges');
			$this->db->join('usuarios usu', 'ges.id_usuario = usu.id_usuario', 'left');
			$this->db->where('ges.id_gestor', $id_gestor);
			$query = $this->db->get();

			if($query->num_rows() == 0)
				return false;

			return $query->row();
		}


		/**
		 * Obtiene el listado de gestores resultantes del filtro especificado, funcion asociada al listado de gestores del administrador
		 *
		 * @param array $filtro Opciones de filtrado: (estado, palabra)
		 * @return array Listado de medios obtenidos
		 */
		function getGestoresAdmin($filtro) {
			$this->db->select('SQL_CALC_FOUND_ROWS ges.id_gestor, ges.id_usuario, ges.nombre, ges.email, usu.nick,usu.fecha_registro, usu.estado', false);
			$this->db->select('(SELECT COUNT(per_ges.id_medio) FROM permisos_gestor_medio per_ges WHERE per_ges.id_gestor = ges.id_gestor and per_ges.estado=1) num_medios', false);
			$this->db->from('gestores ges');
			$this->db->join('usuarios usu', 'usu.id_usuario = ges.id_usuario', 'left');

			if(!empty($filtro['estado']) AND $filtro['estado'] != 'todos'){
				if ($filtro['estado'] == 'activo')
					$this->db->where('usu.estado', '0');
				else if ($filtro['estado'] == 'pendiente')
					$this->db->where('usu.estado', '1');
				else
					$this->db->where('usu.estado', '2');
			}

			if(!empty($filtro['nombre'])){
				$this->db->where("(ges.nombre LIKE '%" . $filtro['nombre'] . "%')");
			}

			$this->db->where('tipo_usuario', 'gestor');
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
		 * Obtiene la lista de medios a los que tiene acceso el anunciante especificado
		 *
		 * @param integer $id_cliente Id del anunciante por el que filtrar
		 * @return array Listado de los medios obtenidos
		 */
		function getMediosCliente($id_cliente) {
			$this->db->select('med.id_medio, med.nombre, med.imagen, med.logo');
			$this->db->from('medios med');
			$this->db->join('permisos_cliente_medio pcm', 'med.id_medio = pcm.id_medio', 'left');
			$this->db->where('pcm.id_cliente', $id_cliente);
			$this->db->where('pcm.estado', 1);
			$this->db->where('fecha_baja is null');
			$query = $this->db->get();

			return $query->result();
		}

		/**
		 * Obtiene el listado de medios resultante del filtro especificado, funcion asociada al filtro de ofertas
		 *
		 * @param array $filtro Opciones de filtrado: (id_cliente, tipo_medio)
		 * @return array Listado de medios obtenidos
		 **/
		function getMediosFiltro($filtro) {
			$this->db->select('med.id_medio, med.nombre, med.imagen, med.logo');
			$this->db->from('medios med');

			if(!empty($filtro['id_cliente'])){
				$this->db->join('permisos_cliente_medio pcm', 'med.id_medio = pcm.id_medio', 'left');
				$this->db->where('pcm.id_cliente', $filtro['id_cliente']);
				$this->db->where('pcm.estado', 1);
			}

			$this->db->where('fecha_baja is null');

			if(!empty($filtro['tipo_medio'])){
				$this->db->where_in("med.id_tipo_medio", $filtro['tipo_medio']);
			}

			$query = $this->db->get();

			return $query->result();
		}

		/**
		 * Obtiene el numero total de gestores obtenidos en la funcion getNumGestoresAdmin
		 *
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getNumGestoresAdmin o array(estado, palabra)
		 * @param integer Numero total de gestores obtenidos
		 */
		public function getNumGestoresAdmin($filtro = false){
			if($filtro !== false){
				$this->db->select('SQL_CALC_FOUND_ROWS ges.id_gestor, ges.nombre, ges.email', false);
                                $this->db->select('usu.fecha_registro, usu.estado, cli.id_usuario', false);
                                $this->db->from('gestores ges');
                                $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');

                                if(!empty($filtro['estado']) && $filtro['estado'] != 'todos'){
                                        if ($filtro['estado'] == 'activo')
                                                $this->db->where('usu.estado', '0');
                                        else if ($filtro['estado'] == 'pendiente')
                                                $this->db->where('usu.estado', '1');
                                        else
                                                $this->db->where('usu.estado', '2');
                                }

                               
                                if(!empty($filtro['nombre'])){
                                        $this->db->where("(ges.nombre LIKE '%" . $filtro['nombre'] . "%' ges.email LIKE '%" . $filtro['nombre'] . "%')");
                                }

                                

                                $this->db->where('tipo_usuario', 'gestor');
				$query = $this->db->get();
				$oResultado = $query->row();

				$this->iNumFilas = $oResultado->numGestores;
			}

			return $this->iNumFilas;
		}
                
                /**
		 * Obtiene el numero de usuarios con permisos pendientes
		 * 
		 * @return integer Numero de usuarios con permisos pendientes
		 */
		function getNumUsuariosPermisosPendientes($id_gestor){
			                        
                        $this->db->select('SQL_CALC_FOUND_ROWS per_ges.id_medio', false);			
			$this->db->from('permisos_gestor_medio per_ges');                        
			$this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
			$this->db->where('per_ges.estado', 1);
                        
                        $query = $this->db->get();
			$aRet1 = $query->result();
                        
                        $medios = Array();
                        $i = 0;
                        
                        foreach ($aRet1 as $medio){
                            
                            $medios[$i] = $medio->id_medio;
                            $i++;
                        }
                        
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			//$this->db->from('eventos eve');
			$query = $this->db->get ();
			$numMedios = $query->row()->totalRows;
                        
			$this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, '.$numMedios.' - '
                                . '(SELECT COUNT(per.estado) FROM medios med_user LEFT JOIN permisos_cliente_medio per ON med_user.id_medio = per.id_medio '
                                . 'WHERE med_user.id_medio IN ('.implode(",", $medios).') '
                                . 'AND per.id_cliente = cli.id_cliente AND (per.estado = 1 OR per.estado=2)) permisos_pdtes', false);			
			$this->db->from('clientes cli');                        
			$this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario', 'left');
                        $this->db->where('usu.estado', 0);
                        
                        $this->db->having('permisos_pdtes >0');
			$query = $this->db->get();
                        
                        //recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			//$this->db->from('eventos eve');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
			return $query->row()->totalRows;
			
			
		}
                
                /**
		 * Obtiene el listado de permisos a medios resultantes del filtro especificado
		 *
		 * @param array $filtro Opciones de filtrado: (cliente, estado, tipo_medio, pagina, datosPorPagina)
		 * @return array Listado de permisos obtenido
		 */
		public function getPermisosGestorMedios($filtro) {
			$this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo, tim.tipo', false);
			$this->db->select('(SELECT per.estado FROM permisos_gestor_medio per WHERE ' . ((!empty($filtro['gestor'])) ? ('per.id_gestor = ' . $filtro['gestor'] . ' AND ') : '') . 'per.id_medio = med.id_medio) estado', false);
			$this->db->from('medios med');
			$this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');

			if(!empty($filtro['tipo_medio']) && $filtro['tipo_medio'] != 0)
				$this->db->where('med.id_tipo_medio', $filtro['tipo_medio']);

			if(isset($filtro['estado']) && $filtro['estado'] != 'todos'){
				if($filtro['estado'] == 0){
					$this->db->having('estado', 0);
				}
				else if($filtro['estado'] == 1){
					$this->db->having('estado', 1);
				}
				else if($filtro['estado'] == 2){
					$this->db->having('(estado = 2 OR estado IS NULL)');
				}
			}

			$this->db->order_by('med.nombre', 'asc');
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
		 * Obtiene el listado de tipos de medio
		 *
		 * @return array Listado de tipos de medios obtenidos
		 */
		function getTiposMedios() {
			$this->db->select('id_tipo, tipo');
			$this->db->from('tipos_medio');
			$this->db->order_by('orden', 'asc');
			$query = $this->db->get();

			return $query->result();
		}

		/**
 		 * Inserta un gestor
 		 * 
 		 * @param array $datos_gestor Datos del gestor a insertar
 		 * @return integer Id del gestor insertado
 		 */
 		public function insertGestor($datos_gestor){
 			$this->db->insert('gestores', $datos_gestor);

 			return $this->db->insert_id();
 		}
                
                /**
		 * Obtiene el numero total de permisos obtenido en la funcion getPermisosCliente
		 *
		 * @param array $filtro Opciones de filtrado
		 * @return integer Numero total de permisos obtenidos
		 */
		public function getNumPermisosGestor($filtro = false){
			if($filtro !== false){
				$this->db->select('COUNT(med.id_medio) AS numPermisos');
				$this->db->from('medios med');
                $this->db->where('med.fecha_baja is null');
				$query = $this->db->get();
				$oResultado = $query->row();

				$this->iNumFilas = $oResultado->numPermisos;
			}

			return $this->iNumFilas;
		}
                

		/**
		 * Actualiza el gestor de medios especificado
		 *
		 * @param integer $id_gestor Id del gestor de medios a actualizar
		 * @param array $datos_gestor Array con los datos a actualizar del medio
		 * @return boolean Siempre true
		 */
		public function updateGestor($id_gestor, $datos_gestor){
			$this->db->where('id_gestor', $id_gestor);
			$this->db->update('gestores', $datos_gestor);

			return true;
		}
	}