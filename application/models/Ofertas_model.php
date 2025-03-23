<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class ofertas_model extends CI_Model
{

    private $iNumFilas = 0;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Modifica el estado de las inscripciones para la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a la que pertenecen las inscripciones a modificar
     * @param array $estados
     *            Array de cambios: posicion 2k Id del anunciante, posicion 2k+1 estado final de la inscripcion
     */
    public function cambiarEstadoInscripciones($id_oferta, $estados)
    {
        if (! empty($estados)) {
            foreach ($estados as $id_cliente => $estado) {
                $this->db->where('id_cliente', $id_cliente);
                $this->db->where('id_oferta', $id_oferta);
                $this->db->set('estado', $estado);
                $this->db->update('inscripciones_oferta');
            }
        }
    }

    /**
     * Elimina una oferta
     *
     * @param integer $id_oferta
     *            Id de la oferta a eliminar
     * @return boolean True si se elimino la oferta, false si tenia asociadas inscripciones no canceladas
     */
    function deleteOferta($id_oferta)
    {
        $this->db->select('id_inscripcion_oferta'); /* seleccionamos las inscripciones */
        $this->db->from('inscripciones_oferta');
        $this->db->where('id_oferta', $id_oferta); /* asociadas a esa oferta */
        $this->db->where('estado !=', 3);
        $query = $this->db->get();

        if ($query->num_rows > 0) { /* si la consulta nos devuelve resultados, no podemos eliminar la oferta */
            return false;
        }

        $this->db->where('id_oferta', $id_oferta);
        $this->db->delete('inscripciones_oferta');

        $this->db->where('id_oferta', $id_oferta); /* eliminamos la oferta de la base de datos */
        $this->db->delete('ofertas');

        return true;
    }

    /**
     * Desinscribe al cliente de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta de la que desinscribir al cliente
     * @param integer $id_cliente
     *            Id del cliente a desinscribir
     */
    function desinscribirAnunciante($id_oferta, $id_cliente)
    {
        $this->db->where('id_oferta', $id_oferta);
        $this->db->where('id_cliente', $id_cliente);
        $this->db->where('estado', 0);
        $this->db->delete('inscripciones_oferta');
    }

    /**
     * Obtiene el listado de anunciantes y el estado de su inscripcion a la oferta especificada en el filtro
     *
     * @param array $filtro
     *            Opciones de filtrado: (agencia, oferta, estado, pagina, datosPorPagina)
     * @return array Lista de anunciantes obtenidos
     */
    function getAnunciantesInscripciones($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS cli.id_cliente, cli.nombre', false);
        $this->db->select('IF((SELECT ins.estado FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta ) IS NULL, 0, 1) inscrito', false);
        $this->db->from('clientes cli');
        $this->db->join('permisos_cliente_medio per', 'cli.id_cliente = per.id_cliente', 'left');
        $this->db->join('ofertas ofe', 'per.id_medio = ofe.id_medio', 'left');

        /* Si han seleccionado un medio que tengan como favorito */
        if (! empty($filtro['agencia'])) {
            $this->db->where('cli.id_agencia', $filtro['agencia']);
        }

        /* Si han seleccionado un tipo de medio */
        if (! empty($filtro['oferta'])) {
            $this->db->where('ofe.id_oferta', $filtro['oferta']);
        }

        $this->db->where('per.estado', 1);

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            if ($filtro['estado'] == 0)
                $this->db->having('inscrito', 0);
            else
                $this->db->having('inscrito', 1);
        }

        $this->db->order_by('cli.nombre', 'asc');
        // $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();

        $aRet = $query->result();

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;

        return $aRet;
    }
    function getOfertasEnviadasResume($medio)
    {         $this->db->select('IFNULL(count(*),0) as ofertas, IFNULL(sum(IF(fecha_envio IS NULL, 0, 1)),0) as enviadas,IFNULL((sum(IF(fecha_envio IS NULL, 0, 1)) * 100 / count(*)),0) as percentaje', false);        $this->db->from('ofertas_destinatarios');        $this->db->where('id_cliente', $medio->id_medio);        $query = $this->db->get();
        return $query->row();    }
    /**
     * Obtiene la lista de inscripciones resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (cliente, oferta, estado, pagina, datosPorPagina)
     * @return array Listado de inscripciones obtenidas
     */
    function getInscripciones($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, ofe.titulo, ins.id_cliente, cli.nombre, ins.estado, ins.tipo_inscripion, ins.fecha, ins.documento,', false);
        $this->db->from('inscripciones_oferta ins');
        $this->db->join('ofertas ofe', 'ofe.id_oferta = ins.id_oferta', 'left');
        $this->db->join('clientes cli', 'ins.id_cliente = cli.id_cliente', 'left');



        /* Si han seleccionado un tipo de medio */
        if (! empty($filtro['id_cliente'])) {
            $this->db->where('ins.id_cliente', $filtro['id_cliente']);
        }

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            $this->db->where('ins.estado', $filtro['estado']);
        }

        $this->db->order_by('ins.fecha', 'desc');
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
     * Obtiene la lista de inscripciones resultantes del filtro especificado
     *
     * @param array $filtro
     *            Opciones de filtrado: (cliente, oferta, estado, pagina, datosPorPagina)
     * @return array Listado de inscripciones obtenidas
     */
    function getInscripcionescli($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ins.id_cliente, cli.nombre, ins.estado, ins.tipo_inscripion, ins.fecha, ins.documento, ins.documento, ins.importe_inscrip,', false);
        $this->db->from('inscripciones_oferta ins');
        $this->db->join('clientes cli', 'ins.id_cliente = cli.id_cliente', 'left');



        /* Si han seleccionado un tipo de medio */
        if (! empty($filtro['id_cliente'])) {
            $this->db->where('ins.id_cliente', $filtro['id_cliente']);
        }

        if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
            $this->db->where('ins.estado', $filtro['estado']);
        }

        $this->db->order_by('ins.fecha', 'desc');
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
     * Obtiene la lista de clientes y las oferta a las que estan inscritos
     *
     * @param
     *            array filtro Opciones de filtrado: (palabra, agencia, estado, pagina, datosPorPagina)
     * @return array Listado de clientes obtenidos
     */
    public function getInscripcionesAnunciantes($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS cli.nombre, cli.id_cliente', false);
        $this->db->from('clientes cli');

        if (! empty($filtro['estado']) && $filtro['estado'] != 'todos') {
            $this->db->where('cli.id_cliente IN (SELECT ino2.id_cliente FROM inscripciones_oferta ino2 WHERE ino2.estado = ' . $filtro['estado'] . ')');
        } else {
            $this->db->where('cli.id_cliente IN (SELECT ino2.id_cliente FROM inscripciones_oferta ino2)');
        }

        if (! empty($filtro['palabra'])) {
            $this->db->where("(cli.nombre LIKE '%" . $filtro['palabra'] . "%')");
        }

        $this->db->order_by('cli.nombre', 'asc');
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();

        $aClientes = $query->result();

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;

        for ($i = 0; $i < count($aClientes); $i ++) {
            // Consulta para obtener ofertas del cliente
            $this->db->select('titulo, estado, fecha', false);
            $this->db->from('ofertas ofe');
            $this->db->join('inscripciones_oferta ino', 'ofe.id_oferta = ino.id_oferta');
            $this->db->where('id_cliente', $aClientes[$i]->id_cliente);
            $query = $this->db->get();

            $aClientes[$i]->ofertas = $query->result();
        }

        return $aClientes;
    }

    /**
     * Obtiene los precios minimo y maximo de las ofertas publicadas para establecer los limites del rango de precios de filtro en el listado de ofertas
     *
     * @return resultSet Precios minimo y maximo obtenidos
     */
    function getMaxMinPreciosOfertas($id_cliente = 0){
        $this->db->select('COALESCE(MAX(precio_oferta), 0) max_precio, COALESCE(MIN(precio_oferta), 0) min_precio', false);
        $this->db->from('ofertas ofe');
        $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
        $this->db->where('ofe.fecha_inicio_pub <= sysdate()');
        $this->db->where('ofe.publicada', 1);

        if (! empty($id_cliente)) {
            $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $id_cliente . " AND per.estado = 1)");
        }

        $query = $this->db->get();
        return $query->row();
    }
	
function getInscripcionesimpor($filtro)
{
    $this->db->select('SUM(ins.importe_inscrip) AS total_importe', false);
    $this->db->from('inscripciones_oferta ins');

    /* Filtrar por cliente si está definido */
    if (!empty($filtro['id_cliente'])) {
        $this->db->where('ins.id_cliente', $filtro['id_cliente']);
    }

    /* Filtrar por estado si se ha definido */
    if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
        $this->db->where('ins.estado', $filtro['estado']);
    }

    $query = $this->db->get();

    // Obtener el resultado
    $result = $query->row();

    // Convertir el total en formato moneda euro
    $totalImporte = $result ? $result->total_importe : 0;
    return number_format($totalImporte, 2, ',', '.') . ' €';
}


function getInscripcionesrenta($filtro)
{
    $this->db->select('AVG(ins.renta_esti) AS media_renta', false);
    $this->db->from('inscripciones_oferta ins');

    /* Filtrar por cliente si está definido */
    if (!empty($filtro['id_cliente'])) {
        $this->db->where('ins.id_cliente', $filtro['id_cliente']);
    }

    /* Filtrar por estado si se ha definido */
    if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
        $this->db->where('ins.estado', $filtro['estado']);
    }

    $query = $this->db->get();

    // Obtener el resultado
    $result = $query->row();

    // Convertir la media en formato de porcentaje
    $mediaRenta = $result ? $result->media_renta : 0;
    return number_format($mediaRenta, 2, ',', '.') . ' %';
}





    /**
     * Obtiene el numero total de anunciantes obtenido en la funcion getAnunciantesInscripciones
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getAnunciantesInscripciones o array(agencia, oferta, estado)
     * @return integer Numero total de anunciantes obtenido
     */
    function getNumAnunciantesInscripciones($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(cli.id_cliente) AS numAnunciantes');
            $this->db->from('clientes cli');
            $this->db->join('permisos_cliente_medio per', 'cli.id_cliente = per.id_cliente', 'left');
            $this->db->join('ofertas ofe', 'per.id_medio = ofe.id_medio', 'left');

            /* Si han seleccionado un medio que tengan como favorito */
            if (! empty($filtro['agencia'])) {
                $this->db->where('cli.id_agencia', $filtro['agencia']);
            }

            /* Si han seleccionado un tipo de medio */
            if (! empty($filtro['oferta'])) {
                $this->db->where('ofe.id_oferta', $filtro['oferta']);
            }

            $this->db->where('per.estado', 1);

            if (isset($filtro['estado']) && $filtro['estado'] != 'todos') {
                if ($filtro['estado'] == 0)
                    $this->db->having('inscrito', 0);
                else
                    $this->db->having('inscrito', 1);
            }

            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numAnunciantes;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de inscripciones en la funcion getInscripciones
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getInscripciones o array(cliente, oferta, estado)
     * @return integer Numero total de inscripciones obtenidas
     */
   function getNumInscripciones($filtro = [id_cliente])
{
    $this->db->select('COUNT(*) AS numInscripciones');
    $this->db->from('inscripciones_oferta');

    // Verificar si 'id_cliente' está en el filtro y es un valor adecuado
    if (!empty($filtro['id_cliente'])) {
        // Si es un array, usar 'where_in' para múltiples IDs
        if (is_array($filtro['id_cliente'])) {
            $this->db->where_in('id_cliente', $filtro['id_cliente']);
        }
        // Si es un valor numérico, usar 'where' para un solo ID
        elseif (is_numeric($filtro['id_cliente'])) {
            $this->db->where('id_cliente', $filtro['id_cliente']);
        }
        // Si es una cadena de texto con una lista de IDs separada por comas
        elseif (is_string($filtro['id_cliente']) && strpos($filtro['id_cliente'], ',') !== false) {
            $idClientes = array_map('trim', explode(',', $filtro['id_cliente']));
            $this->db->where_in('id_cliente', $idClientes);
        } else {
            // Si el valor de 'id_cliente' no es adecuado, puedes optar por manejarlo de alguna manera
            // Como retornar un valor predeterminado o lanzar un error.
            return 0; // O algún valor adecuado en caso de que 'id_cliente' no sea válido
        }
    }

    $query = $this->db->get();
    return $query->row()->numInscripciones;
}



    /**
     * Obtiene el numero total de anunciantes obtenido en la funcion getInscripcionesAnunciantes
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getInscripcionesAnunciantes o array(palabra, agencia, estado)
     * @return integer Numero total de anunciantes obtenido
     */
    function getNumInscripcionesAnunciantes($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(cli.id_cliente) AS numClientes');
            $this->db->from('clientes cli');
            $this->db->join('inscripciones_oferta ino', 'cli.id_cliente = ino.id_cliente');
            $this->db->join('ofertas ofe', 'ino.id_oferta = ofe.id_oferta');

            if (! empty($filtro['estado']) and $filtro['estado'] != 'todos') {
                if ($filtro['estado'] == 'activo')
                    $this->db->where('usu.estado', '0');
                else if ($filtro['estado'] == 'pendiente')
                    $this->db->where('usu.estado', '1');
                else
                    $this->db->where('usu.estado', '2');
            }

            if (! empty($filtro['palabra'])) {
                $this->db->where("(cli.nombre LIKE '%" . $filtro['palabra'] . "%' OR ofe.titulo LIKE '%" . $filtro['palabra'] . "%')");
            }

            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numClientes;
        }

        return $this->iNumFilas;
    }


    /**
     * Obtiene el numero total de anunciantes obtenido en la funcion getInscripcionesAnunciantes
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el filtro usado en getInscripcionesAnunciantes o array(palabra, agencia, estado)
     * @return integer Numero total de anunciantes obtenido
     */
    public function getNumInscripcionesAnunciantes2($id_cliente = null) {
    if ($id_cliente) {
        $this->db->where('id_cliente', $id_cliente);
    }
    return $this->db->count_all_results('inscripciones_oferta');
}

    /**
     * Obtiene el numero total de ofertas segun el filtro de la funcion getOfertas
     *
     * @param array $filtro
     *            Opciones de filtrado: false si se utiliza el usado en la funcion getOfertas o array(medio, tipo_medio, destacadas, id_cliente, cliente)
     * @return integer Numero total de ofertas obtenidas
     */
    function getNumOfertas($filtro = false)
    {
        if ($filtro !== false) {
            $this->db->select('COUNT(ofe.id_oferta) AS numOfertas');
            $this->db->from('ofertas ofe');
            $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
            $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
            $this->db->where('ofe.fecha_inicio_pub <=', date("Y-m-d"));
            $this->db->where('ofe.publicada', 1);

            /* Si han seleccionado un medio que tengan como favorito */
            if (! empty($filtro['medio']) && $filtro['medio'] > 0) {
                $this->db->where('ofe.id_medio', $filtro['medio']);
            }

            /* Si han seleccionado un tipo de medio */
            if (! empty($filtro['tipo_medio']) && $filtro['tipo_medio'] > 0) {
                $this->db->where('med.id_tipo_medio', $filtro['tipo_medio']);
            }

            /* Si han seleccionado mostrar o no destacadas, utilizamos la lista obtenida antes */
            if (! empty($filtro['destacadas']) && $filtro['destacadas'] > 0) {
                if ($filtro['destacadas'] == 1) {
                    $this->db->where('ofe.destacada', 1); /* Si quiere destacadas */
                } else {
                    $this->db->where('ofe.destacada', 0); /* Si no quiere destacadas */
                }
            }

            if (! empty($filtro['id_cliente']) && $filtro['id_cliente'] > 0) {
                /* $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $filtro['id_cliente'] . ")"); */
            } else if (! empty($filtro['cliente'])) {
                /* $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per LEFT JOIN clientes cli ON per.id_cliente = cli.id_cliente WHERE cli.nombre LIKE '%" . $filtro['cliente'] . "%' OR cli.nombre_contacto LIKE '%" . $filtro['cliente'] . "%' OR cli.apellidos_contacto LIKE '%" . $filtro['cliente'] . "%')"); */
            }

            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numOfertas;
        }

        return $this->iNumFilas;
    }

    /**
     * Obtiene el numero total de ofertas obtenidas en la funcion getOfertasAdmin
     *
     * @return integer Numero de ofertas totales
     */
    public function getNumOfertasAdmin()
    {
        return $this->iNumFilas;
    }

    /**
     * Obtiene los datos de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a obtener
     * @param integer $id_cliente
     *            (Opcional) Id del cliente para el que obtener si esta inscrito en la oferta o no
     * @return resultSet Datos de la oferta obtenida
     */
    function getOferta($id_oferta, $id_cliente, $id_agencia = '')
    {
        $this->db->select('ofe.renta_esti, ofe.imagen, ofe.Ruta, ofe.inversion, ofe.porc_inversion, ofe.inversion_min, ofe.total_reviews, ofe.newsletter_num_envios,ofe.newsletter_parrafo, ofe.precio_oferta, ofe.precio_anterior, ofe.id_medio, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.condiciones, ofe.detalle, ofe.link, ofe.fecha_inicio_pub, ofe.inversion', false);
        $this->db->select('med.descripcion descripcion_medio, ofe.descuento, ofe.id_oferta, ofe.titulo, med.nombre as medio, tim.tipo,med.id_tipo_medio', false);
        $this->db->select('ofe.renta_esti, ofe.duracion_camp, ofe.inversion, ofe.coste_real, med.logo logo_medio, med.web web_medio, ofe.descripcion, ofe.destacada, ofe.galeria_img', false);
        $this->db->select('i.estado as iestado, i.tipo_inscripion as itipo, i.inscrip as inscrip', false);
		$this->db->select('IFNULL(i.tipo_inscripion,-1) as itipo', false);

       
        $this->db->from("ofertas ofe"); 
        $this->db->join("medios med", "ofe.id_medio = med.id_medio");
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');        $this->db->join('inscripciones_oferta i', 'i.id_oferta = ofe.id_oferta and i.id_cliente = ' . $id_cliente , 'left');
        $this->db->where('ofe.id_oferta', $id_oferta); 

        /*
         * if(empty($id_agencia)){
         * $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $id_cliente . " AND per.estado = 1) OR ofe.es_generica=1");
         *
         * }
         */

        $query = $this->db->get();


        $oferta = $query->row();

        return $oferta;
    }
     function getLastOfertaDestinatario($id_oferta, $id_cliente)
    {
        $this->db->select('ofe.*', false);       
        $this->db->from("ofertas_destinatarios ofe");          $this->db->where('ofe.id_oferta', $id_oferta);        $this->db->where('ofe.id_cliente', $id_cliente);
        $this->db->order_by('fecha', "desc"); 
        $this->db->limit(1);  

        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        $oferta = $query->row();

        return $oferta;
    }
    
    /**
     * Obtiene los datos de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a obtener
     * @param integer $id_cliente
     *            (Opcional) Id del cliente para el que obtener si esta inscrito en la oferta o no
     * @return resultSet Datos de la oferta obtenida
     */
    function getOfertaPromo($id_oferta, $id_cliente)
    {
        $this->db->select('ofe.imagen, ofe.precio_oferta, ofe.precio_anterior, ofe.fecha_fin_pub, ofe.condiciones, ofe.detalle, ofe.link, ofe.fecha_inicio_pub, ofe.inversion', false);
        $this->db->select('med.descripcion descripcion_medio, ofe.descuento, ofe.id_oferta, ofe.titulo, med.nombre as medio, tim.tipo', false);
        $this->db->select('ofe.duracion_camp, ofe.coste_real, med.logo logo_medio, med.web web_medio, ofe.descripcion, ofe.destacada', false);
		$this->db->select('ins.id_inscripcion_oferta,  ins.id_oferta, ins.id_cliente', false);
        $this->db->select('IF((SELECT ins.id_inscripcion_oferta FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND ins.id_cliente = ' . $id_cliente . ' LIMIT 1) IS NULL, 0, 1) inscrito', false);
		$this->db->select('IF((SELECT ins.id_tipo_inscripion FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND ins.id_cliente = ' . $id_cliente . ' LIMIT 1) IS NULL, 0, 1) inscrito', false);
        $this->db->from("ofertas ofe");
        $this->db->join("medios med", "ofe.id_medio = med.id_medio");
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->where('ofe.id_oferta', $id_oferta);

        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        $oferta = $query->row();

        return $oferta;
    }

    /**
     * Obtiene los datos de la oferta especifica, funcion asociada a administrador
     *
     * @param integer $id_oferta
     *            Id de la oferta a obtener
     * @param
     *            resultSet Datos de la oferta obtenida
     */
    public function getOfertaAdmin($id_oferta)
    {
        $this->db->select('ofe.id_oferta, ofe.titulo, ofe.id_tipo_oferta, ofe.id_medio, med.id_tipo_medio, ofe.descripcion, ofe.detalle, ofe.condiciones, ofe.duracion_camp, ofe.id_provincia, ofe.id_sector', false);
        $this->db->select('ofe.precio_anterior, ofe.precio_oferta, ofe.descuento, ofe.coste_real, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.fecha_insercion, ofe.imagen, ofe.publicada, ofe.link, ofe.galeria_img', false);
        $this->db->from('ofertas ofe');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->where('ofe.id_oferta', $id_oferta);
        $this->db->order_by('ofe.id_oferta', 'desc');
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * Obtiene un cliente a traves de su id
     *
     * @param id_cliente a identificar
     * @return resultSet Objeto cliente.
     */
    public function getOfertaById ($id_oferta)
    {
        $this->db->select('ofe.*, tip.imagen_tipo_oferta');
        $this->db->from('ofertas ofe');
        $this->db->join('tipos_oferta tip', 'ofe.id_tipo_oferta = tip.id_tipo_oferta', 'left');
        $this->db->where('ofe.id_oferta', $id_oferta);
        return $this->db->get()->row();
    }

	   public function getTiposMedioAIMC()
    {
        $this->db->select('*,IFNULL(label,campo) as label');
        $this->db->from('tipos_medio_AIMC');
		$this->db->where('visible', 1);
		 $this->db->order_by('visible', 'asc');
		$query = $this->db->get();
        $aRet = $query->result();
		return $aRet;
    }
	   public function getTiposOfertaAIMC()
    {
        $this->db->select('*');
        $this->db->from('tipos_oferta_AIMC');
		$query = $this->db->get();
        $aRet = $query->result();
		return $aRet;
    }
    /**
     * Obtiene los datos de la oferta especifica, funcion asociada a medio
     *
     * @param integer $id_oferta
     *            Id de la oferta a obtener
     * @param
     *            resultSet Datos de la oferta obtenida
     */
    public function getOfertaMedio($id_oferta)
    {
        $this->db->select('ofe.fecha_fin_camp, ofe.newsletter_num_envios, ofe.newsletter_parrafo, ofe.galeria_img,ofe.id_oferta, ofe.titulo, ofe.id_medio, med.id_tipo_medio, ofe.descripcion, ofe.detalle, ofe.condiciones, ofe.duracion_camp, ofe.id_provincia, ofe.id_sector', false);
        $this->db->select('ofe.precio_anterior, ofe.precio_oferta, ofe.descuento, ofe.coste_real, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.fecha_insercion, ofe.imagen, ofe.publicada, ofe.link', false);
        $this->db->from('ofertas ofe');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->where('ofe.id_oferta', $id_oferta);
        $this->db->where('ofe.id_medio_crea', $this->session->userdata('id_medio'));
        $query = $this->db->get();
      /*
        $str = $this->db->last_query();
        print_r($str);
        */
        return $query->row();
    }
     /**
     * Obtiene la llista de oferta publicadas resultantes del filtro escogido
     *
     * @param array $filtro
     *            Opciones de filtro: (medio, tipo_medio, destacadas, id_cliente, cliente, ofertas, min_precio, max_precio, ordenar, pagina, datosPorPagina)
     *            @result array Listado de ofertas obtenidas
     */
    function getOfertas($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, 0 as relacion_medio,ofe.id_oferta, ofe.titulo, ofe.porc_inversion, ofe.renta_esti, ofe.descripcion, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.condiciones, ofe.detalle, ofe.precio_anterior, ofe.es_generica, ofe.galeria_img, ofe.inversion', false);
        $this->db->select('ofe.descuento,IFNULL(cm.descuento,0) as descuento_medio, ofe.imagen, ofe.id_medio, med.nombre medio, med.id_tipo_medio, tim.tipo, ofe.duracion_camp, ofe.destacada, ofe.id_tipo_oferta, tio.nombre_tipo_oferta, tio.imagen_tipo_oferta, tio.icono_tipo_oferta', false);
        $this->db->select('CASE WHEN DATEDIFF(sysdate(), ofe.fecha_inicio_pub) <= 7 THEN 1 ELSE 0 END novedad, med.logo logo_medio', false);
        $this->db->select('IF((SELECT ins.id_inscripcion_oferta FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND ins.id_cliente = ' . ((! empty($filtro['id_cliente'])) ? $filtro['id_cliente'] : 0) . ' and ins.estado in (0,1,2) LIMIT 1) IS NULL, 0, 1) inscrito', false);
        $this->db->from('ofertas ofe');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->join('tipos_oferta tio', 'tio.id_tipo_oferta = ofe.id_tipo_oferta', 'left');
         $this->db->join('clientes_medios cm', 'cm.id_cliente = ' . ((! empty($filtro['id_cliente'])) ? $filtro['id_cliente'] : 0) . ' and cm.id_medio = ofe.id_medio', 'left');
        $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= sysdate()))');
        $this->db->where('ofe.fecha_inicio_pub <= sysdate()');
        $this->db->where('ofe.publicada', 1);
        
    

        /* Si han seleccionado un medio que tengan como favorito */
        if (! empty($filtro['medio'])) {
            $this->db->where_in('ofe.id_medio', $filtro['medio']);
        } else {
            /* Si han seleccionado un tipo de medio */
            if (! empty($filtro['tipo_medio'])) {
                $this->db->where_in('med.id_tipo_medio', $filtro['tipo_medio']);
            }
        }
        
        /* Si han seleccionado mostrar o no destacadas, utilizamos la lista obtenida antes */
        /*
         * if(!empty($filtro['destacadas']) && $filtro['destacadas'] > 0){
         * if($filtro['destacadas'] == 1){
         * $this->db->where('ofe.destacada', 1); /*Si quiere destacadas
         * }
         * else{
         * $this->db->where('ofe.destacada', 0); /*Si no quiere destacadas
         * }
         * }
         */
        
        if (! empty($filtro['destacadas'])) {
            $this->db->where_in('ofe.destacada', $filtro['destacadas']);
        }
        
        if (! empty($filtro['id_cliente']) && $filtro['id_cliente'] > 0) {
            $this->db->where("ofe.id_medio not IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $filtro['id_cliente'] . " AND per.estado = 2) ");
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
        } else if (! empty($filtro['cliente'])) {
            /* David Escudero */
            $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per LEFT JOIN clientes cli ON per.id_cliente = cli.id_cliente WHERE per.estado <> 2 AND (cli.nombre LIKE '%" . $filtro['cliente'] . "%' OR  cli.nombre_contacto LIKE '%" . $filtro['cliente'] . "%' OR  cli.apellidos_contacto LIKE '%" . $filtro['cliente'] . "%'))");
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
        }
        
        if (! empty($filtro['ofertas'])) {
            $this->db->where_in('ofe.id_oferta', $filtro['ofertas']);
        }
        
        if (! empty($filtro['min_precio'])) {
            $this->db->where('ofe.precio_oferta >=', $filtro['min_precio']);
        }
        
        if (! empty($filtro['max_precio'])) {
            $this->db->where('ofe.precio_oferta <=', $filtro['max_precio']);
        }
        
        $this->db->order_by('ofe.destacada', 'desc');
        
        if (! empty($filtro['ordenar'])) {
            if ($filtro['ordenar'] == 'masreciente') { // más reciente
                $this->db->order_by('ofe.fecha_insercion', 'desc');
            } else if ($filtro['ordenar'] == 'menosreciente') { // menos reciente
                $this->db->order_by('ofe.fecha_insercion', 'asc');
            } else if ($filtro['ordenar'] == 'ascendente') { // precio ascendente
                $this->db->order_by('ofe.precio_oferta', 'asc');
            } else if ($filtro['ordenar'] == 'descendente') { // precio descendente
                $this->db->order_by('ofe.precio_oferta', 'desc');
            }
        } else {
            $this->db->order_by('ofe.fecha_insercion', 'desc');
        }
        
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();
        
        // echo $this->db->last_query();
        
        $aRet = $query->result();
                
        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;
         
        return $aRet;
    }

    
    function getAnuncianteTiposMedio( )
    {

        $this->db->select('tipo_medio');

        $this->db->from('Anunciante_tipos_medio');   
       
        $query = $this->db->get();

        return $query->result();

    }
    function getMaxPrecioOfertaAnunciante($filtro){
        $this->db->select_max('precio_oferta');
        if (! empty($filtro['id_cliente'])) {
            $this->db->where_in('od.id_cliente', $filtro['id_cliente']);
        }
        $result = $this->db->get('ofertas_destinatarios od')->row();
        return $result->precio_oferta; 
    }
    function getMaxPrecioOfertaMedio($filtro){
        $this->db->select_max('coste_real');
        if (! empty($filtro['medio'])) {
            if ($filtro['medio'] != 0)
                $this->db->where('id_medio', $filtro['medio']);
        }
        $result = $this->db->get('ofertas')->row();

        return $result->coste_real; 
    }

    function getOfertasAnuncianteQuery($filtro)
    {
        $this->db->from('ofertas ofe');      
        if (! empty($filtro['id_cliente'])) {
            $this->db->join('ofertas_destinatarios od', 'od.id_oferta = ofe.id_oferta and od.id_cliente='. $filtro['id_cliente']);
        }  else{
            $this->db->join('ofertas_destinatarios od', 'od.id_oferta = ofe.id_oferta', 'left');
        }
      
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->join('tipos_oferta tio', 'tio.id_tipo_oferta = ofe.id_tipo_oferta', 'left');
      
        $this->db->where("ofe.publicada = 1");
        // $this->db->where('ofe.fecha_inicio_pub <= sysdate()'); 
        if (isset($filtro['activo']) && $filtro['activo'] != "" && $filtro['activo'] != "todos") {
            if ($filtro['activo'] == 0)
               $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) < now()');
            else if ($filtro['activo'] == 1)
               $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) >= now()');
        }     
        
        if (! empty($filtro['fecha_inicio_pub_from'])) {         
               $this->db->where("ofe.fecha_inicio_pub >= '".$filtro['fecha_inicio_pub_from']." 00:00:00'");
        }      
        
        if (! empty($filtro['fecha_inicio_pub_to'])) {         
               $this->db->where("ofe.fecha_inicio_pub <= '".$filtro['fecha_inicio_pub_to']." 23:59:59'");
        }        
        if (! empty($filtro['precio_range'])) {
            $precio_range  =explode(",",$filtro['precio_range']);           
            if(count($precio_range) == 2){
                if(intval($precio_range[0]) > 0){
                    $this->db->where('od.precio_oferta >= '.intval($precio_range[0]));
				}
                if($precio_range[1] < $filtro["maxPrecioOferta"]){
                    $this->db->where('od.precio_oferta <= '.intval($precio_range[1]));
				}
			}
        }             
        if (! empty($filtro['descuento_range'])) {
            $descuento_range  =explode(",",$filtro['descuento_range']);           
            if(count($descuento_range) == 2){
                if(intval($descuento_range[0]) > 0){
                    $this->db->where('od.descuento >= '.intval($descuento_range[0]));
			    }
                if(intval($descuento_range[1]) < 100){
                    $this->db->where('od.descuento <= '.intval($descuento_range[1]));
			    }
			}
        }        
       
        if (! empty($filtro['destacadas'])) {
            $this->db->where_in('ofe.destacada', $filtro['destacadas']);
        }

        if (!empty($filtro['tipo_medio']) && is_array($filtro['tipo_medio']) && count($filtro['tipo_medio']) > 0) {       
            $where = "(";
            foreach($filtro['tipo_medio'] as $tipo_medio){
                if($where == "("){
                        $where .= "tipo_medio  ='".$tipo_medio."' or tipo_medio_parent_1  ='".$tipo_medio."' or tipo_medio_parent_2  ='".$tipo_medio."'  or tipo_medio_parent_3  ='".$tipo_medio."'";
			    }else{
                       $where .= " or tipo_medio  ='".$tipo_medio."' or tipo_medio_parent_1  ='".$tipo_medio."' or tipo_medio_parent_2  ='".$tipo_medio."'  or tipo_medio_parent_3  ='".$tipo_medio."'";
			    }               
			} 
            $where .= ")";
            $this->db->where($where);
        }

        if (! empty($filtro['id_cliente']) && $filtro['id_cliente'] > 0) {
            $this->db->where("ofe.id_medio not IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $filtro['id_cliente'] . " AND per.estado = 2)");
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR ofe.fecha_fin_pub >= CURRENT_DATE())');
        } else if (! empty($filtro['cliente'])) {
       
            $this->db->where("(ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per LEFT JOIN clientes cli ON per.id_cliente = cli.id_cliente WHERE per.estado <> 2 AND (cli.nombre LIKE '%" . $filtro['cliente'] . "%' OR  cli.nombre_contacto LIKE '%" . $filtro['cliente'] . "%' OR  cli.apellidos_contacto LIKE '%" . $filtro['cliente'] . "%')))");
            $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
        }

        if (! empty($filtro['ofertas'])) {
            $this->db->where_in('ofe.id_oferta', $filtro['ofertas']);
        }

        if (! empty($filtro['min_precio'])) {
            $this->db->where('ofe.precio_oferta >=', $filtro['min_precio']);
        }

        if (! empty($filtro['max_precio'])) {
            $this->db->where('ofe.precio_oferta <=', $filtro['max_precio']);
        }

        $this->db->order_by('ofe.destacada', 'desc');

        if (! empty($filtro['ordenar'])) {
            if ($filtro['ordenar'] == 'masreciente') { // más reciente
                $this->db->order_by('ofe.fecha_insercion', 'desc');
            } else if ($filtro['ordenar'] == 'menosreciente') { // menos reciente
                $this->db->order_by('ofe.fecha_insercion', 'asc');
            } else if ($filtro['ordenar'] == 'ascendente') { // precio ascendente
                $this->db->order_by('ofe.precio_oferta', 'asc');
            } else if ($filtro['ordenar'] == 'descendente') { // precio descendente
                $this->db->order_by('ofe.precio_oferta', 'desc');
            }
        } else {
            $this->db->order_by('ofe.fecha_insercion', 'desc');
        }
    }
	
	function getOfertasAnuncianteQuery1($filtro)
    {
        $this->db->from('ofertas ofe');      

        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->join('tipos_oferta tio', 'tio.id_tipo_oferta = ofe.id_tipo_oferta', 'left');
      
        $this->db->where("ofe.publicada = 1");
        // $this->db->where('ofe.fecha_inicio_pub <= sysdate()'); 
        if (isset($filtro['activo']) && $filtro['activo'] != "" && $filtro['activo'] != "todos") {
            if ($filtro['activo'] == 0)
               $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) < now()');
            else if ($filtro['activo'] == 1)
               $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) >= now()');
        }     
           
        
        if (! empty($filtro['fecha_inicio_pub_to'])) {         
               $this->db->where("ofe.fecha_inicio_pub <= '".$filtro['fecha_inicio_pub_to']." 23:59:59'");
        }        
                    
        if (! empty($filtro['descuento_range'])) {
            $descuento_range  =explode(",",$filtro['descuento_range']);           
            if(count($descuento_range) == 2){
                if(intval($descuento_range[0]) > 0){
                    $this->db->where('od.descuento >= '.intval($descuento_range[0]));
			    }
                if(intval($descuento_range[1]) < 100){
                    $this->db->where('od.descuento <= '.intval($descuento_range[1]));
			    }
			}
        }        
       
        if (! empty($filtro['destacadas'])) {
            $this->db->where_in('ofe.destacada', $filtro['destacadas']);
        }

        if (!empty($filtro['tipo_medio']) && is_array($filtro['tipo_medio']) && count($filtro['tipo_medio']) > 0) {       
            $where = "(";
            foreach($filtro['tipo_medio'] as $tipo_medio){
                if($where == "("){
                        $where .= "tipo_medio  ='".$tipo_medio."' or tipo_medio_parent_1  ='".$tipo_medio."' or tipo_medio_parent_2  ='".$tipo_medio."'  or tipo_medio_parent_3  ='".$tipo_medio."'";
			    }else{
                       $where .= " or tipo_medio  ='".$tipo_medio."' or tipo_medio_parent_1  ='".$tipo_medio."' or tipo_medio_parent_2  ='".$tipo_medio."'  or tipo_medio_parent_3  ='".$tipo_medio."'";
			    }               
			} 
            $where .= ")";
            $this->db->where($where);
        }

        

        if (! empty($filtro['min_precio'])) {
            $this->db->where('ofe.precio_oferta >=', $filtro['min_precio']);
        }

        if (! empty($filtro['max_precio'])) {
            $this->db->where('ofe.precio_oferta <=', $filtro['max_precio']);
        }

        $this->db->order_by('ofe.destacada', 'desc');

        if (! empty($filtro['ordenar'])) {
            if ($filtro['ordenar'] == 'masreciente') { // más reciente
                $this->db->order_by('ofe.fecha_insercion', 'desc');
            } else if ($filtro['ordenar'] == 'menosreciente') { // menos reciente
                $this->db->order_by('ofe.fecha_insercion', 'asc');
            } else if ($filtro['ordenar'] == 'ascendente') { // precio ascendente
                $this->db->order_by('ofe.precio_oferta', 'asc');
            } else if ($filtro['ordenar'] == 'descendente') { // precio descendente
                $this->db->order_by('ofe.precio_oferta', 'desc');
            }
        } else {
            $this->db->order_by('ofe.fecha_insercion', 'desc');
        }
    }

     /**
     * Obtiene la llista de oferta publicadas resultantes del filtro escogido
     *
     * @param array $filtro
     *            Opciones de filtro: (medio, tipo_medio, destacadas, id_cliente, cliente, ofertas, min_precio, max_precio, ordenar, pagina, datosPorPagina)
     *            @result array Listado de ofertas obtenidas
     */
    function getOfertasAnunciante($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, ofe.titulo, ofe.porc_inversion, ofe.renta_esti, ofe.descripcion, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.condiciones, ofe.detalle, ofe.precio_anterior, ofe.es_generica, ofe.galeria_img, ofe.inversion', false);
        $this->db->select('ofe.imagen, ofe.id_medio, med.nombre medio, med.id_tipo_medio, tim.tipo, ofe.duracion_camp, ofe.destacada, ofe.id_tipo_oferta, tio.nombre_tipo_oferta, tio.imagen_tipo_oferta, tio.icono_tipo_oferta', false);
        
        $this->getOfertasAnuncianteQuery1($filtro);
       

        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);
        $query = $this->db->get();

        /*
         echo $this->db->last_query();
         print_r($filtro);
         */

        $aRet = $query->result();

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();
        $this->iNumFilas = $query->row()->totalRows;

        return $aRet;
    }
   
    function getOfertasAnuncianteNum($filtro)
    {
          if ($filtro !== false) {
            $this->db->select('COUNT(ofe.id_oferta) AS numOfertas');
            $this->getOfertasAnuncianteQuery1($filtro);

            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numOfertas;
        }

        return $this->iNumFilas;
    }
	
	    function getOfertasAnuncianteNum1($filtro)
    {
          if ($filtro !== false) {
            $this->db->select('COUNT(ofe.id_oferta) AS numOfertas');
			$this->getOfertasAnuncianteQuery1($filtro);
			
            $query = $this->db->get();
            $oResultado = $query->row();

            $this->iNumFilas = $oResultado->numOfertas;
        }

        return $this->iNumFilas;
    }



    /**
     * Obtiene la llista de oferta publicadas resultantes del filtro escogido
     *
     * @result array Listado de ofertas obtenidas
     */
    function getOfertasPromo($id_cliente)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, ofe.titulo, ofe.descripcion, ofe.fecha_fin_pub, ofe.precio_anterior, ofe.precio_oferta', false);
        $this->db->select('ofe.descuento, ofe.imagen, ofe.id_medio, med.nombre medio, med.id_tipo_medio, tim.tipo, ofe.duracion_camp, ofe.destacada', false);
        $this->db->select('CASE WHEN DATEDIFF(sysdate(), ofe.fecha_inicio_pub) <= 7 THEN 1 ELSE 0 END novedad, med.logo logo_medio', false);
        $this->db->select('IF((SELECT ins.id_inscripcion_oferta FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND ins.id_cliente = ' . $id_cliente . ' LIMIT 1) IS NULL, 0, 1) inscrito', false);
        $this->db->from('ofertas ofe');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');
        $this->db->where('(ofe.fecha_fin_pub IS NULL OR (ofe.fecha_fin_pub IS NOT NULL AND ofe.fecha_fin_pub >= CURRENT_DATE()))');
        $this->db->where('ofe.fecha_inicio_pub <= sysdate()');
        $this->db->where('ofe.publicada', 1);
        $this->db->where_in('ofe.destacada', 1);
        $this->db->order_by('ofe.fecha_insercion', 'desc');

        $query = $this->db->get();
        // echo $this->db->last_query();
        $aRet = $query->result();

        return $aRet;
    }

    /**
     * Obtiene la lista de ofertas, filtro asociado al listado de administrador
     *
     * @param array $filtro
     *            Opciones de filtrado: (publicada, destacada, medio, estado, caducidad, pagina, datosPorPagina)
     * @return array Lista de ofertas obtenida
     */
    function getOfertasAdmin($filtro)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ofe.id_oferta, ofe.titulo, med.nombre as medio, tim.tipo AS tipo_medio, ofe.fecha_insercion', false);
        $this->db->select('ofe.fecha_inicio_pub, ofe.fecha_fin_pub, ofe.precio_oferta, ofe.publicada, ofe.coste_real, ofe.destacada', false);
        $this->db->select('(SELECT COUNT(ins.id_inscripcion_oferta) FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND (estado = 0 OR estado = 1)) gestionar', false);
        $this->db->select("CASE WHEN ofe.fecha_fin_pub < CURRENT_DATE() THEN 'caducada' WHEN ofe.fecha_fin_pub = CURRENT_DATE() THEN 'hoy' WHEN DATEDIFF(fecha_fin_pub, CURRENT_DATE()) <= 10 THEN 'menos 10' ELSE 'vigente' END caducidad", false);
        $this->db->from('ofertas ofe');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');

        if (! empty($filtro['publicada'])) {
            if ($filtro['publicada'] == 1)
                $this->db->where('publicada', 1);
            else if ($filtro['publicada'] == 2)
                $this->db->where('publicada', 0);
        }

        if (! empty($filtro['destacada'])) {
            if ($filtro['destacada'] == 1)
                $this->db->where('destacada', 1);
            else if ($filtro['destacada'] == 2)
                $this->db->where('destacada', 0);
        }

        if (! empty($filtro['medio'])) {
            if ($filtro['medio'] != 0)
                $this->db->where('ofe.id_medio', $filtro['medio']);
        }

        if (! empty($filtro['tipo_medio'])) {
            $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
        }

        if (! empty($filtro['estado'])) {
            if ($filtro['estado'] == 1) {
                $this->db->having('gestionar', 0);
            } else if ($filtro['estado'] == 2) {
                $this->db->having('gestionar >', 0);
            }
        }

        if (! empty($filtro['caducidad'])) {
            if ($filtro['caducidad'] == 1) {
                $this->db->or_having('caducidad', 'vigente');
                $this->db->or_having('caducidad', 'menos 10');
                $this->db->or_having('caducidad', 'hoy');
            } else if ($filtro['caducidad'] == 2) {
                $this->db->having('caducidad', 'hoy');
            } else if ($filtro['caducidad'] == 3) {
                $this->db->having('caducidad', 'menos 10');
            } else if ($filtro['caducidad'] == 4) {
                $this->db->having('caducidad', 'caducada');
            }
        }
        if (! empty($filtro['order_by_campo'])) {
            $this->db->order_by($filtro['order_by_campo'], $filtro['order_by_sentido']);
        } else {
            $this->db->order_by('id_oferta', 'desc');
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
     * Obtiene la lista de ofertas, filtro asociado al listado de administrador
     *
     * @param array $filtro
     *            Opciones de filtrado: (publicada, destacada, medio, estado, caducidad, pagina, datosPorPagina)
     * @return array Lista de ofertas obtenida
     */
    function getOfertasMedio($filtro)
    {
        //print_r($filtro);
        $this->db->select('SQL_CALC_FOUND_ROWS t.nombre_tipo_oferta,t.imagen_tipo_oferta,t.icono_tipo_oferta,ofe.id_oferta, ofe.titulo, ofe.imagen, med.nombre medio, tim.tipo AS tipo_medio, ofe.fecha_insercion, IF(IFNULL(ofe.fecha_fin_pub,now()) <= now(),0,1) as activa, ofe.vendida', false);
        $this->db->select('ofe.id_medio_crea,ofe.fecha_inicio_pub, ofe.fecha_fin_pub, ofe.precio_anterior, ofe.precio_oferta, ofe.publicada, ofe.coste_real, ofe.destacada, ofe.newsletter_flag, ofe.newsletter_fecha_envio, ofe.newsletter_num_envios', false);
        $this->db->select('(SELECT COUNT(ins.id_inscripcion_oferta) FROM inscripciones_oferta ins WHERE ins.id_oferta = ofe.id_oferta AND (estado = 0 OR estado = 1)) gestionar', false);
        $this->db->select('(SELECT COUNT(dest.id_oferta_destinatario) FROM ofertas_destinatarios dest WHERE dest.id_oferta = ofe.id_oferta) envios', false);
        $this->db->select('(SELECT IFNULL(SUM(apertura),0) FROM report2022 r22  WHERE r22.id_oferta = ofe.id_oferta) apertura', false);
        $this->db->select("CASE WHEN ofe.fecha_fin_pub < CURRENT_DATE() THEN 'caducada' WHEN ofe.fecha_fin_pub = CURRENT_DATE() THEN 'hoy' WHEN DATEDIFF(fecha_fin_pub, CURRENT_DATE()) <= 10 THEN 'menos 10' ELSE 'vigente' END caducidad", false);
        $this->db->from('ofertas ofe');        
        $this->db->join('tipos_oferta t', 'ofe.id_tipo_oferta = t.id_tipo_oferta', 'left');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio', 'left');
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');                
                 

        if (!empty($filtro['precio_range'])) {
            $precio_range  =explode(",",$filtro['precio_range']);            
            if(count($precio_range) == 2){
                if($precio_range[0] > 0){
                 $this->db->where('ofe.coste_real >= '.intval($precio_range[0]));
				}
                 if($precio_range[1] < $filtro["maxPrecioOferta"]){
                     $this->db->where('ofe.coste_real <= '.intval($precio_range[1]));
				}
			}
        }

        if (! empty($filtro['fecha'])) {
            if ($filtro['fecha'] == 30)
                $this->db->where('ofe.fecha_inicio_pub >=  date_add(fecha_insercion, INTERVAL -30 DAY)');
            else if ($filtro['fecha'] == 90)
               $this->db->where('ofe.fecha_inicio_pub >= date_add(fecha_insercion, INTERVAL -90 DAY)');
        }       
        if (isset($filtro['activo']) && $filtro['activo'] != "" && $filtro['activo'] != "todos") {
            if ($filtro['activo'] == 0)
                $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) < now()');
            else if ($filtro['activo'] == 1)
               $this->db->where('IFNULL(ofe.fecha_fin_pub,now()) >= now()');
        }
        if (isset($filtro['enviado'])  && $filtro['enviado'] != "" && $filtro['enviado'] != "todos") {
            if ($filtro['enviado'] == 0)
                $this->db->where('ofe.newsletter_flag',0);
            else if ($filtro['enviado'] == 1)
              $this->db->where('ofe.newsletter_flag',1);
        }

        if (! empty($filtro['publicada'])) {
            if ($filtro['publicada'] == 1)
                $this->db->where('publicada', 1);
            else if ($filtro['publicada'] == 2)
                $this->db->where('publicada', 0);
        }

        if (! empty($filtro['destacada'])) {
            if ($filtro['destacada'] == 1)
                $this->db->where('destacada', 1);
            else if ($filtro['destacada'] == 2)
                $this->db->where('destacada', 0);
        }

        if (! empty($filtro['medio'])) {
            if ($filtro['medio'] != 0)
                $this->db->where('ofe.id_medio', $filtro['medio']);
        }

        if (! empty($filtro['tipo_medio'])) {
            $this->db->where("med.id_tipo_medio", $filtro['tipo_medio']);
        }
        if (! empty($filtro['id_oferta'])) {
            $this->db->where("ofe.id_oferta", $filtro['id_oferta']);
        }
        if (! empty($filtro['estado'])) {
            if ($filtro['estado'] == 1) {
                $this->db->having('gestionar', 0);
            } else if ($filtro['estado'] == 2) {
                $this->db->having('gestionar >', 0);
            }
        }

        if (! empty($filtro['caducidad'])) {
            if ($filtro['caducidad'] == 1) {
                $this->db->or_having('caducidad', 'vigente');
                $this->db->or_having('caducidad', 'menos 10');
                $this->db->or_having('caducidad', 'hoy');
            } else if ($filtro['caducidad'] == 2) {
                $this->db->having('caducidad', 'hoy');
            } else if ($filtro['caducidad'] == 3) {
                $this->db->having('caducidad', 'menos 10');
            } else if ($filtro['caducidad'] == 4) {
                $this->db->having('caducidad', 'caducada');
            }
        }

        $this->db->order_by('fecha_insercion', 'desc');
        $this->db->limit($filtro['datosPorPagina'], ($filtro['pagina'] - 1) * $filtro['datosPorPagina']);      
        $query = $this->db->get();
        //  echo  $this->db->last_query();
        $aRet = $query->result();

        // recuento de filas
        $this->db->select('FOUND_ROWS() AS totalRows');
        // $this->db->from('eventos eve');
        $query = $this->db->get();       
        $this->iNumFilas = $query->row()->totalRows;

        return $aRet;
    }

    /**
     * Inscribe a los anunciantes pasados en la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta en la inscribir a los anunciantes
     * @param array $anunciantes
     *            Array de ids de los anunciantes a inscribir en la oferta
     * @return array Lista de nombres de los usuarios inscritos (para poder enviarles un email de aviso en el controlador)
     */
function inscribirAnunciantes($id_oferta, $anunciantes)
{
    $usuarios = array();

    // Validar que hay anunciantes para procesar
    if (!empty($anunciantes)) {
        // Obtener nombres de los clientes relacionados con los anunciantes
        $this->db->select('cli.nombre');
        $this->db->from('usuarios usu');
        $this->db->join('clientes cli', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->where_in('cli.id_cliente', $anunciantes);
        $query = $this->db->get();
        $usuarios = $query->result();

        // Obtener el valor de renta_esti de la oferta
        $this->db->select('renta_esti');
        $this->db->from('ofertas');
        $this->db->where('id_oferta', $id_oferta);
        $query = $this->db->get();
        $renta_esti = $query->row() ? $query->row()->renta_esti : 0; // Si no hay valor, asignar 0

        foreach ($anunciantes as $anunciante) {
            // Verificar si ya existe una inscripción para el cliente y la oferta
            $this->db->select('ins.id_inscripcion_oferta');
            $this->db->from('inscripciones_oferta ins');
            $this->db->where('ins.id_cliente', $anunciante);
            $this->db->where('ins.id_oferta', $id_oferta);
            $query = $this->db->get();

            if ($query->num_rows() === 0) { // Si no hay inscripciones existentes
                // Crear datos de inscripción
                $datos_inscripcion = array(
                    'id_oferta' => $id_oferta,
                    'estado' => 1,
                    'fecha' => date('Y-m-d H:i:s'),
                    'tipo_inscripion' => 1,
                    'inscrip' => 11,
                    'id_cliente' => $anunciante,
                    'id_cli_ofe_est' => $anunciante . $id_oferta, // Concatenar id_cliente e id_oferta
                    'importe_inscrip' => 7000,
                    'renta_esti' => $renta_esti, // Agregar renta_esti obtenido de la oferta
                );
				$datos_inscripcionmov = array(
                    'id_oferta' => $id_oferta,
                    'fecha' => date('Y-m-d H:i:s'),
                    'tipo_mov' => inversion,
                    'importe' => 7000,
                    'id_cliente' => $anunciante,
                );

                // Insertar la nueva inscripción en la base de datos
                $this->db->insert('inscripciones_oferta', $datos_inscripcion);
				$this->db->insert('movimientos', $datos_inscripcionmov);
            }
        }
    }

    return $usuarios;
}




    /**
     * Inscribe a los anunciantes pasados en la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta en la inscribir a los anunciantes
     * @param array $anunciantes
     *            Array de ids de los anunciantes a inscribir en la oferta
     * @return array Lista de nombres de los usuarios inscritos (para poder enviarles un email de aviso en el controlador)
     */
    function inscribirAnunciantes2($id_oferta, $anunciantes)
    {
    $usuarios = array();

    // Validar que hay anunciantes para procesar
    if (!empty($anunciantes)) {
        // Obtener nombres de los clientes relacionados con los anunciantes
        $this->db->select('cli.nombre');
        $this->db->from('usuarios usu');
        $this->db->join('clientes cli', 'usu.id_usuario = cli.id_usuario', 'left');
        $this->db->where_in('cli.id_cliente', $anunciantes);
        $query = $this->db->get();

        $usuarios = $query->result();

        foreach ($anunciantes as $anunciante) {
            // Verificar si ya existe una inscripción para el cliente y la oferta
            $this->db->select('ins.id_inscripcion_oferta, ins.tipo_inscripion');
            $this->db->from('inscripciones_oferta ins');
            $this->db->where('ins.id_cliente', $anunciante);
            $this->db->where('ins.id_oferta', $id_oferta);
            $query = $this->db->get();

            if ($query->num_rows() > 0) { // Si no hay inscripciones existentes
                // Crear datos de inscripción
                $datos_inscripcion = array(
                    'id_oferta' => $id_oferta,
                    'estado' => 1,
                    'fecha' => date('Y-m-d H:i:s'),
                    'tipo_inscripion' => 2,
                    'inscrip' => 12,
                    'id_cliente' => $anunciante,
                    // Concatenar id_cliente e id_oferta
                    'id_cli_ofe_est' => $anunciante  . $id_oferta,
                );

                // Insertar la nueva inscripción en la base de datos
                $this->db->insert('inscripciones_oferta', $datos_inscripcion);
            }
        }
    }

    return $usuarios;
}

    /**
     * Inserta una oferta
     *
     * @param array $datos_oferta
     *            Datos de la oferta a insertar
     * @return integer Id de la oferta insertada
     */
    public function insertOferta($datos_oferta)
    {
        $this->db->insert('ofertas', $datos_oferta);

        return $this->db->insert_id();
    }

     /**
     * Inserta un registro en la tabla ofertas_destinatarios
     *
     * @param array $oferta_destinatario
     *            Datos de la oferta y el cliente a insertar
     * @return integer Id de la oferta_destinatario insertada
     */
    public function insertOfertaDestinatario($oferta_destinatario)
    {
        $this->db->insert('ofertas_destinatarios', $oferta_destinatario);

        return $this->db->insert_id();
    }
    /**
     * Inserta un registro en la tabla ofertas_contacto
     *
     * @param array $datos_contacto
     *            Datos del mensaje a insertar
     * @return integer Id del registro ofertas_contacto insertado
     */
    public function insertOfertasContacto($datos_contacto) {
        $datos_contacto['fecha'] = date('Y-m-d H:i:s');
        $this->db->insert('ofertas_contacto', $datos_contacto);

        return $this->db->insert_id();
    }

    /**
     * Inserta un registro en la tabla ofertas_destinatarios
     *
     * @param array $oferta_destinatario
     *            Datos de la oferta y el cliente a insertar
     * @return integer Id de la oferta_destinatario insertada
     */
    public function insertOfertaDestinatarioia($oferta_destinatario)
    {
        $this->db->insert('ofertas_destinatarios', $oferta_destinatario);

        return $this->db->insert_id();
    }

    /**
     * Actualiza la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a actualizar
     * @param array $datos_oferta
     *            Datos a actualizar de la oferta
     * @return boolean Siempre true
     */
    public function updateOferta($id_oferta, $datos_oferta)
    {
        $this->db->where('id_oferta', $id_oferta);
        $this->db->update('ofertas', $datos_oferta);

        return true;
    }

      /**
     * Devuelve las ofertas que tienen pendiente el envio del email a clientes avisando de su publicacion
     *
     * @return array Lista de idOfertas obtenidas
     */
    function getOfertasPendientesEmailPublicacion($agrupado_medio = false)
    {
        $this->db->select("ofe.inversion, ofe.imagen, ofe.porc_inversion, ofe.inversion_min, ofe.total_reviews, ofe.id_medio, ofe.id_oferta, ofe.titulo, ofe.descripcion, ofe.precio_oferta, ofe.precio_anterior, ofe.descuento, ofe.newsletter_num_envios, ofe.fecha_fin_pub, ofe.imagen, ofe.newsletter_parrafo, tip.imagen_tipo_oferta, ofe.newsletter_asunto, tip_med.tipo as tipo_medio, IFNULL(tip_med1.tipo,'') as tipo_medio_parent_1, IFNULL(tip_med2.tipo,'') as tipo_medio_parent_2, IFNULL(tip_med3.tipo,'') as tipo_medio_parent_3,med.nombre as medio");
        $this->db->from('ofertas ofe');
        $this->db->join('tipos_oferta tip', 'ofe.id_tipo_oferta = tip.id_tipo_oferta', 'left');
        $this->db->join('medios med', 'ofe.id_medio = med.id_medio');
        $this->db->join('tipos_medio tip_med', 'med.id_tipo_medio = tip_med.id_tipo', 'left');
        $this->db->join('tipos_medio tip_med1', 'tip_med.id_tipo_parent_1 = tip_med1.id_tipo', 'left');
        $this->db->join('tipos_medio tip_med2', 'tip_med.id_tipo_parent_2 = tip_med2.id_tipo', 'left');     
        $this->db->join('tipos_medio tip_med3', 'tip_med.id_tipo_parent_3 = tip_med3.id_tipo', 'left');
        $this->db->where('newsletter_fecha_envio', '1970-01-01 00:00:01');
        $this->db->where('newsletter_flag = 1');
        $this->db->where('publicada = 1');
        $this->db->where('ofe.fecha_inicio_pub <= sysdate()');

        if ($agrupado_medio){
            $this->db->where('ofe.newsletter_grupo_medio_flag = 1');
            $this->db->order_by('ofe.id_medio asc, ofe.id_oferta asc');
        } else {
            $this->db->where('ofe.newsletter_grupo_medio_flag = 0');
            $this->db->where('date_add(fecha_insercion, INTERVAL 15 MINUTE) < sysdate()');
            $this->db->order_by('ofe.id_oferta', 'asc');
            $this->db->limit(1); //devolvemos 1 en cada vez
        }

        $query = $this->db->get();

        
                $str = $this->db->last_query();
           echo "<pre>";
    print_r($str);
   
        /*
        $str = $this->db->last_query();
        print_r($str);
        */
        return $query->result();
    }

    /**
     * Devuelve los registros de ofertas_destinatarios filtrados por id_oferta
     *
     * @return array Lista de registros obtenidas
     */
    function getClientesOfertasDestinatarios($ofertas){
        $this->db->select('des.id_oferta, des.id_cliente');
        $this->db->from('ofertas_destinatarios des');
        $this->db->where_in('des.id_oferta', $ofertas);
        $query = $this->db->get();
        return $query->result();
    }

    function updateNumEnviosOferta ($id_oferta){
        $sql = 'update ofertas set newsletter_num_envios = (select count(*) from
                ofertas_destinatarios where id_oferta = '.$id_oferta.') where id_oferta = '.$id_oferta;
        $this->db->query($sql);
        return true;
    }

    function getOfertasClientes($id_oferta,$id_cliente){
       $this->db->select('*');
       $this->db->from('ofertas_clientes');
       $this->db->where_in('id_oferta', $id_oferta);
       $this->db->where_in('id_cliente', $id_cliente);
       $query = $this->db->get();
       return $query->result();
    }
     function updateOfertasClientes ($id_oferta,$id_cliente,$valores){
        $sql = 'INSERT INTO ofertas_clientes (id_cliente,id_oferta';
        foreach($valores as $key => $value){
             $sql .= ','.$key;
        }
        $sql .= ') VALUES ('.$id_cliente.','.$id_oferta;
        foreach($valores as $key => $value){
             $sql .= ','.$value;
        }
        $sql .= ') ON DUPLICATE KEY UPDATE ';
        $first = "";
        foreach($valores as $key => $value){
            if($first == ""){
                 $sql .= $key .'='.$value;
                 $first = $key;
            }else{
                $sql .= ','. $key .'='.$value;
            }            
        }             
        $this->db->query($sql);          
        return true;
    }

    function duplicarOferta($id_oferta,$fecha_inicio_pub,$fecha_fin_pub,$fecha_inicio_camp,$fecha_fin_camp,$precio_anterior,$precio_oferta){

        $this->db->select('ofe.*');
        $this->db->from('ofertas ofe');
        $this->db->where('ofe.id_oferta', $id_oferta);
        $datos_oferta = $this->db->get()->row();
        $datos_oferta->id_oferta = $this->siguienteIdOferta();
        $datos_oferta->fecha_inicio_pub  = $fecha_inicio_pub;
        $datos_oferta->fecha_fin_pub  = $fecha_fin_pub;
        $datos_oferta->fecha_inicio_camp  = $fecha_inicio_camp;
        $datos_oferta->fecha_fin_camp  = $fecha_fin_camp;
        $datos_oferta->precio_anterior  = $precio_anterior;
        $datos_oferta->coste_real  = $precio_oferta;
        $datos_oferta->precio_oferta  = $precio_oferta * 1.1;
        $datos_oferta->publicada = 0;

        unset($datos_oferta->newsletter_fecha_envio);
        unset($datos_oferta->newsletter_num_envios);
        unset($datos_oferta->newsletter_flag);


        $datetime1 = new DateTime($fecha_inicio_camp);
        $datetime2 = new DateTime($fecha_fin_camp);
        $diferencia = $datetime1->diff($datetime2);

        if ($diferencia->y > 0) {
            $diferenciaStr .= $diferencia->y . " año" . ($diferencia->y > 1 ? "s" : "");
        }
        
        if ($diferencia->m > 0) {
            if (!empty($diferenciaStr)) {
                $diferenciaStr .= ", ";
            }
            $diferenciaStr .= $diferencia->m . " mes" . ($diferencia->m > 1 ? "es" : "");
        }
        
        if ($diferencia->d > 0) {
            if (!empty($diferenciaStr)) {
                $diferenciaStr .= " y ";
            }
            $diferenciaStr .= $diferencia->d . " día" . ($diferencia->d > 1 ? "s" : "");
        }
        $datos_oferta->duracion_camp  = $diferenciaStr;

        $datos_oferta->fecha_insercion  = date('Y-m-d H:i:s');

        $this->db->insert('ofertas', $datos_oferta);

        return $this->db->insert_id();
    }
    function siguienteIdOferta(){
        $this->db->select('COALESCE(MAX(id_oferta)+1, 1) id_oferta', false);
        $this->db->from('ofertas ofe');

        $row= $this->db->get()->row();
  
        return $row->id_oferta;
    }
	
	function getofertais($id_oferta, $id_cliente, $id_agencia = '')
    {
        $this->db->select('ofe.renta_esti, ofe.imagen, ofe.Ruta, ofe.inversion, ofe.porc_inversion, ofe.inversion_min, ofe.total_reviews, ofe.newsletter_num_envios,ofe.newsletter_parrafo, ofe.precio_oferta, ofe.precio_anterior, ofe.id_medio, ofe.fecha_fin_pub, ofe.detalle_fin_camp, ofe.condiciones, ofe.detalle, ofe.link, ofe.fecha_inicio_pub, ofe.inversion', false);
        $this->db->select('med.descripcion descripcion_medio, ofe.descuento, ofe.id_oferta, ofe.titulo, med.nombre as medio, tim.tipo,med.id_tipo_medio, ins.id_cliente as id_cliente', false);
        $this->db->select('ofe.renta_esti, ofe.duracion_camp, ofe.inversion, ofe.coste_real, med.logo logo_medio, med.web web_medio, ofe.descripcion, ofe.destacada, ofe.galeria_img', false);

       
        $this->db->from("ofertas ofe"); 
        $this->db->join("medios med", "ofe.id_medio = med.id_medio");
		$this->db->join("inscripciones_oferta ins", "ofe.id_oferta = ins.id_oferta");
        $this->db->join('tipos_medio tim', 'med.id_tipo_medio = tim.id_tipo', 'left');        
        $this->db->where('ofe.id_oferta', $id_oferta); 

        /*
         * if(empty($id_agencia)){
         * $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $id_cliente . " AND per.estado = 1) OR ofe.es_generica=1");
         *
         * }
         */

        $query = $this->db->get();


        $ofertais = $query->row();

        return $ofertais;
	}
	function getOfertainver($id_oferta )
    {
        $this->db->select('gam.id_oferta, gam.inversion, gam.Total_Review ', false);
        $this->db->select('ofe.renta_esti, ofe.duracion_camp, ofe.inversion, ofe.coste_real, ofe.descripcion, ofe.destacada, gam.inversion, Steam_App_Id, gam.Total_Review, ofe.galeria_img', false);
		
		
       
        $this->db->from("ofertas ofe");
		$this->db->join("games_inver gam", "ofe.id_oferta = gam.id_oferta");
        $this->db->where('gam.id_oferta', $id_oferta); 

        /*
         * if(empty($id_agencia)){
         * $this->db->where("ofe.id_medio IN (SELECT per.id_medio FROM permisos_cliente_medio per WHERE per.id_cliente = " . $id_cliente . " AND per.estado = 1) OR ofe.es_generica=1");
         *
         * }
         */

        $query = $this->db->get();


        $ofertagam = $query->row();

        return $ofertagam;
    }	
	
	public function getMovimientosPorCliente($filtro)
{
    $this->db->select('
        mov.id_movimiento, mov.id_cliente, mov.id_oferta, mov.tipo_mov, mov.importe, mov.fecha,
        ofe.renta_esti, ofe.titulo, ofe.duracion_camp, ofe.inversion, ofe.coste_real, ofe.descripcion, 
        ofe.destacada, ofe.galeria_img
    ', false);
    
    $this->db->from('movimientos mov');
    $this->db->join('ofertas ofe', 'ofe.id_oferta = mov.id_oferta', 'left');

    /* Filtrar por cliente si está definido */
    if (!empty($filtro['id_cliente'])) {
        $this->db->where('mov.id_cliente', $filtro['id_cliente']);
    }

    $query = $this->db->get();
    return $query->result();
}

public function getSaldoPorCliente($filtro)
{

    $this->db->select('mov.id_cliente,
        SUM(CASE WHEN tipo_mov = "ingreso" THEN importe ELSE 0 END) -
        SUM(CASE WHEN tipo_mov = "Retirada" THEN importe ELSE 0 END) AS saldo_total
    ', false);
    
    $this->db->from('movimientos as mov');
	    /* Filtrar por cliente si está definido */
    if (!empty($filtro['id_cliente'])) {
        $this->db->where('mov.id_cliente', $filtro['id_cliente']);
    }
	
    $query = $this->db->get();
    $result = $query->row(); // Obtener un solo registro como objeto

    return isset($result->saldo_total) ? (float) $result->saldo_total : 0.00;
}

public function getretiradaPorCliente($filtro)
{
    $this->db->select('id_cliente, 
        SUM(CASE WHEN tipo_mov = "Retirada" THEN importe ELSE 0 END) AS saldo_total', false);
    
    $this->db->from('movimientos');

    // Filtrar por cliente si se proporciona un filtro


    $query = $this->db->get();
    $result = $query->row(); // Obtener un solo registro como objeto

        // Convertir el total en formato moneda euro
    $saldo_total = $result ? $result->saldo_total : 0;
    return number_format($saldo_total, 2, ',', '.') . ' €';
}


}