<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Medios_model extends CI_Model
{

    private $iNumFilas = 0;

    public function __construct()
    {
        parent::__construct();
    }    
    function buscar($id_medio,$buscar){        $return = array();

        $this->db->select('*');
        $this->db->from('ofertas');  
        $this->db->where('id_medio = '.$id_medio);  
        $this->db->like('titulo',$buscar, 'both');       
        $query = $this->db->get();   
        // $return["ofertas_sql"] = $this->db->last_query();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }      
                      $return["ofertas"] = $query->result();
		 
        $this->db->reset_query();

        $this->db->select('c.*');
        $this->db->from('clientes c');  
        $this->db->join('usuarios usu', 'c.id_usuario = usu.id_usuario');
        $this->db->like('c.nombre',$buscar, 'both');     
        $this->db->where('usu.estado', 0);
        $query = $this->db->get();    
       // $return["clientes_sql"] = $this->db->last_query();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message()); 
        }          

                          $return["clientes"] = $query->result();
		  
     

        return $return;
	}    
    function getPermisosResumen($id_medio){
       $return = array();                     $this->db->select('count(*) as clientes');
        $this->db->from('clientes_activos a');  
 
        $query = $this->db->get();    
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }                   
            $return["clientes"] = $query->row();
	    
               $this->db->reset_query();                 $this->db->select('count(*) as clientes, (count(*) - SUM(IF(IFNULL(id_permiso_cliente_medio,0)=0,0,1))) as pendientes, (count(*) - SUM(IF(IFNULL(id_permiso_cliente_medio,0)=0,0,1))) * 100 / count(*)  as porcentaje ');
        $this->db->from('clientes_activos a');
        $this->db->join('permisos_cliente_medio p', 'p.id_cliente = a.id_cliente and id_medio = '.$id_medio ,'left');      
        $query = $this->db->get();        
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }              
            $return["pendientes"] = $query->row();
	                          $this->db->reset_query();                 $this->db->select('count(*) as clientes , SUM(IF(IFNULL(estado,0)=1,1,0)) as autorizados,  SUM(IF(IFNULL(estado,0)=1,1,0)) * 100 / count(*)  as porcentaje');
        $this->db->from('clientes_activos c');
        $this->db->join('permisos_cliente_medio p', 'p.id_cliente = c.id_cliente and id_medio = '.$id_medio ,'left');        
        $query = $this->db->get();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }            
            $return["autorizados"] = $query->row();
	  
               $this->db->reset_query();                 $this->db->select('count(*) as clientes , SUM(IF(IFNULL(estado,0)=2,1,0)) as no_autorizados,  SUM(IF(IFNULL(estado,0)=2,1,0)) * 100 / count(*)  as porcentaje');  
        $this->db->from('clientes_activos c');
        $this->db->join('permisos_cliente_medio p', 'p.id_cliente = c.id_cliente and id_medio = '.$id_medio ,'left');       
        $query = $this->db->get();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }            
            $return["no_autorizados"] = $query->row();
	   

        return $return;


    }
    function getHomeResumen($id_medio){       
        $return = array();                     
        $this->db->select('count(*) as ofertas , IFNULL(SUM(IF(id_medio='.$id_medio.',1,0)),0) as propias,  SUM(IF(id_medio='.$id_medio.',1,0)) * 100 / count(*)  as porcentaje');
        $this->db->from('ofertas o');  
        $this->db->where('o.fecha_inicio_pub > date_add(NOW(),INTERVAL -1 MONTH)');       
        $query = $this->db->get();    
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }                   
        $return["ofertas"] = $query->row();
	    
        $this->db->reset_query();                 
        $this->db->select('count(*) as clientes, count(id_medio) as propios, count(id_medio) * 100 / count(*)  as porcentaje ');
        $this->db->from('clientes_activos a');
        $this->db->join('permisos_cliente_medio p', 'p.id_cliente = a.id_cliente and id_medio = '.$id_medio ,'left');      
        $query = $this->db->get();        
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }     
        //print_r( $this->db->last_query());
     
        $return["clientes"] = $query->row();


        $this->db->reset_query();                 
        $this->db->select('count(*) as ofertas_totalse , SUM(IF(o.id_medio='.$id_medio.',1,0)) as propias,  SUM(IF(o.id_medio='.$id_medio.',1,0)) * 100 / count(*)  as porcentaje');
        $this->db->join('ofertas o', 'd.id_oferta = o.id_oferta' );
        $this->db->join('permisos_cliente_medio p' , 'd.id_cliente = p.id_cliente and p.estado = 1 and o.id_medio = p.id_medio');
        $this->db->where('d.fecha > date_add(NOW(),INTERVAL -1 MONTH)');       
        $query = $this->db->get();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }         
        //print_r( $this->db->last_query());
    
        $return["enviadas"] = $query->row();
        $return["enviadas"]->ofertas =  $return["clientes"]->clientes;
	  
        $this->db->reset_query();        
        $this->db->select('count(*) as ofertas, IFNULL(sum(apertura),0) as apertura,  sum(apertura) * 100 / count(*)  as porcentaje');
        $this->db->from('report2022 r');
        $this->db->where('id_medio='.$id_medio);      
        $query = $this->db->get();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }                 
        $return["aperturas"] =$query->row();
	   

        return $return;
	}    function getPerfiles($tipos_medio_id){
        $return  = array("sexo" => array(),"socioeconomico" => array(),"localidad" => array(),"edad" => array());
                 $this->db->select('*');
        $this->db->from('tipos_medio_AIMC a');     
        $this->db->where('tipos_medio_id ='.$tipos_medio_id);     
        $query = $this->db->get();    
       // $return["sql"] = $this->db->last_query();
        if( !$query )
        {
          print_r( $this->db->_error_number());
          print_r( $this->db->_error_message());
 
        }         $aRet1 = $query->result();
    
        
        foreach ($aRet1 as $tmedio) {
            switch ($tmedio->campo) {
             case 'target mujer':
     
                    $return["sexo"]["mujer"] = $tmedio;
                      break;
         
                case 'target hombre':
                    $return["sexo"]["hombre"] = $tmedio;
                break;
                case 'target IA1':
                case 'target IA2':
                case 'target IB':
                case 'target IC':
                case 'target ID':
                case 'target IE1':
                case 'target IE2':
                    $return["socioeconomico"][] = $tmedio;
                break;
                case 'TOTAL SEMANA':
                    $return["localidad"]["total"] = $tmedio;
                break;
                case 'target 14 a 19':
                case 'target 20 a 24':
                case 'target 25 a 34':
                case 'target 35 a 44':
                case 'target 45 a 54':
                case 'target 55 a 64':
                case 'target 65 y más':
                    $return["edad"][] = $tmedio;
                break;
              
            }
 
        }                  
           return $return;
	}

    /**
     * Dar de alta o baja los medios especificados
     *
     * @param array $altas
     *            Array de altas y bajas a llevar a cabo: posicion 2k Id del medio, posicion 2k+1 (1 alta, 0 baja)
     */
    function darAltaMedios($altas)
    {
        if (! empty($altas)) {
            $this->load->model('medios_model');
            foreach ($altas as $id_medio => $alta) {
                if ($alta === '0') { // Si se ha seleccionado la baja del medio
                    $this->db->where('id_medio', $id_medio);
                    $this->db->where('fecha_baja IS NULL');
                    $this->db->set('fecha_baja', date('Y-m-d H:i:s'));
                    $this->db->update('medios');
                    
                    // Desactivamos el usuario
                    $medio = $this->medios_model->getMedio($id_medio);
                    $this->db->where('id_usuario', $medio->id_usuario);
                    $this->db->where('estado = 0');
                    $this->db->set('estado', 3);
                    $this->db->update('usuarios');
                } else {
                    $this->db->where('id_medio', $id_medio);
                    $this->db->where('fecha_baja IS NOT NULL');
                    $this->db->set('fecha_baja', NULL);
                    $this->db->set('fecha_alta', date('Y-m-d H:i:s'));
                    $this->db->update('medios');
                    
                    // Reactivamos el usuario
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
     * Obtiene los datos del medio especificado
     *
     * @param $id_medio Id
     *            del medio a obtener
     * @return resultSet Datos del medio obtenido
     */
    function getMedio($id_medio,$tipo_medio=false)
    {
        $this->db->select('med.id_medio, med.nombre, med.email, med.imagen, med.logo, med.descripcion, med.id_tipo_medio, med.web, usu.id_usuario, usu.nick as nick, usu.fecha_registro');
        $this->db->from('medios med');
        $this->db->join('usuarios usu', 'med.id_usuario = usu.id_usuario', 'left');        
        if($tipo_medio){         
            $this->db->select('tm.tipo');         
            $this->db->join('tipos_medio tm', 'tm.id_tipo = med.id_tipo_medio' );
		}
        $this->db->where('id_medio', $id_medio);
        $query = $this->db->get();
        
        if ($query->num_rows() == 0)
            return false;
        
        $medios = $query->result();
        
        return $medios[0];
    }

    /**
     * Obtiene los datos del medio especificado
     *
     * @param $id_medio Id
     *            del medio a obtener
     * @return resultSet Datos del medio obtenido
     */
    function getMedioGestor($id_medio)
    {
        $this->db->select('med.id_medio, med.nombre, med.email, med.imagen, med.logo, med.descripcion, med.id_tipo_medio, med.web, usu.id_usuario, usu.nick as nick');
        $this->db->from('medios med');
        $this->db->join('usuarios usu', 'med.id_usuario = usu.id_usuario', 'left');
        $this->db->join('permisos_gestor_medio per_ges', 'med.id_medio = per_ges.id_medio', 'left');
        $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
        $this->db->where('per_ges.estado', 1);
        $this->db->where('med.id_medio', $id_medio);
        $query = $this->db->get();
        
        if ($query->num_rows() == 0)
            return false;
        
        $medios = $query->result();
        
        return $medios[0];
    }

    /**
     * Obtiene la lista de medios
     *
     * @return array Lista de medios obtenidos
     */
    function getMedios()
    {
        $this->db->select('id_medio, nombre, imagen, logo, email');
        $this->db->from('medios');
        $this->db->where('fecha_baja is null');
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**

		 * Obtiene los logs del medio especificado
		 *
		 * @param array $filtro Opciones de filtrado: (medio, pagina, datosPorPagina)
		 * @return resultSet Datos del medio obtenido
		 */
		function getMedioLogs($filtro){
			$this->db->select('log.id_log, log.fecha, log.accion');
			$this->db->from('medios med');
			$this->db->join('log_acciones log', 'med.id_usuario = log.id_usuario', 'inner');
			$this->db->where('med.id_medio', $filtro['medio']);
			$this->db->order_by('log.id_log', 'desc');
			$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
			$query = $this->db->get();

			$aRet = $query->result();

			//recuento de filas
			$this->db->select('FOUND_ROWS() AS totalRows');
			$query = $this->db->get();
			$this->iNumFilas = $query->row()->totalRows;
                        
			return $aRet;
		}
                
    /**
     * Obtiene la lista de medios que tienen gestor
     *
     * @return array Lista de medios obtenidos
     */
    function getMediosConGestor()
    {
        $this->db->select('ges.id_gestor, ges.nombre, ges.email, per_ges.id_medio');
        $this->db->from('permisos_gestor_medio per_ges');
        $this->db->join('gestores ges', 'per_ges.id_gestor = ges.id_gestor AND per_ges.estado=1', 'left');
        $this->db->join('usuarios usu', 'ges.id_usuario = usu.id_usuario');
        $this->db->where('usu.estado = 0');
        $this->db->group_by('ges.email');
        $query = $this->db->get();
        
        return $query->result();
    }

     /**
     * Obtiene la lista de medios que tienen gestor a partir de un medio
     *
     * @return array Lista de medios obtenidos
     */
    function getMediosMismoGestor($id_medio){
        $this->db->select('per_ges.id_gestor');
        $this->db->from('permisos_gestor_medio per_ges');
        $this->db->where('id_medio', $id_medio);
        $this->db->where('estado', 1);
        $this->db->group_by('per_ges.id_gestor');
        $query = $this->db->get();
        $aRet1 = $query->result();
        $gestores = Array();
        //Gestores que gestionan este medio (Lo normal es que sea un solo gestor)
        foreach ($aRet1 as $gestor) {     
           $gestores[] = $gestor->id_gestor;
        }
        if(count($gestores) == 0) return array();
        //Todos los medios que controlan los gestores
        $this->db->select('per_ges.id_medio,med.nombre');
        $this->db->from('permisos_gestor_medio per_ges');
        $this->db->join('medios med', 'med.id_medio = per_ges.id_medio');
        $this->db->where('estado', 1);
        $this->db->where_in('per_ges.id_gestor', $gestores);      
        $this->db->group_by('per_ges.id_medio,med.nombre');
        $query = $this->db->get();
        
        return $query->result();
    }



    /**
     * Obtiene la lista de medios que no tienen gestor
     *
     * @return array Lista de medios obtenidos
     */
    function getMediosSinGestor()
    {
        $this->db->select('per_ges.id_medio');
        $this->db->from('permisos_gestor_medio per_ges');
        $this->db->where('estado', 1);
        $query = $this->db->get();
        $aRet1 = $query->result();
        
        $medios = Array();
        $i = 0;
        
        foreach ($aRet1 as $medio) {
            
            $medios[$i] = $medio->id_medio;
            $i ++;
        }
        
        // Si no hay aún gestores con medios que gestiona
        if (empty($medios)) {
            $medios[0] = 0;
        }
        
        $this->db->select('med.id_medio, med.nombre, med.imagen, med.logo, med.email');
        $this->db->from('medios med');
        $this->db->join('usuarios usu', 'med.id_usuario = usu.id_usuario');
        $this->db->where_not_in('med.id_medio', $medios);
        //$this->db->where('med.fecha_baja is null');
        $this->db->where('usu.estado = 0');
        $this->db->order_by('med.nombre', 'asc');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Obtiene el listado de medios resultantes del filtro especificado, funcion asociada al listado de medios del administrador
     *
     * @param array $filtro
     *            Opciones de filtrado: (estado, medio, tipo_medio, pagina, datosPorPagina)
     * @return array Listado de medios obtenidos
     */
    function getMediosAdmin($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo, med.fecha_baja, tim.tipo', false);
        $this->db->select('(SELECT COUNT(per.id_permiso_cliente_medio) num_per_pen FROM permisos_cliente_medio per WHERE estado = 0 AND per.id_medio = med.id_medio) permisos_pendientes', false);
        $this->db->select('(SELECT COUNT(ofe.id_oferta) FROM ofertas ofe WHERE ofe.id_medio = med.id_medio) num_ofertas', false);
        $this->db->select('(SELECT ges.nombre FROM permisos_gestor_medio per_gestor LEFT JOIN gestores ges ON ges.id_gestor=per_gestor.id_gestor WHERE per_gestor.id_medio = med.id_medio  AND per_gestor.estado=1 ORDER BY per_gestor.id_medio DESC LIMIT 1) nom_gestor', false);
        $this->db->from('medios med');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        
        if (! empty($filtro['estado'])) {
            if ($filtro['estado'] == 1)
                $this->db->where('med.fecha_baja is null');
            else if ($filtro['estado'] == 2)
                $this->db->where('med.fecha_baja is not null');
        }
        
        if (! empty($filtro['medio'])) {
            $this->db->where("med.nombre LIKE '%" . $filtro['medio'] . "%'");
        }
        
        if (! empty($filtro['tipo_medio'])) {
            $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
        }
        
        if (! empty($filtro['permisos']) && $filtro['permisos'] != 'todos') {
            if ($filtro['permisos'] == 'pendiente')
                $this->db->where('med.id_medio IN (SELECT foo.id_medio
														 FROM (SELECT med2.id_medio, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM medios med2
															   LEFT JOIN permisos_cliente_medio per ON med2.id_medio = per.id_medio
															   WHERE estado = 0
															   GROUP BY med2.id_medio) foo
														 WHERE num_permisos_pendientes > 0)');
            else
                $this->db->where('med.id_medio NOT IN (SELECT foo.id_medio
														 FROM (SELECT med2.id_medio, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM medios med2
															   LEFT JOIN permisos_cliente_medio per ON med2.id_medio = per.id_medio
															   WHERE estado = 0
															   GROUP BY med2.id_medio) foo
														 WHERE num_permisos_pendientes > 0)');
        }
        
        if (! empty($filtro['order_by_campo'])) {
            $this->db->order_by($filtro['order_by_campo'], $filtro['order_by_sentido']);
        } else {
            $this->db->order_by('med.id_medio', 'desc');
        }
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();
        
        $aRet = $query->result();
        
        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;
        
        return $aRet;
    }

    /**
     * Obtiene el listado de medios resultantes del filtro especificado, funcion asociada al listado de medios del gestor
     *
     * @param array $filtro
     *            Opciones de filtrado: (estado, medio, tipo_medio, pagina, datosPorPagina)
     * @return array Listado de medios obtenidos
     */
    function getMediosGestor($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo, med.email, med.fecha_baja, tim.tipo', false);
        $this->db->select('(SELECT COUNT(per.id_permiso_cliente_medio) num_per_pen FROM permisos_cliente_medio per WHERE per.id_medio = med.id_medio AND (per.estado = 3 OR per.estado IS NULL)) permisos_pendientes', false);
        $this->db->select('(SELECT COUNT(ofe.id_oferta) FROM ofertas ofe WHERE ofe.id_medio = med.id_medio) num_ofertas', false);
        $this->db->from('medios med');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->join('permisos_gestor_medio per_ges', 'med.id_medio = per_ges.id_medio', 'left');
        $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
        $this->db->where('per_ges.estado', 1);
        
        if (! empty($filtro['estado'])) {
            if ($filtro['estado'] == 1)
                $this->db->where('med.fecha_baja is null');
            else if ($filtro['estado'] == 2)
                $this->db->where('med.fecha_baja is not null');
        }
        
        if (! empty($filtro['medio'])) {
            $this->db->where("med.nombre LIKE '%" . $filtro['medio'] . "%'");
        }
        
        if (! empty($filtro['tipo_medio'])) {
            $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
        }
        
        if (! empty($filtro['permisos']) && $filtro['permisos'] != 'todos') {
            if ($filtro['permisos'] == 'pendiente')
                $this->db->where('med.id_medio IN (SELECT foo.id_medio
														 FROM (SELECT med2.id_medio, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM medios med2
															   LEFT JOIN permisos_cliente_medio per ON med2.id_medio = per.id_medio
															   WHERE estado = 0 OR estado=3 OR estado IS NULL
															   GROUP BY med2.id_medio) foo
														 WHERE num_permisos_pendientes > 0)');
            else
                $this->db->where('med.id_medio NOT IN (SELECT foo.id_medio
														 FROM (SELECT med2.id_medio, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM medios med2
															   LEFT JOIN permisos_cliente_medio per ON med2.id_medio = per.id_medio
															   WHERE estado = 0 OR estado=3 OR estado IS NULL
															   GROUP BY med2.id_medio) foo
														 WHERE num_permisos_pendientes > 0)');
        }
        
        $this->db->order_by('nombre', 'desc');
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();
        
        $aRet = $query->result();
        
        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;
        
        return $aRet;
    }

    /**
     * Obtiene la lista de medios a los que tiene acceso el anunciante especificado
     *
     * @param integer $id_cliente
     *            Id del anunciante por el que filtrar
     * @return array Listado de los medios obtenidos
     */
    function getMediosCliente($id_cliente)
    {
        $this->db->select('med.id_medio, med.nombre, med.imagen, med.logo');
        $this->db->from('medios med');
        $this->db->join('permisos_cliente_medio pcm', 'med.id_medio = pcm.id_medio', 'left');
        $this->db->where('pcm.id_cliente', $id_cliente);
        $this->db->where('pcm.estado', 1);
        $this->db->where('fecha_baja is null');
        $query = $this->db->get();
        
        return $query->result();
    }     function getCategorizacion($id_medio)
    {    $this->db->reset_query();     $this->db->select('*');
        $this->db->from('tipo_categorizacion t');
            $this->db->where('t.id_medio = '.$id_medio);
      
        $query = $this->db->get();     return $query->result();       }

    function getClientes($id_medio,$filtro)
    {        
        $this->db->reset_query();        
        $this->db->select('*');
        $this->db->from('clientes c');
        $this->db->join('permisos_cliente_medio p', 'p.id_cliente = c.id_cliente and p.id_medio = '.$id_medio);        
        $this->db->join('sectores s', 's.id_sector = c.id_sector');        
        $this->db->join('provincia v', 'v.id_provincia = c.id_provincia', 'left');        
        $this->db->join('clientes_medios m', 'm.id_cliente = c.id_cliente and m.id_medio = '.$id_medio,'left');        
        $this->db->join('(select count(*) as ofertas, sum(apertura) as apertura, (sum(apertura) * 100 / count(*)) as porcentaje , id_cliente from report2022 where id_medio = '.$id_medio.'  group by id_cliente ) r', 'r.id_cliente = c.id_cliente');     

        if(isset($filtro["estado"]) && $filtro["estado"] !="todos"){        
            $this->db->where('p.estado = '.$filtro["estado"]);
	    }           
        if(isset($filtro["anunciante"]) && $filtro["anunciante"] !="todos" && $filtro["anunciante"] !="" ){        
            $this->db->like('c.nombre',$filtro["anunciante"],'both');
	    }         
        if(isset($filtro["provincia"]) && $filtro["provincia"] !="todas" && $filtro["provincia"] !=""){        
            $this->db->where('c.id_provincia',$filtro["provincia"]);
	    }         
        if(isset($filtro["sector"]) && $filtro["sector"] !="todos" && $filtro["sector"] !=""){        
            $this->db->where('c.id_sector',$filtro["sector"]);
	    }         
        if(isset($filtro["categorizacion"]) && $filtro["categorizacion"] !="todos"  && $filtro["categorizacion"] !=""){        
            $this->db->where('m.id_tipo_categorizacion',$filtro["categorizacion"]);
	    }     
        $query = $this->db->get();    
        //   print_r( $this->db->last_query());
   
        return $query->result();    
    }

       function cambiarCategorizacion($id_medio, $id_cliente,$id_tipo_categorizacion){

        $this->db->reset_query();        $this->db->select('*');
        $this->db->from('tipo_categorizacion t');           $this->db->where('t.id_medio = '.$id_medio.' and   id_tipo_categorizacion='.$id_tipo_categorizacion);        $query = $this->db->get();        $cat =  $query->row();
 
        if (! empty($cat)) {
        $sql = $this->db->insert_string('clientes_medios', array("id_cliente" =>$id_cliente,"id_medio" =>$id_medio,"descuento" => $cat->descuento,"relacion_medio" => $cat->relacion_medio,"id_tipo_categorizacion" =>$id_tipo_categorizacion)) . ' ON DUPLICATE KEY UPDATE id_tipo_categorizacion='.$id_tipo_categorizacion.',relacion_medio='. $cat->relacion_medio.',descuento='.$cat->descuento;
        $this->db->query($sql);

        }
    }
    /**
     * Obtiene el listado de medios resultante del filtro especificado, funcion asociada al filtro de ofertas
     *
     * @param array $filtro
     *            Opciones de filtrado: (id_cliente, tipo_medio)
     * @return array Listado de medios obtenidos
     *        
     */
    function getMediosFiltro($filtro)
    {
        $this->db->select('med.id_medio, med.nombre, med.imagen, med.logo');
        $this->db->from('medios med');
        
        if (! empty($filtro['id_cliente'])) {
            /*
            $this->db->join('permisos_cliente_medio pcm', 'med.id_medio = pcm.id_medio', 'left');
            $this->db->where('pcm.id_cliente', $filtro['id_cliente']);
            $this->db->where('pcm.estado', 1);
            */
            $this->db->join('ofertas ofe', 'med.id_medio = ofe.id_medio', 'left');
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
            $this->db->where('ofe.fecha_inicio_pub <= sysdate()');
            $this->db->where('ofe.publicada', 1);
        }
        
        $this->db->where('fecha_baja is null');
        
        if (! empty($filtro['tipo_medio'])) {
            $this->db->where_in("med.id_tipo_medio", $filtro['tipo_medio']);
        }
        $this->db->group_by('med.id_medio');
        $this->db->order_by('med.nombre', 'asc');
        
        
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Obtiene el numero total de medios obtenido en la funcion getMEdiosAdmin
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getMediosAdmin o array(estado, medio, tipo_medio)
     * @return integer Numero total de medios obtenidos
     */
    public function getNumMediosAdmin($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(med.id_medio) AS numMedios');
            $this->db->from('medios med');
            
            if (! empty($filtro['estado'])) {
                if ($filtro['estado'] == 1)
                    $this->db->where('med.fecha_baja is null');
                else if ($filtro['estado'] == 2)
                    $this->db->where('med.fecha_baja is not null');
            }
            
            if (! empty($filtro['medio'])) {
                $this->db->where("med.nombre LIKE '%" . $filtro['medio'] . "%'");
            }
            
            if (! empty($filtro['tipo_medio'])) {
                $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
            }
            
            $query = $this->db->get();
            $oResultado = $query->row();
            
            $this->iNumFilas = $oResultado->numMedios;
        }
        
        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de medios obtenido en la funcion getMEdiosGestor
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getMediosGestor o array(estado, medio, tipo_medio)
     * @return integer Numero total de medios obtenidos
     */
    public function getNumMediosGestor($filtro = false)
    {
        if (! $filtro) {
            $this->db->select('COUNT(med.id_medio) AS numMedios');
            $this->db->from('medios med');
            $this->db->join('permisos_gestor_medio per_ges', 'med.id_medio = per_ges.id_medio', 'left');
            $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
            $this->db->where('per_ges.estado', 1);
            
            if (! empty($filtro['estado'])) {
                if ($filtro['estado'] == 1)
                    $this->db->where('med.fecha_baja is null');
                else if ($filtro['estado'] == 2)
                    $this->db->where('med.fecha_baja is not null');
            }
            
            if (! empty($filtro['medio'])) {
                $this->db->where("med.nombre LIKE '%" . $filtro['medio'] . "%'");
            }
            
            if (! empty($filtro['tipo_medio'])) {
                $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
            }
            
            $query = $this->db->get();
            $oResultado = $query->row();
            
            $this->iNumFilas = $oResultado->numMedios;
        }
        
        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero de usuarios con permisos pendientes
     *
     * @return integer Numero de usuarios con permisos pendientes
     */
    function getNumUsuariosPermisosPendientes($id_medio)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente AS num_usuarios', false);
        $this->db->select('(SELECT per.estado FROM permisos_cliente_medio per WHERE per.id_medio = ' . $id_medio . ' AND per.id_cliente = cli.id_cliente) estado', false);
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario', 'left');
        
        $this->db->having('(estado = 3 OR estado IS NULL)');
        
        $this->db->where('usu.estado', 0);
        
        $query = $this->db->get();
        
        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        $query = $this->db->get();
        
        return $query->row()->totalRows;
			
			
		}
                
                /**
		 * Obtiene el numero de permisos del medios para calcular porcentajes
		 *
		 * @param array $id_medio identificador del medio
		 * @return array Listado de permisos obtenido
		 */
		public function getPermisosMedioLog($id_medio) {
		    $select_pendientes = '   SELECT COUNT(*)  '  .
		    '   FROM clientes c  '  .
		    '   INNER JOIN  '  .
		    '     (SELECT *  '  .
		    '      FROM usuarios  '  .
		    '      WHERE estado = 0) u ON c.id_usuario = u.id_usuario  '  .
		    '   LEFT JOIN  '  .
		    '     (SELECT *  '  .
		    '      FROM permisos_cliente_medio  '  .
		    '      WHERE id_medio = '.$id_medio.') p ON c.id_cliente = p.id_cliente  '  .
		    '  WHERE p.estado IS NULL  ' ; 
			$this->db->select('SQL_CALC_FOUND_ROWS count(c.id_cliente) permisos', false);
			$this->db->select('('.$select_pendientes.') pendientes', false);
			$this->db->select('(SELECT count(per1.id_permiso_cliente_medio) FROM permisos_cliente_medio per1 WHERE per1.id_medio=' . $id_medio . ' AND per1.estado = 1) aceptados', false);
			$this->db->select('(SELECT count(per2.id_permiso_cliente_medio) FROM permisos_cliente_medio per2 WHERE per2.id_medio=' . $id_medio . ' AND per2.estado = 2) rechazados', false);
			$this->db->from('clientes c');
			$this->db->join('usuarios u', 'u.estado=0 and c.id_usuario = u.id_usuario');
			$query = $this->db->get();
			$aRet = $query->result();			
			return $aRet;
    }

    /**
     * Obtiene el listado de tipos de medio
     *
     * @return array Listado de tipos de medios obtenidos
     */
    function getTiposMedios()
    {
        $this->db->select('id_tipo, tipo');
        $this->db->from('tipos_medio');
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Inserta un medio
     *
     * @param array $datos_medio
     *            Array con los datos del medio a insertar
     * @return integer Id del medio insertado
     */
    public function insertMedio($datos_medio)
    {
        $this->db->insert('medios', $datos_medio);
        
        return $this->db->insert_id();
    }

    /**
     * Actualiza el medio especificado
     *
     * @param integer $id_medio
     *            Id del medio a actualizar
     * @param array $datos_medio
     *            Array con los datos a actualizar del medio
     * @return boolean Siempre true
     */
    public function updateMedio($id_medio, $datos_medio)
    {
        $this->db->where('id_medio', $id_medio);
        $this->db->update('medios', $datos_medio);
        
        return true;
    }
}