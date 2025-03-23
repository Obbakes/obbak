<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    /**
     * Modifica los acceso de los usuarios especificados a la plataforma NLM
     *
     * @param array $cambios
     *            Cambios a llevar a cabo: posicion 2k id del usuario, posicion 2k+1 estado final del acceso
     */
    public function cambiarAccesoAnunciantes($accesos)
    {
        $usuarios_email = array();

        if (! empty($accesos)) {
            $aux = array();

            foreach ($accesos as $id_usuario => $acceso) {
                if ($acceso == 0)
                    $aux[] = $id_usuario;
            }

            if (! empty($aux)) { // Sólo vamos a devolver los usuarios que su estado estaba a 1 y se pasado a 0 (aceptado)
                $this->db->select('cli.email as email, cli.nombre as nombre');
                $this->db->from('usuarios usu');
                $this->db->join('clientes cli', 'usu.id_usuario = cli.id_usuario', 'left');
                $this->db->where_in('usu.id_usuario', $aux);
                $this->db->where('usu.estado', 1);
                $query = $this->db->get();

                $usuarios_email = $query->result();
            }

            foreach ($accesos as $id_usuario => $acceso) {
                $this->db->where('id_usuario', $id_usuario);
                $this->db->set('estado', $acceso);
                $this->db->update('usuarios');
            }
        }

        return $usuarios_email;
    }

    /**
     * Modifica los permisos del anunciante especificado para los medios, realizada por un administrador
     *
     * @param integer $id_cliente
     *            Id del anunciante para el que modificar los permisos
     * @param array $cambios
     *            Cambios a llevar a cabo: posicion 2k id del medio, posicion 2k+1 estado final del permiso
     */
    public function cambiarPermisosAnuncianteAdmin($id_cliente, $permisos)
    {
        if (! empty($permisos)) {
            foreach ($permisos as $id_medio => $nuevo_estado) {
                $valor = $nuevo_estado;
                if ($nuevo_estado == 1 || $nuevo_estado == 2) {
                    $this->crearPermisoClienteMedio($id_cliente, $id_medio, $nuevo_estado);
                } else if ($nuevo_estado == 3) {
                    $this->eliminarPermisoClienteMedio($id_cliente, $id_medio);
                }
            }
        }
    }

    /**
     * Modifica los permisos para el medio especificado de los anunciantes, realizada por un administrador
     *
     * @param integer $id_medio
     *            Id del medio para el que modificar los permisos
     * @param array $cambios
     *            Cambios a llevar a cabo: posicion 2k id del anunciante, posicion 2k+1 estado final del permiso
     */
    public function cambiarPermisosMedioAdmin($id_medio, $permisos)
    {
        if (! empty($permisos)) {
            foreach ($permisos as $id_cliente => $nuevo_estado) {
                $valor = $nuevo_estado;
                if ($nuevo_estado == 1 || $nuevo_estado == 2) {
                    $this->crearPermisoClienteMedio($id_cliente, $id_medio, $nuevo_estado);
                } else if ($nuevo_estado == 3) {
                    $this->eliminarPermisoClienteMedio($id_cliente, $id_medio);
                }
            }
        }
    }

    /**
     * Modifica los permisos del anunciante especificado para los medios, realizada por una agencia
     *
     * @param integer $id_cliente
     *            Id del anunciante para el que modificar los permisos
     * @param array $cambios
     *            Cambios a llevar a cabo: posicion 2k id del medio, posicion 2k+1 estado final del permiso
     */
    public function cambiarPermisosAnuncianteAgencia($id_cliente, $cambios)
    {
        $this->db->select('per.id_medio, IFNULL(per.estado, -1) estado', false);
        $this->db->from('permisos_cliente_medio per');
        $this->db->where('per.id_cliente', $id_cliente);
        $query = $this->db->get();

        $permisos = $query->result();

        if (! empty($cambios)) {
            foreach ($cambios as $medio => $nuevo_est) {
                if ($nuevo_est == 1) {
                    $accion = 'create';
                    $valor = 0;

                    foreach ($permisos as $permiso) {
                        if ($permiso->id_medio == $medio) {
                            if ($permiso->estado == 2) {
                                $accion = 'update';
                                $valor = 0;
                            }

                            break;
                        }
                    }

                    if ($accion == 'create') {
                        $datos = array(
                            'id_cliente' => $id_cliente,
                            'id_medio' => $medio,
                            'estado' => $valor
                        );

                        $this->db->insert('permisos_cliente_medio', $datos);
                    } else if ($accion == 'update') {
                        $this->db->set('estado', $valor);
                        $this->db->where('id_cliente', $id_cliente);
                        $this->db->where('id_medio', $medio);
                        $this->db->update('permisos_cliente_medio');
                    }
                }
            }
        }
    }

    /**
     * Inserta o Actualiza los permisos del cliente con el medio
     */
    public function crearPermisoClienteMedio($id_cliente = 0, $id_medio = 0, $permiso = 0)
    {
        if ($permiso == 1 || $permiso == 2) {
            $this->db->select('per.id_permiso_cliente_medio');
            $this->db->from('permisos_cliente_medio per');
            $this->db->where('per.id_cliente', $id_cliente);
            $this->db->where('per.id_medio', $id_medio);
            $query = $this->db->get();
            $permisoExistente = $query->row();

            if (! empty($permisoExistente)) {
                $this->db->set('estado', $permiso);
                $this->db->where('id_permiso_cliente_medio', $permisoExistente->id_permiso_cliente_medio);
                $this->db->update('permisos_cliente_medio');
            } else {
                $nuevoPermiso = array(
                    'id_cliente' => $id_cliente,
                    'id_medio' => $id_medio,
                    'estado' => $permiso
                );
                $this->db->insert('permisos_cliente_medio', $nuevoPermiso);
            }
        }
        // Registramos en el log el login del medio
        $id_usuario = $this->getUsuarioMedio($id_medio);
        $data = array(
            'id_usuario' => $id_usuario,
            'fecha' => date("Y-m-d H:i:s"),
            'accion' => '[{"id_cliente":"' . $id_cliente . '","permiso":"' . $permiso . '"}]'
        );
        $this->insertLogAccion($data);
    }

    /**
     * Elimina los permisos del cliente con el medio
     */
    public function eliminarPermisoClienteMedio($id_cliente = 0, $id_medio = 0)
    {
        $this->db->select('per.id_permiso_cliente_medio');
        $this->db->from('permisos_cliente_medio per');
        $this->db->where('per.id_cliente', $id_cliente);
        $this->db->where('per.id_medio', $id_medio);
        $query = $this->db->get();
        $permisoExistente = $query->row();

        if (! empty($permisoExistente)) {
            $this->db->where('id_cliente', $id_cliente);
            $this->db->where('id_medio', $id_medio);
            $this->db->delete('permisos_cliente_medio');

            // Registramos en el log el login del medio
            $id_usuario = $this->getUsuarioMedio($id_medio);
            $data = array(
                'id_usuario' => $id_usuario,
                'fecha' => date("Y-m-d H:i:s"),
                'accion' => '[{"id_cliente":"' . $id_cliente . '","permiso":"' . 3 . '"}]'
            );
            $this->insertLogAccion($data);
        }
    }

    /**
     * Obtiene los datos del anunciante especificado, funcion asociada al area de administrador
     *
     * @param integer $id_cliente
     *            Id de anunciante a obteners
     * @return resultSet Datos del anunciante obtenido
     */
    public function getClienteAdmin($id_cliente)
    {
        $this->db->select('cli.id_cliente, cli.nombre, cli.email, cli.nombre_comercial, cli.nombre_contacto, cli.Fecha_nacimiento, cli.apellidos_contacto, cli.email, cli.telefono, cli.direccion, cli.cp, cli.id_provincia, cli.id_sector, cli.newsletter, cli.id_usuario, usu.nick, cli.meses_interesado, cli.medios_interesado, cli.imagen');
        $this->db->select('cli.cif, age.nombre agencia, cli.Fecha_nacimiento, cli.email, cli.poblacion, usu.fecha_registro, usu.estado, cli.web, cli.id_usuario');
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
        $this->db->where('cli.id_cliente', $id_cliente);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        $cliente = $query->row();
        $cliente->permisos_medios = $this->getMediosConPermisoByIdCliente($id_cliente);
        return $query->row();
    }
	
	public function getClienteAdmininver($id_usuario )
    {
        $this->db->select('inver.id_tipo_inver, inver.id_usuario, inver.id_origen, cli.id_cliente, cli.nombre, cli.nombre_comercial, cli.nombre_contacto, cli.Fecha_nacimiento, cli.apellidos_contacto, cli.email, cli.telefono, cli.direccion, cli.cp, cli.id_provincia, cli.id_sector, cli.newsletter, cli.id_usuario, usu.nick, cli.meses_interesado, cli.poblacion, cli.medios_interesado, cli.imagen');
        $this->db->select('cli.cif, cli.imagen, inver.id_usuario, age.nombre agencia, cli.poblacion, usu.fecha_registro, usu.estado, cli.web, cli.direccion, cli.id_usuario');
		$this->db->select('inver.id_tipo_inver, inver.id_origen');
        $this->db->from('clientes cli', 'inversores inver');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
		$this->db->join('inversores inver', 'inver.id_usuario = cli.id_usuario', 'left');
        $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
		$this->db->where('cli.id_usuario', $id_usuario);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;
		        $clientes = $query->row();
 
        return $query->row();

    }

 
        public function getClientesPreferencias($id_cliente)
    {
        $this->db->select('clave,valor');
        $this->db->from('clientes_preferencias');
        $this->db->where('id_cliente', $id_cliente);
        $return = array();
       $results = $this->db->get()->result();
       foreach($results as $result){
            if(!isset($return[$result->clave])){
                $return[$result->clave] = array();
		    }
            $return[$result->clave][] = $result->valor;
	   }
       if(!isset($return["audiencia_masculina"])){
                $return["audiencia_masculina"] = array(50);
	   }
       return $return;
    }
    /**
     * Obtiene un cliente a traves de su id
     *
     * @param
     *            id_cliente a identificar
     * @return resultSet objeto cliente.
     */
    public function getClienteById($id_cliente)
    {
        $this->db->select('*');
        $this->db->from('clientes cli');
        $this->db->where('cli.id_cliente', $id_cliente);
        return $this->db->get()->row();
    }

    /**
     * Comprueba si existe un cliente con un email
     *
     * @param
     *            email a identificar
     * @return True si existe, false no existe.
     */
    public function getClienteByEmail($email_cliente)
    {
        $this->db->select('cli.id_cliente');
        $this->db->from('clientes cli');
        $this->db->where('cli.email', $email_cliente);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return true;

        return false;
    }

    public function getMediosConPermisoByIdCliente($id_cliente)
    {
        $this->db->select('permisos_cliente_medio.id_medio');
        $this->db->from('permisos_cliente_medio');
        $this->db->where('permisos_cliente_medio.id_cliente', $id_cliente);
        $this->db->where('permisos_cliente_medio.estado', 1);
        return $this->db->get()->result();
    }

    /**
     * Obtiene los datos del anunciante especificado a través del código único
     *
     * @param string $codigo
     *            Código del anunciante a obtener
     * @return resultSet Datos del anunciante obtenido
     */
    public function getClientePorCodigo($codigo)
    {
        $this->db->select('cli.id_cliente, cli.nombre, cli.nombre_contacto, cli.apellidos_contacto, cli.email, cli.telefono, cli.direccion, cli.cp, cli.newsletter, cli.id_usuario, usu.nick');
        $this->db->select('cli.cif, cli.poblacion, usu.fecha_registro, usu.estado, cli.web, cli.id_usuario');
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->where('cli.codigo_promo', $codigo);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Obtiene listado de clientes
     */
    public function getClientes()
    {
        $this->db->select('*');
        $this->db->from('clientes cli');
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->result();
    }

    /**
     * Obtiene los datos del anunciante especificado, funcion asociada a la autorización del Medio para ver sus ofertas
     *
     * @param integer $id_cliente
     *            Id de anunciante a obtener
     * @return resultSet Datos del anunciante obtenido
     */
    public function getClienteMedio($id_cliente)
    {
        $this->db->select('cli.id_cliente, cli.nombre_contacto, cli.cif, usu.fecha_registro, usu.fecha_alta');
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->where('cli.id_cliente', $id_cliente);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Obtiene el listado de anunciantes resultantes del filtro, funcion asociada al area de administrador
     *
     * @param array $filtro
     *            Opciones de filtrado: (estado, agencia, palabra, permisos, pagina, datosPorPagina)
     * @return array Listado de anunciantes obtenido
     */
    public function getClientesAdmin($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, cli.nombre, cli.email, cli.cif, cli.id_agencia, cli.imagen, age.nombre nombre_agencia', false);
        $this->db->select('(SELECT COUNT(per.id_permiso_cliente_medio) num_per_pen FROM permisos_cliente_medio per WHERE estado = 0 AND per.id_cliente = cli.id_cliente) permisos_pendientes', false);
        $this->db->select('usu.fecha_registro, usu.estado, cli.id_usuario', false);
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
        $this->db->join('sectores sec', 'cli.id_sector = sec.id_sector', 'left');

        if (! empty($filtro['estado']) && $filtro['estado'] != 'todos') {
            if ($filtro['estado'] == 'activo')
                $this->db->where('usu.estado', '0');
            else if ($filtro['estado'] == 'pendiente')
                $this->db->where('usu.estado', '1');
            else
                $this->db->where('usu.estado', '2');
        }

        if (! empty($filtro['agencia'])) {
            $this->db->where("cli.id_agencia", $filtro['agencia']);
        }

        if (! empty($filtro['sector'])) {
            $this->db->where("cli.id_sector", $filtro['sector']);
        }

        if (! empty($filtro['anunciante'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['anunciante'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.email LIKE '%" . $filtro['anunciante'] . "%')");
        }

        if (! empty($filtro['palabra'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['palabra'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['palabra'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['palabra'] . "%')");
        }

        if (! empty($filtro['permisos']) && $filtro['permisos'] != 'todos') {
            if ($filtro['permisos'] == 'pendiente')
                $this->db->where('cli.id_cliente IN (SELECT foo.id_cliente
														 FROM (SELECT cli.id_cliente, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM clientes cli
															   LEFT JOIN permisos_cliente_medio per ON cli.id_cliente = per.id_cliente
															   WHERE estado = 0
															   GROUP BY cli.id_cliente) foo
														 WHERE num_permisos_pendientes > 0)');
            else
                $this->db->where('cli.id_cliente NOT IN (SELECT foo.id_cliente
														 FROM (SELECT cli.id_cliente, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
															   FROM clientes cli
															   LEFT JOIN permisos_cliente_medio per ON cli.id_cliente = per.id_cliente
															   WHERE estado = 0
															   GROUP BY cli.id_cliente) foo
														 WHERE num_permisos_pendientes > 0)');
        }

        $this->db->where('tipo_usuario', 'cliente');
        if (! empty($filtro['order_by_campo'])) {
            $this->db->order_by($filtro['order_by_campo'], $filtro['order_by_sentido']);
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
     * Obtiene el listado de anunciantes resultante del filtro especificado, asociada al autocompletado del filtro del listado de ofertas
     *
     * @param array $filtro
     *            Opciones de filtrado: (agencia, palabra, pagina, datosPorPagina)
     * @return array Listado de anunciantes obtenidos
     */
    public function getClientesAutocompletar($filtro)
    {
        $this->db->select('cli.id_cliente value, cli.nombre label', false);
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');

        $this->db->where('usu.estado', '0');

        if (! empty($filtro['agencia'])) {
            $this->db->where("cli.id_agencia", $filtro['agencia']);
        }

        if (! empty($filtro['palabra'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['palabra'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['palabra'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['palabra'] . "%')");
        }

        $this->db->where('tipo_usuario', 'cliente');
        $this->db->order_by('nombre', 'asc');
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();

        $aRet = $query->result();

        return $aRet;
    }
    

     /**
     * Obtiene la lista de anunciantes que tienen acceso a la oferta especificada en el filtro
     *
     * @param array $filtro
     *            Opciones de filtrado: (agencia, oferta, pagina, datosPorPagina)
     * @return array Listado de anunciantes obtenidos
     */


    /**
     * Obtiene la lista de anunciantes que tienen acceso a la oferta especificada en el filtro
     *
     * @param array $filtro
     *            Opciones de filtrado: (agencia, oferta, pagina, datosPorPagina)
     * @return array Listado de anunciantes obtenidos
     */
    public function getClientesOferta($filtro)
    {
        $this->db->select('cli.nombre_comercial, cli.id_cliente, cli.nombre, cli.id_usuario, cli.nombre_contacto, cli.apellidos_contacto, age.newsletter as agencia_newsletter, age.email as agencia_email', false);
        $this->db->select("(SELECT group_concat(emails.email SEPARATOR ',')
                            FROM
                            (SELECT clientes.id_cliente,
                                clientes.email
                                FROM clientes clientes
                                UNION DISTINCT SELECT con.id_cliente,
                                con.email
                                FROM clientes_contacto con where fecha_baja is null) emails
                            WHERE emails.id_cliente = cli.id_cliente) as email", false);
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
      
        $this->db->where('usu.estado', '0');

        if (! empty($filtro['notifica_oferta_nueva'])) {
            $this->db->where('cli.notifica_oferta_nueva', $filtro['notifica_oferta_nueva']);
        }

        if (! empty($filtro['agencia'])) {
            $this->db->where("cli.id_agencia", $filtro['agencia']);
        }

        if (! empty($filtro['id_oferta'])) {
            $this->db->join('permisos_cliente_medio per', 'cli.id_cliente = per.id_cliente', 'left');
            $this->db->join('ofertas ofe', 'per.id_medio = ofe.id_medio', 'left');
            $this->db->join('clientes_medios cm', 'cm.id_cliente = cli.id_cliente and cm.id_medio = ofe.id_medio', 'left');
            $this->db->select('IFNULL(cm.descuento,0) as descuento_medio, IFNULL(cm.relacion_medio,100) as relacion_medio', false);
            $this->db->where('per.estado', 1);
            $this->db->where('ofe.id_oferta', $filtro['id_oferta']);
        }

        if (! empty($filtro['id_medio'])) {
            $this->db->join('permisos_cliente_medio per', 'cli.id_cliente = per.id_cliente', 'left');
            $this->db->where('per.estado', 1);
            $this->db->where('per.id_medio', $filtro['id_medio']);
        }

        $this->db->where('tipo_usuario', 'cliente');
        $this->db->order_by('nombre', 'asc');
        if (! empty($filtro['datosPorPagina']) && ! empty($filtro['pagina'])) {
            $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        }
        $query = $this->db->get();

        $aRet = $query->result();

        return $aRet;
    }

    /**
     * Obtiene los clientes registrados en las últimas 24h
     *
     * @return array Listado de anunciantes obtenidos
     */
    public function getClientesRegistroUltimoDia()
    {
        $fechaCorte = date("Y-m-d H:m:s", strtotime('-24 hours'));
        $this->db->select('cli.id_cliente, cli.nombre, cli.nombre_comercial, cli.cif, cli.email, pro.provincia');
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario');
        $this->db->join('provincia pro', 'pro.id_provincia = cli.id_provincia', 'left');
        $this->db->where('IFNULL(usu.fecha_alta, usu.fecha_registro) >=', $fechaCorte);
        $this->db->where('usu.estado', '0');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Obtiene el numero total de cliente obtenidos en la funcion getClientesAdmin
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getAnunciantesAdmin o array(estado, agencia, palabra, permisos)
     * @param
     *            integer Numero total de anunciantes obtenidos
     */
    public function getNumClientesAdmin($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(cli.id_cliente) AS numClientes');
            $this->db->from('clientes cli');
            $this->db->join('usuarios usu', 'usu.id_usuario = cli.id_usuario', 'left');

            if (! empty($filtro['estado']) && $filtro['estado'] != 'todos') {
                if ($filtro['estado'] == 'activo')
                    $this->db->where('usu.estado', '0');
                else if ($filtro['estado'] == 'pendiente')
                    $this->db->where('usu.estado', '1');
                else
                    $this->db->where('usu.estado', '2');
            }

            if (! empty($filtro['agencia'])) {
                $this->db->where("cli.id_agencia", $filtro['agencia']);
            }

            if (! empty($filtro['palabra'])) {
                $this->db->where("(cli.nombre LIKE '%" . $filtro['palabra'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['palabra'] . "%' ORcli.apellidos_contacto LIKE '%" . $filtro['palabra'] . "%')");
            }

            if (! empty($filtro['permisos']) && $filtro['permisos'] != 'todos') {
                if ($filtro['permisos'] == 'pendiente')
                    $this->db->where('cli.id_cliente IN (SELECT foo.id_cliente
															 FROM (SELECT cli.id_cliente, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
																   FROM clientes cli
																   LEFT JOIN permisos_cliente_medio per ON cli.id_cliente = per.id_cliente
																   WHERE estado = 0
																   GROUP BY cli.id_cliente) foo
															 WHERE num_permisos_pendientes > 0)');
                else
                    $this->db->where('cli.id_cliente NOT IN (SELECT foo.id_cliente
															 FROM (SELECT cli.id_cliente, COUNT(per.id_permiso_cliente_medio) num_permisos_pendientes
																   FROM clientes cli
																   LEFT JOIN permisos_cliente_medio per ON cli.id_cliente = per.id_cliente
																   WHERE estado = 0
																   GROUP BY cli.id_cliente) foo
															 WHERE num_permisos_pendientes > 0)');
            }

            $this->db->where('tipo_usuario', 'cliente');
            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numClientes;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de permisos obtenido en la funcion getPermisosCliente
     *
     * @param array $filtro
     *            Opciones de filtrado
     * @return integer Numero total de permisos obtenidos
     */
    public function getNumPermisosCliente($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(med.id_medio) AS numPermisos');
            $this->db->from('medios med');
            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numPermisos;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de permisos obtenido en la funcion getPermisosClienteGestor
     *
     * @param array $filtro
     *            Opciones de filtrado
     * @return integer Numero total de permisos obtenidos
     */
    public function getNumPermisosClienteGestor($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(med.id_medio) AS numPermisos');
            $this->db->from('medios med');
            $this->db->join('permisos_gestor_medio per_ges', 'med.id_medio = per_ges.id_medio', 'left');
            $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
            $this->db->where('per_ges.estado', 1);
            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numPermisos;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de permisos obtenido en la funcion getPermisosMedio
     *
     * @param array $filtro
     *            Opciones de filtrado
     * @return integer Numero total de permisos obtenidos
     */
    public function getNumPermisosMedio($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(cli.id_cliente) AS numPermisos');
            $this->db->from('clientes cli');

            if (! empty($filtro['anunciante'])) {
                $this->db->where("(cli.nombre LIKE '%" . $filtro['anunciante'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.email LIKE '%" . $filtro['anunciante'] . "%')");
            }

            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numPermisos;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el listado de permisos a medios resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (cliente, estado, tipo_medio, pagina, datosPorPagina)
     * @return array Listado de permisos obtenido
     */
    public function getPermisosCliente($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo, tim.tipo', false);
        $this->db->select('(SELECT per.estado FROM permisos_cliente_medio per WHERE ' . ((! empty($filtro['cliente'])) ? ('per.id_cliente = ' . $filtro['cliente'] . ' AND ') : '') . 'per.id_medio = med.id_medio) estado', false);
        $this->db->from('medios med');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');

        if (! empty($filtro['tipo_medio']) && $filtro['tipo_medio'] != 0)
            $this->db->where('med.id_tipo_medio', $filtro['tipo_medio']);

        if (! empty($filtro['medio']) && $filtro['medio'] != 0)
            $this->db->where('med.id_medio', $filtro['medio']);

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            if ($filtro['estado'] == 0) {
                $this->db->having('estado', 0);
            } else if ($filtro['estado'] == 1) {
                $this->db->having('estado', 1);
            } else if ($filtro['estado'] == 2) {
                $this->db->having('(estado = 2 OR estado IS NULL)');
            }
        }

        $this->db->order_by('med.nombre', 'asc');
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
     * Obtiene el listado de permisos a medios del Gestor resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (cliente, estado, tipo_medio, pagina, datosPorPagina)
     * @return array Listado de permisos obtenido
     */
    public function getPermisosClienteGestor($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS med.id_medio, med.nombre, med.logo, tim.tipo', false);
        $this->db->select('(SELECT per.estado FROM permisos_cliente_medio per WHERE ' . ((! empty($filtro['cliente'])) ? ('per.id_cliente = ' . $filtro['cliente'] . ' AND ') : '') . 'per.id_medio = med.id_medio) estado', false);
        $this->db->from('medios med');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->join('permisos_gestor_medio per_ges', 'med.id_medio = per_ges.id_medio', 'left');
        $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
        $this->db->where('per_ges.estado', 1);

        if (! empty($filtro['tipo_medio']) && $filtro['tipo_medio'] != 0)
            $this->db->where('med.id_tipo_medio', $filtro['tipo_medio']);

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            if ($filtro['estado'] == 0) {
                $this->db->having('estado', 0);
            } else if ($filtro['estado'] == 1) {
                $this->db->having('estado', 1);
            } else if ($filtro['estado'] == 2) {
                $this->db->having('(estado = 2 OR estado IS NULL)');
            }
        }

        $this->db->order_by('med.nombre', 'asc');
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
     * Obtiene el listado de permisos de los anunciantes resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (medio, agencia, estado, pagina, datosPorPagina)
     * @return array Listado de permisos obtenido
     */
    public function getPermisosAnunciantesGestor($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS per_ges.id_medio', false);
        $this->db->from('permisos_gestor_medio per_ges');
        $this->db->where('per_ges.id_gestor', $this->session->userdata('id_gestor'));
        $this->db->where('per_ges.estado', 1);

        $query = $this->db->get();
        $aRet1 = $query->result();

        $medios = Array();
        $i = 0;

        foreach ($aRet1 as $medio) {

            $medios[$i] = $medio->id_medio;
            $i ++;
        }

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $numMedios = $query->row()->totalRows;

        $this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, cli.nombre, cli.email, cli.cif, pro.provincia, ' . $numMedios . ' - ' . '(SELECT COUNT(per.estado) FROM medios med_user LEFT JOIN permisos_cliente_medio per ON med_user.id_medio = per.id_medio ' . 'WHERE med_user.id_medio IN (' . implode(",", $medios) . ') ' . 'AND per.id_cliente = cli.id_cliente AND (per.estado = 1 OR per.estado=2)) permisos_pdtes', false);
        $this->db->from('clientes cli');
        $this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario', 'left');
        $this->db->join('provincia pro', 'cli.id_provincia = pro.id_provincia', 'left');

        if (! empty($filtro['provincia']) && $filtro['provincia'] != 'todas') {
            $this->db->where('cli.id_provincia', $filtro['provincia']);
        }

        if (! empty($filtro['anunciante'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['anunciante'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.email LIKE '%" . $filtro['anunciante'] . "%')");
        }

        $this->db->where('usu.estado', 0);

        $this->db->order_by('cli.nombre', 'asc');
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
     * Obtiene el listado de permisos de los anunciantes resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (medio, agencia, estado, pagina, datosPorPagina)
     * @return array Listado de permisos obtenido
     */
    public function getPermisosMedio($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, cli.nombre, cli.email, cli.cif, age.nombre agencia, pro.provincia,s.sector', false);
        $this->db->select('(SELECT per.estado FROM permisos_cliente_medio per WHERE ' . ((! empty($filtro['medio'])) ? ('per.id_medio = ' . $filtro['medio'] . ' AND ') : '') . 'per.id_cliente = cli.id_cliente) estado', false);
        $this->db->from('clientes cli');
        $this->db->join('sectores s', 's.id_sector = cli.id_sector', 'left');
        $this->db->join('usuarios usu', 'cli.id_usuario = usu.id_usuario');
        $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
        $this->db->join('provincia pro', 'cli.id_provincia = pro.id_provincia', 'left');

        if (! empty($filtro['agencia']) && $filtro['agencia'] != 0)
            $this->db->where('cli.id_agencia', $filtro['agencia']);


        //1:Autorizado
        //2:NO Autorizado
        //3:Pendiente Autorizar

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            if ($filtro['estado'] == 0) {
                $this->db->having('estado', 0);
            } else if ($filtro['estado'] == 1) {
                $this->db->having('estado', 1);
            } else if ($filtro['estado'] == 2) {
                $this->db->having('estado', 2);
            } else if ($filtro['estado'] == 3) {
                $this->db->having('(estado = 3 OR estado IS NULL)');
            }
        }

        if (! empty($filtro['provincia']) && $filtro['provincia'] != 'todas') {
            $this->db->where('cli.id_provincia', $filtro['provincia']);
        }
        if(isset($filtro["sector"]) && $filtro["sector"] !="todos"){       
        $this->db->where('cli.id_sector',$filtro["sector"]);
	   } 


        if (! empty($filtro['anunciante'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['anunciante'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['anunciante'] . "%' OR cli.email LIKE '%" . $filtro['anunciante'] . "%')");
        }
        if (!empty($filtro['id_cliente'])) {
            $this->db->where("cli.id_cliente = ".$filtro['id_cliente']);
        }
        $this->db->where('usu.estado', 0);

        $this->db->order_by('cli.nombre', 'asc');
        //$this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();
        // echo  $this->db->last_query();
        $aRet = $query->result();

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;

        return $aRet;
    }

    /**
     * Obtiene el listado de las provincias
     *
     * @return array Listado de provincias
     */
    function getProvincias()
    {
        $this->db->select('id_provincia, provincia');
        $this->db->from('provincia');
        $this->db->order_by('provincia', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Obtiene el listado de los sectores
     *
     * @return array Listado de sectores
     */
    function getSectores()
    {
        $this->db->select('id_sector, sector');
        $this->db->from('sectores');
        $this->db->order_by('sector', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Obtiene el listado de tipos de medio
     *
     * @return array Listado de tipos de medios obtenidos
     */
    function getTiposMedios()
    {
        $this->db->select('*');
        $this->db->from('tipos_medio');
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

      function getMedios()
    {
        $this->db->select('*');
        $this->db->from('medios');      
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * Obtiene el listado de los tipos de ofertas
     *
     * @return array Listado de tipos de ofertas
     */
    function getTiposOferta()
    {
        $this->db->select('id_tipo_oferta, nombre_tipo_oferta');
        $this->db->from('tipos_oferta');
        $this->db->order_by('nombre_tipo_oferta', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Devuelve el id_usuario de un Medio
     *
     * @param
     *            id_medio a identificar
     * @return id_usuario.
     */
    public function getUsuarioMedio($id_medio)
    {
        $this->db->select('med.id_usuario');
        $this->db->from('medios med');
        $this->db->where('med.id_medio', $id_medio);
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            $oResultado = $query->row();

            return $oResultado->id_usuario;
        }
        return false;
    }

    /**
     * Inserta un anunciante
     *
     * @param array $datos_cliente
     *            Array con los datos del anunciante a insertar
     * @return integer Id del anunciante insertado
     */
    public function insertCliente($datos_cliente)
{
    // Insertar datos en la tabla 'clientes'
    $this->db->insert('clientes', $datos_cliente);
    $id_cliente = $this->db->insert_id(); // Obtener el ID recién insertado

    // Verificar si el ID fue generado correctamente
    if ($id_cliente) {
        // Insertar en la tabla 'inversores' con el ID del cliente
        $datos_inversor = array(
            'id_cliente' => $id_cliente, // Asociar el cliente con la tabla inversores
            'id_tipo_inver' => isset($datos_cliente['id_tipo_inver']) ? $datos_cliente['id_tipo_inver'] : null, // Opcional: tomar este valor si está en $datos_cliente
            'id_origen' => isset($datos_cliente['id_origen']) ? $datos_cliente['id_origen'] : null, // Opcional: tomar este valor si está en $datos_cliente
        );

        $this->db->insert('inversores', $datos_inversor);
    }

    return $id_cliente; // Retornar el ID del cliente
}

	
	/**
     * Inserta un anunciante
     *
     * @param array $datos_cliente
     *            Array con los datos del anunciante a insertar
     * @return integer Id del anunciante insertado
     */
    public function insertinver($datos_inver)
    {
		$this->db->insert('inversores', $datos_inver);
        $id_cliente = $this->db->insert_id();

        return $id_cliente;
    }


    /**
     * Inserta un registro de acción
     *
     * @param array $datos
     *            Array con los datos del registro a insertar
     *            
     */
    public function insertLogAccion($datos)
    {
        $this->db->insert('log_acciones', $datos);

        return true;
    }

     /**
     * Rellena el perfil de un cliente.
     *
     * @param array $datos_cliente
     *            DATOS del perfil a completar.
     *            
     */
    public function completarPerfil($datos_cliente)
    {
        // Insertamos nuevos datos.
        // Insertamos los tipos de medio.
        $first = true;
        $tipos_medio = '';
        $meses = '';
        $preferencias = array();
        // Insertamos los meses
        if (! empty($datos_cliente['meses'])) {
            foreach ($datos_cliente['meses'] as $mes) {
                if ($first) {
                    $meses = $mes;
                    $first = false;
                } else {
                    $meses .= ', ' . $mes;
                }
                $preferencias[] =  array(
                            'id_cliente' =>$datos_cliente['id_usuario'],
                            'clave' => "meses",
                            'valor' => $mes
                        );
            }
        }

        if(isset($datos_cliente['sector']) && $datos_cliente['sector'] != ""){
         $preferencias[] =  array(
                            'id_cliente' =>$datos_cliente['id_usuario'],
                            'clave' => "sector",
                            'valor' => 2
                        );
		}
        

        $datos = array(
            "meses_interesado" => $meses,
            "medios_interesado" => $tipos_medio,
            "id_sector" => 2
        );

        $this->db->where('id_cliente', $datos_cliente['id_usuario']);
        $this->db->update('clientes', $datos);

         $this->db->where('id_cliente', $datos_cliente['id_usuario']);
         $this->db->delete('clientes_preferencias');
         if (! empty($datos_cliente['preferencias'])) {
        
            foreach ($datos_cliente['preferencias'] as $clave => $valor) {
               
              if(!is_array($valor)){
              $valor = [$valor];
			  }
              foreach($valor as $v){
                     $preferencias[] =  array(
                            'id_cliente' =>$datos_cliente['id_usuario'],
                            'clave' => $clave,
                            'valor' => $v
                        );
			  }
         
            }
        }
        foreach($preferencias as $preferencia){
            $this->db->insert('clientes_preferencias',$preferencia);
		}


    }

    /**
     * Actualiza el anunciante especificado
     *
     * @param integer $id_cliente
     *            Id del anunciante a actualizar
     * @param array $datos_cliente
     *            Array con los datos a actualizar del anunciante
     */
public function updateCliente($id_cliente, $datos_cliente, $nick = '', $id_usuario=0)
{
    // Actualiza la tabla clientes
    $this->db->where('id_cliente', $id_cliente);
    $this->db->update('clientes', $datos_cliente);

    // Actualiza la tabla usuarios si el email está definido
         /**    if (!empty($datos_cliente['email'])) {
        $this->db->set('nick', $datos_cliente['email']);
        $this->db->where('id_usuario', "(SELECT id_usuario FROM clientes WHERE id_cliente = $id_cliente)", false);
        $this->db->update('usuarios');
    }**/

    // Actualiza el nick en la tabla usuarios si $nick no está vacío
    if (!empty($nick)) {
        $this->db->set('nick', $nick);
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('usuarios');
    }
}

	
	    public function updateinversor($id_cliente, $datos_cliente, $id_usuario = 0)
    {
        $this->db->where('id_cliente', $id_cliente);
        $this->db->update('inversores', $datos_cliente);


         /**if(!empty($datos_cliente['email'])){
         $this->db->set('nick', $datos_cliente['email']);
          $this->db->where('usu.id_usuario = cli.id_usuario');
          $this->db->where('cli.id_cliente', $id_cliente);
          $this->db->update('usuarios usu, clientes cli');
          }**/

        if (! empty($nick)) {
            $this->db->set('nick', $nick);
            $this->db->where('usu.id_usuario', $id_usuario);
            $this->db->update('usuarios usu');
        }
    }

    public function getResumenSemanalClientes()
    {
        $sql = 'SELECT main.id_cliente,
                       nombre,
                       aceptados,
                       denegados,
                       count(m.id_medio) - aceptados - denegados AS pendientes,
                       count(m.id_medio) AS totales,
                       ifnull(ofertas.num, 0) AS ofertas_publicadas
                FROM
                  (SELECT c.id_cliente,
                          c.nombre,
                          ifnull(sum(p.aceptados),0) AS aceptados,
                          ifnull(sum(p.denegados),0) AS denegados
                   FROM
                     (SELECT c.id_cliente,
                             c.nombre
                      FROM clientes c
                      INNER JOIN
                        (SELECT *
                         FROM usuarios
                         WHERE estado = 0) u ON c.id_usuario = u.id_usuario)c
                   LEFT JOIN
                     (SELECT p.id_cliente,
                             p.id_medio,
                             if(estado=1, 1, 0) AS aceptados,
                             IF (estado=2,
                                 1,
                                 0) AS denegados
                      FROM permisos_cliente_medio p) p ON c.id_cliente = p.id_cliente
                   INNER JOIN
                     (SELECT id_medio
                      FROM medios m
                      INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                      AND u.estado = 0
                      AND m.fecha_baja IS NULL)m ON p.id_medio = m.id_medio
                   GROUP BY c.id_cliente) main
                CROSS JOIN
                  (SELECT m.id_medio
                   FROM medios m
                   INNER JOIN
                     (SELECT *
                      FROM usuarios
                      WHERE estado = 0) u ON m.id_usuario = u.id_usuario
                   AND m.fecha_baja IS NULL)m
                LEFT JOIN
                  (SELECT p.id_cliente,
                          count(DISTINCT id_oferta) AS num
                   FROM permisos_cliente_medio p
                   INNER JOIN
                     (SELECT *
                      FROM ofertas
                      WHERE fecha_inicio_pub > DATE_SUB(CURDATE(), INTERVAL 80 DAY)) o ON p.id_medio = o.id_medio
                   AND p.estado = 1
                   GROUP BY p.id_cliente) ofertas ON main.id_cliente = ofertas.id_cliente
                GROUP BY main.id_cliente';
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Actualiza las notificaciones del cliente especificado
     *
     * @param integer $id_cliente
     *            Id del anunciante a actualizar
     * @param array $datos_notificaciones
     *            Array con los datos a actualizar del anunciante
     */
public function updateClienteNotificaciones($id_cliente, $notificaciones)
{
    if (!is_array($notificaciones)) {
        log_message('error', 'updateClienteNotificaciones: notificaciones no es un array.');
        return false;
    }

    $data = [
        'newsletter' => isset($notificaciones['newsletter']) ? (int) $notificaciones['newsletter'] : 0,
        'notifica_oferta_nueva' => isset($notificaciones['notifica_oferta_nueva']) ? (int) $notificaciones['notifica_oferta_nueva'] : 0
    ];

    $this->db->where('id_cliente', $id_cliente);
    return $this->db->update('clientes', $data);
}

    /**
     * Obtiene los permisos de notificaciones del anunciante especificado
     *
     * @param integer $id_cliente
     *            Id de anunciante a obtener
     */
    public function getClienteNotificaciones($id_cliente)
    {
        $this->db->select('cli.id_cliente, cli.newsletter, cli.notifica_oferta_nueva');
        $this->db->from('clientes cli');
        $this->db->where('cli.id_cliente', $id_cliente);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }

    /**
     * Obtiene los contactos asociados a un anunciante especificado
     *
     * @param integer $id_cliente
     *            Id de anunciante a obtener
     */
    public function getClienteContactosByIdCliente($id_cliente)
    {
        $this->db->from('clientes_contacto cli');
        $this->db->where('cli.id_cliente', $id_cliente);
        $this->db->where('cli.fecha_baja is null');
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->result();
    }

    public function getClientesNotificaNuevaOfertaDiario()
    {
        $sql = ' SELECT
                	cli.id_cliente,
                	ofe.id_oferta
                FROM
                	(
                	SELECT
                		*
                	FROM
                		clientes
                	WHERE
                		notifica_oferta_nueva = 2) cli
                inner join (
                	SELECT
                		*
                	FROM
                		permisos_cliente_medio
                	WHERE
                		estado = 1) per_cli_med ON
                	cli.id_cliente = per_cli_med.id_cliente
                inner join (
                	SELECT
                		*
                	FROM
                		ofertas ofe
                	WHERE
                		ofe.newsletter_flag = 1
                		and ofe.publicada = 1
                		and ofe.newsletter_fecha_envio_diario is null
                		and ofe.fecha_inicio_pub > Date_sub(SYSDATE(),interval 1 day) 
                ) ofe ON
                	ofe.id_medio = per_cli_med.id_medio
                                where
                	des.id_oferta_destinatario is null
                ORDER BY
                	cli.id_cliente,
                	ofe.id_oferta';
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return false;

        $rows = $query->result();
        foreach ($rows as $row) {
            if (! isset($clientes[$row->id_cliente])) {
                $ofertas = array();
            } else {
                $ofertas = $clientes[$row->id_cliente];
            }
            if (! array_key_exists($row->id_oferta, $ofertas)) {
                array_push($ofertas, $row->id_oferta);
            }
            $clientes[$row->id_cliente] = $ofertas;
        }
        return $clientes;
    }

    /**
     * Inserta un contacto de un anunciante
     *
     * @param array $datos_contacto
     *            Array con los datos del contacto del anunciante a insertar
     * @return integer Id del contacto insertado
     */
    public function insertClienteContacto($datos_contacto)
    {
        $this->db->insert('clientes_contacto', $datos_contacto);

        $id_contacto = $this->db->insert_id();

        return $id_contacto;
    }

    /**
     * Actualiza el contacto de anunciante con el id especificado
     *
     * @param integer $idcontacto
     *            Id del registro a actualizar
     * @param array $datos_contacto
     *            Array con los datos a actualizar del contacto
     */
    public function updateClienteContacto($idcontacto, $datos_contacto)
    {
        $this->db->where('id_cliente_contacto', $idcontacto);
        $this->db->update('clientes_contacto', $datos_contacto);
    }

    /**
     * Obtiene el contacto de anunciante con el id especificado
     *
     * @param integer $id_contacto_cliente
     *            Id de anunciante a obtener
     */
    public function getClienteContacto($id_contacto_cliente)
    {
        $this->db->from('clientes_contacto con');
        $this->db->where('con.id_cliente_contacto', $id_contacto_cliente);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        return $query->row();
    }
	/**
     * Rellena el perfil de un cliente.
     *
     * @param array $datos_cliente
     *            DATOS del perfil a completar.
     *            
     */
    public function completarPerfilinver($datos_cliente)
    {
        // Insertamos nuevos datos.
        // Insertamos los tipos de medio.
        $first = true;
        $preferencias = array();


        $this->db->where('id_cliente', $datos_cliente['id_cliente']);
        $this->db->update('clientes', $datos);

         $this->db->where('id_cliente', $datos_cliente['id_cliente']);
         $this->db->delete('clientes_preferencias');
         if (! empty($datos_cliente['preferencias'])) {
        
            foreach ($datos_cliente['preferencias'] as $clave => $valor) {
               
              if(!is_array($valor)){
              $valor = [$valor];
			  }
              foreach($valor as $v){
                     $preferencias[] =  array(
                            'id_cliente' =>$datos_cliente['id_cliente'],
                            'clave' => $clave,
                            'valor' => $v
                        );
			  }
         
            }
        }
        foreach($preferencias as $preferencia){
            $this->db->insert('clientes_preferencias',$preferencia);
		}


    }
	
		function getInscripcionescli($filtro)
{
    $this->db->select('SQL_CALC_FOUND_ROWS ins.id_cliente, cli.nombre, ins.estado, ins.tipo_inscripion, ins.id_oferta, ins.fecha', false);
    $this->db->from('inscripciones_oferta ins');
    $this->db->join('clientes cli', 'ins.id_cliente = cli.id_cliente', 'left');

    // Filtrar por id_cliente si está definido
    if (isset($filtro['id_cliente'])) {
        $this->db->where('ins.id_cliente',$filtro['id_cliente']);
    }

    $query = $this->db->get();

    if (!$query) {
        return false; // Retorna false si la consulta falla
    }

    $aRet = $query->result();

    // Obtener número de filas encontradas
    $this->db->select('FOUND_ROWS() AS totalRows', false);
    $query = $this->db->get();
    $this->iNumFilas = $query->row()->totalRows ?? 0;

    return $aRet;
}

public function getMovimientosPorCliente($id_cliente)
{
    $this->db->select('id_movimiento, id_cliente, id_oferta, tipo_mov, importe');
    $this->db->from('movimientos');
    $this->db->where('id_cliente', $id_cliente);
    
    $query = $this->db->get();
    return $query->result(); // Retorna un array de objetos con los resultados
}

}
