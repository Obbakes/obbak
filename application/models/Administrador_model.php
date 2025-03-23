<?php 
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');
		
	class Administrador_model extends CI_Model {
 		
		public function __construct() {
  			parent::__construct();
			$this->load->library('email');		
 		}
		
		/**
		 * Obtiene el numero de ofertas que caducan en diez dias
		 * 
		 * @return integer Numero de ofertas que caducan en diez dias
		 */
		function getNumOfertasCaducan10Dias(){
			$this->db->select('count(ofe.id_oferta) num_ofertas');
			$this->db->from('ofertas ofe');
			$this->db->where('DATEDIFF(ofe.fecha_fin_pub, CURRENT_DATE()) <=', '10');
			$this->db->where('DATEDIFF(ofe.fecha_fin_pub, CURRENT_DATE()) >', '1');
			$this->db->where('ofe.fecha_fin_pub IS NOT NULL');
			$query = $this->db->get();
			
			$resultado = $query->row();
			
			return $resultado->num_ofertas;
		}
		
		/**
		 * Obtiene el numero de ofertas que caducan hoy
		 * 
		 * @return integer Numero de ofertas que caducan hoy
		 */
		function getNumOfertasCaducanHoy(){
			$this->db->select('count(ofe.id_oferta) num_ofertas');
			$this->db->from('ofertas ofe');
			$this->db->where('ofe.fecha_fin_pub', 'CURRENT_DATE()');
			$query = $this->db->get();
			
			$resultado = $query->row();
			
			return $resultado->num_ofertas;
		}
		
		/**
		 * Obtiene el numero de ofertas pendientes
		 * 
		 * @return integer Numero de ofertas pendientes
		 */
		function getNumOfertasGestionar(){
			$this->db->select('ofe.id_oferta, (SELECT COUNT(ins.id_inscripcion_oferta) FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND (estado = 0 OR estado = 1)) gestionar', false);
			$this->db->distinct();
			$this->db->from('ofertas ofe');
			$this->db->having('gestionar >', 0);
			$query = $this->db->get();
			
			return $query->num_rows();
		}	

		/**
		 * Obtiene el numero de ofertas publicadas
		 *
		 * @return integer Numero de ofertas publicadas
		 */
		function getNumOfertasPublicadas(){
			$this->db->select('count(ofe.id_oferta) num_ofertas');
			$this->db->from('ofertas ofe');
			$this->db->where('ofe.publicada', 1);
			$query = $this->db->get();
			
			$resultado = $query->row();
			
			return $resultado->num_ofertas;
		}	
				
 		/**
 		 * Obtiene el numero de usuarios pendientes
 		 * 
 		 * @return integer Numero de usuarios pendientes
 		 */
		function getNumUsuariosPendientes(){
			$this->db->select('count(usu.id_usuario) num_usuarios');
			$this->db->from('clientes cli');
			$this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario', 'left');
			$this->db->where('usu.estado', 1);
			$query = $this->db->get();
			
			$resultado = $query->row();
			
			return $resultado->num_usuarios;
		}
		
		/**
		 * Obtiene el numero de usuarios con permisos pendientes
		 * 
		 * @return integer Numero de usuarios con permisos pendientes
		 */
		function getNumUsuariosPermisosPendientes(){
			$consulta = 'SELECT COUNT(pen.id_usuario) num_usuarios
						 FROM (SELECT cli.id_usuario, COUNT(per.id_permiso_cliente_medio) num_permisos
							   FROM clientes cli
							   LEFT JOIN permisos_cliente_medio per ON cli.id_cliente = per.id_cliente
							   GROUP BY cli.id_usuario) pen
						 WHERE pen.num_permisos = 0';
			$query = $this->db->query($consulta);
			
			$resultado = $query->row();
			
			return $resultado->num_usuarios;
		}
                
                /**
 		 * Inserta un registro de acción
 		 *
 		 * @param array $datos Array con los datos del registro a insertar
 		 * 
 		 */
 		public function insertLogAccion($datos){
 			$this->db->insert('log_acciones',$datos);

 			return true;
 		}
		
		/**
		 * Inserta un usuario
		 * 
		 * @param array $datos_usuario Datos del usuario a insertar
		 * @return integer Id del usuario insertado
		 */
		function insertUsuario($datos_usuario){
			$this->db->insert('usuarios', $datos_usuario);
			
			return $this->db->insert_id();
		}
		
		/**
		 * Actualiza el usuario especificado
		 * 
		 * @param integer $id_usuario Id del usuario a actualizar
		 * @param array $datos_usuario Datos a actualizar del usuario
		 */
		function updateUsuario($id_usuario, $datos_usuario){
			$this->db->where('id_usuario', $id_usuario);
			$this->db->update('usuarios', $datos_usuario);
                        
                        //Registramos en el log el login del medio
                        $data = array(
                            'id_usuario' => $id_usuario,
                            'fecha' => date("Y-m-d H:i:s"),
                            'accion' => 'cambio perfil'

                                    );


                        $this->db->insert('log_acciones',$data);
		}
	}
?>