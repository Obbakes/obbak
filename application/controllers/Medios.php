<?php

if (! defined('BASEPATH'))

    exit('No direct script access allowed');

require_once('BaseController.php');

class Medios extends BaseController

{



    function __construct()

    {

        parent::__construct();

    }



    /**

     * Elimina una oferta de la bd

     *

     * @param integer $id_oferta

     *            Id de la oferta a eliminar

     */

    function borrarOferta($id_oferta)

    {

        $this->load->model('ofertas_model');



        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        $oferta = $this->ofertas_model->getOfertaMedio($id_oferta);



        if (file_exists($oferta->imagen))

            unlink($oferta->imagen);



        if (! empty($oferta)) {

            $ret = $this->ofertas_model->deleteOferta($id_oferta);

            echo ($ret) ? 'true' : 'false';

        } else {

            echo 'false';

        }

    }



    /**

     * Muestra el formulario de cambio de contraseña

     *

     * @param integer $modo

     *            Modo de validacion: 0 no valida, 1 si

     */

    function cambiarPass($modo = 0)

    {

        $this->load->model('clientes_model');

        $this->load->model('administrador_model');

        $this->load->library('form_validation');



        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        // $cliente = $this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));



        $data['opc'] = 'perfil';



        if ($modo != 0) {

            $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required');

            $this->form_validation->set_rules('pass_conf', 'Repetición de la contraseña', 'trim|matches[pass]');



            // Mensajes de error

            $this->form_validation->set_message('required', '%s es un dato necesario.');

            $this->form_validation->set_message('matches', 'las contraseñas no coinciden.');



            // Formato del contenedor del error

            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');



            // Si la validación del formulario falla, cargamos la vista de modificación del perfil con los datos del formulario

            if ($this->form_validation->run() == true) {

                $datos_usuario = array(

                    'pass' => md5($this->input->post('pass'))

                );



                $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);

                $this->session->set_flashdata('correcto', 'Su contraseña ha sido modificada correctamente');

                redirect('medios/editarMedio/' . $this->session->userdata('id_medio'));

            }

        }

        // $data['cliente'] = $cliente;

        $data['page'] = 'medios/cambiarPass';
 $this->load->vars($data);
        $this->load->view('medios/medios_container');

    }



    /**

     * Funcion de conexion entre los botones del panel de control para anunciantes y las vistas con filtros necesarios

     *

     * @param integer $opcion

     *            Opcion de filtrado de anunciantes a la que dirigir

     */

    function conexionListadoAnunciantes($opcion = 0)

    {

        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        $filtro = array();



        if ($opcion == 1) { // anunciantes pendientes de aceptacion

            $filtro['estado'] = 'pendiente';

        } else if ($opcion == 2) { // anunciantes con permisos pendientes de aceptacion

            $filtro['estado'] = 3;

        }



        $this->session->set_userdata('filtro_permisos', $filtro);



        redirect('medios/permisosMedio/' . $this->session->userdata('id_medio') . '/2/1');

    }



    /**

     * Muestra el formulario de edicion del medio especificado

     *

     * @param integer $id_medio

     *            Id del medio a editar

     * @param integer $modo

     *            Modo de validacion: 0 no valida, 1 si

     */

    function editarMedio($id_medio = 0, $modo = 0)

    {

        $this->load->model('medios_model');

        $this->load->library('form_validation');

        $this->load->library('upload');

        $this->load->library('subida');



        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        $data['opc'] = 'perfil';

        $medio = $this->medios_model->getMedio($id_medio);

        $data['tipos_medios'] = $this->medios_model->getTiposMedios();



        if ($modo == 0) {

            $data['medio'] = $medio;

            $data['correcto'] = $this->session->flashdata('correcto');

            $data['page'] = 'medios/editar_medio';
 $this->load->vars($data);
            $this->load->view('medios/medios_container');

        } else {

            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[255]|required');

            $this->form_validation->set_rules('descripcion', 'Descripción breve', 'trim|required|max_length[2000]');

            $this->form_validation->set_rules('tipo_medio', 'Tipo medio', 'trim|required');

            $this->form_validation->set_rules('email', 'Email', 'trim|required');

            if ($this->input->post('web') !== '') {

                $this->form_validation->set_rules('web', 'Web', 'trim|callback_urlCheck');

            }



            // Mensajes de error

            $this->form_validation->set_message('required', '%s es un dato necesario.');

            $this->form_validation->set_message('comprobarEmail', 'El email tiene un formato incorrecto.');

            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');

            $this->form_validation->set_message('urlCheck', 'La página web tiene un formato incorrecto.');



            // Formato del contenedor del error

            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');



            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario

            if ($this->form_validation->run() == FALSE) {

                $data['validado'] = true;

                $data['medio'] = $medio;

                $data['page'] = 'medios/editar_medio';
 $this->load->vars($data);
                $this->load->view('medios/medios_container');

            } else {

                $error = '';

                $archivo = '';

                $logo = '';



                if (! empty($_FILES['imagen']['name'])) {

                    $extension = explode('.', $_FILES['imagen']['name']);

                    $extension = $extension[count($extension) - 1];



                    // Elaboramos un titulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta

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

                    $data['page'] = 'medios/editar_medio';
 $this->load->vars($data);
                    $this->load->view('medios/medios_container');

                    return;

                }

                if (empty($error)) {

                    if ($this->input->post('defectoImage') == 'defecto') {

                        $archivo = 'images/iconos/news1.png';

                    }

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

                    $data['page'] = 'medios/editar_medio';
 $this->load->vars($data);
                    $this->load->view('medios/medios_container');

                    return;

                }



                if (empty($error)) {

                    if ($this->input->post('defectoLogo') == 'defecto') {

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



                if (! empty($archivo))

                    $datos_medio['imagen'] = $archivo;



                if (! empty($logo))

                    $datos_medio['logo'] = $logo;



                $this->medios_model->updateMedio($id_medio, $datos_medio);

                $this->session->set_flashdata('correcto', 'Se han guardado los datos correctamente');



                redirect('medios/editarMedio/' . $id_medio);

            }

        }

    }



    /**

     * Comprueba que la fecha de fin de campaÃ±a sea posterior a la de inicio de campaÃ±a, funcion de validacion de formulario

     *

     * @param string $fecha_fin_camp

     *            Fecha de fin de campaÃ±a

     * @param string $fecha_inicio_camp

     *            Fecha de inicio de campaÃ±a

     * @return boolean true si la fecha de fin es posterior a la de inicio, false si no

     */

    function checkFechaFinCamp($fecha_fin_camp, $fecha_inicio_camp)

    {

        $fecha_fin_camp_indef = $this->input->post('fecha_fin_camp_indef');



        if (! empty($fecha_fin_camp_indef))

            return true;



        $time_ini_camp = strtotime($fecha_inicio_camp);

        $time_fin_camp = strtotime($fecha_fin_camp);



        if ($time_ini_camp <= $time_fin_camp)

            return true;



        return false;

    }



    /**

     * Comprueba que la fecha de fin de publicacion haya sido seleccionada, funcion de validacion de formulario

     *

     * @param string $fecha_fin_pub

     *            Fecha de fin de publicacion

     * @return boolean true si la fecha de fin es posterior a la de inicio, false si no

     */

    function checkFechaFinPub($fecha_fin_pub)

    {

        $fecha_fin_pub_indef = $this->input->post('fecha_fin_pub_indef');



        if (! empty($fecha_fin_pub_indef))

            return true;



        if (! empty($fecha_fin_pub))

            return true;



        return false;

    }



    /**

     * Comprueba que la fecha de inicio de camapÃ±a sea posterior a la de fin de publicacion, funcion de validacion de formulario

     *

     * @param string $fecha_inicio_camp

     *            Fecha de inicio de campaÃ±a

     * @param string $fecha_fin_pub

     *            Fecha de fin de publicacion

     * @return boolean true si la fecha de inicio es posterior a la de fin, false si no

     */

    function checkFechaInicioCamp($fecha_inicio_camp, $fecha_fin_pub)

    {

        $time_ini_camp = strtotime($fecha_inicio_camp);

        $time_fin_pub = strtotime($fecha_fin_pub);



        if ($time_fin_pub < $time_ini_camp)

            return true;



        return false;

    }



    /**

     * Comprueba que el dato1 es mayor que el dato2

     *

     * @param integer $dato1

     *            Primer dato a comprobar

     * @param integer $dato2

     *            Segundo dato a comprobar

     * @return boolean true si dato1 es mayor que dato2, false si no

     */

    function checkMayorQue($dato1, $dato2)

    {

        if ($dato1 > $dato2)

            return true;



        return false;

    }



    /**

     * Comprueba que el dato es mayor que 0

     *

     * @param integer $dato

     *            Dato a comprobar

     * @return boolean true si mayor que 0, false si no

     */

    function checkMayorQueCero($dato)

    {

        if ($dato > 0)

            return true;



        return false;

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

     * Muestra el formulario de edicion de la oferta especificada

     *

     * @param integer $id_oferta

     *            Id de la oferta a editar

     * @param integer $modo

     *            Modo de validacion: 0 no valida, 1 si

     */

    function editarOferta($id_oferta = 0, $modo = 0)

    {

        $this->load->model('ofertas_model');

        $this->load->model('medios_model');

        $this->load->model('clientes_model');

        $this->load->library('form_validation');

        $this->load->library('upload');

        $this->load->library('subida');



        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        $data['opc'] = 'ofertas';

        $oferta = $this->ofertas_model->getOfertaMedio($id_oferta);

        $data['tipos_medio'] = $this->medios_model->getTiposMedios();

        $data['provincias'] = $this->clientes_model->getProvincias();

        $data['sectores'] = $this->clientes_model->getSectores();

        $data['oferta'] = $oferta;

        $data['page'] = 'medios/editar_oferta';



        if ($modo == 1) {

            $this->form_validation->set_rules('titulo', 'T&iacute;tulo', 'trim|max_length[40]|required');

            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n breve', 'trim|max_length[50]|required');

            $this->form_validation->set_rules('detalle', 'Detalles', 'trim|required|max_length[1000]');

            $this->form_validation->set_rules('condiciones', 'Condiciones', 'trim|required|max_length[1000]');

            $this->form_validation->set_rules('duracion_camp', 'Duracion campa&ntilde;a', 'trim|required');

            $this->form_validation->set_rules('det_duracion_camp', 'Detalle Limite Campaña', 'trim|max_length[50]');

            $this->form_validation->set_rules('precio_anterior', 'Precio anterior', 'trim|required|numeric|callback_checkMayorQueCero|callback_checkMayorQue[' . $this->input->post('precio_oferta') . ']');

            $this->form_validation->set_rules('descuento', 'Descuento', 'trim');

            $this->form_validation->set_rules('coste_real', 'Coste real', 'trim|required|numeric|callback_checkMayorQueCero');

            $this->form_validation->set_rules('precio_oferta', 'Precio oferta', 'trim|required|numeric|callback_checkMayorQueCero');

            $this->form_validation->set_rules('fecha_fin_pub', 'Fecha fin publicación', 'trim|callback_checkFechaFinPub');

            $this->form_validation->set_rules('fecha_fin_pub_indef', 'Check pub indef', 'trim');

            $this->form_validation->set_rules('fecha_insercion', 'Fecha Creación', 'trim|required');

            // $this->form_validation->set_rules('id_tipo_medio', 'Tipo de medio', 'trim|required');

            // $this->form_validation->set_rules('id_medio', 'Medio', 'trim|required');

            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');

            $this->form_validation->set_rules('sector', 'Sector', 'trim');

            $this->form_validation->set_rules('publicada', 'Publicar', 'trim');

            $this->form_validation->set_rules('link', 'Link medio', 'trim|callback_urlCheck');



            // Mensajes de error

            $this->form_validation->set_message('required', '%s es un dato necesario.');

            $this->form_validation->set_message('numeric', '%s no es un número.');

            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');

            $this->form_validation->set_message('checkMayorQueCero', '%s debe ser mayor que 0.');

            $this->form_validation->set_message('checkMayorQue', '%s debe ser mayor que Precio oferta.');

            $this->form_validation->set_message('checkFechaFinPub', 'Debes seleccionar una fecha limite de contratacion o seleccionar periodo indefinido.');

            $this->form_validation->set_message('urlCheck', 'El link tiene un formato incorrecto.');



            // Formato del contenedor del error

            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');



            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario

            var_dump($this->form_validation->run());

            echo validation_errors();

            if ($this->form_validation->run() == FALSE) {

                $data['validado'] = true;

            } else {

                $error = '';

                $archivo = '';



                if (! empty($_FILES['imagen']['name'])) {

                    $extension = explode('.', $_FILES['imagen']['name']);

                    $extension = $extension[count($extension) - 1];



                    // Elaboramos un titulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta

                    $nombre_fichero = '';



                    if (! empty($oferta->imagen))

                        $nombre_fichero = $oferta->imagen;



                    $archivo = $this->subida->uploadImagen('imagen', 'images/ofertas', $nombre_fichero, $extension, TRUE, 'jpg');



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

                    $data['error_imagen'] = $error;

                } else {

                    $datos_oferta = array(

                        'titulo' => $this->input->post('titulo'),

                        'id_medio' => $this->session->userdata('id_medio'),

                        'id_provincia' => $this->input->post('provincia'),

                        'id_sector' => $this->input->post('sector'),

                        'descripcion' => $this->input->post('descripcion'),

                        'detalle' => $this->input->post('detalle'),

                        'condiciones' => $this->input->post('condiciones'),

                        'precio_anterior' => $this->input->post('precio_anterior'),

                        'precio_oferta' => $this->input->post('precio_oferta'),

                        'coste_real' => $this->input->post('coste_real'),

                        'descuento' => 100 - ((0.0 + $this->input->post('precio_oferta')) / (0.0 + $this->input->post('precio_anterior')) * 100),

                        'fecha_fin_pub' => ($this->input->post('fecha_fin_pub_indef') == 1) ? NULL : $this->input->post('fecha_fin_pub'),

                        'detalle_fin_camp' => $this->input->post('det_duracion_camp'),

                        'fecha_insercion' => $this->input->post('fecha_insercion'),

                        'duracion_camp' => $this->input->post('duracion_camp'),
                        
                        'newsletter_num_envios' => $this->input->post('newsletter_num_envios'),

                        'publicada' => ($this->input->post('publicada') == '') ? 0 : 1,

                        'link' => $this->input->post('link')

                    );



                    if (! empty($archivo))

                        $datos_oferta['imagen'] = $archivo;



                    $this->ofertas_model->updateOferta($id_oferta, $datos_oferta);



                    redirect('medios/ofertas');

                }

            }

        }

 $this->load->vars($data);

        $this->load->view('medios/medios_container');

    }


              /**
     * Muestra los detalles de la oferta especificada
     *
     * @param integer $id_oferta
     *            Id de la oferta a mostrar en detalle
     */
    function oferta($id_oferta)
    {
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('medios_model');

        if ($this->session->userdata('tipo_usuario') != 'medio') {
            redirect('inicio');
        }
        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));
        $oferta = $this->ofertas_model->getOfertaMedio($id_oferta);
        if ($oferta != false) {

            $data['h1'] = 'Oferta';
            $data['opc'] = 'ofertas';
            $data['oferta'] = $oferta;
            $data['oferta_destinatario'] = new stdClass();// $this->ofertas_model->getLastOfertaDestinatario($id_oferta, $this->session->userdata('id_cliente'));
            $data['perfiles'] = $this->medios_model->getPerfiles($oferta->id_tipo_medio);
             $filtro = array();
            $filtro['pagina'] = 1;
            $filtro['datosPorPagina'] = 4;
            $filtro['id_cliente'] =$this->session->userdata('id_cliente');
            $filtro['ordenar'] = "masreciente";
            $data['ofertas'] = $this->ofertas_model->getOfertas($filtro);
            $data['page'] = 'medios/oferta';
            $data['cliente'] = array();//$this->clientes_model->getClienteAdmin($this->session->userdata('id_cliente'));
            $data['ofertas_clientes'] = array();//$this->ofertas_model->getOfertasClientes($id_oferta,$this->session->userdata('id_cliente'));
            $data['nueva_inscripcion'] = false;
             $data['medio'] = $medio;
 $this->load->vars($data);
            $this->load->view('default_medio');
        } else {
            redirect('medios/ofertas');
        }
    }


    /**

     * Muestra el panel de control del medio

     */

    function ofertas($modo = '0', $pagina = 1,$id_oferta = 0,$busqueda = "")

    {

        $this->load->model('medios_model');

        $this->load->model('ofertas_model');

        $this->load->library('pagination');

  

        if ($this->session->userdata('tipo_usuario') != 'medio') {

            redirect('inicio');

        }

 

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));



        if (! empty($medio)) {



            if ($modo === '0') {

                $filtro = array();

            } else if ($modo === '1') {

                $filtro = array();

                $filtro['activo'] = $this->input->post('activo');
                $filtro['enviado'] = $this->input->post('enviado');
                $filtro['fecha'] = $this->input->post('fecha');
                $filtro['precio_range'] = $this->input->post('precio_range');

            } else if ($modo === '2') {

                $filtro = ($this->session->userdata('filtro_ofertas') === false) ? array() : $this->session->userdata('filtro_ofertas');

            }

             
            $filtro['pagina'] = $pagina;

            $filtro['datosPorPagina'] = 10;

            if($id_oferta > 0){
                $filtro['id_oferta'] = $id_oferta;
			}

            $filtro['medio'] = $medio->id_medio;
            $filtro["maxPrecioOferta"] = $this->ofertas_model->getMaxPrecioOfertaMedio($filtro);  
            $this->session->set_userdata('filtro_ofertas', $filtro);

            $ofertas = $this->ofertas_model->getOfertasMedio($filtro);

            $numOfertas = $this->ofertas_model->getNumOfertasAdmin();



            $config = array();

            $config['use_page_numbers'] = TRUE;

            $config["base_url"] = base_url() . "medios/ofertas/2/";

            $config["total_rows"] = $numOfertas;

            $config["per_page"] = $filtro['datosPorPagina'];



            $config["uri_segment"] = 4;



            $this->pagination->initialize($config);



            $data["paginacion"] = $this->pagination->create_links();



            $data['filtro'] = $filtro;
            $data['ofertas'] = $ofertas;
            $data['h1'] = 'Ofertas';
            $data['opc'] = 'ofertas';
            $data['page'] = 'medios/ofertas';
            $data['busqueda'] = $busqueda;
            $data['medio'] = $medio;
	    $this->load->vars($data);
            $this->load->view('default_medio');

        } else {

            redirect('inicio');

        }

    }



    function index()

    {

        $this->load->model('medios_model');



        if ($this->session->userdata('tipo_usuario') == 'medio') {

            redirect('/medios/home');

        } else {

            redirect('/inicio/index');

        }

    }



    /**

     * Muestra el formulario para crear una nueva oferta

     *

     * @param integer $modo

     *            Modo de validacion: 0 no valida, 1 si

     */

    function nuevaOferta($modo = 0)

    {

        $this->load->model('ofertas_model');

        $this->load->model('medios_model');

        $this->load->model('clientes_model');

        $this->load->library('form_validation');

        $this->load->library('upload');

        $this->load->library('subida');



        if ($this->session->userdata('tipo_usuario') != 'medio')

            redirect('inicio');



        if ($modo == 2) {

            $this->session->unset_userdata('datos_oferta');

            redirect('medios/nuevaOferta');

        }



        $data['opc'] = 'ofertas';

        $data['tipos_medio'] = $this->medios_model->getTiposMedios();

        $data['provincias'] = $this->clientes_model->getProvincias();

        $data['sectores'] = $this->clientes_model->getSectores();

        $data['page'] = 'medios/nueva_oferta';

        $data['datos_oferta'] = $this->session->userdata('datos_oferta');



        if ($modo == 1) {

            $this->form_validation->set_rules('titulo', 'T&iacute;tulo', 'trim|max_length[40]|required');

            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n breve', 'trim|max_length[50]|required');

            $this->form_validation->set_rules('detalle', 'Detalles', 'trim|required|max_length[1000]');

            $this->form_validation->set_rules('condiciones', 'Condiciones', 'trim|required|max_length[1000]');

            $this->form_validation->set_rules('duracion_camp', 'Duracion campa&ntilde;a', 'trim|required');

            $this->form_validation->set_rules('det_duracion_camp', 'Detalle Limite Campaña', 'trim|max_length[50]');

            $this->form_validation->set_rules('precio_anterior', 'Precio anterior', 'trim|required|numeric|callback_checkMayorQueCero|callback_checkMayorQue[' . $this->input->post('precio_oferta') . ']');

            $this->form_validation->set_rules('coste_real', 'Coste real', 'trim|required|numeric|callback_checkMayorQueCero');

            $this->form_validation->set_rules('precio_oferta', 'Precio oferta', 'trim|required|numeric|callback_checkMayorQueCero');

            $this->form_validation->set_rules('descuento', 'Descuento', 'trim');

            $this->form_validation->set_rules('fecha_fin_pub', 'Fecha fin publicaci�n', 'trim|callback_checkFechaFinPub');

            $this->form_validation->set_rules('fecha_fin_pub_indef', 'Check pub indef', 'trim');

            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');

            $this->form_validation->set_rules('sector', 'Sector', 'trim');

            $this->form_validation->set_rules('link', 'Link medio', 'trim|callback_urlCheck');



            // Mensajes de error

            $this->form_validation->set_message('required', '%s es un dato necesario.');

            $this->form_validation->set_message('cumeric', '%s no es un número.');

            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');

            $this->form_validation->set_message('checkMayorQueCero', '%s debe ser mayor que 0.');

            $this->form_validation->set_message('checkMayorQue', '%s debe ser mayor que Precio oferta.');

            $this->form_validation->set_message('checkFechaFinPub', 'Debes seleccionar una fecha limite de contratacion o seleccionar periodo indefinido.');

            $this->form_validation->set_message('urlCheck', 'El link tiene un formato incorrecto.');



            // Formato del contenedor del error

            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');



            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario

            if ($this->form_validation->run() == FALSE) {

                $data['validado'] = true;

            } else {

                $error = '';

                $archivo = '';



                if (! empty($_FILES['imagen']['name'])) {

                    $extension = explode('.', $_FILES['imagen']['name']);

                    $extension = $extension[count($extension) - 1];



                    // Elaboramos un t�tulo del archivo de imagen que no se pueda repetir, mediante el t�tulo sin tildes de la oferta

                    $nombre_fichero = '';



                    $archivo = $this->subida->uploadImagen('imagen', 'images/ofertas', $nombre_fichero, $extension, TRUE, 'jpg');



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

                    $data['error_imagen'] = $error;

                } else if (empty($archivo)) {

                    $data['error_imagen'] = '<span style="font-size: 10pt; font-weight: bold;color:red;">Seleccione una imagen</span>';

                } else {

                    $datos_oferta = array(

                        'titulo' => $this->input->post('titulo'),

                        'id_medio' => $this->session->userdata('id_medio'),

                        'id_medio_crea' => $this->session->userdata('id_medio'),

                        'id_provincia' => $this->input->post('provincia'),

                        'id_sector' => $this->input->post('sector'),

                        'id_alcance' => 0,

                        'descripcion' => $this->input->post('descripcion'),

                        'detalle' => $this->input->post('detalle'),

                        'condiciones' => $this->input->post('condiciones'),

                        'precio_anterior' => $this->input->post('precio_anterior'),

                        'precio_oferta' => $this->input->post('precio_oferta'),

                        'coste_real' => $this->input->post('coste_real'),

                        'descuento' => 100 - ((0.0 + $this->input->post('precio_oferta')) / (0.0 + $this->input->post('precio_anterior')) * 100),

                        'fecha_inicio_pub' => date('Y-m-d'),

                        'fecha_fin_pub' => ($this->input->post('fecha_fin_pub_indef') == 1) ? NULL : $this->input->post('fecha_fin_pub'),

                        'fecha_inicio_camp' => date('Y-m-d'),

                        'fecha_fin_camp' => date('Y-m-d'),

                        'detalle_fin_camp' => $this->input->post('det_duracion_camp'),

                        'imagen' => $archivo,

                        'publicada' => 0,

                        'fecha_insercion' => date('Y-m-d H:i:s'),

                        'duracion_camp' => $this->input->post('duracion_camp'),

                        'link' => $this->input->post('link')

                    );



                    $id_oferta = $this->ofertas_model->insertOferta($datos_oferta);



                    // Enviamos email al administrador informando de la nueva oferta

                    $this->email->from(EMAIL_OFICIAL, 'BIMADS');

                    $this->email->to(EMAIL_OFICIAL);



                    $datos_email['nombre_medio'] = $this->session->userdata('nombre');

                    $datos_email['titulo_oferta'] = $this->input->post('titulo');



                    $this->email->subject('Nueva oferta registrada por un Medio');

                    $this->email->message($this->load->view('medios/email_nueva_oferta', $datos_email, TRUE));



                    $this->email->send();



                    redirect('medios/ofertas');

                }

            }

        }


 $this->load->vars($data);
        $this->load->view('medios/medios_container');

    }

    

    function reenviarOferta($id_oferta)

    {
    
      $this->load->model('medios_model');
      $this->load->model('ofertas_model');
      $this->load->library('email');

        if ($this->session->userdata('tipo_usuario') == 'medio') {

                    $oferta  =     $this->ofertas_model->getOfertasMedio(array("id_oferta" => $id_oferta,'medio' => $this->session->userdata('id_medio')));

                    if(count($oferta) == 1){
                         // Enviamos email al administrador informando de la nueva oferta

                        $this->email->from(EMAIL_OFICIAL, 'BIMADS');

                         $this->email->to(EMAIL_OFICIAL);
                       

                         $datos_email['id_medio'] = $this->session->userdata('id_medio');
                        $datos_email['nombre_medio'] = $this->session->userdata('nombre');
                        $datos_email['id_oferta'] = $id_oferta;
                        $datos_email['titulo_oferta'] =$oferta[0]->titulo;

                        $this->email->subject('Nueva oferta para reenviar por un Medio');

                        $this->email->message($this->load->view('email/email_reenviar_oferta', $datos_email, TRUE));



                        $this->email->send();

                        $this->setAdviceOk("Se ha enviado un email a ".EMAIL_OFICIAL." para que proceda a reenviar la oferta");

                        redirect('medios/ofertas/2');
					} 
          
                   


        } else {

            redirect('/inicio/index');

        }

    }

     function eliminarOferta($id_oferta)

    {
    
      $this->load->model('medios_model');
      $this->load->model('ofertas_model');
      $this->load->library('email');

        if ($this->session->userdata('tipo_usuario') == 'medio') {

                    $oferta  =     $this->ofertas_model->getOfertasMedio(array("id_oferta" => $id_oferta,'medio' => $this->session->userdata('id_medio')));

                    if(count($oferta) == 1){
                         // Enviamos email al administrador informando de la nueva oferta

                        $this->email->from(EMAIL_OFICIAL, 'BIMADS');

                         $this->email->to(EMAIL_OFICIAL);
                       

                         $datos_email['id_medio'] = $this->session->userdata('id_medio');
                        $datos_email['nombre_medio'] = $this->session->userdata('nombre');
                        $datos_email['id_oferta'] = $id_oferta;
                        $datos_email['titulo_oferta'] =$oferta[0]->titulo;

                        $this->email->subject('Nueva oferta para eliminar por un Medio');

                        $this->email->message($this->load->view('email/email_eliminar_oferta', $datos_email, TRUE));



                        $this->email->send();

                          $this->setAdviceOk("Se ha enviado un email a ".EMAIL_OFICIAL." para que proceda a eliminar la oferta");

                        redirect('medios/ofertas/2');
					}else{
                    print_r( $this->db->last_query());
                    die();
					}
          
                   


        } else {

            redirect('/inicio/index');

        }

    }

    /**

     * Obtiene la descripción del medio especificado

     *

     * @param integer $id_medio

     *            Id del medio para el que obtener la descripcion

     */

    function obtener_descripcion_medio($id_medio)

    {

        $this->load->model('medios_model');



        $medio = $this->medios_model->getMedio($id_medio);



        if (empty($medio))

            echo '';

        else

            echo $medio->descripcion;

    }



    /**

     * Muestra la lista de medios asociados al tipo de medio pasada por post

     *

     * Parametros Post:

     * array tipos - Array con los id de los tipos de medios a obtener

     */

    function obtenerMediosFiltro()

    {

        $this->load->model('medios_model');



        $tipos = trim($this->input->post('tipos'));



        $tipos_medios = (empty($tipos)) ? array() : explode(' ', $tipos);



        $filtro['tipo_medio'] = $tipos_medios;

        $filtro['id_cliente'] = $this->session->userdata('id_cliente');



        $data['medios'] = $this->medios_model->getMediosFiltro($filtro);

        $data['seleccionados'] = ($this->input->post('seleccionados') == '') ? array() : explode(' ', $this->input->post('seleccionados'));

 $this->load->vars($data);

        $this->load->view('medios/lista_medios');

    }



    /**
     * Muestra el listado de permisos de los anunciantes para medio especificado
     */
    function permisos($modo = '0', $pagina = 1, $datosporpagina = 15,$id_cliente = 0,$busqueda = "")
    {
        $this->load->model('clientes_model');
        $this->load->model('medios_model');
        $this->load->library('pagination');

        if ($this->session->userdata('tipo_usuario') != 'medio') {
            redirect('inicio');
        }

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));

        if (! empty($medio)) {
            if ($modo === '0') {
                $filtro = array();

                $this->session->set_userdata('filtro_permisos', $filtro);
            } else if ($modo === '1') {
                $filtro['estado'] = $this->input->post('estado');
                $filtro['anunciante'] = $this->input->post('anunciante');
                $filtro['provincia'] = $this->input->post('provincia');
                $filtro['sector'] = $this->input->post('sector');
                $this->session->set_userdata('filtro_permisos', $filtro);
            } else if ($modo === '2') {
                $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
            }

            $filtro['pagina'] = $pagina;
            $filtro['datosPorPagina'] = $datosporpagina;
            $filtro['medio'] = $medio->id_medio;
            if($id_cliente > 0){
                $filtro['id_cliente'] =  $id_cliente   ;  
			}

            $permisos = $this->clientes_model->getPermisosMedio($filtro);
            $numPermisos = $this->clientes_model->getNumPermisosMedio();
            $numPermisosPendientes = $this->medios_model->getNumUsuariosPermisosPendientes($medio->id_medio); // TODO: esto se puede sacar del resumen de abajo
            if ($id_cliente == 0 && $numPermisosPendientes > 0) {
                $this->setAdviceWarning('Tienes ' . $numPermisosPendientes . ' permisos pendientes de revisión en un total de '.$numPermisos.' anunciantes');
            }
            $config = array();
            $config['use_page_numbers'] = TRUE;
            $config["base_url"] = base_url() . "medios/permisos/2/";
            $config["total_rows"] = $numPermisos;
            $config["per_page"] = $filtro['datosPorPagina'];
            $config["uri_segment"] = 5;
            $config["cur_page"] = $pagina;

            $this->pagination->initialize($config);

            $data["paginacion"] = $this->pagination->create_links();

            $data['filtro'] = $filtro;
            $data['permisos'] = $permisos;

            $data['provincias'] = $this->clientes_model->getProvincias();
            $data['sectores'] = $this->clientes_model->getSectores();
            $data['opc'] = 'permisos';
            $data['page'] = 'medios/permisos';

            $data['medio'] = $medio;
            $data['busqueda'] = $busqueda;
            $data["resumen"] =  $this->medios_model->getPermisosResumen($medio->id_medio);
            $data['h1'] = 'Permisos';
 $this->load->vars($data);
            $this->load->view('default_medio');
        } else {
            redirect('inicio');
        }
    }


    /**

     * Modifica los permisos de los anunciantes para el medio especificado

     *

     * Parametro Post:

     * array cambios - Array con los cambios a llevar a cabo: posicion 2k id del cliente, posicion 2k+1 estado final del permiso

     *

     * @param integer $id_medio

     *            Id del medio para el que modificar los permisos

     */

    function cambiarPermisosMedio()

    {

        $this->load->model('clientes_model');

        $this->load->model('medios_model');

        if ($this->session->userdata('tipo_usuario') != 'medio') {

            redirect('inicio');

        }



        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));


       
        if (! empty($medio)) {

            $permisos = array();

            $cambios = $this->input->post('checked');
            $nuevo_estado  = $this->input->post('cambiarEstado');
           

            if (! empty($cambios)) {

                for ($i = 0; $i < count($cambios); $i ++) {                 
                    $permisos[$cambios[$i]] = $nuevo_estado;
                }
                  $this->clientes_model->cambiarPermisosMedioAdmin($medio->id_medio, $permisos);
                  $this->setAlertOk("Se han actualizado ".count($cambios)." permisos");
            }

          

            redirect('medios/permisos/2');

        } else {

            redirect('inicio');

        }

    }

    function cambiarCategorizacion(){
      $this->load->model('medios_model');

        if ($this->session->userdata('tipo_usuario') != 'medio') {

            redirect('inicio');

        }



        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));


       
        if (! empty($medio)) {

            $id_cliente = $this->input->post('id_cliente');
            $id_tipo_categorizacion  = $this->input->post('id_tipo_categorizacion');

                 $this->medios_model->cambiarCategorizacion($medio->id_medio, $id_cliente,$id_tipo_categorizacion);
                  $this->setAlertOk("Se ha actualizado la categorizacion");
                   redirect('medios/clientes');
         }else{
        redirect('inicio');  
		 }
	}
    
    function buscar(){
        $this->load->model('medios_model');
        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));
        if (empty ($medio)){
            redirect('inicio');
        }

        $buscar = $_POST["buscar"];
        $data["buscar"] =$buscar;
        $data["resultados"] =  $this->medios_model->buscar($medio->id_medio,$buscar);
 $this->load->vars($data);
        $this->load->view('medios/partials/buscar');
    }

    function home($modo = 0){

        $this->load->model('medios_model');
         $this->load->model('ofertas_model');
          $this->load->model('clientes_model');

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));

        if (empty ($medio)){
            redirect('inicio');
        }

        $data['medio'] = $medio;

        $data['h1'] = 'Página Principal';

        $data['opc'] = 'home';

        $data['page'] = 'medios/home';

        $data["resumen"] =  $this->medios_model->getHomeResumen($medio->id_medio);

      
        $filtro = array();          

        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 5;
        $filtro['medio'] = $medio->id_medio;
        $filtro['estado'] = 3;//Pendiente Autorizar
        $permisos = $this->clientes_model->getPermisosMedio($filtro);

        $data['permisos'] = $permisos;
        $data["paginacion"] = "";
 $this->load->vars($data);
        $this->load->view('default_medio');

    }



    function perfil($modo = 0) {

        $this->load->model('medios_model');

        $this->load->model('administrador_model');

        $this->load->library('form_validation');



        if ($this->session->userdata('tipo_usuario') != 'medio') {

            redirect('inicio');

        }



        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));

        if (empty ($medio)){

            redirect('inicio');

        }



        if ($modo != 0) {



            $this->form_validation->set_error_delimiters('<span class="error-formulario">', '</span>');

            $this->form_validation->set_rules('nick', 'Usuario', 'trim');

            $this->form_validation->set_rules('email', 'Email', 'trim');

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

                $datos_medio = array(
               
                      'email' => $this->input->post('email')
                    
                );

                $this->medios_model->updateMedio($medio->id_medio, $datos_medio);

                if (! empty($this->input->post('nick'))) {

                    $datos_usuario = array(

                  
                        'nick' => $this->input->post('nick')
                    );

                    $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);

                }else if (! empty($this->input->post('pass'))) {

                    $datos_usuario = array(

                        'pass' => md5($this->input->post('pass')),
                        'nick' => $this->input->post('nick')
                    );

                    $this->administrador_model->updateUsuario($this->session->userdata('id_usuario'), $datos_usuario);

                }



                $data['aviso_ok'] = 'Los datos han sido actualizados correctamente.';

            } else {

                $data['aviso_error'] = 'El formulario contiene errores.';

            }
              $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));
        }

        

        $data['medio'] = $medio;

        $data['h1'] = 'Perfil de Usuario';

        $data['opc'] = 'perfil';

        $data['page'] = 'medios/perfil';
 $this->load->vars($data);
        $this->load->view('default_medio');

    }

    function medio($modo = 0) {

        $this->load->model('medios_model');

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'),true);

        if (empty ($medio)){
            redirect('inicio');
        }

        $data['medio'] = $medio;
        $data['h1'] = 'Perfil de Medios';
        $data['opc'] = 'medio';
        $data['page'] = 'medios/medio';
        $data['perfiles'] = $this->medios_model->getPerfiles($medio->id_tipo_medio);
 $this->load->vars($data);
        $this->load->view('default_medio');

    }

 

    function clientes($modo = 0) {

        $this->load->model('medios_model');
          $this->load->model('clientes_model');

           if ($this->session->userdata('tipo_usuario') != 'medio') {
            redirect('inicio');
        }

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));
        if (empty ($medio)){
            redirect('inicio');
        }
            $filtro = array();
            if ($modo === '0') {
                $filtro = array("estado" => 1);

                $this->session->set_userdata('filtro_clientes', $filtro);
            } else if ($modo === '1') {
                $filtro['estado'] = 1;
                $filtro['anunciante'] = $this->input->post('anunciante');
                $filtro['provincia'] = $this->input->post('provincia');
                $filtro['categorizacion'] = $this->input->post('categorizacion');
                $filtro['sector'] = $this->input->post('sector');

                $this->session->set_userdata('filtro_clientes', $filtro);
            } else if ($modo === '2') {
                $filtro = ($this->session->userdata('filtro_clientes') === false) ? array() : $this->session->userdata('filtro_clientes');
            }



        $data['medio'] = $medio;
        $data['h1'] = 'Listado Clientes';
        $data['opc'] = 'clientes';
        $data['page'] = 'medios/clientes';
        $data['categorizacion'] = $this->medios_model->getCategorizacion($medio->id_medio);
        $data['provincias'] = $this->clientes_model->getProvincias();
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['filtro'] = $filtro;
        $data['clientes'] = $this->medios_model->getClientes($medio->id_medio,$filtro);
 $this->load->vars($data);
        $this->load->view('default_medio');

    }



    function cierreCuenta(){

        $this->load->model('medios_model');

        $this->load->model('administrador_model');

        if ($this->session->userdata('tipo_usuario') != 'medio') {

            redirect('inicio');

        }

        $medio = $this->medios_model->getMedio($this->session->userdata('id_medio'));

        if (! empty($medio)) {

            $datos_usuario = array(

                'estado' => 3 // desactivado

            );

            $this->administrador_model->updateUsuario($medio->id_usuario, $datos_usuario);

            redirect('inicio/logout');

        } else {

            $data['aviso_error'] = 'Se ha producido un error, vuelva a intentarlo o consulte con el administrador';

            redirect('medios/perfil');

        }

    }

    function duplicarOferta(){
       
        if ($this->session->userdata('tipo_usuario') != 'medio') {
            redirect('inicio');
        }
        
        $this->load->model('ofertas_model');
       
        $id_oferta = $this->ofertas_model->duplicarOferta($_POST["id_oferta"],$_POST["fecha_inicio_pub"],$_POST["fecha_fin_pub"],$_POST["fecha_inicio_camp"],$_POST["fecha_fin_camp"],$_POST["precio_anterior"],$_POST["precio_oferta"]);

        $filtro['id_oferta'] = $id_oferta;
        $this->session->set_userdata('filtro_ofertas', $filtro);

        redirect('medios/ofertas/2');
    }

    function siguienteIdOferta(){
       
        $this->load->model('ofertas_model');
        $r =  $this->ofertas_model->siguienteIdOferta();
      
        echo $r;
    }

}

?>