<?php 
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');

	class Newsletters_model extends CI_Model {

		/**
		 * Numero de filas de la última consulta
		 *
		 * @var integer
		 */
		private $iNumFilas = 0;
		
 		public function __construct() {
			parent::__construct();
			$this->load->library('email');
		}
		
		/**
		 * Cancela una newsletter
		 * 
		 * @param integer $id_newsletter Id de la newsletter a cancelar
		 */
		public function cancelarNewsletter($id_newsletter){
			$this->db->set('estado', 'c');
			$this->db->where('id_newsletter', $id_newsletter);
			$this->db->update('newsletters');
		}
		
		/**
		 * Elimina los destinatarios de la newsletter especificada
		 * 
		 * @param integer $id_newsletter Id de la newsletter para la que eliminar los destinatarios
		 */
		public function deleteDestinatariosNewsletter($id_newsletter){
			$this->db->where('id_newsletter', $id_newsletter);
			$this->db->delete('newsletter_destinatarios');
		}

		/**
		 * Obtiene un codigo de 12 caracteres alfanumericos aleatorio
		 *
		 * @return string Codigo obtenido
		 */
		public function generarCodigo(){
			$pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$codigo;

			$codigo = '';

			for($i = 0; $i < 12; $i++){
				$codigo .= $pattern[rand(0, strlen($pattern) - 1)];
			}

			return $codigo;
		}

		/**
		 * Obtiene los datos de las agencias especificadas
		 *
		 * @param array $agencias Array de ids de las agencias a obtener
		 * @return array Listado de agencias obtenidas
		 */
		public function getAgencias($agencias){
			if(empty($agencias))
				return array();
			
			$this->db->select('age.id_agencia, age.nombre');
			$this->db->from('agencias age');
			$this->db->where('age.email !=', '');
			$this->db->where('age.email IS NOT NULL');
			$this->db->where_in('age.id_agencia', $agencias);
			$this->db->order_by('age.nombre', 'asc');
			$query = $this->db->get();
			
			return $query->result();
		}

		/**
		 * Obtiene las agencias resultantes del filtro especificado
		 *
		 * @param array $filtro Opciones de filtrado: (agencias, palabra, pagina, datosPorPagina)
		 * @return array Listado de agencias obtenidas
		 */
		public function getAgenciasNewsletter($filtro){
			$this->db->select('SQL_CALC_FOUND_ROWS age.id_agencia, age.nombre, age.email', false);
			$this->db->from('agencias age');
			
			if(!empty($filtro['agencias']))
				$this->db->where_not_in('age.id_agencia', $filtro['agencias']);
			
			if(!empty($filtro['palabra']))
				$this->db->where("age.nombre LIKE '%" . $filtro['palabra'] . "%'");
			
			$this->db->order_by('age.nombre', 'asc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();
	
			$aRet = $query->result();
	
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
	
			return $aRet;
		}

		/**
		 * Obtiene los datos de los anunciantes especificados
		 *
		 * @param array $ofertas Array de ids de las ofertas a las que tiene acceso los anunciantes a obtener
		 * @param array $clientes Array de ids de los anunciantes a obtener
		 * @return array Listado de anunciantes obtenidos
		 */
		public function getClientes($ofertas, $clientes){
			if(empty($clientes))
				return array();
			
			$this->db->select('cli.id_cliente, cli.nombre');
			$this->db->distinct();
			$this->db->from('clientes cli');
                        
           $this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario', 'left');
			
			if(!empty($ofertas)){
				$this->db->join('permisos_cliente_medio pcm', 'cli.id_cliente = pcm.id_cliente', 'left');
				$this->db->join('ofertas ofe', 'pcm.id_medio = ofe.id_medio', 'left');
				$this->db->where_in('ofe.id_oferta', $ofertas);
			}
            $this->db->where('usu.estado', 0);
			$this->db->where('cli.email !=', '');
			$this->db->where('cli.email IS NOT NULL');
			$this->db->where_in('cli.id_cliente', $clientes);
			$this->db->order_by('cli.nombre', 'asc');
			$query = $this->db->get();
			
			return $query->result();
		}

		/**
		 * Obtiene los anunciantes resultantes del filtro especificado
		 *
		 * @param array $filtro Opciones de filtrado: (clientes, palabra, ofertas, pagina, datosPorPagina)
		 * @return array Listado de medios obtenidos
		 */
		public function getClientesNewsletter($filtro){
			$this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, cli.nombre, cli.email', false);
			$this->db->distinct();
			$this->db->from('clientes cli');
            $this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario');
			$this->db->join('permisos_cliente_medio pcm', 'cli.id_cliente = pcm.id_cliente');
			$this->db->join('ofertas ofe', 'pcm.id_medio = ofe.id_medio');
			
			if(!empty($filtro['clientes'])){
				$this->db->where_not_in('cli.id_cliente', $filtro['clientes']);
			}
			
			if(!empty($filtro['palabra'])){
				$this->db->where("cli.nombre LIKE '%" . $filtro['palabra'] . "%'");
			}
			
			if(!empty($filtro['ofertas'])){
				$this->db->where_in('ofe.id_oferta', $filtro['ofertas']);
			}
			
			if(!empty($filtro['agencia'])){
				$this->db->where('cli.id_agencia', $filtro['agencia']);
			}
			
            $this->db->where('usu.estado', 0);
			$this->db->where('pcm.estado', 1);
			$this->db->where('cli.newsletter', 1);
			$this->db->order_by('cli.nombre', 'asc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();
			//print_r($this->db->last_query());
			
			$aRet = $query->result();
	
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
	
			return $aRet;
		}
		
		/**
		 * Obtiene las estadisticas generales de una newsletter
		 * 
		 * @param array $filtro Opciones de filtrado: newsletter
		 * @param array Datos estadisticos
		 */
		function getEstadisticasGenerales($filtro){
			$this->db->select('new.id_newsletter, new.nombre, new.ofertas', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_newsletter = new.id_newsletter AND esn.tipo_acceso = \'n\') clicksNewsletter', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_newsletter = new.id_newsletter AND esn.tipo_acceso = \'v\') clicksOfertas', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_newsletter = new.id_newsletter AND esn.tipo_acceso = \'c\') clicksContacto', false);
			$this->db->from('newsletters new');
			
			if(!empty($filtro['newsletter']))
				$this->db->where('new.id_newsletter', $filtro['newsletter']);
			
			$this->db->where('new.confirmada', 1);
			$this->db->where('new.estado', 't');
			$this->db->order_by('new.nombre', 'asc');
			$query = $this->db->get();
			
			$estadisticas = $query->result();
			
			for($i = 0; $i < count($estadisticas); $i++){
				$ofertas = explode(' ', $estadisticas[$i]->ofertas);
				
				if(empty($ofertas))
					$estadisticas[$i]->ofertas = array();
				else{
					$where = ' AND esn.id_newsletter = ' . $estadisticas[$i]->id_newsletter;
					
					$this->db->select('ofe.id_oferta, ofe.titulo', false);
					$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_oferta = ofe.id_oferta AND esn.tipo_acceso = \'v\'' . $where  . ') clicksOferta', false);
					$this->db->from('ofertas ofe');
					$this->db->where_in('ofe.id_oferta', $ofertas);
					$this->db->order_by('clicksOferta', 'desc');
					$this->db->limit(3);
					$query = $this->db->get();
					
					if($query->num_rows() == 0)
						$estadisticas[$i]->ofertas = array();
					else
						$estadisticas[$i]->ofertas = $query->result();
				}
			}
			
			return $estadisticas;
		}
		
		/**
		 * Obtiene las estadisticas de los anunciantes destinatarios de una newsletter
		 * 
		 * @param array $filtro Opciones de filtrado: newsletter(obligatorio)
		 * @param array Datos estadisticos
		 */
		function getEstadisticasClientes($filtro){
			$ofertas = array();
			
			if(empty($filtro['newsletter'])){
				$this->db->select('GROUP_CONCAT(ofe.id_oferta SEPARATOR \' \') ofertas', false);
				$this->db->from('ofertas ofe');
				$query = $this->db->get();
				
				if($query->num_rows > 0){
					$ofertas = $query->row();
					$ofertas = explode(' ', $ofertas->ofertas);
				}
			}
			else{
				$this->db->select('new.ofertas', false);
				$this->db->from('newsletters new');
				$this->db->where('new.id_newsletter', $filtro['newsletter']);
				$query = $this->db->get();
				
				if($query->num_rows > 0){
					$ofertas = $query->row();
					$ofertas = explode(' ', $ofertas->ofertas);
				}
			}
			
			$this->db->select('cli.id_cliente, cli.nombre', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_cliente = ned.id_generico AND esn.tipo_acceso = \'c\' AND esn.id_newsletter = ' . $filtro['newsletter'] . ') clicksContacto', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_cliente = ned.id_generico AND esn.tipo_acceso = \'p\' AND esn.id_newsletter = ' . $filtro['newsletter'] . ') clicksPass', false);
			$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_cliente = ned.id_generico AND esn.tipo_acceso = \'l\' AND esn.id_newsletter = ' . $filtro['newsletter'] . ') clicksLogin', false);
			$this->db->distinct();
			$this->db->from('newsletter_destinatarios ned');
			$this->db->join('newsletters new', 'ned.id_newsletter = new.id_newsletter', 'left');
			$this->db->join('clientes cli', 'ned.id_generico = cli.id_cliente', 'left');
			
			if(!empty($filtro['newsletter']))
				$this->db->where('new.id_newsletter', $filtro['newsletter']);
			
			$this->db->where('new.confirmada', 1);
			$this->db->where('new.estado', 't');
			$this->db->where('ned.tipo', 'c');
			$this->db->where('ned.estado', 'e');
			$this->db->order_by('cli.nombre', 'asc');
			$query = $this->db->get();
			
			$estadisticas = $query->result();
			
			for($i = 0; $i < count($estadisticas); $i++){
				if(empty($ofertas))
					$estadisticas[$i]->ofertas = array();
				else{
					$where = ' AND esn.id_cliente = ' . $estadisticas[$i]->id_cliente;
					$where .= (empty($filtro['newsletter'])) ? '' : (' AND esn.id_newsletter = ' . $filtro['newsletter']);
					
					$this->db->select('ofe.id_oferta, ofe.titulo', false);
					$this->db->select('(SELECT COUNT(esn.id_estadistica_newsletter) FROM estadisticas_newsletters esn WHERE esn.id_oferta = ofe.id_oferta AND esn.tipo_acceso = \'v\'' . $where  . ') clicksOferta', false);
					$this->db->from('ofertas ofe');
					$this->db->where_in('ofe.id_oferta', $ofertas);
					$this->db->having('clicksOferta >', 0);
					$this->db->order_by('clicksOferta', 'desc');
					$query = $this->db->get();
					
					if($query->num_rows() == 0)
						$estadisticas[$i]->ofertas = array();
					else
						$estadisticas[$i]->ofertas = $query->result();
				}
			}
			
			return $estadisticas;
		}
		
		/**
		 * Obtiene los datos de los medios especificados
		 * 
		 * @param array $medios Array de ids de los medios a obtener
		 * @return array Listado de medios obtenidos
		 */
		public function getMedios($medios){
			if(empty($medios))
				return array();
			
			$this->db->select('med.id_medio, med.nombre, med.logo');
			$this->db->from('medios med');;
			$this->db->where_in('med.id_medio', $medios);

			$this->db->order_by('med.nombre', 'asc');
			$query = $this->db->get();
			
			return $query->result();
		}
		
		/**
		 * Obtiene los medios resultantes del filtro especificado
		 * 
		 * @param array $filtro Opciones de filtrado: (medios, palabra, pagina, datosPorPagina)
		 * @return array Listado de medios obtenidos
		 */
		public function getMediosNewsletter($filtro){
		    $this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo', false);
		    $this->db->distinct();
			$this->db->from('medios med');
			$this->db->join('ofertas ofe', 'ofe.id_medio = med.id_medio');
			$this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
			$this->db->where('ofe.fecha_inicio_pub <= sysdate()');
			$this->db->where('ofe.publicada', 1);
			
			if(!empty($filtro['medios']))
				$this->db->where_not_in('med.id_medio', $filtro['medios']);
			
			if(!empty($filtro['palabra']))
				$this->db->where("med.nombre LIKE '%" . $filtro['palabra'] . "%'");
			
			$this->db->order_by('med.nombre', 'asc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();
	
			$aRet = $query->result();
	
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
	
			return $aRet;
		}
		
		/**
		 * Obtiene los datos de la newsletter especificada
		 * 
		 * @param integer $id_newsletter Id de la newsletter a obtener
		 * @param boolean $basica true para obtener los datos basica, false para obtener datos mas detallados
		 * @return resultSet Datos de la newsletter obtenida
		 */
		public function getNewsletter($id_newsletter, $basica = false){
			$this->db->select('new.id_newsletter, new.nombre, new.asunto, new.descripcion, new.fecha, new.estado, new.medios, new.ofertas');
			
			if(!$basica){
				$this->db->select('(SELECT COUNT(ned.id_newsletter_destinatario) FROM newsletter_destinatarios ned WHERE ned.id_newsletter = new.id_newsletter) AS total', false);
				$this->db->select('(SELECT COUNT(ned.id_newsletter_destinatario) FROM newsletter_destinatarios ned WHERE ned.id_newsletter = new.id_newsletter AND (ned.estado = \'e\' OR ned.estado = \'s\')) AS enviados', false);
			}
			
			$this->db->from('newsletters new');
			$this->db->where('new.id_newsletter', $id_newsletter);
			$query = $this->db->get();
			
			if($query->num_rows() == 0)
				return false;
			
			$newsletter = $query->row();
			
			if(!$basica){
				$this->db->select('med.id_medio, med.nombre, med.logo');
				$this->db->from('medios med');
				$this->db->where_in('med.id_medio', explode(' ', $newsletter->medios));
				$this->db->order_by('med.nombre');
				$query = $this->db->get();
				
				$newsletter->medios = $query->result();
				
				$this->db->select('ofe.id_oferta, ofe.titulo, ofe.descripcion, ofe.publicada, IF(ofe.fecha_inicio_pub <= sysdate(), 1, 0) visible', false);
				$this->db->select('IF((ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE())), 0, 1) caducada', false);
				$this->db->from('ofertas ofe');
				$this->db->where_in('ofe.id_oferta', explode(' ', $newsletter->ofertas));
				$this->db->order_by('ofe.titulo', 'asc');
				$query = $this->db->get();
				
				$newsletter->ofertas = $query->result();
				
				$this->db->select('cli.id_cliente, cli.nombre, ned.estado', false);
				$this->db->from('newsletter_destinatarios ned');
				$this->db->join('clientes cli', 'ned.id_generico = cli.id_cliente', 'left');
				$this->db->where('ned.tipo', 'c');
				$this->db->where('ned.id_newsletter', $id_newsletter);
				$this->db->order_by('cli.nombre', 'asc');
				$query = $this->db->get();
				
				$newsletter->clientes = $query->result();
	
				$this->db->select('age.id_agencia, age.nombre, ned.estado');
				$this->db->from('newsletter_destinatarios ned');
				$this->db->join('agencias age', 'ned.id_generico = age.id_agencia', 'left');
				$this->db->where('ned.tipo', 'a');
				$this->db->where('ned.id_newsletter', $id_newsletter);
				$this->db->order_by('age.nombre', 'asc');
				$query = $this->db->get();
				
				$newsletter->agencias = $query->result();
			}
			else{
				$this->db->select("GROUP_CONCAT(ned.id_generico SEPARATOR ' ') as clientes", false);
				$this->db->from('newsletter_destinatarios ned');
				$this->db->where('ned.tipo', 'c');
				$this->db->where('ned.id_newsletter', $id_newsletter);
				$query = $this->db->get();
				
				if($query->num_rows() == 0)
					$newsletter->clientes = '';
				else{
					$resultado = $query->row();
					
					$newsletter->clientes = $resultado->clientes;
				}
				
				$this->db->select("GROUP_CONCAT(ned.id_generico SEPARATOR ' ') as agencias", false);
				$this->db->from('newsletter_destinatarios ned');
				$this->db->where('ned.tipo', 'a');
				$this->db->where('ned.id_newsletter', $id_newsletter);
				$query = $this->db->get();
				
				if($query->num_rows() == 0)
					$newsletter->agencias = '';
				else{
					$resultado = $query->row();
					
					$newsletter->agencias = $resultado->agencias;
				}
			}
			
			return $newsletter;
		}
		
		/**
		 * Obtiene los ids de la newsletter y destinatario al que esta asociado el codigo especificado
		 * 
		 * @param integer $codigo codigo
		 * @return object Ids de la newsletter y destinatario obtenidos
		 */
		public function getNewsletterByCodigo($codigo){
			$this->db->select('ned.id_newsletter, ned.id_generico, cli.email, cli.id_usuario, cli.nombre');
			$this->db->from('newsletter_destinatarios ned');
			$this->db->join('clientes cli', 'ned.id_generico = cli.id_cliente', 'left');
			$this->db->where('codigo', $codigo);
			$this->db->where('ned.tipo', 'c');
			$query = $this->db->get();
			
			if($query->num_rows() == 0)
				return false;
			
			return $query->row();
		}
	
		/**
		 * Obtiene la lista de newsletter resultantes del filtro especificado
		 * 
		 * @param array $aFiltro Opciones de filtrado: (palabra, estado, pagina, datosPorPagina)
		 * @return array Lista de newsletters obtenidas
		 */
		public function getNewsletters($aFiltro){
			$this->db->select('SQL_CALC_FOUND_ROWS new.id_newsletter, new.nombre, new.medios, new.ofertas, new.estado, new.fecha, new.confirmada', false);
			$this->db->select('(SELECT COUNT(ned.id_newsletter_destinatario) FROM newsletter_destinatarios ned WHERE ned.id_newsletter = new.id_newsletter) AS total', false);
			$this->db->select('(SELECT COUNT(ned.id_newsletter_destinatario) FROM newsletter_destinatarios ned WHERE ned.id_newsletter = new.id_newsletter AND (ned.estado = \'e\' OR ned.estado = \'s\')) AS enviados', false);
			$this->db->from('newsletters new');
	
			if(!empty($aFiltro['palabra'])){
				$this->db->where("new.nombre LIKE '%" . $aFiltro['palabra'] . "%'");
			}
	
			if(!empty($aFiltro['estado'])){
				if($aFiltro['estado'] == 'd'){
					$this->db->where('new.confirmada', 0);
					$this->db->where('new.estado !=', 'c');
				}
				else{
					if($aFiltro['estado'] != 'c')
						$this->db->where('new.confirmada', 1);
					
					$this->db->where('new.estado', $aFiltro['estado']);
				}
			}
	
			$this->db->order_by('new.fecha', 'desc');
			$this->db->limit($aFiltro['datosPorPagina'], ($aFiltro['pagina'] - 1) * $aFiltro['datosPorPagina']);
			$query = $this->db->get();
	
			$aRet = $query->result();
	
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
	
			return $aRet;
		}

		/**
		 * Obtiene el numero total de agencias obtenidas en getAgenciasNewsletter
		 *
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getAgenciasNewsletter o array(agencias, palabra)
		 * @return integer Numero total de agencias obtenidas
		 */
		public function getNumAgenciasNewsletter($filtro = false){
			if($filtro !== false){
				$this->db->select('COUNT(age.id_agencia) AS numAgencias', false);
				$this->db->from('agencias age');
				
				if(!empty($filtro['agencias']))
					$this->db->where_not_in('age.id_agencia', $filtro['agencias']);
				
				if(!empty($filtro['palabra']))
					$this->db->where("age.nombre LIKE '%" . $filtro['palabra'] . "%'");
	
				$query = $this->db->get();
				$oResultado = $query->row();
	
				$this->iNumFilas = $oResultado->numAgencias;
			}
	
			return $this->iNumFilas;
		}

		/**
		 * Obtiene el numero total de anunciantes obtenidos en getClientesNewsletter
		 *
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getClientesNewsletter o array(clientes, palabra, ofertas)
		 * @return integer Numero total de medios obtenidos
		 */
		public function getNumClientesNewsletter($filtro = false){
			if($filtro !== false){
				$consulta = 'SELECT COUNT(cli_per.id_cliente) AS numClientes
							 FROM (SELECT DISTINCT cli.id_cliente
								   FROM ofertas ofe 
								   WHERE 1 ';
				
				if(!empty($filtro['ofertas']))
					$consulta .=  'AND ofe.id_oferta NOT IN (' . implode(', ', $filtro['ofertas']) . ') ';
				
				if(!empty($filtro['palabra']))
					$consulta .=  "AND ofe.titulo LIKE '%" . $filtro['palabra'] . "%' ";
			
				if(!empty($filtro['medios']))
					$consulta .=  'AND ofe.id_medio IN (' . implode(', ', $filtro['medios']) . ') ';
				
				$consulta .=	  ') cli_per';
	
				$query = $this->db->query($consulta);
				$oResultado = $query->row();
	
				$this->iNumFilas = $oResultado->numClientes;
			}
	
			return $this->iNumFilas;
		}
		
		/**
		 * Obtiene el numero total de medios obtenidos en getMediosNewsletter
		 * 
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getMediosNewsletter o array(medios, palabra)
		 * @return integer Numero total de medios obtenidos
		 */
		public function getNumMediosNewsletter($filtro = false){
			if($filtro !== false){
				$this->db->select('COUNT(med.id_medio) AS numMedios', false);
				$this->db->from('medios med');
				
				if(!empty($filtro['medios']))
					$this->db->where_not_in('med.id_medio', $filtro['medios']);
				
				if(!empty($filtro['palabra']))
					$this->db->where("med.nombre LIKE '%" . $filtro['palabra'] . "%'");
	
				$query = $this->db->get();
				$oResultado = $query->row();
	
				$this->iNumFilas = $oResultado->numMedios;
			}
	
			return $this->iNumFilas;
		}
		
		/**
		 * Obtiene el numero total de newsletter obtenidas en getNewsletters
		 * 
		 * @param array $aFiltro Opciones de filtrado: false si se utiliza el filtro usado en getNewsletters o array(palabra, estado)
		 * @return integer Numero total de newsletters obtenidas
		 */
		public function getNumNewsletters($aFiltro = false){
			if($aFiltro !== false){
				$this->db->select('COUNT(new.id_newsletter) AS numNewsletters', false);
				$this->db->from('newsletters new');
		
				if(!empty($aFiltro['palabra'])){
					$this->db->where("new.nombre LIKE '%" . $aFiltro['palabra'] . "%'");
				}
	
				if(!empty($aFiltro['estado'])){
					if($aFiltro['estado'] == 'd'){
						$this->db->where('new.confirmada', 0);
						$this->db->where('new.estado !=', 'c');
					}
					else{
						if($aFiltro['estado'] != 'c')
							$this->db->where('new.confirmada', 1);
						
						$this->db->where('new.estado', $aFiltro['estado']);
					}
				}
	
				$query = $this->db->get();
				$oResultado = $query->row();
	
				$this->iNumFilas = $oResultado->numNewsletters;
			}
	
			return $this->iNumFilas;
		}

		/**
		 * Obtiene el numero total de ofertas obtenidas en getOfertasNewsletter
		 *
		 * @param array $filtro Opciones de filtrado: false si se utiliza el filtro usado en getOfertasNewsletter o array(ofertas, palabra, medios)
		 * @return integer Numero total de ofertas obtenidas
		 */
		public function getNumOfertasNewsletter($filtro = false){
			if($filtro !== false){
				$this->db->select('COUNT(ofe.id_oferta) AS numOfertas', false);
				$this->db->from('ofertas ofe');
				
				if(!empty($filtro['ofertas']))
					$this->db->where_not_in('ofe.id_oferta', $filtro['ofertas']);
				
				if(!empty($filtro['palabra']))
					$this->db->where("ofe.titulo LIKE '%" . $filtro['palabra'] . "%'");
				
				if(!empty($filtro['medios']))
					$this->db->where_in('ofe.id_medio', $filtro['medios']);
	
				$query = $this->db->get();
				$oResultado = $query->row();
	
				$this->iNumFilas = $oResultado->numNewsletters;
			}
	
			return $this->iNumFilas;
		}
		
		/**
		 * Obtiene los datos de las ofertas especificadas
		 * 
		 * @param array $medios Array de ids de los medios a los que deben pertenecer las ofertas a obtener
		 * @param array $ofertas Array de ids de las ofertas a obtener
		 * @return array Listado de ofertas obtenidas
		 */
		public function getOfertas($medios, $ofertas){
			if(empty($ofertas))
				return array();
			
			$this->db->select('ofe.id_oferta, ofe.titulo, ofe.descripcion');
			$this->db->from('ofertas ofe');
			$this->db->where_in('ofe.id_oferta', $ofertas);
			
			if(!empty($medios))
				$this->db->where_in('ofe.id_medio', $medios);

			$this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
			$this->db->where('ofe.fecha_inicio_pub <= sysdate()');
			$this->db->where('ofe.publicada', 1);
			$this->db->order_by('ofe.titulo', 'asc');
			$query = $this->db->get();
			
			return $query->result();
		}

		/**
		 * Obtiene las ofertas resultantes del filtro especificado
		 *
		 * @param array $filtro Opciones de filtrado: (ofertas, palabra, medios, pagina, datosPorPagina)
		 * @return array Listado de ofertas obtenidas
		 */
		public function getOfertasNewsletter($filtro){
			$this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, ofe.titulo, ofe.descripcion', false);
			$this->db->select('IF((ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE())), 0, 1) caducada', false);
			$this->db->select('IF((ofe.fecha_inicio_pub <= sysdate()), 1, 0) visible, ofe.publicada', false);
			$this->db->from('ofertas ofe');
			
			if(!empty($filtro['ofertas']))
				$this->db->where_not_in('ofe.id_oferta', $filtro['ofertas']);
			
			if(!empty($filtro['palabra']))
				$this->db->where("ofe.titulo LIKE '%" . $filtro['palabra'] . "%'");
			
			if(!empty($filtro['medios']))
				$this->db->where_in('ofe.id_medio', $filtro['medios']);
			
			$this->db->order_by('ofe.titulo', 'asc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();
	
			$aRet = $query->result();
	
			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get ();
			$this->iNumFilas = $query->row()->totalRows;
	
			return $aRet;
		}
		
		/**
		 * Obtiene las ofertas a las que tiene acceso el usuario especificado y que estan asociada a la newsletter especificada
		 * 
		 * @param integer $id_newletter Id de la newsletter
		 * @param integer $id_cliente Id del cliente
		 * @return array Lista de ofertas obtenida
		 */
		public function getOfertasNewsletterCliente($id_newletter, $id_cliente){
			$this->db->select('new.ofertas');
			$this->db->from('newsletters new');
			$this->db->join('newsletter_destinatarios ned', 'new.id_newsletter = ned.id_newsletter', 'left');
			$this->db->where('ned.tipo', 'c');
			$this->db->where('ned.id_generico', $id_cliente);
			$this->db->where('new.id_newsletter', $id_newletter);
			$this->db->where('new.ofertas IS NOT NULL');
			$this->db->where('new.ofertas !=', '');
			$query = $this->db->get();
			
			if($query->num_rows() == 0)
				return array();
			
			$oOfertas = $query->row();
			$aOfertas = explode(' ', $oOfertas->ofertas);
			
			$this->db->select('ofe.id_oferta, ofe.titulo, ofe.descripcion, ofe.fecha_fin_pub, ofe.precio_anterior, ofe.precio_oferta', false);
			$this->db->select('ofe.descuento, ofe.imagen, ofe.id_medio, med.nombre medio, med.id_tipo_medio, tim.tipo, ofe.duracion_camp, ofe.destacada', false);
			$this->db->select('CASE WHEN DATEDIFF(sysdate(), ofe.fecha_inicio_pub) <= 7 THEN 1 ELSE 0 END novedad, med.logo logo_medio', false);
			$this->db->from('ofertas ofe');
			$this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
			$this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
			$this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
			$this->db->where('ofe.publicada', 1);
			$this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $id_cliente . " AND per.estado = 1)");
			$this->db->where_in('ofe.id_oferta', $aOfertas);
			$this->db->order_by('ofe.fecha_insercion', 'desc');
			$query = $this->db->get();
			
			return $query->result();
		}

		/**
		 * Inserta destinatarios agencias en la newsletter especificada
		 *
		 * @param integer $id_newsletter Id de la newsletter a la que pertenecen las agencias destinatarias a insertar
		 * @param array $agencias Array de ids de las agencias destinatarias a insertar
		 */
		public function insertAgenciasNewsletter($id_newsletter, $agencias){
			$datos_agencia = array(
				'id_newsletter' => $id_newsletter,
				'tipo' => 'a',
				'estado' => 'p'
			);
			
			if(!empty($agencias)){
				foreach($agencias as $agencia){
					$datos_agencia['id_generico'] = $agencia;
					
					$this->db->insert('newsletter_destinatarios', $datos_agencia);
				}
			}
		}
		
		/**
		 * Inserta destinatarios anunciantes en la newsletter especificada
		 * 
		 * @param integer $id_newsletter Id de la newsletter a la que pertenecen los anunciantes destinatarios a insertar
		 * @param array $clientes Array de ids de los anunciantes destinatarios a insertar
		 */
		public function insertClientesNewsletter($id_newsletter, $clientes){
			$datos_cliente = array(
				'id_newsletter' => $id_newsletter,
				'tipo' => 'c',
				'estado' => 'p'
			);
			
			if(!empty($clientes)){
				foreach($clientes as $cliente){
					$datos_cliente['id_generico'] = $cliente;
					
					$flag = false;
					
					while(!$flag){
						$codigo = $this->generarCodigo();
						
						$this->db->select('ned.id_newsletter_destinatario');
						$this->db->from('newsletter_destinatarios ned');
						$this->db->where('ned.codigo', $codigo);
						$query = $this->db->get();
						
						if($query->num_rows() == 0){
							$flag = true;
							$datos_cliente['codigo'] = $codigo;
						}
					}
					
					$this->db->insert('newsletter_destinatarios', $datos_cliente);
				}
			}
		}
		
		/**
		 * Inserta una newsletter
		 * 
		 * @param array $datos_newsletter Array con los datos de la newsletter a insertar
		 * @return integer Id de la newsletter insertada
		 */
		public function insertNewsletter($datos_newsletter){
			$this->db->insert('newsletters', $datos_newsletter);
			
			return $this->db->insert_id();
		}
		
		/**
		 * Insert un registro de estadistica de newsletter
		 * 
		 * @param array $aDatos Datos del registro a insertar
		 * @retur integer Id del registro creado
		 */
		public function insertEstadisticaNewsletter($datos){
			$this->db->insert('estadisticas_newsletters', $datos);
			
			return $this->db->insert_id();
		}
		
		/**
		 * Actualiza la newsletter especificada
		 * 
		 * @param integer $id_newsletter Id de la newsletter a actualizar
		 * @param array $datos_newsletter Array con los datos a actualizar de la newsletter
		 */
		public function updateNewsletter($id_newsletter, $datos_newsletter){
			$this->db->where('id_newsletter', $id_newsletter);
			$this->db->update('newsletters', $datos_newsletter);
		}
	}
?>