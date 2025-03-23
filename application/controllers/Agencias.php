<?php
	if ( ! defined('BASEPATH'))
		exit('No direct script access allowed');
		require_once('BaseController.php');

	class Agencias extends BaseController {
		private $emailOficial = '';

		function __construct() {
			parent::__construct();
		}

		/**
		 * Muestra la lista de anunciante pertenecientes a la agencia
		 *
		 * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * @param integer $pagina Pagina a mostrar del listado de anunciantes
		 */
		function anunciantes($modo = '0', $pagina = 1){
			$this->load->model('clientes_model');
			$this->load->library('pagination');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			if($modo === '0'){
				$filtro = array();

				$this->session->set_userdata('filtro_clientes', $filtro);
			}
			else if($modo === '1'){
				$filtro['estado'] = $this->input->post('estado');
				$filtro['anunciante'] = $this->input->post('anunciante');

				$this->session->set_userdata('filtro_clientes', $filtro);
			}
			else if($modo === '2'){
				$filtro = ($this->session->userdata('filtro_clientes') === false) ? array() : $this->session->userdata('filtro_clientes');
			}

			$filtro['agencia'] = $this->session->userdata('id_agencia');
			$filtro['pagina'] = $pagina;
			$filtro['datosPorPagina'] = 15;

			$clientes = $this->clientes_model->getClientesAdmin($filtro);
			$numClientes = $this->clientes_model->getNumClientesAdmin();

			$config = array();
			$config['use_page_numbers'] = TRUE;
			$config["base_url"] = base_url() . "agencias/anunciantes/2";
			$config["total_rows"] = $numClientes;
			$config["per_page"] = $filtro['datosPorPagina'];
			$config["uri_segment"] = 4;

			$this->pagination->initialize($config);

			$data["paginacion"] = $this->pagination->create_links();

			$data['opc'] = 'anunciantes';
			$data['clientes'] = $clientes;
			$data['filtro'] = $filtro;
			$data['page'] = 'agencias/anunciantes';
			$this->load->view('default_agencia', array_merge($data));
		}

		/**
		 * Muestra el formulario de cambio de contraseña
		 *
		 * @param integer $modo Modo de validacion: 0 no valida, 1 si
		 */
		function cambiarPass($modo = 0){
			$this->load->model('agencias_model');
			$this->load->model('administrador_model');
			$this->load->library('form_validation');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$agencia = $this->agencias_model->getAgencia($this->session->userdata('id_agencia'));

			$data['opc'] = 'perfil';

			if($modo != 0){
				$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required');
				$this->form_validation->set_rules('pass_conf', 'Repetición de la contraseña', 'trim|matches[pass]');

				//Mensajes de error
				$this->form_validation->set_message('required', '%s es un dato necesario.');
				$this->form_validation->set_message('matches', 'as contraseñas no coinciden.');

				//Formato del contenedor del error
				$this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

				//Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
				if ($this->form_validation->run() == true){
					$datos_usuario = array(
						'pass' => md5($this->input->post('pass'))
					);

					$this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);
					$this->session->set_flashdata('correcto', 'Su contraseña ha sido modificada correctamente');
					redirect('agencias/perfil');
				}
			}
			$data['agencia'] = $agencia;
			$data['page'] = 'agencias/cambiarPass';
			$this->load->view('default_cliente', array_merge($data));
		}

		/**
		 * Modifica los permisos para los medios pasados por post para el anunciante especificado
		 *
		 * Parametros Post:
		 * array cambios Array con los cambios para los permisos: posicion 2k id del medio, posicion 2k+1 nuevo estado del permiso
		 *
		 * @param integer $id_cliente Id del cliente para el que realizar los cambios
		 */
		function cambiarPermisosAnunciante($id_cliente){
			$this->load->model('clientes_model');

			$permisos = array();
			$cambios = $this->input->post('cambios');

			$cambios = explode(' ', $cambios);

			if(!empty($cambios)){
				for($i = 0; $i < count($cambios); $i += 2){
					$permisos[$cambios[$i]] = $cambios[($i + 1)];
				}
			}

			$this->clientes_model->cambiarPermisosAnuncianteAgencia($id_cliente, $permisos);

			redirect('agencias/permisosAnunciante/' . $id_cliente . '/2/' . $this->input->post('pagina'));
		}

		/**
		 * Obtiene los anunciantes pertencientes a la agencia logueada que se corresponden con el texto introducido en el campo cliente del filtro de ofertas
		 */
		function clientesAutocompletar(){
			$this->load->model('clientes_model');

			$filtro['agencia'] = $this->session->userdata('id_agencia');
			$filtro['pagina'] = 1;
			$filtro['datosPorPagina'] = 100;
			$filtro['palabra'] = $this->input->post('palabra');

			$clientes = $this->clientes_model->getClientesAutocompletar($filtro);

			echo json_encode($clientes);
		}

		/**
		 * Desinscribe de la ofertas pasadas por post al anunciante especificado
		 *
		 * Parametros Post:
		 * array ofertas - Array con los ids de las ofertas de las que desinscribir al anunciante
		 *
		 * @param integer $id_cliente Id del anunciante a desinscribir de la ofertas
		 */
		function desinscribirAnunciante($id_cliente){
			$this->load->model('ofertas_model');

			$ofertas = $this->input->post('ofertas');

			if(!empty($ofertas)){
				$ofertas = explode(' ', $ofertas);

				foreach($ofertas as $oferta){
					$this->ofertas_model->desinscribirAnunciante($oferta, $id_cliente);
				}
			}

			redirect('agencias/inscripcionesAnunciante/' . $id_cliente . '/2');
		}

		/**
		 * Muestra el formulario para editar un anunciante
		 *
		 * @param integer $id_cliente Id del cliente a editar
		 * @param integer $modo Modo de validacion: 0 no valida, 1 si
		 */
		function editarAnunciante($id_cliente = 0, $modo = 0){
			$this->load->model('clientes_model');
			$this->load->library('form_validation');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$cliente = $this->clientes_model->getClienteAdmin($id_cliente);

			$data['opc'] = 'anunciantes';
			$data['datos_acceso_enviados'] = $this->session->flashdata('datos_acceso_enviados');

			if($modo == 0){
				$data['cliente'] = $cliente;
				$data['page'] = 'agencias/editar_anunciante';
				$this->load->view('default_agencia', array_merge($data));
			}
			else{
				$this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
				$this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
				$this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
				$this->form_validation->set_rules('direccion', 'Dirección', 'trim');
				$this->form_validation->set_rules('cp', 'Código Postal', 'trim');
				$this->form_validation->set_rules('cif', 'CIF', 'trim');
				$this->form_validation->set_rules('web', 'Web', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico[' . $cliente->id_usuario . ']' . (!empty($cliente->email) ? '|required' : ''));
				$this->form_validation->set_rules('poblacion', 'Población', 'trim');

				//Mensajes de error
				$this->form_validation->set_message('required', '%s es un dato necesario.');
				$this->form_validation->set_message('emailEsUnico', 'Este email ya está siendo utilizado, por favor utilice otro.');
				$this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');

				//Formato del contenedor del error
				$this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

				//Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
				if ($this->form_validation->run() == FALSE){
					$data['validado'] = true;
					$data['cliente'] = $cliente;
					$data['page'] = 'agencias/editar_anunciante';
					$this->load->view('default_agencia', array_merge($data));
				}
				else{
					$datos_cliente = array(
						'nombre' => $this->input->post('nombre'),
						'nombre_contacto' => $this->input->post('nombre_contacto'),
						'apellidos_contacto' => $this->input->post('apellidos_contacto'),
						'telefono' => $this->input->post('telefono'),
						'direccion' => $this->input->post('direccion'),
						'email' => $this->input->post('email'),
						'cp' => $this->input->post('cp'),
						'cif' => $this->input->post('cif'),
						'web' => $this->input->post('web'),
						'poblacion' => $this->input->post('poblacion')
					);

					$this->clientes_model->updateCliente($id_cliente, $datos_cliente);

					if($cliente->email != $this->input->post('email')){
						//TODO: enviar email con datos acceso
					}

					redirect('agencias/editarAnunciante/' . $id_cliente);
				}
			}
		}

		/**
		 * Comprueba que el email pasado no esta siendo utilizado por ningun otro usuario, funcion de validacion de fomrulario
		 *
		 * @param string $email Email a comprobar
		 * @param integer $id_usuario Id del usuario a excluir de la comprobacion
		 * @return boolean true si no esta siendo usado, false si lo esta siendo
		 */
		public function emailEsUnico($email, $id_usuario = 0){
			$this->load->model('inicio_model');

			return $this->inicio_model->emailEsUnico($email, $id_usuario);
		}

		/**
		 * Envia los datos de acceso de nuevo al cliente especificado
		 *
		 * @param integer $id_cliente Id del cliente al que enviar los datos de acceso
		 */
		function enviarDatosAcceso($id_cliente){
			$this->load->model('clientes_model');
			$this->load->model('administrador_model');

			$cliente = $this->clientes_model->getClienteAdmin($id_cliente);

			if(empty($cliente))
				redirect('agencias/editarAnunciante/' . $id_cliente);

			$pass = $this->generarCodigo();

			$datos_usuario['pass'] = md5($pass);

			$this->administrador_model->updateUsuario($cliente->id_usuario, $datos_usuario);

			//enviar email
			$mensaje = $this->load->view('agencias/email_datos_acceso', array('usuario' => $cliente->email, 'pass' => $pass), TRUE);

			$this->load->library('email');
			$this->email->from(EMAIL_OFICIAL);
			$this->email->to($cliente->email);

			$this->email->subject('Bimads: Nuevos datos de acceso');
			$this->email->message($mensaje);

			$this->email->send();

			$this->session->set_flashdata('datos_acceso_enviados', 'Los datos de acceso se han enviado correctamente');

			redirect('agencias/editarAnunciante/' . $id_cliente);;
		}

		/**
		 * Genera un codigo de 12 caracteres alfanumericos aleatorio
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
		 * Obtiene el listado de anunciantes pertenecientes a la agencia logueada que tienen acceso a la oferta especificada
		 *
		 * @param integer $id_oferta Id de la oferta por la que filtrar los anunciantes
		 */
		function getClientesOferta($id_oferta){
			$this->load->model('clientes_model');

			$filtro['agencia'] = $this->session->userdata('id_agencia');
			$filtro['pagina'] = 1;
			$filtro['datosPorPagina'] = 100;
			
			$ofertas_array = array();
			array_push($ofertas_array, $id_oferta);
			$filtro['id_oferta'] = $ofertas_array;
			

			$data['clientes'] = $this->clientes_model->getClientesOferta($filtro);

			$this->load->view('agencias/lista_clientes_oferta', $data);
		}

		/**
		 * Muestra los detalles de la oferta especificada
		 *
		 * @param integer $id_oferta Id de la oferta a mostrar en detalle
		 */
		function getDetallesOferta($id_oferta){
			$this->load->model('ofertas_model');

			$data['oferta'] = $this->ofertas_model->getOferta($id_oferta,0,$this->session->userdata('id_agencia'));

			$this->load->view('agencias/detallesOferta', $data);
		}

		/**
		 * Muestra la lista de anunciantes pertenecientes a la agencia logueada con acceso a la oferta y si estan inscritos a ella o no
		 *
		 * Parametros Post:
		 * integer id_oferta - Id de la oferta por la que filtrar los anunciantes
		 * integer modo - Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * integer pagina - Pagina a mostrar de los anunciantes
		 */
		function getInscripcionesOferta(){
			$this->load->model('ofertas_model');
			$this->load->library('pagination');

			$oferta = $this->input->post('oferta');
			$modo = $this->input->post('modo');
			$pagina = $this->input->post('pagina');

			if($modo === '0'){
				$filtro = array();

				$this->session->set_userdata('filtro_inscripciones', $filtro);
			}
			else if($modo === '1'){
				$filtro['estado'] = $this->input->post('estado');

				$this->session->set_userdata('filtro_inscripciones', $filtro);
			}
			else if($modo === '2'){
				$filtro = ($this->session->userdata('filtro_inscripciones') === false) ? array() : $this->session->userdata('filtro_inscripciones');
			}

			$filtro['oferta'] = $oferta;
			$filtro['agencia'] = $this->session->userdata('id_agencia');
			$filtro['pagina'] = $pagina;
			$filtro['datosPorPagina'] = 15;

			$anunciantes = $this->ofertas_model->getAnunciantesInscripciones($filtro);
			$numAnunciantes = $this->ofertas_model->getNumAnunciantesInscripciones();

			$config = array();
			$config['use_page_numbers'] = TRUE;
			$config["base_function"] = 'obtener_inscripciones_oferta';
			$config["parameters"] = 2;
			$config["total_rows"] = $numAnunciantes;
			$config["per_page"] = $filtro['datosPorPagina'];

			$this->pagination->initialize($config);

			$data["paginacion"] = $this->pagination->create_links_ajax();

			$data['anunciantes'] = $anunciantes;
			$data['filtro'] = $filtro;
			$this->load->view('agencias/pop_up_inscripciones', array_merge($data));
		}

		/**
		 * Redirige a ofertas si el usuario logueado es agencia
		 */
		function index(){
			if($this->session->userdata('tipo_usuario') == 'agencia'){
				redirect('agencias/ofertas');
			}
			else{
				redirect('inicio/index');
			}
		}

		/**
		 * Inscribe a los anunciantes mandados por post a la oferta especificada
		 *
		 * Parametros Post
		 * array anunciantes - Array con los ids de los anunciantes a inscribir en la oferta
		 *
		 * @param integer $id_oferta Id de la oferta a la que inscribir a los anunciantes
		 */
		function inscribirseOferta($id_oferta){
			$this->load->model('ofertas_model');

			$anunciantes = ($this->input->post('anunciantes') == '') ? array() : explode(' ', $this->input->post('anunciantes'));

			$anunciantes = $this->ofertas_model->inscribirAnunciantes($id_oferta, $anunciantes);
			$oferta = $this->ofertas_model->getOferta($id_oferta);

			if(!empty($anunciantes)){
				$this->load->library('email');

				$data = array(
					'oferta' => $oferta->titulo
				);

				foreach($anunciantes as $anunciante){
					$this->email->from(EMAIL_OFICIAL, 'BIMADS');
					$this->email->to(EMAIL_OFICIAL);

					$data['anunciante'] = $anunciante->nombre;

					$this->email->subject('Un anunciante se ha inscrito a una oferta en Bimads');
					$this->email->message($this->load->view('agencias/email_inscripcion_oferta', $data, TRUE));

					$this->email->send();
				}
			}

			return 'ok';
		}

		/**
		 * Muestra la lista de ofertas a las que esta inscrito el anunciantes especificado
		 *
		 * @param integer $id_cliente Id del anunciante por el que filtrar las ofertas
		 * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * @param integer $pagina Pagina a mostrar de las ofertas
		 */
		function inscripcionesAnunciante($id_cliente, $modo = 0, $pagina = 1){
			$this->load->model('ofertas_model');
			$this->load->library('pagination');
			$this->load->model('clientes_model');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$filtro = array();

			if($modo == 1){
				$filtro['estado'] = $this->input->post('estado');
			}
			else if($modo == 2){
				$filtro = $this->session->userdata('filtro_inscripciones');

				if(empty($filtro))
					$filtro = array();
			}

			$this->session->set_userdata('filtro_inscripciones', $filtro);

			$filtro['pagina'] = $pagina;
			$filtro['datosPorPagina'] = 15;
			$filtro['cliente'] = $id_cliente;

			$inscripciones = $this->ofertas_model->getInscripciones($filtro);
			$numInscripciones = $this->ofertas_model->getNumInscripciones();

			$config = array();
			$config['use_page_numbers'] = TRUE;
			$config["base_url"] = base_url() . "agencias/inscripcionesAnunciante/" . $id_cliente . "/2/";
			$config["total_rows"] = $numInscripciones;
			$config["per_page"] = $filtro['datosPorPagina'];;
			$config["uri_segment"] = 5;

			$this->pagination->initialize($config);

			$data["paginacion"] = $this->pagination->create_links();

			$data['cliente'] = $this->clientes_model->getClienteAdmin($id_cliente);
			$data['filtro'] = $filtro;
			$data['opc'] = 'anunciantes';
			$data['inscripciones'] = $inscripciones;
			$data['page'] = 'agencias/inscripciones_anunciante';
			$this->load->view('default_agencia', array_merge($data));
		}

		/**
		 * inscripcionesAnunciantes
		 *
		 * @param number $modo
		 * @param number $pagina
		 */
		function inscripcionesAnunciantes($modo = 0, $pagina = 1){
			$this->load->model('ofertas_model');
			$this->load->library('pagination');

			$filtro = array();

			if($modo == 1){
				$filtro['estado'] = $this->input->post('estado');
				$filtro['palabra'] = $this->input->post('palabra');
			}
			else if($modo == 2){
				$filtro = $this->session->userdata('filtro_inscripciones');

				if(empty($filtro))
					$filtro = array();
			}

			$filtro['agencia'] = $this->session->userdata('id_agencia');
			$this->session->set_userdata('filtro_inscripciones', $filtro);

			$filtro['pagina'] = $pagina;
			$filtro['datosPorPagina'] = 15;

			$data['inscripciones'] = $this->ofertas_model->getInscripcionesAnunciantes($filtro);
			$data['numInscripciones'] = $this->ofertas_model->getNumInscripcionesAnunciantes();

			$config = array();
			$config['use_page_numbers'] = TRUE;
			$config["base_url"] = base_url() . "agencias/inscripcionesAnunciantes/2/";
			$config["total_rows"] = $data['numInscripciones'];
			$config["per_page"] = $filtro['datosPorPagina'];;
			$config["uri_segment"] = 3;

			$this->pagination->initialize($config);

			$data["paginacion"] = $this->pagination->create_links();
			$data['filtro'] = $filtro;

			$data['page'] = 'agencias/inscripciones_anunciantes_ofertas';
			$this->load->view('default_agencia', array_merge($data));
		}

		/**
		 * Muestra la lista de ofertas resultante de la busqueda realizada por la agencia, obtenida por ajax
		 *
		 * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * @param integer $pagina Pagina a mostrar del listado de ofertas
		 */
		function lista_ofertas($iModo = 0, $iPagina = 1){
			$this->load->model('ofertas_model');
			$this->load->library('pagination');
			$this->load->model('medios_model');

			$filtro = array();

			if($iModo === '0'){
				$this->session->set_userdata('filtro_ofertas', $filtro);
			}
			else if($iModo === '1'){
				$filtro['cliente'] = $this->input->post('cliente');
				$filtro['id_cliente'] = $this->input->post('id_cliente');
				$filtro['max_precio'] = $this->input->post('max_precio');
				$filtro['min_precio'] = $this->input->post('min_precio');
				$filtro['ordenar'] = $this->input->post('ordenar');

				$tipos_medios = array();

				if($this->input->post('tipos') != ''){
					$tipos_medios = explode(' ', $this->input->post('tipos'));
					$filtro['tipo_medio'] = $tipos_medios;
				}

				$medios = array();

				if($this->input->post('medios') != ''){
					$medios = explode(' ', $this->input->post('medios'));
					$filtro['medio'] = $medios;
				}

				$destacadas = array();

				if($this->input->post('destacadas') != ''){
					$destacadas = explode(' ', $this->input->post('destacadas'));
					$filtro['destacadas'] = $destacadas;
				}

				if($this->input->post('selec_precios') != ''){
					$fprecios = explode(' ', $this->input->post('selec_precios'));
					$filtro['fprecios'] = $fprecios;
				}

				$this->session->set_userdata('filtro_ofertas', $filtro);
			}
			else if($iModo === '2'){
				$filtro = ($this->session->userdata('filtro_ofertas') === false) ? array() : $this->session->userdata('filtro_ofertas');
			}

			$filtro['pagina'] = $iPagina;
			$filtro['datosPorPagina'] = 10;

			$ofertas = $this->ofertas_model->getOfertas($filtro);
			$numOfertas = $this->ofertas_model->getNumOfertas();

			//Creamos el módulo de paginación para las ofertas
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

			$data['opc'] = 'ofertas';
			$data['tipos_medio'] = $this->medios_model->getTiposMedios();
			$data['ofertas'] = $ofertas;
			$data['numOfertas'] = $numOfertas;
			$data['filtro'] = $filtro;
			$this->load->view('agencias/lista_ofertas', array_merge($data));
		}

		/**
		 * Muestra el formulario de creacion de un nuevo anunciante
		 *
		 * @param integer $modo Modo de validacion: 0 no valida, 1 si
		 */
		function nuevoAnunciante($modo = 0){
			$this->load->library('form_validation');
			$this->load->model('administrador_model');
			$this->load->model('clientes_model');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$data['opc'] = 'anunciantes';

			if($modo == 0){
				$data['page'] = 'agencias/nuevo_anunciante';
				$this->load->view('default_agencia', array_merge($data));
			}
			else{
				$this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico');
				$this->form_validation->set_rules('web', 'Web', 'trim|required');

				//Mensajes de error
				$this->form_validation->set_message('required', '%s es un dato necesario.');
				$this->form_validation->set_message('emailEsUnico', 'Este email ya está siendo utilizado, por favor utilice otro.');
				$this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');

				//Formato del contenedor del error
				$this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

				//Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
				if ($this->form_validation->run() == FALSE){
					$data['validado'] = true;
					$data['page'] = 'agencias/nuevo_anunciante';
					$this->load->view('default_agencia', array_merge($data));
				}
				else{
					$pass = $this->generarCodigo();

					$datos_usuario = array(
						'nick' => $this->input->post('email'),
						'pass' => md5($pass),
						'tipo_usuario' => 'cliente',
						'fecha_registro' => date("Y-m-d H:i:s"),
						'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
						'estado' => 1
					);

					$id_usuario = $this->administrador_model->insertUsuario($datos_usuario);

					$datos_cliente = array(
						'nombre' => $this->input->post('nombre'),
						'nombre_contacto' => '',
						'apellidos_contacto' => '',
						'telefono' => $this->input->post('telefono'),
						'direccion' => '',
						'poblacion' => '',
						'cp' => '',
						'cif' => '',
						'id_pais' => 1,
						'id_provincia' => 1,
						'email' => $this->input->post('email'),
						'web' => $this->input->post('web'),
						'id_usuario' => $id_usuario,
						'condiciones' => 1,
						'id_agencia' => $this->session->userdata('id_agencia')
					);

					$id_cliente = $this->clientes_model->insertCliente($datos_cliente);

					redirect('agencias/editarAnunciante/' . $id_cliente);
				}
			}
		}

		/**
		 * Muestra los detalles de la oferta especificada
		 *
		 * @param integer $id_oferta Id de la oferta a mostrar en detalle
		 */
		function oferta($id_oferta){
			$this->load->model('ofertas_model');

			if($this->session->userdata('tipo_usuario') != 'agencia'){
				redirect('inicio/index/' . $id_oferta);
			}

			$oferta = $this->ofertas_model->getOferta($id_oferta);

			$data['opc'] = 'ofertas';
			$data['oferta'] = $oferta;
			$data['page'] = 'agencias/oferta';
			$this->load->view('default_agencia',array_merge($data));
		}

		/**
		 * Muestra la lista de ofertas resultante de la busqueda realizada por la agencia
		 *
		 * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * @param integer $pagina Pagina a mostrar del listado de ofertas
		 */
		function ofertas(){
			$this->load->model('ofertas_model');
			$this->load->model('medios_model');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$precios = $this->ofertas_model->getMaxMinPreciosOfertas();

			$data['opc'] = 'ofertas';
			$data['precios'] = $precios;
			$data['tipos_medio'] = $this->medios_model->getTiposMedios();
			$data['page'] = 'agencias/ofertas';
			$this->load->view('default_agencia', array_merge($data));
		}

		/**
		 * Muestra el formulario de edicion de los datos de perfil de la agencia logueada
		 *
		 * @param integer $modo Modo de validacion: 0 no valida, 1 si
		 */
		function perfil($modo = 0){
			$this->load->model('agencias_model');
			$this->load->library('form_validation');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			$agencia = $this->agencias_model->getAgencia($this->session->userdata('id_agencia'));

			$data['opc'] = 'perfil';

			if($modo != 0){
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
				$this->form_validation->set_rules('direccion', 'Dirección', 'trim|required');
				$this->form_validation->set_rules('cp', 'Código Postal', 'trim|required');
				$this->form_validation->set_rules('cif', 'CIF', 'trim|required');
				$this->form_validation->set_rules('poblacion', 'Población', 'trim|required');

				//Mensajes de error
				$this->form_validation->set_message('required', '%s es un dato necesario.');

				//Formato del contenedor del error
				$this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

				//Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
				if ($this->form_validation->run()){
					$datos_agencia = array(
						'nombre' => $this->input->post('nombre'),
						'telefono' => $this->input->post('telefono'),
						'direccion' => $this->input->post('direccion'),
						'cp' => $this->input->post('cp'),
						'cif' => $this->input->post('cif'),
						'poblacion' => $this->input->post('poblacion')
					);

					$this->agencias_model->updateAgencia($this->session->userdata('id_agencia'), $datos_agencia);
					$this->session->set_flashdata('correcto', 'Los datos han sido actializados correctamente');

					redirect('agencias/perfil');
				}
			}
			$data['correcto'] = $this->session->flashdata('correcto');
			$data['agencia'] = $agencia;
			$data['page'] = 'agencias/perfil';
			$this->load->view('default_agencia', array_merge($data));
		}

		/**
		 * Muestra el listado de permisos para los medios del anunciante especificado
		 *
		 * @param integer $id_cliente Id del cliente por el que filtrar los permisos
		 * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
		 * @param integer $pagina Pagina a mostrar de los permisos
		 */
		function permisosAnunciante($id_cliente, $modo = '0', $pagina = 1){
			$this->load->model('clientes_model');
			$this->load->library('pagination');
			$this->load->model('medios_model');

			if($this->session->userdata('tipo_usuario') != 'agencia')
				redirect('inicio');

			if($modo === '0'){
				$filtro = array();

				$this->session->set_userdata('filtro_permisos', $filtro);
			}
			else if($modo === '1'){
				$filtro['tipo_medio'] = $this->input->post('tipo_medio');
				$filtro['estado'] = $this->input->post('estado');

				$this->session->set_userdata('filtro_permisos', $filtro);
			}
			else if($modo === '2'){
				$filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
			}

			$filtro['pagina'] = $pagina;
			$filtro['datosPorPagina'] = 15;
			$filtro['cliente'] = $id_cliente;

			$permisos = $this->clientes_model->getPermisosCliente($filtro);
			$numPermisos = $this->clientes_model->getNumPermisosCliente();

			$config = array();
			$config['use_page_numbers'] = TRUE;
			$config["base_url"] = base_url() . "agencias/permisosAnunciante/" . $id_cliente . '/2';
			$config["total_rows"] = $numPermisos;
			$config["per_page"] = $filtro['datosPorPagina'];
			$config["uri_segment"] = 5;

			$this->pagination->initialize($config);

			$data["paginacion"] = $this->pagination->create_links();

			$data['filtro'] = $filtro;
			$data['opc'] = 'anunciantes';
			$data['cliente'] = $this->clientes_model->getClienteAdmin($id_cliente);
			$data['permisos'] = $permisos;
			$data['tipos_medios'] = $this->medios_model->getTiposMedios();
			$data['page'] = 'agencias/permisos_anunciante';
			$this->load->view('default_agencia', array_merge($data));
		}
	}
