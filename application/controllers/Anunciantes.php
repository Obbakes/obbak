<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');


    require_once('BaseController.php');

class Anunciantes extends BaseController
{

    private $emailOficial = '';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra la vista de notificaciones
     */
function notificaciones($modo = '0')
{
    $this->load->model('clientes_model');
    $this->load->model('ofertas_model');
    $this->load->model('medios_model');

    $notificaciones = [];
    $id_cliente = $this->session->userdata('id_cliente'); // Obtener ID del cliente de sesión

    if (!$id_cliente) {
        show_error('Cliente no identificado', 403);
        return;
    }

    $filtro['id_cliente'] = $id_cliente; // Filtrar solo por id_cliente

    if ($modo === '0') {
        $cliente = $this->clientes_model->getClienteAdmin($id_cliente);
        if ($cliente) {
            $notificaciones = [
                'newsletter' => (int) $cliente->newsletter,
                'notifica_oferta_nueva' => (int) $cliente->notifica_oferta_nueva,
                'cliente' => $id_cliente
            ];
        }
    } elseif ($modo === '1') {
        // Asegurar que los valores sean definidos y sean enteros
        $notificaciones = [
            'newsletter' => (int) $this->input->post('newsletter'),
            'notifica_oferta_nueva' => (int) $this->input->post('notifica_oferta_nueva'),
            'cliente' => $id_cliente
        ];

        // Validar que $notificaciones sea un array válido antes de actualizar
        if (!empty($notificaciones)) {
            $this->clientes_model->updateClienteNotificaciones($id_cliente, $notificaciones);
        }
    }

    // Obtener datos relacionados con el cliente
    $numOfertas = $this->ofertas_model->getNumOfertas($filtro);
    $numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);
    $inscripciones_total = $this->ofertas_model->getInscripcionesimpor($filtro);
    $inscripciones = $this->ofertas_model->getInscripcionescli($filtro);
    $renta_med = $this->ofertas_model->getInscripcionesrenta($filtro);
	$cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

    $notificaciones['recibir_todas'] = !($notificaciones['newsletter'] == 0 || $notificaciones['notifica_oferta_nueva'] == 0);

    // Cargar vista con datos
    $data = [
        'page' => "anunciantes/notificaciones",
        'title' => "Notificaciones",
        'h1' => 'Notificaciones',
        'opc' => 'perfil',
        'notificaciones' => $notificaciones,
        'numOfertas' => $numOfertas,
        'inscripciones_total' => $inscripciones_total,
        'tipos_medio' => $this->ofertas_model->getAnuncianteTiposMedio(),
        'numInscripciones' => $numInscripciones,
        'renta_med' => $renta_med,
        'inscripciones' => $this->ofertas_model->getInscripciones($filtro),
		'cliente' =>$cliente
    ];

    $this->load->vars($data);
    $this->load->view('default_anunciantes');
}


    /**
     * Desinscribe al anunciante logueado de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta de la que desinscribir al anunciante
     */
    function desinscribirseOferta($id_oferta)
    {
        $this->load->model('ofertas_model');

        $this->ofertas_model->desinscribirAnunciante($id_oferta, $this->session->userdata('id_cliente'));

        redirect('anunciantes/inscripciones/2');
    }

    /**
     * Muestra los detalles de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a mostrar en detalle
     */
    function getDetallesOferta($id_oferta = 0, $pagina = 1)
    {
        $this->load->model('ofertas_model');

        $data['oferta'] = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));
        $data['pagina'] = $pagina;
        $this->load->model('clientes_model');
        $data['cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($data['oferta'] != false) {
 $this->load->vars($data);
            $this->load->view('anunciantes/detallesOferta');
        } else {
            redirect('anunciantes/ofertas');
        }
    }

    /**
     * Muestra los detalles de la oferta especificada de la lista de ofertas en promocion
     *
     * @param integer $id_oferta
     *            Id de la oferta a mostrar en detalle
     */
    function getDetallesOfertaPromo($id_oferta = 0, $pagina = 1, $id_cliente = 0)
    {
        $this->load->model('ofertas_model');

        $data['oferta'] = $this->ofertas_model->getOfertaPromo($id_oferta, $id_cliente);
        $data['pagina'] = $pagina;

        if ($data['oferta'] != false) {
 $this->load->vars($data);
            $this->load->view('anunciantes/detallesOferta');
        } else {
            redirect('anunciantes/ofertas');
        }
    }

    /**
     * Muestra la Galería de Imágenes de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a mostrar la galería
     */
    function getGaleriaImgOferta($id_oferta = 0, $pagina = 1)
    {
        $this->load->model('ofertas_model');

        $data['oferta'] = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));
        $data['pagina'] = $pagina;
        $this->load->model('clientes_model');
        $data['cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($data['oferta'] != false) {
 $this->load->vars($data);
            $this->load->view('anunciantes/galeriaimgOferta');
        } else {
            redirect('anunciantes/ofertas');
        }
    }

    /**
     * Redirige a ofertas si el usuario logueado es anunciante
     */
    function index()
    {
        if ($this->session->userdata('tipo_usuario') == 'cliente') {
            redirect('anunciantes/ofertaspanel');
        } else {
            redirect('inicio/index');
        }
    }

    /**
     * Inscribe al anunciante logueado en la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta en la que inscribir al anunciante
     */
    function inscribirseOferta($id_oferta)
    {
        $this->load->model('ofertas_model');

        $anunciantes = array(
            $this->session->userdata('id_cliente')
        );

        // echo 'Ya estás inscrito a esta oferta, en breve nos pondremos en contacto contigo. Gracias por confiar en Bimads';

        $anunciantes = $this->ofertas_model->inscribirAnunciantes($id_oferta, $anunciantes);
        $oferta = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));

        if (! empty($anunciantes)) {
            $this->load->library('email');

            $data = array(
                'titulo' => $oferta->titulo,
                'imagen' => $oferta->imagen,
                'precio_oferta' => $oferta->precio_oferta,
                'fecha_fin_pub' => $oferta->fecha_fin_pub,
                'descripcion_medio' => $oferta->descripcion_medio
            );

            foreach ($anunciantes as $anunciante) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to(EMAIL_OFICIAL);
                $this->email->cc('');

                $data['anunciante'] = $anunciante->nombre;

                $this->email->subject('Un anunciante se ha inscrito a una oferta en Bimads');
                $this->email->message($this->load->view('anunciantes/anunciante_interesado.php', $data, TRUE));

                $this->email->send();
            }
        }

        redirect('anunciantes/oferta/' . $id_oferta . '/1');
    }


    /**
     * Inscribe al anunciante logueado en la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta en la que inscribir al anunciante
     */
    function inscribirseOferta2($id_oferta)
    {
        $this->load->model('ofertas_model');

        $anunciantes = array(
            $this->session->userdata('id_cliente')
        );

        // echo 'Ya estás inscrito a esta oferta, en breve nos pondremos en contacto contigo. Gracias por confiar en Bimads';

        $anunciantes = $this->ofertas_model->inscribirAnunciantes2($id_oferta, $anunciantes);
        $oferta = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));

        if (! empty($anunciantes)) {
            $this->load->library('email');

            $data = array(
                'titulo' => $oferta->titulo,
                'imagen' => $oferta->imagen,
                'precio_oferta' => $oferta->precio_oferta,
                'fecha_fin_pub' => $oferta->fecha_fin_pub,
                'descripcion_medio' => $oferta->descripcion_medio
            );

            foreach ($anunciantes as $anunciante) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to(EMAIL_OFICIAL);
                $this->email->cc('');

                $data['anunciante'] = $anunciante->nombre;

                $this->email->subject('Un anunciante se ha inscrito a una oferta en Bimads');
                $this->email->message($this->load->view('anunciantes/email_inscripcion_oferta', $data, TRUE));

                $this->email->send();
            }
        }

        redirect('anunciantes/oferta/' . $id_oferta . '/1');
    }


    /**
     * Inscribe al anunciante logueado en la oferta de Promoción especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta en la que inscribir al anunciante
     */
    function inscribirseOfertaPromo($id_oferta, $id_cliente)
    {
        $this->load->model('ofertas_model');

        $anunciantes = array(
            $id_cliente
        );

        echo 'Ya estás inscrito a esta oferta, en breve nos pondremos en contacto contigo. Gracias por confiar en Bimads';

        $anunciantes = $this->ofertas_model->inscribirAnunciantes2($id_oferta, $anunciantes);
        $oferta = $this->ofertas_model->getOfertaPromo($id_oferta, $id_cliente);

        if (! empty($anunciantes)) {
            $this->load->library('email');

            $data = array(
                'titulo' => $oferta->titulo,
                'imagen' => $oferta->imagen,
                'precio_oferta' => $oferta->precio_oferta,
                'fecha_fin_pub' => $oferta->fecha_fin_pub,
                'descripcion_medio' => $oferta->descripcion_medio
            );

            foreach ($anunciantes as $anunciante) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to(EMAIL_OFICIAL);
                $this->email->cc('info@parabellumgames.es');

                $data['anunciante'] = $anunciante->nombre;

                $this->email->subject('Un anunciante se ha inscrito a una oferta en Bimads');
                $this->email->message($this->load->view('anunciantes/email_inscripcion_oferta', $data, TRUE));

                $this->email->send();
            }
        }

        // redirect('anunciantes/oferta/' . $id_oferta);
    }

    /**
     * Muestra la lista de inscripciones a ofertas del anunciante logueado
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de las inscripciones
     */
    function inscripciones($modo = '0', $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->library('pagination');
		
		$cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));


        if ($modo === '0') {
            $filtro = array();

            $this->session->set_userdata('filtro_inscripciones', $filtro);
        } else if ($modo === '1') {
            $filtro['estado'] = $this->input->post('estado');

            $this->session->set_userdata('id_cliente', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('id_cliente') === false) ? array() : $this->session->userdata('id_cliente');
        }

        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 10;
        $filtro['id_cliente'] = $this->session->userdata('id_cliente');

        $inscripciones = $this->ofertas_model->getInscripciones($filtro);
        $numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);
		$Clientes = $this->ofertas_model->getInscripcionesAnunciantes($filtro);
		$numOfertas = $this->ofertas_model->getOfertasAnuncianteNum1($filtro);

        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "anunciantes/inscripciones/2/";
        $config["total_rows"] = $numInscripciones;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 4;

        $this->pagination->initialize($config);

        $data["paginacion"] = $this->pagination->create_links();

        $data['filtro'] = $filtro;
        $data['h1'] = 'Mis Juegos';
        $data['opc'] = 'inversiones';
        $data['inscripciones'] = $inscripciones;
		$data['numInscripciones'] = $numInscripciones;
		$data['numOfertas'] = $numOfertas;
		$data['Clientes'] = $Clientes;
        $data['page'] = 'anunciantes/inscripciones';
		$data['page'] = 'anunciantes/oferta';

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $data['cliente'] = $cliente;
 $this->load->vars($data);
        $this->load->view('anunciantes/inscripciones');
    }



    /**
     * Obtenemos la lista de ofertas para cuando el filtro se hace por ajax
     *
     * @param integer $iModo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     */
    function lista_ofertas($iModo = 0, $iPagina = 1)
    {
        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $this->load->model('ofertas_model');
        $this->load->model('medios_model');
        $this->load->library('pagination');

        $filtro = array();
        $filtro['id_cliente'] = $this->session->userdata('id_cliente');

        if ($iModo === '0') {
            $this->session->set_userdata('filtro_ofertas', $filtro);
        } else if ($iModo === '1') {
            $filtro['max_precio'] = $this->input->post('max_precio');
            $filtro['min_precio'] = $this->input->post('min_precio');
            $filtro['ordenar'] = $this->input->post('ordenar');

            $tipos_medios = array();

            if ($this->input->post('tipos') != '') {
                $tipos_medios = explode(' ', $this->input->post('tipos'));
                $filtro['tipo_medio'] = $tipos_medios;
            }

            $medios = array();

            if ($this->input->post('medios') != '') {
                $medios = explode(' ', $this->input->post('medios'));
                $filtro['medio'] = $medios;
            }

            $destacadas = array();

            if ($this->input->post('destacadas') != '') {
                $destacadas = explode(' ', $this->input->post('destacadas'));
                $filtro['destacadas'] = $destacadas;
            }

            $this->session->set_userdata('filtro_ofertas', $filtro);
        } else if ($iModo === '2') {
            $filtro = ($this->session->userdata('filtro_ofertas') === false) ? array() : $this->session->userdata('filtro_ofertas');
        }

        $filtro['pagina'] = $iPagina;
        $filtro['datosPorPagina'] = 1000;

        $ofertas = $this->ofertas_model->getOfertas($filtro);
        $numOfertas = $this->ofertas_model->getNumOfertas($filtro);

        // Creamos el módulo de paginación para las ofertas
        $aConfig = array();
        $aConfig['use_page_numbers'] = TRUE;
        $aConfig["base_function"] = 'obtener_ofertas';
        $aConfig["parameters"] = $iModo . ',';
        $aConfig["total_rows"] = $numOfertas;
        $aConfig["per_page"] = $filtro['datosPorPagina'];
        $aConfig["cur_page"] = $filtro['pagina'];
        $aConfig['num_links'] = 3;

        $this->pagination->initialize($aConfig);

        $data['paginacion'] = $this->pagination->create_links_ajax();

        $this->load->model('clientes_model');

        $data['h1'] = 'Juegos';
        $data['opc'] = 'Juegos';
        $data['tipos_medio'] = $this->medios_model->getTiposMedios();
        $data['ofertas'] = $ofertas;
        $data['cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $data['numOfertas'] = $numOfertas;
        $data['filtro'] = $filtro;
 	$this->load->vars($data);
        $this->load->view('anunciantes/lista_ofertas');
    }

  
  

    function getTiposMedios($ofertas)
    {
        $tipos = array();
        foreach ($ofertas as $oferta) {
            if (! in_array($oferta->id_tipo_medio, $tipos)) {
                $tipo = (object) array(
                    'id_tipo' => $oferta->id_tipo_medio,
                    'tipo' => $oferta->tipo
                );
                $tipos[$oferta->id_tipo_medio] = $tipo;
            }
        }
        return $tipos;
    }

    function getMedios($ofertas)
    {
        $medios = array();
        foreach ($ofertas as $oferta) {
            if (! in_array($oferta->id_medio, $medios)) {
                $medio = (object) array(
                    'id_medio' => $oferta->id_medio,
                    'id_tipo_medio' => $oferta->id_tipo_medio,
                    'nombre' => $oferta->medio
                );
                $medios[$oferta->id_medio] = $medio;
            }
        }
        return $medios;
    }


      /**
     * Muestra los detalles de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a mostrar en detalle
     */
    function oferta($id_oferta, $nueva_inscripcion = 0)
    {
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('medios_model');
		$cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
		$idCliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio/login/0/' . $id_oferta);

        $oferta = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));
		$inscripciones = $this->ofertas_model->getInscripciones('id_cliente');
        $inscripciones_total = $this->ofertas_model->getInscripciones('id_cliente');
		$games = $this->ofertas_model->getOfertainver($id_oferta);
		$numOfertas = $this->ofertas_model->getNumOfertas($this->session->userdata('id_cliente'));
		$numInscripciones = $this->ofertas_model->getNumInscripcionesAnunciantes2($this->session->userdata('id_cliente'));
      
        if ($oferta != false) {

            $data['h1'] = 'Oferta';
            $data['opc'] = 'ofertas';
            $data['oferta'] = $oferta;
			$data['games'] = $games;
			$data['numOfertas'] = $numOfertas;
			$data['numInscripciones'] = $numInscripciones;
			$data['inscripciones'] = $inscripciones;
			$data['inscripciones_total'] = $inscripciones_total;
            $data['perfiles'] = $this->medios_model->getPerfiles($oferta->id_tipo_medio);

             $filtro = array();       
            $filtro['activo'] = 1;
        
            $filtro['pagina'] = 1;
            $filtro['datosPorPagina'] = 4;
            $filtro['id_cliente'] =$this->session->userdata('id_cliente');
            $filtro['ordenar'] = "masreciente";
            $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            $filtro['id_cliente'] = $cliente->id_cliente;
            $data['ofertas'] =  $oferta;
            $data['page'] = 'anunciantes/oferta';
            $data['cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            $data['ofertas_clientes'] = $this->ofertas_model->getOfertasClientes($id_oferta,$this->session->userdata('id_cliente'));
            if ($nueva_inscripcion) {
                $data['aviso_ok'] = 'Te has inscrito en la oferta correctamente.';
            }
            $data['nueva_inscripcion'] = $nueva_inscripcion;
            $this->load->vars($data);
            $this->load->view('default_anunciantes');
        } else {
            redirect('anunciantes/ofertas');
        }
    }
	    function obtenerIdOferta() {
			
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('medios_model');
		$cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
		$idCliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio/login/0/' . $id_oferta);

        $idOferta = $this->ofertas_model->getOferta($id_oferta, $this->session->userdata('id_cliente'));
		
    
        // Aquí podrías obtener el ID de oferta de cualquier parte, por ejemplo:
        return $idOferta ; // Simulamos que el ID de oferta es 123
    }

     function updateOfertasClientes($id_oferta)
    {
     $this->load->model('ofertas_model');
     $values = array("precio" => "null","audiencia" => "null","fecha" => "null","soporte" => "null");
     if(isset($_POST["precio"]) && $_POST["precio"] !=""){
        $values["precio"] = $_POST["precio"];
     }
     if(isset($_POST["audiencia"]) && $_POST["audiencia"] !=""){
        $values["audiencia"] = $_POST["audiencia"];
     }
     if(isset($_POST["fecha"]) && $_POST["fecha"] !=""){
        $values["fecha"] = $_POST["fecha"];
     }
     if(isset($_POST["soporte"]) && $_POST["soporte"] !=""){
        $values["soporte"] = $_POST["soporte"];
     }
     $this->ofertas_model->updateOfertasClientes($id_oferta,$this->session->userdata('id_cliente'),$values);
        header('Content-Type: application/json');
        echo json_encode( array("error" => false) );
    }

    /**
     * Muestra la lista de ofertas resultante de la busqueda realizada por el anunciante
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de la busqueda
     */
    function ofertas($modo = '0', $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->model('medios_model');
        $this->load->model('clientes_model');
        $this->load->library('pagination');

        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($modo === '0') {
            $filtro = array();
            $filtro['pagina'] = 1;
            $filtro['datosPorPagina'] = 1000; 
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));	
          
        } else if ($modo === '1') {
            $filtro = array();
  			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
 
        } else if ($modo === '2') {
            
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        }
        $filtro['id_cliente'] = $cliente->id_cliente;  
        $ofertas = $this->ofertas_model->getOfertas($filtro);
        $numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);
		$inscripciones_total = $this->ofertas_model->getInscripciones($filtro);
		$inscripciones = $this->ofertas_model->getInscripcionescli($filtro);


        // Creamos el módulo de paginación para las ofertas
        $aConfig = array();
        $aConfig['use_page_numbers'] = TRUE;
        $aConfig["base_function"] = 'obtener_ofertas';
        $aConfig["parameters"] = 0 . ',';
        $aConfig["total_rows"] = $numOfertas;
        $aConfig["per_page"] = $filtro['datosPorPagina'];
        $aConfig["cur_page"] = $filtro['pagina'];
        $aConfig['num_links'] = 3;
        $this->pagination->initialize($aConfig);

        $data['paginacion'] = $this->pagination->create_links_ajax();

        $data['h1'] = 'Juegos';
        $data['opc'] = 'Juegos';
        $data['ofertas'] = $ofertas;
        $data['cliente'] = $cliente;
        $data['numOfertas'] = $numOfertas;
		$data['inscripciones_total'] = $inscripciones_total;
        $data['tipos_medio'] = $this->ofertas_model->getAnuncianteTiposMedio();
		$data['numInscripciones'] = $numInscripciones;
		$data['inscripciones'] = $this->ofertas_model->getInscripciones($filtro);

        
 
        $data['page'] = 'anunciantes/index';
        $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
	
	/**
     * Muestra la lista de ofertas resultante de la busqueda realizada por el anunciante panel
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de la busqueda
     */
    function ofertaspanel($modo = '0', $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->model('medios_model');
        $this->load->model('clientes_model');
        $this->load->library('pagination');

        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($modo === '0') {
            $filtro = array();
            $filtro['pagina'] = 1;
            $filtro['datosPorPagina'] = 1000; 
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));	
          
        } else if ($modo === '1') {
            $filtro = array();
  			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
 
        } else if ($modo === '2') {
            
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        }
        $filtro['id_cliente'] = $cliente->id_cliente;  
        $ofertas = $this->ofertas_model->getOfertas($filtro);
        $numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);
		$inscripciones_total = $this->ofertas_model->getInscripcionesimpor($filtro);
		$inscripciones = $this->ofertas_model->getInscripcionescli($filtro);
		$renta_med = $this->ofertas_model->getInscripcionesrenta($filtro);
		$movimientos = $this->ofertas_model->getMovimientosPorCliente($filtro);
		$saldo = $this->ofertas_model->getSaldoPorCliente($filtro);
		$retiradas = $this->ofertas_model->getretiradaPorCliente($filtro);


        // Creamos el módulo de paginación para las ofertas
        $aConfig = array();
        $aConfig['use_page_numbers'] = TRUE;
        $aConfig["base_function"] = 'obtener_ofertas';
        $aConfig["parameters"] = 0 . ',';
        $aConfig["total_rows"] = $numOfertas;
        $aConfig["per_page"] = $filtro['datosPorPagina'];
        $aConfig["cur_page"] = $filtro['pagina'];
        $aConfig['num_links'] = 3;
        $this->pagination->initialize($aConfig);

        $data['paginacion'] = $this->pagination->create_links_ajax();

        $data['h1'] = 'Juegos_panel';
        $data['opc'] = 'Juegos_panel';
        $data['ofertas'] = $ofertas;
        $data['cliente'] = $cliente;
        $data['numOfertas'] = $numOfertas;
		$data['inscripciones_total'] = $inscripciones_total;
        $data['tipos_medio'] = $this->ofertas_model->getAnuncianteTiposMedio();
		$data['numInscripciones'] = $numInscripciones;
		$data['renta_med'] = $renta_med;
		$data['movimientos'] = $movimientos;
		$data['saldo'] = $saldo;
		$data['inscripciones'] = $this->ofertas_model->getInscripciones($filtro);
		$data['retiradas'] = $retiradas;

        
 
        $data['page'] = 'anunciantes/index_panel';
        $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }


/**
     * Muestra la lista de ofertas resultante de la busqueda realizada por el anunciante panel
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de la busqueda
     */
    function cartera($modo = '0', $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->model('medios_model');
        $this->load->model('clientes_model');
        $this->load->library('pagination');

        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($modo === '0') {
            $filtro = array();
            $filtro['pagina'] = 1;
            $filtro['datosPorPagina'] = 1000; 
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));	
          
        } else if ($modo === '1') {
            $filtro = array();
  			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
 
        } else if ($modo === '2') {
            
			$filtro['id_cliente'] = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        }
        $filtro['id_cliente'] = $cliente->id_cliente;  
        $ofertas = $this->ofertas_model->getOfertas($filtro);
        $numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);
		$inscripciones_total = $this->ofertas_model->getInscripcionesimpor($filtro);
		$inscripciones = $this->ofertas_model->getInscripcionescli($filtro);
		$renta_med = $this->ofertas_model->getInscripcionesrenta($filtro);
		$movimientos = $this->ofertas_model->getMovimientosPorCliente($filtro);
		$saldo = $this->ofertas_model->getSaldoPorCliente($filtro);
		$retiradas = $this->ofertas_model->getretiradaPorCliente($filtro);


        // Creamos el módulo de paginación para las ofertas
        $aConfig = array();
        $aConfig['use_page_numbers'] = TRUE;
        $aConfig["base_function"] = 'obtener_ofertas';
        $aConfig["parameters"] = 0 . ',';
        $aConfig["total_rows"] = $numOfertas;
        $aConfig["per_page"] = $filtro['datosPorPagina'];
        $aConfig["cur_page"] = $filtro['pagina'];
        $aConfig['num_links'] = 3;
        $this->pagination->initialize($aConfig);

        $data['paginacion'] = $this->pagination->create_links_ajax();

        $data['h1'] = 'Juegos_panel';
        $data['opc'] = 'Juegos_panel';
        $data['ofertas'] = $ofertas;
        $data['cliente'] = $cliente;
        $data['numOfertas'] = $numOfertas;
		$data['inscripciones_total'] = $inscripciones_total;
        $data['tipos_medio'] = $this->ofertas_model->getAnuncianteTiposMedio();
		$data['numInscripciones'] = $numInscripciones;
		$data['renta_med'] = $renta_med;
		$data['movimientos'] = $movimientos;
		$data['saldo'] = $saldo;
		$data['inscripciones'] = $this->ofertas_model->getInscripciones($filtro);
		$data['retiradas'] = $retiradas;

        
 
        $data['page'] = 'anunciantes/cartera';
        $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }

    /**
     * Obtenemos la lista de ofertas en Promoción para los usuarios que han introducido el código promocional
     *
     * @param string $codigo
     *            Código único que identifica al usuario
     */
    function ofertasPromo($codigo = 0)
    {
        $this->load->model('clientes_model');

        $result = $this->clientes_model->getClientePorCodigo($codigo); // comprobamos el codigo de usuario
        if (! $result) {
            redirect('inicio');
        }

        $this->load->model('ofertas_model');

        $ofertas = $this->ofertas_model->getOfertasPromo($result->id_cliente);

        $data['h1'] = 'Ofertas Promoción';
        $data['opc'] = 'ofertas';
        $data['ofertas'] = $ofertas;
        $data['cliente'] = $result->id_cliente;
        $data['page'] = "inicio/ofertas_promocion";
        $data['titulo'] = "Bimads - Ofertas Promoción";
 $this->load->vars($data);
        $this->load->view('principal');
    }

    /**
     * Muestra y modifica el formulario del anunciante logueado
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function perfil($modo = 0)
    {
        $this->load->model('clientes_model');
		$this->load->model('ofertas_model');
        $this->load->model('administrador_model');
        $this->load->library('form_validation');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
		$filtro['id_cliente'] = $cliente->id_cliente;
		$numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);

        if ($modo != 0) {

            $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

            $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
            $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');
			$this->form_validation->set_rules('imagen', 'Imagen', 'trim');

            $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim');
            if (! empty($this->input->post('pass'))) {
                $this->form_validation->set_rules('pass_conf', 'Confirmar Contraseña', 'trim|matches[pass]');
            } else if (! empty($this->input->post('pass_conf'))) {
                $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim|matches[pass_conf]');
            }

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('matches', 'Las contraseñas no coinciden.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $datos_cliente = array(
                    'nombre_contacto' => $this->input->post('nombre_contacto'),
                    'apellidos_contacto' => $this->input->post('apellidos_contacto'),
					'imagen' => $this->input->post('imagen')
                );
                $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);
                if (! empty($this->input->post('pass'))) {
                    $datos_usuario = array(
                        'pass' => md5($this->input->post('pass'))
                    );
                    $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);
                }
                $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
                $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            } else {
                $data['aviso_error'] = 'El formulario contiene errores.';
            }
        }
        $data['cliente'] = $cliente;
		$data['numOfertas'] = $numOfertas;
		$data['numInscripciones'] = $numInscripciones;

        $data['h1'] = 'Perfil';
        $data['opc'] = 'perfil';
        $data['page'] = 'anunciantes/perfil';
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }

    /**
     * Muestra y modifica el formulario del anunciante logueado
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
function imagenUsuario()
{
    $this->load->model('clientes_model');
    $this->load->model('administrador_model');
    $this->load->library('form_validation');
    $this->load->library('upload');
    $this->load->library('subida');

    if ($this->session->userdata('tipo_usuario') != 'cliente') {
        redirect('inicio');
    }

    $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

    $error = '';
    $archivo = '';

    if (!empty($_FILES['avatar']['name'])) {
        $nombre_fichero = $cliente->id_cliente;

        // Subida de imagen
        $archivo = $this->subida->uploadImagen('avatar', 'images/anunciantes', $nombre_fichero, 'png', FALSE);

        switch ($archivo) {
            case -1:
                $error = 'Formato incorrecto';
                break;
            case -2:
                $error = 'No se completó la subida';
                break;
            case -3:
                $error = 'Directorio de destino inaccesible';
                break;
            default:
                $archivo = $archivo['archivo'];
                break;
        }
    }

    if (!empty($error)) {
        $this->session->set_flashdata('aviso_error', $error);
    } elseif (empty($archivo)) {
        $this->session->set_flashdata('aviso_warning', 'Seleccione una imagen');
    } else {
        $datos_cliente = ['imagen' => $archivo];
        $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);
        $this->session->set_flashdata('aviso_ok', 'Imagen subida correctamente');
    }

    // Redirección a anunciantes/perfilEmpresa
    redirect('anunciantes/perfilEmpresa');
}

	
	
	
	

    /**
     * anunciantes/imagenUsuarioBorrar
     */
    function imagenUsuarioBorrar()
    {
        $this->load->model('clientes_model');
        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        if (! empty($cliente)) {
            $datos_cliente = array(
                'imagen' => ''
            );
            $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);
            $data['aviso_ok'] = 'Imagen eliminada correctamente';
        } else {
            $data['aviso_error'] = 'Se ha producido un error';
        }
 $this->load->vars($data);
        $this->load->view('avisos');
    }

    /**
     * anunciantes/imagenUsuarioBorrar
     */
    function cierreCuenta()
    {
        $this->load->model('clientes_model');
        $this->load->model('administrador_model');
        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }
        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        if (! empty($cliente)) {
            $datos_usuario = array(
                'estado' => 3 // desactivado
            );
            $this->administrador_model->updateUsuario($cliente->id_usuario, $datos_usuario);
            redirect('inicio/logout');
        } else {
            $data['aviso_error'] = 'Se ha producido un error, vuelva a intentarlo o consulte con el administrador';
            redirect('anunciantes/perfil');
        }
    }

    /**
     * anunciantes/ofertas/contacto
     */
    function contacto($idOferta = 0)
    {
        $this->load->model('clientes_model');
        $this->load->model('ofertas_model');
        $this->load->library('form_validation');
        $this->load->helper('form');

        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $oferta = $this->ofertas_model->getOfertaById($idOferta);

        if (! empty($oferta)) {

            $this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required');

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $mensaje = $this->input->post('mensaje');
                $datos_contacto = array(
                    'mensaje' => $mensaje,
                    'id_oferta' => $idOferta,
                    'id_cliente' => $this->session->userdata('id_cliente')
                );
                $this->ofertas_model->insertOfertasContacto($datos_contacto);
                $this->enviarEmailContactoOferta($oferta, $cliente, $mensaje);
                $this->session->set_flashdata('aviso_ok', 'Su mensaje ha sido enviado correctamente');
            }
        }
        $data['cliente'] = $cliente;
        $data['title'] = "Ofertas contacto";
        $date['oferta'] = $oferta;
 $this->load->vars($data);
        $this->load->view('anunciantes/ofertas_oferta_modal_contacto_enviado');
    }

    function enviarEmailContactoOferta($oferta, $cliente, $mensaje)
    {
        $this->load->library('email');
        $this->load->model('ofertas_model');
        $this->email->from(EMAIL_OFICIAL, 'BIMADS');
        $this->email->to(EMAIL_OFICIAL);
        $this->email->cc('gonzalo.reina@bimads.com');
        // $asunto = 'Mensaje de '.$cliente->nombre .' en oferta ' . $oferta->titulo;
        $asunto = 'Mensaje de cliente en oferta';
        $this->email->subject($asunto);
        $datos_mail['oferta'] = $oferta;
        $datos_mail['cliente'] = $cliente;
        $datos_mail['mensaje'] = $mensaje;

        $this->email->message($this->load->view('email/oferta_contacto', $datos_mail, TRUE));

        $this->email->send();
    }

    /**
     * Muestra la vista de perfil contactos
     */
    function perfilContactos($modo = 0, $idcontacto = 0){
        $this->load->model('clientes_model');
        $this->load->library('form_validation');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($this->session->userdata('tipo_usuario') != 'cliente' || empty ($cliente)) {
            redirect('inicio');
        }

        $data['cliente'] = $cliente;

        if ($modo == 0){ //list by idCliente

        } else if ($modo == 1){ //get by idClienteContacto
            if ($idcontacto>0){
               $contacto = $this->clientes_model->getClienteContacto($idcontacto);
               $data['contacto_edit'] = $contacto;
            }
        } else if ($modo == 2){ //save

            $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

            $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
            $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');

            if ($this->form_validation->run() == true) {
                $datos_contacto = array(
                    'id_cliente' => $cliente->id_cliente,
                    'nombre_contacto' => $this->input->post('nombre_contacto'),
                    'apellidos_contacto' => $this->input->post('apellidos_contacto'),
                    'email' => $this->input->post('email'),
                    'telefono' => $this->input->post('telefono'),
                    'fecha_graba' => date('Y-m-d H:i:s')
                );
                if ($idcontacto > 0){
                    $this->clientes_model->updateClienteContacto($idcontacto, $datos_contacto);
                } else {
                    $this->clientes_model->insertClienteContacto($datos_contacto);
                }
                $data['aviso_ok'] = 'Datos del contacto guardados correctamente.';
            }
        } else if ($modo == 3){ //delete
            $datos_contacto = array(
                'fecha_baja' => date('Y-m-d H:i:s')
            );
            if ($idcontacto > 0){
                $this->clientes_model->updateClienteContacto($idcontacto, $datos_contacto);
            }
        }
        $data['contactos']=$this->clientes_model->getClienteContactosByIdCliente($cliente->id_cliente);
        $data['page'] = "anunciantes/perfil_contactos";

        $data['h1'] = 'Perfil Contactos';
        $data['opc'] = 'perfil';
        $data['title'] = "Perfil Contactos";
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }

   /**
     * Muestra la vista de perfil empresa
     */
    function perfilEmpresa($modo = 0)
    {
        $this->load->model('clientes_model');
        $this->load->library('form_validation');
		$this->load->model('ofertas_model');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['tipos_medio'] = $this->clientes_model->getTiposMedios();
		$filtro['id_cliente'] = $cliente->id_cliente;
		$numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);

        if ($modo != 0) {
            $this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
            $this->form_validation->set_rules('nombre_comercial', 'Nombre Comercial', 'trim|required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
            $this->form_validation->set_rules('direccion', 'Dirección', 'trim');
            $this->form_validation->set_rules('cp', 'Código Postal', 'trim');
            $this->form_validation->set_rules('cif', 'CIF', 'trim');
            $this->form_validation->set_rules('web', 'Web', 'trim');
            $this->form_validation->set_rules('Fecha_nacimiento', 'Fecha Nacimiento', 'trim');
			$this->form_validation->set_rules('email', 'email', 'trim');
            $this->form_validation->set_rules('poblacion', 'Población', 'trim');

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span class="error-formulario" </span>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $datos_cliente = array(
                    'nombre' => $this->input->post('nombre'),
                    'nombre_comercial' => $this->input->post('nombre_comercial'),
					'id_usuario' => $this->session->userdata('id_usuario'),
                    'telefono' => $this->input->post('telefono'),
                    'direccion' => $this->input->post('direccion'),
                    'cp' => $this->input->post('cp'),
                    'cif' => $this->input->post('cif'),
					'imagen' => $this->input->post('imagen'),
                    'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
                    'web' => $this->input->post('web'),
                    'poblacion' => $this->input->post('poblacion'),
					'email' => $this->input->post('email')
                );

                $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);

               

                $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
                $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            } else {
                $data['aviso_error'] = 'El formulario contiene errores.';
            }
        }
		$data['numOfertas'] = $numOfertas;
		$data['numInscripciones'] = $numInscripciones;
        $data['cliente'] = $cliente;
        $data['page'] = "anunciantes/perfil_empresa";

        $data['h1'] = 'Perfil Empresa';
        $data['opc'] = 'perfil Empresa';
        $data['title'] = "Perfil Empresa";
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
	
	   /**
     * Muestra la vista de perfil empresa
     */
    function perfilEmpresa1($modo = 0)
    {
        $this->load->model('clientes_model');
        $this->load->library('form_validation');
		$this->load->model('ofertas_model');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['tipos_medio'] = $this->clientes_model->getTiposMedios();
		$filtro['id_cliente'] = $cliente->id_cliente;
		$numOfertas = $this->ofertas_model->getNumOfertas($filtro);
		$numInscripciones = $this->ofertas_model->getNumInscripciones($filtro);

        if ($modo != 0) {
            $this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
            $this->form_validation->set_rules('nombre_comercial', 'Nombre Comercial', 'trim|required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
            $this->form_validation->set_rules('direccion', 'Dirección', 'trim');
            $this->form_validation->set_rules('cp', 'Código Postal', 'trim');
            $this->form_validation->set_rules('cif', 'CIF', 'trim');
            $this->form_validation->set_rules('web', 'Web', 'trim');
            $this->form_validation->set_rules('Fecha_nacimiento', 'Fecha Nacimiento', 'trim');
			$this->form_validation->set_rules('email', 'email', 'trim');
            $this->form_validation->set_rules('poblacion', 'Población', 'trim');

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span class="error-formulario" </span>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $datos_cliente = array(
                    'nombre' => $this->input->post('nombre'),
                    'nombre_comercial' => $this->input->post('nombre_comercial'),
					'id_usuario' => $this->session->userdata('id_cliente'),
                    'telefono' => $this->input->post('telefono'),
                    'direccion' => $this->input->post('direccion'),
                    'cp' => $this->input->post('cp'),
                    'cif' => $this->input->post('cif'),
                    'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
                    'web' => $this->input->post('web'),
                    'poblacion' => $this->input->post('poblacion'),
					'email' => $this->input->post('email')
                );

                $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);

               

                $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
                $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            } else {
                $data['aviso_error'] = 'El formulario contiene errores.';
            }
        }
		$data['numOfertas'] = $numOfertas;
		$data['numInscripciones'] = $numInscripciones;
        $data['cliente'] = $cliente;
        $data['page'] = "anunciantes/perfil_empresa1";

        $data['h1'] = 'Perfil Empresa1';
        $data['opc'] = 'perfil Empresa1';
        $data['title'] = "Perfil Empresa1";
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }

    /**
     * Muestra la vista de perfil preferencias
     */
    function preferencias($modo = 0)
    {
  

        $this->load->model('clientes_model');
        $this->load->library('form_validation');

        if ($this->session->userdata('tipo_usuario') != 'cliente') {
            redirect('inicio');
        }
        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        $data['medios'] = $this->clientes_model->getMedios();
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['tipos_medio'] = $this->clientes_model->getTiposMedios();
        
        
        if ($modo != 0) {
            $datos_cliente = array(
                'id_usuario' => $this->session->userdata('id_cliente'),
                'tipo_medio' => $this->input->post('tipo_medio'),
                'sector' => $this->input->post('sector'),
                'meses' => $this->input->post('meses'),
                'preferencias'  => $this->input->post('preferencias'),
				'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
				'id_tipo_inver'  => $this->input->post('id_tipo_inver')
				
            );
          
            $this->clientes_model->completarPerfil($datos_cliente);
     
     
            $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
            $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        }
        $cliente->preferencias =$this->clientes_model->getClientesPreferencias($this->session->userdata('id_cliente'));
   
        $modal_home_attr = empty($cliente->id_sector);
        $modal_profile_attr = true;
        $modal_messages_attr = true;
        $modal_settings_attr = true;
   
        foreach($cliente->preferencias as $key => $value){
            if($key == "mess" || $key == "medios"   ){
                $modal_home_attr = false;
			}
            if(isset($cliente->preferencias["ha_hecho_publicidad"])){
                $modal_profile_attr = false;
			}
            if(strpos($key, 'audiencia') === 0  && $key != "audiencia_masculina"){

                 $modal_messages_attr = false;
			}
            if(strpos($key, 'soporte') === 0 ){
                 $modal_settings_attr = false;
			}
            if($key == "soporte_tipos_medio" && is_array($value) && count($value) > 0){
                $modal_settings_attr = false;
            }
		}


       if( $modal_home_attr){
            $data['modal'] = "home-attr";
	   }else if($modal_profile_attr){
            $data['modal'] = "profile-attr";
	   }else if($modal_messages_attr){
            $data['modal'] = "messages-attr";
	   }else if($modal_settings_attr){
            $data['modal'] = "settings-attr";
	   }

 
        $data['cliente'] = $cliente;
        $data['page'] = "anunciantes/perfil_preferencias";

        $data['h1'] = 'Preferencias';
        $data['opc'] = 'perfil';
        $data['title'] = "Preferencias";
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
 
    /**
     * Muestra y modifica el formulario del anunciante logueado
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function datosanunciante($modo = 0, $id_oferta = 0)
    {
        $this->load->model('clientes_model');
        $this->load->library('form_validation');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $data['opc'] = 'datosanunciante';

        if ($modo == 1) {
            $this->form_validation->set_rules('tipo_medio[]', 'Los tipos de ofertas', 'trim|required');
            $this->form_validation->set_rules('sector', 'Tu sector', 'trim|required');
            $this->form_validation->set_rules('meses[]', 'Los meses', 'trim|required');

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<p class="perfil-error">', '</p>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $datos_cliente = array(
                    'id_usuario' => $this->session->userdata('id_cliente'),
                    'tipo_medio' => $this->input->post('tipo_medio'),
                    'sector' => $this->input->post('sector'),
					'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
                    'meses' => $this->input->post('meses')
                );
                $this->clientes_model->completarPerfil($datos_cliente);
                $perfil_completado = true;
            } else {
                $perfil_completado = false;
            }
        } else {
            $perfil_completado = false;
        }
        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        $data['cliente'] = $cliente;
        if (is_null($cliente->id_sector) || $modo == 2 || (! $perfil_completado && $modo == 1)) {
            $data['sectores'] = $this->clientes_model->getSectores();
            $data['provincias'] = $this->clientes_model->getProvincias();
            $data['tipos_medio'] = $this->clientes_model->getTiposMedios();
            $data['correcto'] = $this->session->flashdata('correcto');
            $data['page'] = 'anunciantes/datosanunciante';
 $this->load->vars($data);
            $this->load->view('default_anunciantes');
        } else {
            $data['page'] = 'anunciantes/perfilcompletado';
 $this->load->vars($data);
            $this->load->view('default_anunciantes');
        }
        // Tarea 020-Enviamos un email de info al admin
        if ($id_oferta > 0) {
            $this->enviarEmailAdminInteresOferta($cliente, $id_oferta);
        }
    }

    function enviarEmailAdminInteresOferta($cliente, $id_oferta)
    {
        $this->load->library('email');
        $this->load->model('ofertas_model');
        $oferta = $this->ofertas_model->getOfertaAdmin($id_oferta);
        $this->email->from(EMAIL_OFICIAL, 'BIMADS');
        $this->email->to(EMAIL_OFICIAL);
        $this->email->cc('gonzalo.reina@bimads.com');
        $this->email->subject('El anunciante \'' . $cliente->nombre . '\' (id_cliente=' . $cliente->id_cliente . ') se ha interesado por la oferta \'' . $oferta->titulo . '\' (id_oferta=' . $id_oferta . ')');
        $this->email->send();
    }

    function ofertais($id_oferta)
    {
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('medios_model');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio/login/0/' . $id_oferta);

        $ofertais = $this->ofertas_model->ofertais($id_oferta, $this->session->userdata('id_cliente'));
        

    }
	
	/**
     * Muestra la lista de inscripciones a ofertas del anunciante logueado
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de las inscripciones
    
    function inscripciones($modo = '0', $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->library('pagination');


        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 10;


        $inscripciones = $this->ofertas_model->getInscripciones($filtro);
        $numInscripciones = $this->ofertas_model->getNumInscripciones();

        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "anunciantes/inscripciones/3/";
        $config["total_rows"] = $numInscripciones;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 4;

        $this->pagination->initialize($config);

        $data["paginacion"] = $this->pagination->create_links();

        $data['filtro'] = $filtro;
        $data['h1'] = 'Mis Juegos1';
        $data['opc'] = 'inversiones1';
        $data['inscripciones'] = $inscripciones;
		$data['numInscripciones'] = $Inscripciones;
        $data['page'] = 'anunciantes/inscripciones';

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
        $data['cliente'] = $cliente;
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
	   */
	function perfilinver($modo = 0)
    {
        $this->load->model('clientes_model');
        $this->load->model('administrador_model');
        $this->load->library('form_validation');

        if ($this->session->userdata('tipo_usuario') != 'cliente')
            redirect('inicio');

        $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

        if ($modo != 0) {

            $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

            $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
            $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');

            $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim');
            if (! empty($this->input->post('pass'))) {
                $this->form_validation->set_rules('pass_conf', 'Confirmar Contraseña', 'trim|matches[pass]');
            } else if (! empty($this->input->post('pass_conf'))) {
                $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim|matches[pass_conf]');
            }

            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('matches', 'Las contraseñas no coinciden.');

            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
            if ($this->form_validation->run() == true) {
                $datos_cliente = array(
                    'nombre' => $this->input->post('nombre'),
                    'poblacion' => $this->input->post('poblacion'),
					'cif' => $this->input->post('cif'),
					'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
					'cp' => $this->input->post('cp'),
					'direccion' => $this->input->post('direccion')
                );

                $this->clientes_model->updateCliente($this->session->userdata('id_cliente'), $datos_cliente);
                if (! empty($this->input->post('pass'))) {
                    $datos_usuario = array(
                        'pass' => md5($this->input->post('pass'))
                    );
                    $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);
                }
                $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
                $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            } else {
                $data['aviso_error'] = 'El formulario contiene errores.';
            }
			 // Redirigir con un ancla
            redirect('https://app.obbak.es/anunciantes/preferencias#progress-company-document');
            exit(); // Terminar la ejecución después de la redirección
        } else {
            $data['aviso_error'] = 'El formulario contiene errores.';	
		
		}
        $data['cliente'] = $cliente;

        $data['h1'] = 'Perfilinver';
        $data['opc'] = 'perfilinver';
        $data['page'] = 'anunciantes/index_oferta1';
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
	
	function perfilinversor($modo = 0)
{
    $this->load->model('clientes_model');
    $this->load->model('administrador_model');
    $this->load->library('form_validation');

    if ($this->session->userdata('tipo_usuario') != 'cliente') {
        redirect('inicio');
    }

    $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

    if ($modo != 0) {
        $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

        $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
        $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');

        $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim');
        if (!empty($this->input->post('pass'))) {
            $this->form_validation->set_rules('pass_conf', 'Confirmar Contraseña', 'trim|matches[pass]');
        } else if (!empty($this->input->post('pass_conf'))) {
            $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim|matches[pass_conf]');
        }

        // Mensajes de error
        $this->form_validation->set_message('required', '%s es un dato necesario.');
        $this->form_validation->set_message('matches', 'Las contraseñas no coinciden.');

        // Formato del contenedor del error
        $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

        if ($this->form_validation->run() == true) {
            // Se construyen los datos del cliente sin incluir id_cliente desde el formulario
            $datos_cliente = array(
                'id_tipo_inver' => $this->input->post('id_tipo_inver'),
                'id_origen' => $this->input->post('id_origen'),
            );

            // Actualizar los datos del cliente
            $this->clientes_model->updateinversor($this->session->userdata('id_cliente'), $datos_cliente);

            $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
            $cliente = $this->clientes_model->getClienteAdmininver($this->session->userdata('id_cliente'));
        } else {
            $data['aviso_error'] = 'El formulario contiene errores.';
        }
		 // Redirigir con un ancla
            redirect('https://app.obbak.es/anunciantes/preferencias#progress-bank-detail');
            exit(); // Terminar la ejecución después de la redirección
        } else {
            $data['aviso_error'] = 'El formulario contiene errores.';	
		
		}

    // Carga la vista con los datos necesarios
		$data['cliente'] = $cliente;



        $data['h1'] = 'Perfilinver';
        $data['opc'] = 'perfilinver';
        $data['page'] = 'anunciantes/index_oferta1';
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }
	
		function perfilinversor1($modo = 0)
{
    $this->load->model('clientes_model');
    $this->load->model('administrador_model');
    $this->load->library('form_validation');

    if ($this->session->userdata('tipo_usuario') != 'cliente') {
        redirect('inicio');
    }

    $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));

    if ($modo != 0) {
        $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

        $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
        $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');

        $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim');
        if (!empty($this->input->post('pass'))) {
            $this->form_validation->set_rules('pass_conf', 'Confirmar Contraseña', 'trim|matches[pass]');
        } else if (!empty($this->input->post('pass_conf'))) {
            $this->form_validation->set_rules('pass', 'Cambiar Contraseña', 'trim|matches[pass_conf]');
        }

        // Mensajes de error
        $this->form_validation->set_message('required', '%s es un dato necesario.');
        $this->form_validation->set_message('matches', 'Las contraseñas no coinciden.');

        // Formato del contenedor del error
        $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

        if ($this->form_validation->run() == true) {
            // Se construyen los datos del cliente sin incluir id_cliente desde el formulario
            $datos_cliente = array(
                'condiciones' => 1,
                'fecha_graba_inver' => date('Y-m-d H:i:s'),
            );

            // Actualizar los datos del cliente
            $this->clientes_model->updateinversor($this->session->userdata('id_cliente'), $datos_cliente);

            $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';
            $cliente = $this->clientes_model->getClienteAdmininver($this->session->userdata('id_cliente'));
        } else {
            $data['aviso_error'] = 'El formulario contiene errores.';
        }
		 // Redirigir con un ancla
            redirect('https://app.obbak.es/anunciantes/ofertaspanel');
            exit(); // Terminar la ejecución después de la redirección
        } else {
            $data['aviso_error'] = 'El formulario contiene errores.';	
		
		}

    // Carga la vista con los datos necesarios
		$data['cliente'] = $cliente;



        $data['h1'] = 'Perfilinver';
        $data['opc'] = 'perfilinver';
        $data['page'] = 'anunciantes/index_oferta1';
 $this->load->vars($data);
        $this->load->view('default_anunciantes');
    }

}