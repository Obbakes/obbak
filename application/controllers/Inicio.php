<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('BaseController.php');
class Inicio extends BaseController
{

    private $emailOficial = '';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra la vista de informacion de un anuncio de marca para emprendedores
     */
    function anuncio_marca_emprendedores()
    {
        $data['page'] = "inicio/anuncio-marca-emprendedores";
        $data['title'] = "Anuncio Periódico MARCA Emprendedores";
        $data['confirmacion'] = '';
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Muestra la vista de informacion de campañas
     */
    function campanias()
    {
        $data['page'] = "inicio/campanias";
        $data['title'] = "Campañas";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Comprueba que el captcha ha sido introducido correctamente por el usuario, funcion de validacion de formulario
     *
     * @param string $val
     *            Valor introducido para el captcha
     * @return boolean true si fue introducido correctamente, false si no
     */
    function check_captcha($val)
    {
        $this->load->library('recaptcha');
        $this->lang->load('recaptcha');
        
        if ($this->recaptcha->check_answer($this->input->ip_address(), $this->input->post('recaptcha_challenge_field'), $val)) {
            return true;
        }
        
        $this->form_validation->set_message('check_captcha', $this->lang->line('recaptcha_incorrect_response'));
        
        return false;
    }

    /**
     * Comprueba que el formato del email es correcto, funcion de validacion de formulario
     *
     * @param string $email
     *            Email a comprobar
     * @return boolean true si es correcto, false si no
     */
    public function comprobarEmail($email)
    {
        $aux = str_replace('-', '', $email);
        
        if (preg_match('/^[a-z][\w.-]+@\w[\w.-]+\.[\w.-]*[a-z][a-z]$/', $aux))
            return true;
        
        return false;
    }

    /**
     * Comprueba que el código promocional introducido sea correcto
     *
     * @param string $codigo
     *            Código a comprobar
     * @return boolean true si es correcto, false si no
     */
    public function comprobarCodigo($codigo)
    {
        if ($codigo == 'SME_17' || $codigo == '') {
            return true;
        } else {
            return false;
        }
    }

    public function noExisteEmail($email)
    {
        $this->load->model('clientes_model');
        if (! $this->clientes_model->getClienteByEmail($email)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Muestra las condiciones que un usuario debe aceptar antes de acceder a la pagina
     */
    function condiciones()
    {
        if ($this->session->userdata('logged_in')) {
            
            $this->load->library('form_validation');
            $this->load->model('inicio_model');
            
            $this->form_validation->set_rules('checkcondiciones', 'checkcondiciones', 'required');
            $this->form_validation->set_message('required', 'Debe aceptar las condiciones');
            
            if ($this->form_validation->run() == FALSE) {
                $data['condiciones'] = $this->inicio_model->checkCondiciones();
                $data['page'] = "inicio/condiciones";
                $this->load->view('principal', array_merge($data));
            } else {
                $this->inicio_model->aceptaCondiciones();
                redirect('/inicio/ofertas');
            }
        } else {
            $data['page'] = "inicio/condiciones";
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Muestra el formulario de contacto
     */
    function contacto($codigo = '')
    {
        $this->load->model('newsletters_model');
        
        $newsletter = $this->newsletters_model->getNewsletterByCodigo($codigo);
        
        if (! empty($newsletter) && ! empty($codigo)) {
            $iIdCliente = $newsletter->id_generico;
            $iIdNewsletter = $newsletter->id_newsletter;
            
            $datosEstadisticaNewsletter = array(
                'id_newsletter' => $iIdNewsletter,
                'id_cliente' => $iIdCliente,
                'tipo_acceso' => 'c',
                'fecha' => date('Y-m-d H:i:s')
            );
            
            $this->newsletters_model->insertEstadisticaNewsletter($datosEstadisticaNewsletter);
        }
        
        $this->load->library('recaptcha');
        $this->lang->load('recaptcha');
        
        $data['recaptcha'] = $this->recaptcha->get_html();
        $data['title'] = "Contacto";
        $data['page'] = "inicio/contacto";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Muestra el formulario de contacto
     */
    function contactoBaja($codigo = '')
    {
        $this->load->model('newsletters_model');
        
        $newsletter = $this->newsletters_model->getNewsletterByCodigo($codigo);
        
        if (! empty($newsletter) && ! empty($codigo)) {
            $iIdCliente = $newsletter->id_generico;
            $iIdNewsletter = $newsletter->id_newsletter;
            
            $data['nombre_cli'] = $newsletter->nombre;
            $data['email_cli'] = $newsletter->email;
            
            $datosEstadisticaNewsletter = array(
                'id_newsletter' => $iIdNewsletter,
                'id_cliente' => $iIdCliente,
                'tipo_acceso' => 'c',
                'fecha' => date('Y-m-d H:i:s')
            );
            
            $this->newsletters_model->insertEstadisticaNewsletter($datosEstadisticaNewsletter);
        }
        
        $this->load->library('recaptcha');
        $this->lang->load('recaptcha');
        
        $data['recaptcha'] = $this->recaptcha->get_html();
        $data['title'] = "Solicitud de Baja";
        $data['page'] = "inicio/contacto-baja";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Realiza el envío del formulario de la página de inicio
     */
    function contactoInicio()
    {
        
        // Check for empty fields
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['message']) || ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "No arguments Provided!";
            return false;
        }
        
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $email_address = strip_tags(htmlspecialchars($_POST['email']));
        $phone = strip_tags(htmlspecialchars($_POST['phone']));
        $message = strip_tags(htmlspecialchars($_POST['message']));
        
        // Create the email and send the message
        $email_subject = "Contacto desde la Web Obbak:  $name";
        $email_body = "Has recibido un nuevo mensaje desde la web.\n\n" . "Aquí tiene los detalles:\n\nNombre: $name\n\nEmail: $email_address\n\nTlf: $phone\n\nMensaje:\n$message";
        
        $this->load->library('email');
        $this->email->from($email_address);
        $this->email->to(EMAIL_OFICIAL);
        
        $this->email->subject($email_subject);
        $this->email->message($email_body);
        
        $this->email->send();
        
        return true;
    }

    /**
     * Comprueba que el formato del codigo postal es correcto, funcion de validacion de formulario
     *
     * @param string $str
     *            Codigo postal a comprobar
     * @return boolean true si es correcto, false si no
     */
    public function cp_check($str)
    {
        $str2 = str_replace('-', '', $str);
        
        if (preg_match('/^[0-9]{5}$/', $str2))
            return true;
        
        return false;
    }

    /**
     * Muestra el formulario de encuesta planifica tu campaña
     */
    function cuantocuestaanunciarse()
    {
        $this->load->library('recaptcha');
        $this->lang->load('recaptcha');
        
        $data['recaptcha'] = $this->recaptcha->get_html();
        $data['titulo'] = "Obbak - Cuánto cuesta anunciarse en Prensa, TV y Radio";
        $data['page'] = "inicio/encuesta_cuanto_cuesta";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Muestra la vista de informacion de el referente
     */
    function elreferente()
    {
        $data['page'] = "inicio/elreferente";
        $data['title'] = "ElReferente";
        $data['confirmacion'] = '';
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Comprueba que el email no esta siendo utilizado por ningun otro usuario, funcion de validacion de formulario
     *
     * @param string $email
     *            Email a comprobar
     * @param string $tipo_email
     *            Tipo de usuario al que estara asociado: cliente, agencia
     * @param integer $id_usuario
     *            Id del usuario a no filtrar en la comprobacion
     * @return boolean true si es correcto, false si no
     */
    public function emailEsUnico($email, $tipo_email = '', $id_usuario = 0)
    {
        $this->load->model('inicio_model');
        echo $email;
        return $this->inicio_model->emailEsUnico($email, $tipo_email, $id_usuario);
    }

    /**
     * Obtiene un codigo de 12 caracteres alfanumericos aleatorio
     *
     * @return string Codigo obtenido
     */
    public function generarCodigo()
    {
        $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codigo;
        
        $codigo = '';
        
        for ($i = 0; $i < 12; $i ++) {
            $codigo .= $pattern[rand(0, strlen($pattern) - 1)];
        }
        
        return $codigo;
    }

    /**
     * Muestra la página de inicio de nolimitsmedia
     *
     * @param intenger $id_oferta
     *            Id de la oferta a la que redireccionar si se especifica una
     * @param intenger $id_origen
     *            Id de la campaña
     */
    function index($id_oferta = 0, $id_origen = 0)
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->session->userdata('tipo_usuario') == 'admin')
                redirect('administrador');
            else if ($this->session->userdata('tipo_usuario') == 'medio')
                redirect('medios');
            else if ($this->session->userdata('tipo_usuario') == 'agencia') {
                if ($id_oferta == 0)
                    redirect('agencias');
                
                redirect('agencias/oferta/' . $id_oferta);
            } else if ($this->session->userdata('tipo_usuario') == 'cliente') {
                if ($id_oferta == 0)
                    redirect('anunciantes');
                
                redirect('anunciantes/oferta/' . $id_oferta);
            }
        }
        
        // Si tiene una campaña de origen, lo guardamos en la sesión para rasterarlo
        if ($id_origen != 0) {
            $newdata = array(
                'id_origen' => $id_origen,
                'tipo_usuario' => 'anonymous',
                'logged_in' => FALSE
            );
            $this->session->set_userdata($newdata);
        }
          redirect("http://obbak.es");
       /* $data['form'] = 'login';
        $data['page'] = "inicio/principal-inicio";
        $data['title'] = "NOT Logged in";
        $data['titulo'] = "Obbak - Las mejores ofertas publicitarias de última hora";
        $data['descripcion'] = '<meta name="description" content="La web donde podrás encontrar las mejores ofertas publicitarias de última hora de los principales medios y soportes publicitarios" />';
        $this->load->view('principal', array_merge($data));
        // redirect('inicio/principal'); //Si no está logueado lo redirigimos al principal de Nolimitsmedia
        // redirect('inicio/login/0/' . $id_oferta); //Si no está logueado lo redirigimos al login
        */
    }

    /**
     * Muestra y verifica el formulario de login
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     * @param integer $id_oferta
     *            Id de la oferta a la que debe redirigir después de realizar el login
     */
    function login($modo = 0, $id_oferta = 0)
    {
        if ($this->session->userdata('logged_in'))
            redirect('inicio/index');
        
        $data['id_oferta'] = $id_oferta;
        
        if ($modo = 0) {
            $this->load->model('clientes_model');
            $data['form'] = 'login';
            $data['page'] = "inicio/default_login";
            $data['descripcion'] = '<meta name="description" content="Login de Obbak" />';
            $data['titulo'] = "Obbak - login";
            $this->load->view('principal', array_merge($data));
        } else {
            $this->load->library('form_validation');
            $this->load->model('inicio_model');
            $this->load->model('clientes_model');
            $this->form_validation->set_rules('email_log', 'Email', 'trim|required');
            $this->form_validation->set_rules('password_log', 'Contraseña', 'trim|required');
            
            $this->form_validation->set_message('required', 'Debe introducir el campo: %s');
            
            if ($this->form_validation->run() == FALSE) {
            	$data['descripcion'] = '<meta name="description" content="Login de Obbak" />';
            	$data['titulo'] = "Obbak - login";
                $data['form'] = 'login';
                $data['page'] = "inicio/default_login";
                $this->load->view('principal', array_merge($data));
            } else {
                $email = $this->input->post('email_log');
                $password = md5($this->input->post('password_log'));
                $result = $this->inicio_model->login($email, $password); // comprobamos que el email y pass existan en la bd
                
                if ($result) {
                    if ($this->session->userdata('estado') == 3) {
                        $this->session->sess_destroy();
                        $data['form'] = 'login';
                        $data['page'] = "inicio/default_login";
                        $data['error_login'] = "Usuario desactivado. Póngase en contacto con nosotros para reactivarlo en info@Obbak.com";
                        $this->load->view('principal', array_merge($data));
                    }                    /*
                     * else if($this->session->userdata('estado') == 1){
                     * $this->session->sess_destroy();
                     * $data['form'] = 'login';
                     * $data['page']="inicio/default_login";
                     * $data['error_login'] = "Estamos valorando su solicitud. Le daremos una respuesta en un plazo máximo de 48 horas.";
                     * $this->load->view('principal',array_merge($data));
                     * }
                     */
                    else if ($this->session->userdata('tipo_usuario') == 'admin') // si es admin le redirigimos
                        redirect('administrador');
                    else {
                        if ($this->session->userdata('tipo_usuario') == 'medio') {
                            redirect('medios/home');
                        } else if ($this->session->userdata('tipo_usuario') == 'gestor') {
                            redirect('gestores/index');
                        } else if ($this->session->userdata('tipo_usuario') == 'cliente') {
                            $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
                            if (empty($cliente->direccion)) {
                                redirect('anunciantes/preferencias');
                                return;
                            }
                            if ($id_oferta == 0)
                                redirect('anunciantes/ofertaspanel');
                            
                            redirect('anunciantes/oferta/' . $id_oferta);
                        } else {
                            if ($id_oferta == 0)
                                redirect('agencias');
                            
                            redirect('agencias/oferta/' . $id_oferta);
                        }
                    }
                } else {
                    $data['form'] = 'login';
                    $data['page'] = "inicio/default_login";
                    $data['error_login'] = "Usuario/contraseña no encontrado en el sistema";
                    $this->load->view('principal', array_merge($data));
                }
            }
        }
    }

    /**
     * Cierra la sesion
     */
    function logout()
    {
        $this->session->sess_destroy();
        $this->index();
    }

    /**
     * Comprueba que el formato del numero de telefono es correcto, funcion de validacion de formulario
     *
     * @param string $str
     *            Telefono a comprobar
     * @return boolean true si es correcto, false si no
     */
    public function number_check($str)
    {
        $str2 = str_replace('-', '', $str);
        
        if (preg_match('/^[0-9]{9}$/', $str2))
            return true;
        
        return false;
    }

    /**
     * Muestra el listado de ofertas de la newsletter para el usuario especificado
     */
    public function ofertasNewsletter($codigo = '', $id_oferta = 0)
    {
        $this->load->model('newsletters_model');
        
        $newsletter = $this->newsletters_model->getNewsletterByCodigo($codigo);
        
        if (empty($newsletter) || empty($codigo))
            redirect('inicio/principal');
        
        $iIdCliente = $newsletter->id_generico;
        $iIdNewsletter = $newsletter->id_newsletter;
        
        if ($id_oferta != 0) {
            
            $datosEstadisticaNewsletter = array(
                'id_newsletter' => $iIdNewsletter,
                'id_cliente' => $iIdCliente,
                'tipo_acceso' => 'v',
                'fecha' => date('Y-m-d H:i:s'),
                'id_oferta' => $id_oferta
            );
        } else {
            $datosEstadisticaNewsletter = array(
                'id_newsletter' => $iIdNewsletter,
                'id_cliente' => $iIdCliente,
                'tipo_acceso' => 'n',
                'fecha' => date('Y-m-d H:i:s')
            );
        }
        
        $bLogueado = false;
        $sUrl = '';
        
        if ($this->session->userdata('logged_in')) {
            $bLogueado = true;
            
            if ($this->session->userdata('tipo_usuario') == 'admin')
                $sUrl = 'administrador';
            else if ($this->session->userdata('tipo_usuario') == 'agencia')
                $sUrl = 'agencias';
            else if ($this->session->userdata('tipo_usuario') == 'cliente')
                $sUrl = 'anunciantes';
        }
        
        $this->newsletters_model->insertEstadisticaNewsletter($datosEstadisticaNewsletter);
        
        $data['ofertas'] = $this->newsletters_model->getOfertasNewsletterCliente($iIdNewsletter, $iIdCliente);
        $data['codigo'] = $codigo;
        $data['bLogueado'] = $bLogueado;
        $data['sUrl'] = $sUrl;
        $data['mensaje_recuperacion'] = $this->session->flashdata('recuperacion_pass_newsletter');
        $data['page'] = "inicio/ofertas_newsletter";
        $data['title'] = "NOT Logged in";
        $data['titulo'] = "Obbak - Ofertas Newsletter";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Muestra la pagina inicial de nolimits media
     */
    function principal()
    {
        if (! $this->session->userdata('logged_in')) {
            $data['form'] = 'login';
            $data['page'] = "inicio/principal-inicio";
            $data['title'] = "NOT Logged in";
            $data['titulo'] = "Obbak";
            $data['descripcion'] = '<meta name="description" content="La web donde podrás encontrar las mejores ofertas publicitarias de última hora de los principales medios y soportes publicitarios" />';
            $this->load->view('principal', array_merge($data));
        } else {
            redirect('inicio/index');
        }
    }

    /**
     * Obtiene una contraseña aleatoria para el usuario que la ha solicitado y la envia a su correo electronico
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     * @param integer $id_oferta
     *            Id de la oferta a la que redirigir después de obtener la contraseña
     */
    function recuperarPass($modo = '0', $id_oferta = 0)
    {
        if ($this->session->userdata('logged_in'))
            redirect('inicio/index');
        
        $data['id_oferta'] = $id_oferta;
        
        if ($modo === '0') {
            $data['error_recuperar'] = $this->session->flashdata('recuperacion_pass');
            $data['form'] = 'recuperar';
            $data['page'] = "inicio/recuperar_pass";
            $this->load->view('principal', array_merge($data));
        } else {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('email_rec', 'Email', 'trim|required');
            
            $this->form_validation->set_message('required', 'Debe introducir el campo: %s');
            
            if ($this->form_validation->run() == FALSE) {
                $data['form'] = 'recuperar';
                $data['page'] = "inicio/recuperar_pass";
                $this->load->view('principal', array_merge($data));
            } else {
                $this->load->model('inicio_model');
                $this->load->model('administrador_model');
                
                $usuario = $this->inicio_model->getUsuarioEmail($this->input->post('email_rec')); // comprobamos que el email y pass existan en la bd
                
                if (! empty($usuario)) {
                    $pass = $this->generarCodigo();
                    
                    $this->load->model('administrador_model');
                    
                    $data_usuario = array(
                        'pass' => md5($pass)
                    );
                    
                    $this->administrador_model->updateUsuario($usuario->id_usuario, $data_usuario);
                    
                    $datos_pass['pass'] = $pass;
                    
                    $this->load->library('email');
                    $this->email->from(EMAIL_OFICIAL, 'Obbak');
                    // $this->email->to($this->input->post('email_rec'));
                    $this->email->to($usuario->email);
                    
                    $this->email->subject('Recuperación de contraseña para Obbak');
                    $this->email->message($this->load->view('email/email_recuperacion_pass', $datos_pass, TRUE));
                    
                    $this->email->send();
                    
                    $this->session->set_flashdata('recuperacion_pass', 'Un email ha sido enviado a su correo');
                    
                    redirect('inicio/recuperarPass/0/' . $id_oferta);
                } else {
                    $data['form'] = 'recuperar';
                    $data['page'] = "inicio/recuperar_pass";
                    $data['error_recuperar'] = "No existe el usuario/contraseña en nuestro sistema";
                    $this->load->view('principal', array_merge($data));
                }
            }
        }
    }

    /**
     * Obtiene una contraseña aleatoria para el usuario que la ha solicitado y la envia a su correo electronico
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     * @param integer $id_oferta
     *            Id de la oferta a la que redirigir después de obtener la contraseña
     */
    function recuperarPassNewsletter($codigo)
    {
        $this->load->model('newsletters_model');
        $newsletter = $this->newsletters_model->getNewsletterByCodigo($codigo);
        
        if (! empty($newsletter)) {
            $pass = $this->generarCodigo();
            
            $this->load->model('administrador_model');
            
            $data_usuario = array(
                'pass' => md5($pass)
            );
            
            $this->administrador_model->updateUsuario($newsletter->id_usuario, $data_usuario);
            
            $datos_pass['pass'] = $pass;
            $datos_pass['email'] = $newsletter->email;
            
            $this->load->library('email');
            $this->email->from(EMAIL_OFICIAL, 'Obbak');
            $this->email->to($newsletter->email);
            
            $this->email->subject('Recuperación de contraseña para Obbak');
            $this->email->message($this->load->view('email/email_recuperacion_pass_newsletter', $datos_pass, TRUE));
            
            $this->email->send();
            
            $this->session->set_flashdata('recuperacion_pass_newsletter', 'Un email ha sido enviado a su correo con su nueva contraseña');
            
            redirect('inicio/ofertasNewsletter/' . $codigo);
        }
    }
    
    /**
     * Comprueba que el nick no esta siendo usado por ningun otro usuario
     *
     * @param string $nick
     *            Email a comprobar
     * @param integer $id_usuario
     *            Id del usuario a excluir de la comprobacion
     * @return boolean true si no esta siendo usado, false si lo esta siendo
     */
    public function nickEsUnico($nick, $id_usuario = 0)
    {
        $this->load->model('inicio_model');
        
        return $this->inicio_model->nickEsUnico($nick, $id_usuario);
    } 
    
    /**
     * Registra a un anunciante o una agencia
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function registration($modo = 0)
    {
        if ($this->session->userdata('logged_in'))
            redirect('inicio/index');
        
        if ($modo == 0) {
            $this->load->model('clientes_model');
            $data['provincias'] = $this->clientes_model->getProvincias();
            $data['sectores'] = $this->clientes_model->getSectores();
            $data['form'] = 'registro';
            $data['page'] = "inicio/registro_cliente";
            $data['title'] = "NOT Logged in";
            $data['titulo'] = "Obbak - Identificación";
            $this->load->view('principal', array_merge($data));
        } else {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('nombre_comercial', 'Nombre Comercial', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('cif', 'CIF', 'trim|required');
            $this->form_validation->set_rules('provincia', 'Provincia', 'trim|required');
            $this->form_validation->set_rules('Fecha_nacimiento', 'Fecha Nacimiento', 'trim|required');
            $this->form_validation->set_rules('telefono', 'telefono', 'trim|callback_number_check');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');
            $this->form_validation->set_rules('checkcondiciones', 'checkcondiciones', 'required');
            $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_noExisteEmail|callback_nickEsUnico');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
            $this->form_validation->set_message('comprobarEmail', 'Formato no válido');
            $this->form_validation->set_message('number_check', 'Formato no válido');
            $this->form_validation->set_message('cp_check', 'Formato no válido');
            $this->form_validation->set_message('emailEsUnico', 'El nombre ya está en uso, prueba con otro');
            $this->form_validation->set_message('noExisteEmail', 'El correo electrónico ya está en uso.');
            $this->form_validation->set_message('nickEsUnico', 'El correo electrónico ya está en uso.');
           
            $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->model('clientes_model');
                $data['provincias'] = $this->clientes_model->getProvincias();
                $data['provincia'] = $this->input->post('provincia');
                $data['form'] = $this->input->post('tipo_usuario');
                $data['page'] = "inicio/registro_cliente";
                $data['title'] = "NOT Logged in";
                $data['titulo'] = "Obbak - Identificación";
                $this->load->view('principal', array_merge($data));
            } else {
                $id = 0;
                
                // Guardamos el id_origen de la campaña
                $id_origen = (empty($this->session->userdata('id_origen')) ? 0 : $this->session->userdata('id_origen'));
                
                $this->load->model('administrador_model');
                
                // Guardamos el código promocional
                $codigo_promo = $this->input->post('codigo_promo');
                
                // Cuando venimos de registro de Agencia
                
                if ($this->input->post('tipo_usuario') == 'agencia') {
                    $this->load->model('agencias_model');
                    
                    $datos_usuario = array(
                        'nick' => $this->input->post('email'),
                        'pass' => md5($this->input->post('password')),
                        'tipo_usuario' => 'agencia',
                        'fecha_registro' => date("Y-m-d H:i:s"),
                        'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                        'id_origen' => $id_origen,
                        'estado' => 1
                    );
                    
                    $id_usuario = $this->administrador_model->insertUsuario($datos_usuario);
                    
                    $datos_agencia = array(
                        'nombre' => $this->input->post('empresa'),
                        'codigo' => '',
                        'email' => $this->input->post('email'),
                        'telefono' => $this->input->post('telefono'),
                        'direccion' => '',
                        'poblacion' => $this->input->post('poblacion'),
                        'cp' => '',
                        'cif' => '',
                        'porcentaje' => 0,
                        'id_provincia' => $this->input->post('provincia'),
                        'id_pais' => 1,
                        'id_usuario' => $id_usuario,
                        'fecha_alta' => date('Y-m-d H:i:s')
                    );
                    
                    $id = $this->agencias_model->insertAgencia($datos_agencia);
                }                // Si es registro de Anunciante
                else {
                    $this->load->model('clientes_model');
                    
                    $datos_usuario = array(
                        'nick' => $this->input->post('email'),
                        'pass' => md5($this->input->post('password')),
                        'tipo_usuario' => 'cliente',
                        'fecha_registro' => date("Y-m-d H:i:s"),
                        'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                        'id_origen' => $id_origen,
                        'estado' => 0
                    );
                    
                    $id_usuario = $this->administrador_model->insertUsuario($datos_usuario);
                    
                    // Si ha introducido el código promocional correctamente
                    if (! empty($codigo_promo) && $codigo_promo == 'SME_17') {
                        
                        $codigo_enlace_promo = $this->generarCodigo();
                        // Le enviamos el enlace para las ofertas en promoción
                        $datos_email['codigo_promo'] = true;
                        $datos_email['enlace_promo'] = $codigo_enlace_promo;
                    } else {
                        $codigo_enlace_promo = '';
                    }
                    
                    $datos_cliente = array(
                        'nombre' => $this->input->post('nombre'),
                        'nombre_comercial' => $this->input->post('nombre_comercial'),
                        'nombre_contacto' => '',
                        'apellidos_contacto' => '',
                        'Fecha_nacimiento' => $this->input->post('Fecha_nacimiento'),
                        'telefono' => $this->input->post('telefono'),
                        'direccion' => '',
                        'poblacion' => '',
                        'cp' => '',
                        'cif' => $this->input->post('cif'),
                        'id_pais' => 1,
                        'id_provincia' => $this->input->post('provincia'),
                        'id_sector' => '',
                        'email' => $this->input->post('email'),
                        'id_usuario' => $id_usuario,
                        'condiciones' => 1,
						'id_sector' => 1,
						'meses_interesado' => 'enero',
						'medios_interesado' => 'Prensa',						
                        'id_agencia' => 1,
                        'codigo_promo' => $codigo_enlace_promo
                    );
					
					$datos_inver = array(
						'id_usuario' => $id_usuario
					);	
                    
                    $id = $this->clientes_model->insertCliente($datos_cliente);
					

                }
                // Enviamos email al nuevo usuario registrado
                $this->load->library('email');
                $this->email->from(EMAIL_OFICIAL, 'Obbak');
                $this->email->to($this->input->post('email'));
                
                $datos_email['nick'] = $this->input->post('email');
                $datos_email['pass'] = $this->input->post('password');
                $datos_email['nombre'] = $this->input->post('nombre');
                $datos_email['telefono'] = $this->input->post('telefono');
                $datos_email['email'] = $this->input->post('email');
                $datos_email['tipo'] = $this->input->post('tipo_usuario');
                $datos_email['nombre_comercial'] = $this->input->post('nombre_comercial');
                $datos_email['id'] = $id;
                
                $this->email->subject('¡Bienvenido a Obbak.com! Comienza a Impulsar tus juegos y rentabviliza tus inversiones Hoy');
                $this->email->message($this->load->view('email/email_registro_v01', $datos_email, TRUE));
                
                $this->email->send();
                
                // Enviamos email al administrador informando del nuevo registro
                 $this->email->from(EMAIL_OFICIAL, 'Obbak');
                 $this->email->to(EMAIL_OFICIAL);
                 $this->email->cc('');
                
                $this->email->subject(($this->input->post('tipo_usuario') == 'agencia') ? 'Nueva agencia registrada en Obbak.' : 'Nuevo anunciante registrado en Obbak');
                $this->email->message($this->load->view('email/email_registro_admin', $datos_email, TRUE));
                
                $this->email->send();
                
                if ($this->input->post('tipo_usuario') != 'agencia') {}
                
                if (! empty($codigo_promo) && $codigo_promo == 'SME_17') {
                    
                    // Le redireccionamos a la página de ofertas
                    redirect('anunciantes/ofertasPromo/' . $codigo_enlace_promo);
                } else {
                    $data['page'] = "inicio/thankyou";
                }
                
                $this->load->view('principal', array_merge($data));
            }
        }
    }

    /**
     * Muestra el fomrulario de registro de un nuevo medio
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function registroMedio($modo = 0)
    {
        if ($this->session->userdata('logged_in')) {
            redirect('inicio/index');
        }
        
        $this->load->model('medios_model');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('subida');
        $this->load->model('administrador_model');
        
        $data['titulo'] = "Obbak - Registro Medio";
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        
        if ($modo == 0) {
            $data['page'] = 'inicio/registro_medio';
            $this->load->view('principal', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[255]|required');
            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n breve', 'trim|required|max_length[2000]');
            $this->form_validation->set_rules('nick', 'Nick', 'trim|required|callback_emailEsUnico');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('checkcondiciones', 'checkcondiciones', 'required');
            $this->form_validation->set_rules('tipo_medio', 'Tipo medio', 'trim|required');
            if ($this->input->post('web') !== '') {
                $this->form_validation->set_rules('web', 'Web', 'trim|callback_urlCheck');
            }
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
            $this->form_validation->set_message('comprobarEmail', 'El email tiene un formato incorrecto.');
            $this->form_validation->set_message('emailEsUnico', 'Ese nick ya está siendo usado.');
            $this->form_validation->set_message('urlCheck', 'La página web tiene un formato incorrecto.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['page'] = 'inicio/registro_medio';
                $this->load->view('principal', array_merge($data));
            } else {
                $error = '';
                $archivo = '';
                $logo = '';
                
                if (! empty($_FILES['imagen']['name'])) {
                    $extension = explode('.', $_FILES['imagen']['name']);
                    $extension = $extension[count($extension) - 1];
                    
                    // Elaboramos un t�tulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta
                    $nombre_fichero = '';
                    
                    $archivo = $this->subida->uploadImagen('imagen', 'images/medios', $nombre_fichero, $extension);
                    
                    // si no....
                    switch ($archivo) {
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
                
                if (! empty($error)) {
                    $data['validado'] = true;
                    $data['error_imagen'] = $error;
                    $data['page'] = 'inicio/registro_medio';
                    $this->load->view('principal', array_merge($data));
                    return;
                }
                
                if (empty($archivo)) {
                    /*
                     * $data['validado'] = true;
                     * $data['error_imagen'] = '<span style="font-size: 10pt; font-weight: bold;color:red;">Seleccione una imagen</span>';
                     * $data['page'] = 'administrador/nuevo_medio';
                     * $this->load->view('administrador_container', array_merge($data));
                     * return;
                     */
                    $archivo = 'images/medios/medio_default.png';
                }
                
                if (! empty($_FILES['logo']['name'])) {
                    $extension = explode('.', $_FILES['logo']['name']);
                    $extension = $extension[count($extension) - 1];
                    
                    // Elaboramos un t�tulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta
                    $nombre_fichero = '';
                    
                    $logo = $this->subida->uploadImagen('logo', 'images/medios/logo', $nombre_fichero, $extension);
                    
                    // si no....
                    switch ($logo) {
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
                
                if (! empty($error)) {
                    $data['validado'] = true;
                    $data['error_logo'] = $error;
                    $data['page'] = 'inicio/registro_medio';
                    $this->load->view('principal', array_merge($data));
                    return;
                }
                
                if (empty($logo)) {
                    $logo = 'images/medios/logo/medio_logo_default.png';
                }
                
                // Guardamos el id_origen de la campaña
                $id_origen = (empty($this->session->userdata('id_origen')) ? 0 : $this->session->userdata('id_origen'));
                
                $usuario = array(
                    'nick' => $this->input->post('nick'),
                    'pass' => md5($this->input->post('password')),
                    'tipo_usuario' => 'medio',
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                    'id_origen' => $id_origen,
                    'estado' => 3
                );
                
                $id_usuario = $this->administrador_model->insertUsuario($usuario);
                
                $datos_medio = array(
                    'nombre' => $this->input->post('nombre'),
                    'email' => $this->input->post('email'),
                    'web' => $this->input->post('web'),
                    'imagen' => $archivo,
                    'logo' => $logo,
                    'descripcion' => nl2br($this->input->post('descripcion')),
                    'cif' => '',
                    'fecha_alta' => date("Y-m-d H:i:s"),
                    'fecha_baja' => date("Y-m-d H:i:s"),
                    'id_usuario' => $id_usuario,
                    'id_tipo_medio' => $this->input->post('tipo_medio')
                );
                
                $id_medio = $this->medios_model->insertMedio($datos_medio);
                
                // Enviamos email al nuevo usuario registrado
                $this->load->library('email');
                $this->email->from(EMAIL_OFICIAL, 'Obbak');
                $this->email->to($this->input->post('email'));
                
                $datos_email['nick'] = $this->input->post('nick');
                $datos_email['pass'] = $this->input->post('password');
                $datos_email['nombre'] = $this->input->post('nombre');
                $datos_email['telefono'] = $this->input->post('telefono');
                $datos_email['email'] = $this->input->post('email');
                $datos_email['tipo'] = 'Medio';
                $datos_email['id'] = $id_medio;
                
                $this->email->subject('Gracias por registrarse en Obbak');
                $this->email->message($this->load->view('email/email_registro_medio', $datos_email, TRUE));
                
                $this->email->send();
                
                // Enviamos email al administrador informando del nuevo registro
                $this->email->from(EMAIL_OFICIAL, 'Obbak');
                $this->email->to(EMAIL_OFICIAL);
                $this->email->cc('gonzalo.reina@Obbak.com');
                
                $this->email->subject('Nuevo Medio registrado en Obbak');
                $this->email->message($this->load->view('email/email_registro_medio_admin', $datos_email, TRUE));
                
                $this->email->send();
                
                $data['page'] = "thankyou";
                $this->load->view('principal', array_merge($data));
            }
        }
    }

    /**
     * Valida los datos del formulario de contacto y si son correctos envia el email con los datos, llamada desde contacto
     */
    function send_contact()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|callback_check_captcha');
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('tipo', 'tipo', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->contacto();
        } else {
            $this->load->library('email');
            $this->email->from(EMAIL_OFICIAL);
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
            $this->email->message('Nombre: ' . $this->input->post('nombre') . '
		Email de contacto: ' . $this->input->post('email') . '
		' . 'Mensaje: ' . $this->input->post('comentario') . '
		' . 'Teléfono: ' . $this->input->post('telefono'));
            
            $this->email->send();
            
            if ($this->input->post('copia_email') == 'on') {
                $this->load->library('email');
                $this->email->from(EMAIL_OFICIAL);
                $this->email->to($this->input->post('email'));
                
                $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
                $this->email->message('Nombre: ' . $this->input->post('nombre') . '
			Email de contacto: ' . $this->input->post('email') . '
			' . 'Mensaje: ' . $this->input->post('comentario') . '
			' . 'Teléfono: ' . $this->input->post('telefono'));
                
                $this->email->send();
            }
            
            $data['page'] = "inicio/thankyou";
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Valida los datos del formulario de contacto y si son correctos envia el email con los datos, llamada desde contacto
     */
    function send_contact_baja()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|callback_check_captcha');
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->contactoBaja();
        } else {
            $this->load->library('email');
            $this->email->from(EMAIL_OFICIAL);
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Solicitud de Baja: ' . $this->input->post('nombre'));
            
            $mensaje = ('Nombre: ' . $this->input->post('nombre') . '                   
                                                        Email: ' . $this->input->post('email') . '
                                                        ' . 'Mensaje: ' . $this->input->post('comentario'));
            if ($this->input->post('check_newsletter') == 'on') {
                $mensaje .= 'Tipo Baja: Solicita sólo baja de la newsletter.';
            }
            
            $this->email->message($mensaje);
            
            $this->email->send();
            
            $data['page'] = "inicio/thankyou";
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Valida los datos del formulario de contacto y si son correctos envia el email con los datos, llamada desde index
     */
    function send_contact_home()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('tipo', 'tipo', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $this->load->library('email');
            $this->email->from($this->input->post('email'));
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
            $this->email->message('Nombre: ' . $this->input->post('nombre') . '
		Email de contacto: ' . $this->input->post('email') . '
		' . 'Mensaje: ' . $this->input->post('comentario') . '
		' . 'Teléfono: ' . $this->input->post('telefono'));
            
            $this->email->send();
            
            if ($this->input->post('copia_email') == 'on') {
                $this->load->library('email');
                $this->email->from(EMAIL_OFICIAL);
                $this->email->to($this->input->post('email'));
                
                $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
                $this->email->message('Nombre: ' . $this->input->post('nombre') . '
			Email de contacto: ' . $this->input->post('email') . '
			' . 'Mensaje: ' . $this->input->post('comentario') . '
			' . 'Teléfono: ' . $this->input->post('telefono'));
                
                $this->email->send();
            }
            
            if (! $this->session->userdata('logged_in')) {
                $data['page'] = "inicio/thankyou";
                $this->load->view('principal', array_merge($data));
            } else {
                $data['page'] = "inicio/thankyou";
                $this->load->view('principal', array_merge($data));
            }
        }
    }

    /**
     * Valida los datos del formulario de contacto y si son correctos envia el email con los datos, llamada desde anuncia_marca_emprendedores
     */
    function send_contact_marca()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->elreferente();
        } else {
            $this->load->library('email');
            $this->email->from($this->input->post('email'));
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
            $this->email->message('Nombre: ' . $this->input->post('nombre') . '
		Email de contacto: ' . $this->input->post('email') . '
		' . 'Mensaje: ' . $this->input->post('comentario') . '
		' . 'Teléfono: ' . $this->input->post('telefono'));
            
            $this->email->send();
            
            $data['page'] = "inicio/anuncio-marca-emprendedores";
            $data['confirmacion'] = 'Su mensaje ha sido enviado correctamente.';
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Valida los datos del formulario de contacto y si son correctos envia el email con los datos, llamada desde elreferente
     */
    function send_contact_referente()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->elreferente();
        } else {
            $this->load->library('email');
            $this->email->from($this->input->post('email'));
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Contacto de: ' . $this->input->post('tipo'));
            $this->email->message('Nombre: ' . $this->input->post('nombre') . '
		Email de contacto: ' . $this->input->post('email') . '
		' . 'Mensaje: ' . $this->input->post('comentario') . '
		' . 'Teléfono: ' . $this->input->post('telefono'));
            
            $this->email->send();
            
            $data['page'] = "inicio/elreferente";
            $data['confirmacion'] = 'Su mensaje ha sido enviado correctamente.';
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Valida los datos del formulario de encuesta cuanto cuesta y si son correctos envia el email con los datos, llamada desde cuantocuestaanunciarse
     */
    function send_encuesta_coste()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|callback_check_captcha');
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('presupuesto', 'presupuesto', 'trim|required');
        $this->form_validation->set_rules('medios', 'medios', 'xss_clean');
        $this->form_validation->set_rules('ambito', 'ambito', 'trim|required');
        $this->form_validation->set_rules('detalle_ambito', 'detalle_ambito', 'trim');
        $this->form_validation->set_rules('target', 'target', 'trim|required|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comentario', 'comentario', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->cuantocuestaanunciarse();
        } else {
            $this->load->library('email');
            $this->email->from(EMAIL_OFICIAL);
            $this->email->to(EMAIL_OFICIAL);
            
            $this->email->subject('Obbak - Nueva Encuesta de campaña');
            
            $datos_medios = $this->input->post('medios');
            
            if (! empty($datos_medios)) {
                $medios = '';
                foreach ($datos_medios as $medio) {
                    $medios = $medios . '/' . $medio;
                }
            } else {
                $medios = '';
            }
            
            $datos['datos_encuesta'] = '<strong>Nombre:</strong> ' . $this->input->post('nombre') . '<br />' . '<strong>Email de contacto:</strong> ' . $this->input->post('email') . '<br />' . '<strong>Fechas Campañas:</strong> ' . $this->input->post('comentario') . '<br />' . '<strong>Presupuesto:</strong> ' . $this->input->post('presupuesto') . '<br />' . '<strong>Ámbito Geográfico:</strong> ' . $this->input->post('ambito') . ' -> ' . $this->input->post('detalle_ambito') . '<br />' . '<strong>Target Objetivo:</strong> ' . $this->input->post('target') . '<br />' . '<strong>Medios:</strong> ' . $medios;
            
            $this->email->message($this->load->view('email/email_contacto_encuesta', $datos, TRUE));
            $this->email->send();
            
            $data['page'] = "inicio/thankyou";
            $this->load->view('principal', array_merge($data));
        }
    }

    /**
     * Envía los emails a los medios para que pasen su filtro de anunciantes
     */
    function send_permisos_medios($idAnunciante = '')
    {
        if (! empty($idAnunciante)) {
            
            // Obtenemos los datos del anunciante
            $this->load->model('clientes_model');
            
            $data_anunciante = $this->clientes_model->getClienteMedio($idAnunciante);
            
            if (! empty($data_anunciante)) {
                $fechaactual = Date("Y-m-d H:i:s");
                $nuevafecha = date('Y-m-d', strtotime("$data_anunciante->fecha_registro + 2 day"));
                
                // Comprobamos que no se hayan consumido las 48h para contestar
                if ($fechaactual <= $nuevafecha) {
                    
                    echo ('Procesando emails a medios...no cierre esta ventana hasta que no se muestre el mensaje de Proceso Completado.' . '<img src="' . base_url() . 'images/ajax-loader.gif"/>');
                    
                    $this->load->model('medios_model');
                    
                    $data_email['id_cliente'] = $idAnunciante;
                    $data_email['cliente'] = $data_anunciante->nombre;
                    $data_email['cif'] = $data_anunciante->cif;
                    
                    $data_medios = $this->medios_model->getMediosSinGestor();
                    
                    // $medio = $data_medios [0];
                    foreach ($data_medios as $medio) {
                        // Enviamos email a los medios para que procesen la autorización al nuevo anunciante
                        $data_email['id_medio'] = $medio->id_medio;
                        $data_email['medio'] = $medio->nombre;
                        
                        //$this->send_permisos_medios_aux($data_email, $medio->email);
                    }
                    
                    // Enviamos ahora email a los gestores de Medios
                    $data_gestores = $this->medios_model->getMediosConGestor();
                    foreach ($data_gestores as $gestor) {
                        // Enviamos email a los medios para que procesen la autorización al nuevo anunciante
                        $data_email['id_medio'] = $gestor->id_gestor;
                        $data_email['medio'] = $gestor->nombre;
                        
                        //$this->send_permisos_medios_aux($data_email, $gestor->email);
                    }
                    
                    $data['page'] = "inicio/proceso_completado";
                    $this->load->view('principal', array_merge($data));
                } else {
                    echo ('Enlace caducado - Obbak');
                }
            } else {
                echo ('Enlace caducado - Obbak');
            }
        } else {
            echo ('Enlace caducado - Obbak');
        }
    }
    
    function send_permisos_medios_aux ($data_email, $email) {
        $this->load->library('email');
        $this->email->from(EMAIL_OFICIAL, 'Obbak');
        // $this->email->to(EMAIL_OFICIAL);
        $this->email->to($email);
        
        $this->email->subject('Obbak - Nuevo anunciante registrado para gestionar');
        $this->email->message($this->load->view('email/email_notifica_permisos_gestor', $data_email, TRUE));
        
        $this->email->send();
    }

    /**
     * Crea un registro de estadistica de newsletter
     *
     * @param string $codigo
     *            Codigo de destinatario de newsletter
     * @param char $tipo_acceso
     *            Tipo de acceso a registrar
     */
    function setEstadisticaNewsletter($codigo, $tipo_acceso, $id_oferta = 0)
    {
        $this->load->model('newsletters_model');
        
        $newsletter = $this->newsletters_model->getNewsletterByCodigo($codigo);
        
        if (empty($newsletter) || empty($codigo))
            redirect('inicio/principal');
        
        $iIdCliente = $newsletter->id_generico;
        $iIdNewsletter = $newsletter->id_newsletter;
        
        $datosEstadisticaNewsletter = array(
            'id_newsletter' => $iIdNewsletter,
            'id_cliente' => $iIdCliente,
            'tipo_acceso' => $tipo_acceso,
            'fecha' => date('Y-m-d H:i:s')
        );
        
        if (! empty($id_oferta))
            $datosEstadisticaNewsletter['id_oferta'] = $id_oferta;
        
        $this->newsletters_model->insertEstadisticaNewsletter($datosEstadisticaNewsletter);
    }

    /**
     * Muestra la vista de confirmación de la solicitud del permiso para el anunciante
     * 
     * @param string $idAnunciante
     *            Codigo del anunciante
     * @param string $idMedio
     *            Codigo del Medio
     * @param char $permiso
     *            Tipo de permiso a registrar
     */
    function solPermisoAnunciante($idAnunciante = '', $idMedio = '', $permiso = '')
    {
        if (! empty($idAnunciante) && ! empty($idMedio) && ! empty($permiso)) {
            
            // Obtenemos los datos del anunciante
            $this->load->model('clientes_model');
            
            $data_anunciante = $this->clientes_model->getClienteMedio($idAnunciante);
            
            if (! empty($data_anunciante)) {
                $fechaactual = Date("Y-m-d H:i:s");
                $nuevafecha = date('Y-m-d', strtotime("$data_anunciante->fecha_registro + 1500000 day"));
                
                // Comprobamos que no se hayan consumido los 15 dias para contestar
                if ($fechaactual <= $nuevafecha) {
                    
                    // Obtenemos los datos del medio
                    $this->load->model('medios_model');
                    
                    $data_medio = $this->medios_model->getMedio($idMedio);
                    
                    if (! empty($data_medio)) {
                        
                        if ($permiso == 1) {
                            $autorizacion = 'SI AUTORIZAR';
                        } else {
                            $autorizacion = 'NO AUTORIZAR';
                        }
                        
                        // Enviamos un email al Administrador para que procese la solicitud
                        /*
                         * $this->load->library('email');
                         * $this->email->from(EMAIL_OFICIAL);
                         * $this->email->to(EMAIL_OFICIAL);
                         *
                         * $this->email->subject('Obbak - Nueva Solicitud de Permisos a Anunciante');
                         */
                        
                        $data['medio'] = $data_medio->nombre;
                        $data['anunciante'] = $data_anunciante->nombre_contacto;
                        $data['id_cliente'] = $data_anunciante->id_cliente;
                        $data['autorizacion'] = $autorizacion;
                        
                        /*
                         * $this->email->message($this->load->view('email/email_solicitud_permiso', $data, TRUE));
                         * $this->email->send();
                         */
                        

                        $medios_con_gestor = $this->medios_model->getMediosMismoGestor($idMedio);

                        if(count( $medios_con_gestor) > 0){
                             $data['medio'] = "";
                             foreach ($medios_con_gestor as $gestor) {
                                 // Registramos en la base de datos el cambio del permiso
                                $this->clientes_model->crearPermisoClienteMedio($idAnunciante, $gestor->id_medio, $permiso);
                                $data['medio'] .= $gestor->nombre." | ";    
                            }
                        }else{
                            // Registramos en la base de datos el cambio del permiso
                            $this->clientes_model->crearPermisoClienteMedio($idAnunciante, $idMedio, $permiso);
                        }

               
                        
                        // Mostramos al Medio que se ha enviado la solicitud de su autorización
                        $data['title'] = "Autorización de permisos al Anunciante";
                        $data['page'] = "inicio/confirma_permiso_medio";
                        $this->load->view('principal', array_merge($data));
                    }
                } else { // El periodo para procesar la solicitud ha caducado
                    echo 'ha caducado el enlace';
                    // $this->index();
                }
            }
        } else {
            //echo 'algun dato vacío';
            
            $data['medio'] = 'xxx';
            $data['anunciante'] =  'xxx';
            $data['id_cliente'] =  'xxx';
            $data['autorizacion'] =  'xxx';
            
            $data['title'] = "Autorización de permisos al Anunciante";
            $data['page'] = "inicio/confirma_permiso_medio";
            $this->load->view('principal', array_merge($data));
            
        }
    }

    /**
     * Muestra la vista de informacion de quienes somos
     */
    function somos()
    {
        $data['page'] = "inicio/quienes-somos";
        $data['title'] = "Quienes Somos";
        $this->load->view('principal', array_merge($data));
    }

    
    /**
     * Muestra la vista de informacion de quienes somos
     */
    function thankyou()
    {
        $data['page'] = "inicio/thankyou";
        $data['title'] = "Gracias";
        $this->load->view('principal', array_merge($data));
    }
    
    /**
     * Muestra la vista de informacion de trabaja con nosotros
     */
    function trabaja()
    {
        $data['page'] = "inicio/trabaja-con-nosotros";
        $data['title'] = "Trabaja Con Nosotros";
        $this->load->view('principal', array_merge($data));
    }

    /**
     * Validate URL format
     *
     * @param
     *            string
     * @return string
     */
    function urlCheck($str)
    {
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (! preg_match($pattern, $str)) {
            // $this->set_message('urlCheck', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Muestra la vista de informacion de política de privacidad
     */
    function privacidad()
    {
        $data['page'] = "inicio/politica-privacidad";
        $data['title'] = "Política de Privacidad";
        $this->load->view('principal', array_merge($data));
    }
    
    /**
     * Muestra la vista de informacion de terminos de uso
     */
    function terminos()
    {
        $data['page'] = "inicio/terminos-uso";
        $data['title'] = "Términos de uso";
        $this->load->view('principal', array_merge($data));
    }
    function pixel(){
        try{
            $this->load->model('pixel_model');

            $tipo = $this->input->get('tipo');
            $accion = $this->input->get('accion');
            $id_cliente = $this->input->get('id_cliente');
            $id_usuario = $this->input->get('id_usuario');
            $id_oferta = $this->input->get('id_oferta');
            $email = $this->input->get('email');
            $data = array(
                'tipo' => $tipo,
                'accion' => $accion,
                'id_cliente' => $id_cliente,
                'id_usuario' => $id_usuario,
                'id_oferta' => $id_oferta,
                'email' => $email
            );
            if($tipo != "") $this->pixel_model->insert_pixel($data);
            
        }catch(Exception $ex){}
        header("Content-type: image/gif");
        echo base64_decode("R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBAAAB/4Ae/wAAAAAA"); 
    }
}
?>