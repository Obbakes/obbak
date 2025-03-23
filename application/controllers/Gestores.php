<?php 
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');
	
    require_once('BaseController.php');

	class Gestores extends BaseController {
            function __construct() {
                    parent::__construct();
            }
                
                
            /**
             * Muestra el formulario de cambio de contraseña
             *
             * @param integer $modo Modo de validacion: 0 no valida, 1 si
             */
            function cambiarPass($modo = 0){
                
                if($this->session->userdata('tipo_usuario') != 'gestor')
                        redirect('inicio');
                
                $this->load->model('administrador_model');
                $this->load->library('form_validation');


                $data['opc'] = 'perfil';

                if($modo != 0){
                        $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required');
                        $this->form_validation->set_rules('pass_conf', 'Repetición de la contraseña', 'trim|matches[pass]');

                        //Mensajes de error
                        $this->form_validation->set_message('required', '%s es un dato necesario.');
                        $this->form_validation->set_message('matches', 'las contraseñas no coinciden.');

                        //Formato del contenedor del error
                        $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

                        //Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario
                        if ($this->form_validation->run() == true){
                                $datos_usuario = array(
                                        'pass' => md5($this->input->post('pass'))
                                );

                                $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);
                                $this->session->set_flashdata('correcto', 'Su contraseña ha sido modificada correctamente');
                                redirect('gestores/editarPerfil/');
                        }
                }
                
                $data['page'] = 'gestores/cambiarPass';
                $this->load->view('gestores/gestor_container', array_merge($data));
            }
            
            /**
            * Modifica los permisos para los medios para el cliente especificado
            *
            * Parametro Post:
            * array cambios - Array con los cambios a llevar a cabo: posicion 2k id del medio, posicion 2k+1 estado final del permiso
            *
            * @param integer $id_cliente Id del anunciante para el que modificar los permisos
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

                   $this->clientes_model->cambiarPermisosAnuncianteAdmin($id_cliente, $permisos);

                   redirect('gestores/permisosAnunciante/' . $id_cliente . '/2/' . $this->input->post('pagina'));
            }
            
                
            /**
             * Modifica los permisos de los anunciantes para el medio especificado
             *
             * Parametro Post:
             * array cambios - Array con los cambios a llevar a cabo: posicion 2k id del cliente, posicion 2k+1 estado final del permiso
             *
             * @param integer $id_medio Id del medio para el que modificar los permisos
             */
            function cambiarPermisosMedio($id_medio){
                
                    if($this->session->userdata('tipo_usuario') != 'gestor')
                        redirect('inicio');
                    
                    $this->load->model('clientes_model');

                    $permisos = array();
                    $cambios = $this->input->post('cambios');

                    $cambios = explode(' ', $cambios);

                    if(!empty($cambios)){
                            for($i = 0; $i < count($cambios); $i += 2){
                                    $permisos[$cambios[$i]] = $cambios[($i + 1)];
                            }
                    }

                    $this->clientes_model->cambiarPermisosMedioAdmin($id_medio, $permisos);

                    redirect('gestores/permisosMedio/' . $id_medio . '/2/' . $this->input->post('pagina'));
            }
            
            /**
            * Funcion de conexion entre los botones del panel de control para anunciantes y las vistas con filtros necesarios
            *
            * @param integer $opcion Opcion de filtrado de anunciantes a la que dirigir
            */
           function conexionListadoAnunciantes($opcion = 0){
                   if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('inicio');

                   $filtro = array();

                   if($opcion == 1){ //anunciantes pendientes de aceptacion
                           $filtro['estado'] = 'pendiente';
                   }
                   else if($opcion == 2){ //anunciantes con permisos pendientes de aceptacion
                           $filtro['estado'] = 3;
                   }

                   $this->session->set_userdata('filtro_permisos', $filtro);

                   redirect('medios/permisosMedio/'.$this->session->userdata('id_medio').'/2/1');
           }
            
            /**
		 * Da de alta o baja los medios especificados (post)
		 *
		 * Parametros Post:
		 * array medios - Array las altas/bajas a llevar a cabo: posicion 2k id del medio, posicion 2k+1 dar de alta o baja
		 */
		function darAltaMedios(){
                        if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('inicio');
			$this->load->model('medios_model');
			$altas = array();
			$cambios = $this->input->post('cambios');

			$cambios = explode(' ', $cambios);

			if(!empty($cambios)){
				for($i = 0; $i < count($cambios); $i += 2){
					$altas[$cambios[$i]] = $cambios[($i + 1)];
				}
			}

			$this->medios_model->darAltaMedios($altas);

			redirect('gestores/medios/2/' . $this->input->post('pagina'));
		}
           
            /**
            * Elimina la imagen del medio especificado
            *
            * @param integer $id_medio Id del medio
            */
            function deleteImagenMedio($id_medio){
                    if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('gestores/medios');
                    $this->load->model('medios_model');

                    $medio = $this->medios_model->getMedio($id_medio);

                    if(!empty($medio->imagen) && $medio->imagen != 'images/medios/medio_default.png'){
                           if(file_exists($medio->imagen)) {
                                   unlink($medio->imagen);
                           }

                           $datosMedio = array(
                                   'imagen' => 'images/medios/medio_default.png'
                           );

                           $this->medios_model->updateMedio($id_medio, $datosMedio);
                    }
            }

            /**
            * Elimina el logo del medio especificado
            *
            * @param integer $id_medio Id del medio
            */
            function deleteLogoMedio($id_medio){
                    if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('gestores/medios');
                    
                    $this->load->model('medios_model');

                    $medio = $this->medios_model->getMedio($id_medio);

                    if(!empty($medio->logo) && $medio->logo != 'images/medios/logo/medio_logo_default.png'){
                           if(file_exists($medio->logo)) {
                                   unlink($medio->logo);
                           }

                           $datosMedio = array(
                                   'logo' => 'images/medios/logo/medio_logo_default.png'
                           );

                           $this->medios_model->updateMedio($id_medio, $datosMedio);
                    }
            }
                
            /**
            * Muestra el formulario de edicion del medio especificado
            *
            * @param integer $id_medio Id del medio a editar
            * @param integer $modo Modo de validacion: 0 no valida, 1 si
            */
            function editarMedio($id_medio  = 0, $modo = 0){
                    if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('inicio');
                    
                    $this->load->model('medios_model');
                    $this->load->library('form_validation');
                    $this->load->library('upload');
                    $this->load->library('subida');

                    
                    $data['opc'] = 'perfil';
                    $medio = $this->medios_model->getMedioGestor($id_medio);
                    $data['tipos_medios'] = $this->medios_model->getTiposMedios();
                    
                    if (!$medio){
                        redirect('gestores/medios');
                    }else{
                        if($modo == 0){
                                $data['medio'] = $medio;
                                $data['correcto'] = $this->session->flashdata('correcto');
                                $data['page'] = 'gestores/editar_medio';
                                $this->load->view('gestores/gestor_container', array_merge($data));
                        }
                        else{
                                $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[255]|required');
                                $this->form_validation->set_rules('descripcion', 'Descripción breve', 'trim|required|max_length[2000]');
                                $this->form_validation->set_rules('tipo_medio', 'Tipo medio', 'trim|required');
                                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                                if($this->input->post('web') !== ''){
                                        $this->form_validation->set_rules('web', 'Web', 'trim|callback_urlCheck');
                                }

                                //Mensajes de error
                                $this->form_validation->set_message('required', '%s es un dato necesario.');
                                $this->form_validation->set_message('comprobarEmail', 'El email tiene un formato incorrecto.');
                                $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
                                $this->form_validation->set_message('urlCheck', 'La página web tiene un formato incorrecto.');

                                //Formato del contenedor del error
                                $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

                                //Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
                                if ($this->form_validation->run() == FALSE){
                                        $data['validado'] = true;
                                        $data['medio'] = $medio;
                                        $data['page'] = 'gestores/editar_medio';
                                        $this->load->view('gestores/gestor_container', array_merge($data));
                                }
                                else{
                                        $error = '';
                                        $archivo = '';
                                        $logo = '';

                                        if(!empty($_FILES['imagen']['name'])){
                                                $extension = explode('.', $_FILES['imagen']['name']);
                                                $extension = $extension[count($extension) - 1];

                                                //Elaboramos un titulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta
                                                $nombre_fichero = '';

                                                $archivo = $this->subida->uploadImagen('imagen', 'images/medios', $nombre_fichero, $extension);

                                                //si no....
                                                switch($archivo){
                                                        case - 1:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">Formato incorrecto</span>';
                                                                break;
                                                        case - 2:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">No se completo la subida</span>';
                                                                break;
                                                        case - 3:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">Directorio de destino inaccesible</span>';
                                                                break;
                                                        default: // no ha habido errores o error no controlado
                                                                $archivo = $archivo['archivo'];
                                                                break;
                                                }
                                        }

                                        if(!empty($error)){
                                                $data['validado'] = true;
                                                $data['error_imagen'] = $error;
                                                $data['page'] = 'gestores/editar_medio';
                                                $this->load->view('gestores/gestor_container', array_merge($data));
                                                return;
                                        }
                                        if(empty($error)){
                                                if($this->input->post('defectoImage') == 'defecto'){
                                                        $archivo = 'images/iconos/news1.png';
                                                }
                                        }

                                        if(!empty($_FILES['logo']['name'])){
                                                $extension = explode('.', $_FILES['logo']['name']);
                                                $extension = $extension[count($extension) - 1];

                                                //Elaboramos un t�tulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta
                                                $nombre_fichero = '';

                                                $logo = $this->subida->uploadImagen('logo', 'images/medios/logo', $nombre_fichero, $extension);

                                                //si no....
                                                switch($logo){
                                                        case - 1:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">Formato incorrecto</span>';
                                                                break;
                                                        case - 2:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">No se completo la subida</span>';
                                                                break;
                                                        case - 3:
                                                                $error = '<span style="font-size: 10pt; font-weight: bold;color:red;">Directorio de destino inaccesible</span>';
                                                                break;
                                                        default: // no ha habido errores o error no controlado
                                                                $logo = $logo['archivo'];
                                                                break;
                                                }
                                        }

                                        if(!empty($error)){
                                                $data['validado'] = true;
                                                $data['error_logo'] = $error;
                                                $data['page'] = 'gestores/editar_medio';
                                                $this->load->view('gestores/gestor_container', array_merge($data));
                                                return;
                                        }

                                        if(empty($error)){
                                                if($this->input->post('defectoLogo') == 'defecto'){
                                                        $logo = 'images/iconos/news1.png';
                                                }
                                        }

                                        $datos_medio = array(
                                                'nombre' => $this->input->post('nombre'),
                                                'descripcion' => nl2br($this->input->post('descripcion')),
                                                'web' => $this->input->post('web'),
                                                'email' => $this->input->post('email'),
                                                'id_tipo_medio' => $this->input->post('tipo_medio')
                                        );

                                        if(!empty($archivo))
                                                $datos_medio['imagen'] = $archivo;

                                        if(!empty($logo))
                                                $datos_medio['logo'] = $logo;

                                        $this->medios_model->updateMedio($id_medio, $datos_medio);
                                        $this->session->set_flashdata('correcto', 'Se han guardado los datos correctamente');

                                        redirect('gestores/editarMedio/' . $id_medio);
                                }
                        }
                    }
            }
            
            /**
            * Muestra el formulario de edicion del gestor de medios especificado
            *
            * @param integer $id_gestor Id del gestor de medios a editar
            * @param integer $modo Modo de validacion: 0 no valida, 1 si
            */
            function editarPerfil($modo = 0){
                   

                   if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('inicio');
                   
                   $this->load->model('gestores_model');
                   $this->load->library('form_validation');

                   $gestor = $this->gestores_model->getGestor($this->session->userdata('id_gestor'));

                   $data['opc'] = 'perfil';
                   $data['mensajeNuevoGestor'] = $this->session->flashdata('mensajeNuevoGestor');

                   if($modo == 0){

                           $data['gestor'] = $gestor;
                           $data['page'] = 'gestores/editar_perfil';
                           $this->load->view('gestores/gestor_container', array_merge($data));
                   }
                   else{
                           $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');				
                           $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico[' . $gestor->id_usuario . ']|required');

                           //Mensajes de error
                           $this->form_validation->set_message('required', '%s es un dato necesario.');
                           $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
                           $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
                           $this->form_validation->set_message('emailEsUnico', 'Este email ya est&aacute; siendo utilizado, por favor utilice otro.');

                           //Formato del contenedor del error
                           $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');

                           //Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
                           if ($this->form_validation->run() == FALSE){
                                   $data['validado'] = true;
                                   $data['gestor'] = $gestor;
                                   $data['page'] = 'gestores/editar_perfil';
                                   $this->load->view('gestores/gestor_container', array_merge($data));
                           }
                           else{
                                   $datos_gestor = array(
                                           'nombre' => $this->input->post('nombre'),						
                                           'email' => $this->input->post('email')
                                   );

                                   $this->gestores_model->updateGestor($this->session->userdata('id_gestor'), $datos_gestor);

                                   if($gestor->email != $this->input->post('email')){
                                           //TODO: enviar email de cambio de datos
                                   }

                                   $this->session->set_flashdata('mensajeNuevoGestor', 'Los datos del perfil se han actualizado correctamente.');

                                   redirect('gestores/editarPerfil/' . $this->session->userdata('id_gestor'));
                           }
                   }
            }
            
            /**
             * Muestra el panel de control del medio
             */
            function index(){
                    $this->load->model('gestores_model');

                    if($this->session->userdata('tipo_usuario') == 'gestor'){

                            $data['usuarios_permisos_pendientes'] = $this->gestores_model->getNumUsuariosPermisosPendientes($this->session->userdata('id_gestor'));

                            $data['opc'] = 'home';
                            $data['page'] = 'gestores/panel_control';
                            $this->load->view('gestores/gestor_container', array_merge($data));
                    }
                    else{
                             redirect('/inicio/index/');
                    }
            }

            /**
             * Obtiene la descripción del medio especificado
             * 
             * @param intenger $id_medio Id del medio para el que obtener la descripcion
             */
            function obtener_descripcion_medio($id_medio){
                    $this->load->model('medios_model');

                    $medio = $this->medios_model->getMedio($id_medio);

                    if(empty($medio))
                            echo '';
                    else
                            echo $medio->descripcion;
            }
            
            /**
            * Muestra el listado de medios
            *
            * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
            * @param integer $pagina Pagina a mostrar de los medios
            */
            function medios($modo = '0', $pagina = 1, $datosporpagina = 15){
                   if($this->session->userdata('tipo_usuario') != 'gestor')
                           redirect('inicio');
                
                    $this->load->model('medios_model');
                    $this->load->library('pagination');

                   if($modo === '0'){
                           $filtro = array();

                           //Por defecto siempre mostramos los dados de alta
                           $filtro['estado'] = 1;
                           $this->session->set_userdata('filtro_medios', $filtro);
                   }
                   else if($modo === '1'){
                           $filtro['medio'] = $this->input->post('medio');
                           $filtro['estado'] = $this->input->post('estado');
                           $filtro['permisos'] = $this->input->post('permisos');
                           $filtro['tipo_medio'] = $this->input->post('tipo_medio');

                           $this->session->set_userdata('filtro_medios', $filtro);
                   }
                   else if($modo === '2'){
                           $filtro = ($this->session->userdata('filtro_medios') === false) ? array() : $this->session->userdata('filtro_medios');
                   }

                   $filtro['pagina'] = $pagina;
                   $filtro['datosPorPagina'] = $datosporpagina;

                   $medios = $this->medios_model->getMediosGestor($filtro);
                   $numMedios = $this->medios_model->getNumMediosGestor();

                   $config = array();
                   $config['use_page_numbers'] = TRUE;
                   $config["base_url"] = base_url() . "gestores/medios/2";
                   $config["total_rows"] = $numMedios;
                   $config["per_page"] = $filtro['datosPorPagina'];
                   $config["uri_segment"] = 4;

                   $this->pagination->initialize($config);

                   $data["paginacion"] = $this->pagination->create_links();

                   $data['opc'] = 'medios';
                   $data['tipos_medios'] = $this->medios_model->getTiposMedios();
                   $data['medios'] = $medios;
                   $data['total_medios'] = $numMedios;
                   $data['filtro'] = $filtro;
                   $data['page'] = 'gestores/medios';
                   $this->load->view('gestores/gestor_container', array_merge($data));
            }

            /**
             * Muestra la lista de medios asociados al tipo de medio pasada por post
             * 
             * Parametros Post:
             * array tipos - Array con los id de los tipos de medios a obtener
             */
            function obtenerMediosFiltro(){
                    $this->load->model('medios_model');

                    $tipos = trim($this->input->post('tipos'));

                    $tipos_medios = (empty($tipos)) ? array() : explode(' ', $tipos);

                    $filtro['tipo_medio'] = $tipos_medios;
                    $filtro['id_cliente'] = $this->session->userdata('id_cliente');

                    $data['medios'] = $this->medios_model->getMediosFiltro($filtro);
                    $data['seleccionados'] = ($this->input->post('seleccionados') == '') ? array() : explode(' ', $this->input->post('seleccionados'));

                    $this->load->view('medios/lista_medios', $data);
            }
            
            /**
            * Muestra el listado de permisos para medios del anunciante especificado
            *
            * @param integer $id_cliente Id del anunciante por el que filtrar los permisos
            * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
            * @param integer $pagina Pagina a mostrar de los permisos
            */
           function permisosAnunciante($id_cliente, $modo = '0', $pagina = 1){
               $this->load->model('clientes_model');
               $this->load->library('pagination');
               $this->load->model('medios_model');

               if($this->session->userdata('tipo_usuario') != 'gestor')
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

               $permisos = $this->clientes_model->getPermisosClienteGestor($filtro);
               $numPermisos = $this->clientes_model->getNumPermisosClienteGestor();

               $config = array();
               $config['use_page_numbers'] = TRUE;
               $config["base_url"] = base_url() . "gestores/permisosAnunciante/" . $id_cliente . "/2";
               $config["total_rows"] = $numPermisos;
               $config["per_page"] = $filtro['datosPorPagina'];;
               $config["uri_segment"] = 5;

               $this->pagination->initialize($config);

               $data["paginacion"] = $this->pagination->create_links();

               $data['opc'] = 'anunciantes';
               $data['filtro'] = $filtro;
               $data['cliente'] = $this->clientes_model->getClienteAdmin($id_cliente);
               $data['permisos'] = $permisos;
               $data['tipos_medios'] = $this->medios_model->getTiposMedios();
               $data['page'] = 'gestores/permisos_anunciante';
               $this->load->view('gestores/gestor_container', array_merge($data));
            }

            /**
             * Muestra el listado de permisos de los anunciantes del Gestor
             * 
             * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
             * @param integer $pagina Pagina a mostrar de los permisos
             */
            function permisosAnunciantes($modo = '0', $pagina = 1, $datosporpagina = 15){
                    

                    if($this->session->userdata('tipo_usuario') != 'gestor')
                            redirect('inicio');
                                        
                    $this->load->model('clientes_model');
                    $this->load->model('medios_model');
                    $this->load->library('pagination');
                    
                    
                    $filtro['estado'] = 1;
                    $numMedios = $this->medios_model->getNumMediosGestor();
                    
                    if (!$numMedios && $numMedios<=0){
                        
                        redirect('gestores/medios');  
                    }else if ($numMedios==1){
                        $filtro['estado'] = 1;
                        $medios = $this->medios_model->getMediosGestor($filtro); 
                        
                        redirect('gestores/permisosMedio/'. $medios[0]->id_medio);
                    }else{
                    
                        
                        if($modo === '0'){
                                $filtro = array();

                                $this->session->set_userdata('filtro_permisos', $filtro);
                        }
                        else if($modo === '1'){
                                $filtro['anunciante'] = $this->input->post('anunciante');
                                $filtro['provincia'] = $this->input->post('provincia');

                                $this->session->set_userdata('filtro_permisos', $filtro);
                        }
                        else if($modo === '2'){
                                $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
                        }

                        
                        $filtro['pagina'] = $pagina;
                        $filtro['datosPorPagina'] = $datosporpagina;

                        $permisos = $this->clientes_model->getPermisosAnunciantesGestor($filtro);
                                                
                        $numPermisos = $this->clientes_model->getNumPermisosMedio();

                        $config = array();
                        $config['use_page_numbers'] = TRUE;
                        $config["base_url"] = base_url() . "gestores/permisosAnunciantes/2";
                        $config["total_rows"] = $numPermisos;
                        $config["per_page"] = $filtro['datosPorPagina'];
                        $config["uri_segment"] = 5;

                        $this->pagination->initialize($config);

                        $data["paginacion"] = $this->pagination->create_links();

                        $data['opc'] = 'anunciantes';
                        $data['filtro'] = $filtro;
                        $data['permisos'] = $permisos;
                        $data['total_anunciantes'] = $numPermisos;
                        $data['page'] = 'gestores/permisos_anunciantes';

                        $data['provincias'] = $this->clientes_model->getProvincias();

                        $this->load->view('gestores/gestor_container', array_merge($data));
                    }
            }
            
            /**
             * Muestra el listado de permisos de los anunciantes para los medios especificados
             *
             * @param integer $id_medio Id del medio por el que filtrar los permisos
             * @param integer $modo Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
             * @param integer $pagina Pagina a mostrar de los permisos
             */
            function permisosMedio($id_medio, $modo = '0', $pagina = 1, $datosporpagina = 15){
                    

                    if($this->session->userdata('tipo_usuario') != 'gestor')
                            redirect('inicio');
                                        
                    $this->load->model('clientes_model');
                    $this->load->model('medios_model');
                    $this->load->library('pagination');
                    
                    $medio = $this->medios_model->getMedioGestor($id_medio);
                                        
                    if (!$medio)
                        redirect('gestores/medios');

                    if($modo === '0'){
                            $filtro = array();

                            $this->session->set_userdata('filtro_permisos', $filtro);
                    }
                    else if($modo === '1'){
                            $filtro['estado'] = $this->input->post('estado');
                            $filtro['anunciante'] = $this->input->post('anunciante');
                            $filtro['provincia'] = $this->input->post('provincia');

                            $this->session->set_userdata('filtro_permisos', $filtro);
                    }
                    else if($modo === '2'){
                            $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
                    }

                    $filtro['pagina'] = $pagina;
                    $filtro['datosPorPagina'] = $datosporpagina;
                    $filtro['medio'] = $id_medio;

                    $permisos = $this->clientes_model->getPermisosMedio($filtro);
                    $numPermisos = $this->clientes_model->getNumPermisosMedio();

                    $config = array();
                    $config['use_page_numbers'] = TRUE;
                    $config["base_url"] = base_url() . "gestores/permisosMedio/" . $id_medio . "/2";
                    $config["total_rows"] = $numPermisos;
                    $config["per_page"] = $filtro['datosPorPagina'];
                    $config["uri_segment"] = 5;

                    $this->pagination->initialize($config);

                    $data["paginacion"] = $this->pagination->create_links();

                    $data['opc'] = 'medios';
                    $data['filtro'] = $filtro;
                    $data['medio'] = $this->medios_model->getMedio($id_medio);
                    $data['permisos'] = $permisos;
                    $data['page'] = 'gestores/permisos_medio';
                    
                    $data['provincias'] = $this->clientes_model->getProvincias();
                    
                    $this->load->view('gestores/gestor_container', array_merge($data));
            }
	}
?>