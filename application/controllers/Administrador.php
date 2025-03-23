<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
 require_once('BaseController.php');

class Administrador extends BaseController
{

    private $emailOficial = '';

    function __construct()
    {
        parent::__construct();

    }

    /**
     * Muestra el listado de las agencias
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de las agencias
     */
    function agencias($modo = '0', $pagina = 1)
    {
        $this->load->model('agencias_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_agencias', $filtro);
        } else if ($modo === '1') {
            $filtro['agencia'] = $this->input->post('agencia');
            $filtro['estado'] = $this->input->post('estado');
            
            $this->session->set_userdata('filtro_agencias', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_agencias') === false) ? array() : $this->session->userdata('filtro_agencias');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 15;
        
        $agencias = $this->agencias_model->getAgenciasAdmin($filtro);
        $numAgencias = $this->agencias_model->getNumAgenciasAdmin();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/agencias/2";
        $config["total_rows"] = $numAgencias;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 4;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'agencias';
        $data['agencias'] = $agencias;
        $data['filtro'] = $filtro;
        $data['page'] = 'administrador/agencias';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de anunciantes
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los anunciantes
     */
    function anunciantes($modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('clientes_model');
        $this->load->library('pagination');
        $this->load->model('agencias_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            // Inicializamos el orden de la tabla, por la primera columna en orden descendente
            $filtro['order_by_campo'] = 'id_cliente';
            $filtro['order_by_sentido'] = 'desc';
            
            $this->session->set_userdata('filtro_clientes', $filtro);
        } else if ($modo === '1') {
            $filtro['agencia'] = $this->input->post('agencia');
            $filtro['estado'] = $this->input->post('estado');
            $filtro['permisos'] = $this->input->post('permisos');
            $filtro['anunciante'] = $this->input->post('anunciante');
            $filtro['sector'] = $this->input->post('sector');
            $filtro['order_by_campo'] = $this->input->post('order_by_campo');
            $filtro['order_by_sentido'] = $this->input->post('order_by_sentido');
            
            $this->session->set_userdata('filtro_clientes', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_clientes') === false) ? array() : $this->session->userdata('filtro_clientes');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        
        $clientes = $this->clientes_model->getClientesAdmin($filtro);
        $numClientes = $this->clientes_model->getNumClientesAdmin();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/anunciantes/2";
        $config["total_rows"] = $numClientes;
        $config["per_page"] = $filtro['datosPorPagina'];
        $config["uri_segment"] = 4;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'anunciantes';
        $data['agencias'] = $this->agencias_model->getAgencias();
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['clientes'] = $clientes;
        $data['total_clientes'] = $numClientes;
        $data['filtro'] = $filtro;
        $data['page'] = 'administrador/anunciantes';
        $this->load->view('administrador_container', array_merge($data));
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
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $oferta = $this->ofertas_model->getOfertaAdmin($id_oferta);
        
        if (file_exists($oferta->imagen))
            unlink($oferta->imagen);
        
        $ret = $this->ofertas_model->deleteOferta($id_oferta);
        
        echo ($ret) ? 'true' : 'false';
    }

    /**
     * Modifica el acceso de la agencias (post)
     *
     * Parametros Post:
     * array cambios - Array con los cambios a llevar a cabo: posicion 2k id de la agencia, posicion 2k+1 estado final del acceso
     */
    function cambiarAccesoAgencias()
    {
        $this->load->model('agencias_model');
        
        $accesos = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $accesos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $agencias = $this->agencias_model->cambiarAccesoAgencias($accesos);
        
        if (! empty($agencias)) {
            $this->load->library('email');
            
            foreach ($agencias as $agencia) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to($agencia->email);
                
                $this->email->subject('Ya puede acceder a Bimads');
                $this->email->message($this->load->view('administrador/email_acceso_aceptado', array(), TRUE));
                
                $this->email->send();
            }
        }
        
        redirect('administrador/agencias/2/' . $this->input->post('pagina'));
    }

    /**
     * Modifica el acceso a la plataforma nolimitsmedia de los usuarios especificados (post)
     *
     * Parametros Post:
     * array cambios - Array de cambios de acceso: posicion 2k id del usuario, posicion 2k+1 estado final del acceso
     */
    function cambiarAccesoAnunciantes()
    {
        $this->load->model('clientes_model');
        
        $accesos = array();
        $cambios = $this->input->post('cambios');
        $notificacion = $this->input->post('notificacion');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $accesos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $anunciantes = $this->clientes_model->cambiarAccesoAnunciantes($accesos);
        
        if (! empty($anunciantes) && $notificacion == '1') {
            $this->load->library('email');
            
            foreach ($anunciantes as $anunciante) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to($anunciante->email);
                
                $this->email->subject('Estimado ' . $anunciante->nombre . ' ya puede acceder a Bimads');
                $this->email->message($this->load->view('administrador/email_acceso_aceptado', array(), TRUE));
                
                $this->email->send();
            }
        }
        
        redirect('administrador/anunciantes/2/' . $this->input->post('pagina'));
    }

    /**
     * Modifica el acceso a la plataforma nolimitsmedia de los usuarios especificados (post)
     *
     * Parametros Post:
     * array cambios - Array de cambios de acceso: posicion 2k id del usuario, posicion 2k+1 estado final del acceso
     */
    function cambiarAccesoGestorMedios()
    {
        $this->load->model('gestores_model');
        
        $accesos = array();
        $cambios = $this->input->post('cambios');
        $notificacion = $this->input->post('notificacion');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $accesos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $gestores = $this->gestores_model->cambiarAccesoGestores($accesos);
        
        if (! empty($gestores) && $notificacion == '1') {
            $this->load->library('email');
            
            foreach ($gestores as $gestor) {
                $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                $this->email->to($gestor->email);
                
                $this->email->subject('Estimado ' . $gestor->nombre . ' ya puede acceder a Bimads');
                $this->email->message($this->load->view('administrador/email_acceso_aceptado', array(), TRUE));
                
                $this->email->send();
            }
        }
        
        redirect('administrador/gestorMedios/2/' . $this->input->post('pagina'));
    }

    /**
     * Cambia el estado de destacado (post) de la oferta especificada
     *
     * Parametros Post:
     * integer destacada - 0 para quitar de destacada, 1 para poner destacada
     *
     * @param integer $id_oferta
     *            Id de la oferta a destacar o no
     */
    function cambiarDestacadaOferta($id_oferta)
    {
        $this->load->model('ofertas_model');
        
        if ($this->input->post('destacada') == '')
            return;
        
        $datos_oferta = array(
            'destacada' => ($this->input->post('destacada') == 1) ? 1 : 0
        );
        
        $this->ofertas_model->updateOferta($id_oferta, $datos_oferta);
    }

    /**
     * Modifica el estado de las inscripciones para la oferta especificada
     *
     * Parametros Post:
     * array cambios - Array con cambios en las inscripciones: posicion 2k id del anunciante, posicion 2k+1 estado final de la inscripcion
     *
     * @param integer $id_oferta
     *            Id de la oferta para la que modificar las inscripcion
     */
    function cambiarEstadoInscripciones($id_oferta)
    {
        $this->load->model('ofertas_model');
        
        $estados = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $estados[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $this->ofertas_model->cambiarEstadoInscripciones($id_oferta, $estados);
        
        redirect('administrador/inscripcionesOferta/' . $id_oferta . '/2/' . $this->input->post('pagina'));
    }

    /**
     * Modifica los permisos para los medios para el cliente especificado
     *
     * Parametro Post:
     * array cambios - Array con los cambios a llevar a cabo: posicion 2k id del medio, posicion 2k+1 estado final del permiso
     *
     * @param integer $id_cliente
     *            Id del anunciante para el que modificar los permisos
     */
    function cambiarPermisosAnunciante($id_cliente)
    {
        $this->load->model('clientes_model');
        
        $permisos = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $permisos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $this->clientes_model->cambiarPermisosAnuncianteAdmin($id_cliente, $permisos);
        
        redirect('administrador/permisosAnunciante/' . $id_cliente . '/2/' . $this->input->post('pagina'));
    }

    /**
     * Modifica los permisos para los medios pasados por post para el gestor especificado
     *
     * Parametros Post:
     * array cambios Array con los cambios para los permisos: posicion 2k id del medio, posicion 2k+1 nuevo estado del permiso
     *
     * @param integer $id_gestor
     *            Id del gestor de medios para el que realizar los cambios
     */
    function cambiarPermisosGestorMedios($id_gestor)
    {
        $this->load->model('gestores_model');
        
        $permisos = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $permisos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $this->gestores_model->cambiarPermisosGestorMedio($id_gestor, $permisos);
        
        redirect('administrador/permisosGestorMedio/' . $id_gestor . '/2/' . $this->input->post('pagina'));
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
    function cambiarPermisosMedio($id_medio)
    {
        $this->load->model('clientes_model');
        
        $permisos = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $permisos[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $this->clientes_model->cambiarPermisosMedioAdmin($id_medio, $permisos);
        
        redirect('administrador/permisosMedio/' . $id_medio . '/2/' . $this->input->post('pagina'));
    }

    /**
     * Cancela la newsletter especificada
     *
     * @param integer $id_newsletter
     *            Id de la newsletter a cancelar
     */
    function cancelarNewsletter($id_newsletter)
    {
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $ret = $this->newsletters_model->cancelarNewsletter($id_newsletter);
        
        echo ($ret) ? 'true' : 'false';
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
     * Funcion de conexion entre los botones del panel de control para anunciantes y las vistas con filtros necesarios
     *
     * @param integer $opcion
     *            Opcion de filtrado de anunciantes a la que dirigir
     */
    function conexionListadoAnunciantes($opcion = 0)
    {
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $filtro = array();
        
        if ($opcion == 1) { // anunciantes pendientes de aceptacion
            $filtro['estado'] = 'pendiente';
        } else if ($opcion == 2) { // anunciantes con permisos pendientes de aceptacion
            $filtro['permisos'] = 'pendiente';
        }
        
        $this->session->set_userdata('filtro_clientes', $filtro);
        
        redirect('administrador/anunciantes/2/1');
    }

    /**
     * Funcion de conexion entre los botones del panel de control para ofertas y las vistas con filtros necesarios
     *
     * @param integer $opcion
     *            Opcion de filtrado de ofertas a la que dirigir
     */
    function conexionListadoOfertas($opcion = 0)
    {
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $filtro = array();
        
        if ($opcion == 1) { // sin gestionar
            $filtro['estado'] = 2;
        } else if ($opcion == 2) { // publicadas
            $filtro['publicada'] = 1;
        } else if ($opcion == 3) { // caducan hoy
            $filtro['caducidad'] = 3;
        } else if ($opcion == 4) { // caducan en 10 dias o menos
            $filtro['caducidad'] = 2;
        }
        
        $this->session->set_userdata('filtro_ofertas', $filtro);
        
        redirect('administrador/ofertas/2/1');
    }

    /**
     * Funcion previa que redirige al punto exacto de edicion en que se quedo la newsletter que se desea continuar editando
     *
     * @param integer $id_newsletter
     *            Id de la newsletter a continuar editando
     */
    function continuarEdicionNewsletter($id_newsletter = 0)
    {
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $newsletter = $this->newsletters_model->getNewsletter($id_newsletter, true);
        
        if (empty($newsletter))
            redirect('administrador/newsletters/2');
        
        $datos_newsletter = array(
            'id_newsletter' => $id_newsletter,
            'nombre' => $newsletter->nombre,
            'asunto' => $newsletter->asunto,
            'descripcion' => $newsletter->descripcion,
            'medios' => $newsletter->medios,
            'ofertas' => $newsletter->ofertas,
            'clientes' => $newsletter->clientes,
            'agencias' => $newsletter->agencias
        );
        
        $this->session->set_userdata('datos_newsletter', $datos_newsletter);
        
        if (empty($datos_newsletter['medios']))
            redirect('administrador/nuevaNewsletterMedios');
        else if (empty($datos_newsletter['ofertas']))
            redirect('administrador/nuevaNewsletterOfertas');
        else if (empty($datos_newsletter['clientes']) && empty($datos_newsletter['agencias']))
            redirect('administrador/nuevaNewsletterDestinatarios');
        else
            redirect('administrador/nuevaNewsletterConfirmacion');
    }

    /**
     * Da de alta o baja los medios especificados (post)
     *
     * Parametros Post:
     * array medios - Array las altas/bajas a llevar a cabo: posicion 2k id del medio, posicion 2k+1 dar de alta o baja
     */
    function darAltaMedios()
    {
        $this->load->model('medios_model');
        $altas = array();
        $cambios = $this->input->post('cambios');
        
        $cambios = explode(' ', $cambios);
        
        if (! empty($cambios)) {
            for ($i = 0; $i < count($cambios); $i += 2) {
                $altas[$cambios[$i]] = $cambios[($i + 1)];
            }
        }
        
        $this->medios_model->darAltaMedios($altas);
        
        redirect('administrador/medios/2/' . $this->input->post('pagina'));
    }

    /**
     * Elimina la imagen del medio especificado
     *
     * @param integer $id_medio
     *            Id del medio
     */
    function deleteImagenMedio($id_medio)
    {
        $this->load->model('medios_model');
        
        $medio = $this->medios_model->getMedio($id_medio);
        
        if (! empty($medio->imagen) && $medio->imagen != 'images/medios/medio_default.png') {
            if (file_exists($medio->imagen)) {
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
     * @param integer $id_medio
     *            Id del medio
     */
    function deleteLogoMedio($id_medio)
    {
        $this->load->model('medios_model');
        
        $medio = $this->medios_model->getMedio($id_medio);
        
        if (! empty($medio->logo) && $medio->logo != 'images/medios/logo/medio_logo_default.png') {
            if (file_exists($medio->logo)) {
                unlink($medio->logo);
            }
            
            $datosMedio = array(
                'logo' => 'images/medios/logo/medio_logo_default.png'
            );
            
            $this->medios_model->updateMedio($id_medio, $datosMedio);
        }
    }

    /**
     * Obtiene los datos de la newsletter especificada y los usa en el formulario de una nueva
     *
     * @param integer $id_newsletter
     *            Id de la newsletter a duplicar
     */
    function duplicarNewsletter($id_newsletter = 0)
    {
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $newsletter = $this->newsletters_model->getNewsletter($id_newsletter);
        
        if (empty($newsletter))
            redirect('administrador/newsletter/2');
        
        $datos_newsletter = array(
            'nombre' => $newsletter->nombre,
            'asunto' => $newsletter->asunto,
            'descripcion' => $newsletter->descripcion,
            'medios' => '',
            'ofertas' => '',
            'clientes' => '',
            'agencias' => ''
        );
        
        if (! empty($newsletter->medios)) {
            foreach ($newsletter->medios as $medio) {
                if (! empty($datos_newsletter['medios']))
                    $datos_newsletter['medios'] .= ' ';
                
                $datos_newsletter['medios'] .= $medio->id_medio;
            }
        }
        
        if (! empty($newsletter->ofertas)) {
            foreach ($newsletter->ofertas as $oferta) {
                if (! empty($datos_newsletter['ofertas']))
                    $datos_newsletter['ofertas'] .= ' ';
                
                $datos_newsletter['ofertas'] .= $oferta->id_oferta;
            }
        }
        
        if (! empty($newsletter->clientes)) {
            foreach ($newsletter->clientes as $cliente) {
                if (! empty($datos_newsletter['clientes']))
                    $datos_newsletter['clientes'] .= ' ';
                
                $datos_newsletter['clientes'] .= $cliente->id_cliente;
            }
        }
        
        if (! empty($newsletter->agencias)) {
            foreach ($newsletter->agencias as $agencia) {
                if (! empty($datos_newsletter['agencias']))
                    $datos_newsletter['agencias'] .= ' ';
                
                $datos_newsletter['agencias'] .= $agencia->id_agencia;
            }
        }
        
        $this->session->set_userdata('datos_newsletter', $datos_newsletter);
        
        redirect('administrador/nuevaNewsletterMedios');
    }

    /**
     * Funcion que obtiene los datos de la oferta especificada y los usa para el formulario de creacion de una nueva
     *
     * @param integer $id_oferta
     *            Id de la oferta a duplicar
     */
    function duplicarOferta($id_oferta = 0)
    {
        $this->load->model('ofertas_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $oferta = $this->ofertas_model->getOfertaAdmin($id_oferta);
        
        if (empty($oferta))
            redirect('administrador/ofertas/2');
        
        $this->session->set_userdata('datos_oferta', $oferta);
        
        redirect('administrador/nuevaOferta');
    }

    /**
     * Muestra el formulario de edicion de la agencia especificada
     *
     * @param integer $id_agencia
     *            Id de la agencia a editar
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function editarAgencia($id_agencia = 0, $modo = 0)
    {
        $this->load->model('agencias_model');
        $this->load->library('form_validation');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $agencia = $this->agencias_model->getAgencia($id_agencia);
        
        $data['opc'] = 'agencias';
        $data['mensajeNuevaAgencia'] = $this->session->flashdata('mensajeNuevaAgencia');
        
        if ($modo == 0) {
            $data['agencia'] = $agencia;
            $data['page'] = 'administrador/editar_agencia';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('telefono', 'Tel�fono', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('direccion', 'Direcci�n', 'trim|max_length[200]');
            $this->form_validation->set_rules('poblacion', 'Poblaci�n', 'trim|max_length[100]');
            $this->form_validation->set_rules('cp', 'C�digo Postal', 'trim|max_length[20]');
            $this->form_validation->set_rules('porcentaje', 'Porcentaje de beneficio', 'trim|required');
            $this->form_validation->set_rules('cif', 'CIF', 'trim|max_length[20]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico[' . $agencia->id_usuario . ']|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como maximo %s caracteres.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            $this->form_validation->set_message('emailEsUnico', 'Este email ya est&aacute; siendo utilizado, por favor utilice otro.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificacion del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['agencia'] = $agencia;
                $data['page'] = 'administrador/editar_agencia';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $datos_agencia = array(
                    'nombre' => $this->input->post('nombre'),
                    'telefono' => $this->input->post('telefono'),
                    'direccion' => $this->input->post('direccion'),
                    'poblacion' => $this->input->post('poblacion'),
                    'cp' => $this->input->post('cp'),
                    'cif' => $this->input->post('cif'),
                    'porcentaje' => $this->input->post('porcentaje'),
                    'email' => $this->input->post('email')
                );
                
                $this->agencias_model->updateAgencia($id_agencia, $datos_agencia);
                
                if ($agencia->email != $this->input->post('email')) {
                    // TODO: enviar email de cambio de datos
                }
                
                redirect('administrador/editarAgencia/' . $id_agencia);
            }
        }
    }

    /**
     * Muestra el formulacion de edicion del anunciante especificado
     *
     * @param integer $id_cliente
     *            Id del anunciante a editar
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function editarAnunciante($id_cliente = 0, $modo = 0)
    {
        $this->load->model('clientes_model');
        $this->load->library('form_validation');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'anunciantes';
        $cliente = $this->clientes_model->getClienteAdmin($id_cliente);
        $data['provincias'] = $this->clientes_model->getProvincias();
        $data['sectores'] = $this->clientes_model->getSectores();
        
        if ($modo == 0) {
            $data['cliente'] = $cliente;
            $data['page'] = 'administrador/editar_anunciante';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            echo $this->input->post('nick') . ' - ' . $cliente->id_usuario;
            $this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
            $this->form_validation->set_rules('nick', 'Nick', 'trim|required|callback_emailEsUnico[' . $cliente->id_usuario . ']');
            $this->form_validation->set_rules('nombre_contacto', 'Nombre', 'trim');
            $this->form_validation->set_rules('apellidos_contacto', 'Apellidos', 'trim');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
            $this->form_validation->set_rules('email', 'Email', 'trim');
            $this->form_validation->set_rules('direccion', 'Direcci�n', 'trim');
            $this->form_validation->set_rules('cp', 'C�digo Postal', 'trim');
            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');
            $this->form_validation->set_rules('sector', 'Sector', 'trim');
            $this->form_validation->set_rules('cif', 'CIF', 'trim');
            $this->form_validation->set_rules('web', 'Web', 'trim');
            $this->form_validation->set_rules('poblacion', 'Población', 'trim');
            $this->form_validation->set_rules('newsletter', 'Newsletter', 'trim');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('emailEsUnico', 'Este nick ya está siendo utilizado, por favor utilice otro.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['cliente'] = $cliente;
                $data['page'] = 'administrador/editar_anunciante';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $datos_cliente = array(
                    'nombre' => $this->input->post('nombre'),
                    'nombre_contacto' => $this->input->post('nombre_contacto'),
                    'apellidos_contacto' => $this->input->post('apellidos_contacto'),
                    'telefono' => $this->input->post('telefono'),
                    'direccion' => $this->input->post('direccion'),
                    'email' => $this->input->post('email'),
                    'cp' => $this->input->post('cp'),
                    'id_provincia' => $this->input->post('provincia'),
                    'id_sector' => $this->input->post('sector'),
                    'cif' => $this->input->post('cif'),
                    'web' => $this->input->post('web'),
                    'poblacion' => $this->input->post('poblacion'),
                    'newsletter' => ($this->input->post('newsletter') == '') ? 0 : 1
                );
                
                $this->clientes_model->updateCliente($id_cliente, $datos_cliente, $this->input->post('nick'), $cliente->id_usuario);
                
                /*
                 * if($cliente->email != $this->input->post('email')){
                 * //TODO: enviar email con datos acceso
                 * }
                 */
                
                redirect('administrador/editarAnunciante/' . $id_cliente);
            }
        }
    }

    /**
     * Muestra el formulario de edicion del gestor de medios especificado
     *
     * @param integer $id_gestor
     *            Id del gestor de medios a editar
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function editarGestorMedio($id_gestor = 0, $modo = 0)
    {
        $this->load->model('gestores_model');
        $this->load->library('form_validation');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $gestor = $this->gestores_model->getGestor($id_gestor);
        
        $data['opc'] = 'gestor_medios';
        $data['mensajeNuevoGestor'] = $this->session->flashdata('mensajeNuevoGestor');
        
        if ($modo == 0) {
            
            $data['gestor'] = $gestor;
            $data['page'] = 'administrador/editar_gestor_medio';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico[' . $gestor->id_usuario . ']|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            $this->form_validation->set_message('emailEsUnico', 'Este email ya est&aacute; siendo utilizado, por favor utilice otro.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['gestor'] = $gestor;
                $data['page'] = 'administrador/editar_gestor_medio';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $datos_gestor = array(
                    'nombre' => $this->input->post('nombre'),
                    'email' => $this->input->post('email')
                );
                
                $this->gestores_model->updateGestor($id_gestor, $datos_gestor);
                
                if ($gestor->email != $this->input->post('email')) {
                    // TODO: enviar email de cambio de datos
                }
                
                $this->session->set_flashdata('mensajeNuevoGestor', 'El Gestor de Medios se actualiz� correctamente.');
                
                redirect('administrador/editarGestorMedio/' . $id_gestor);
            }
        }
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
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'medios';
        $medio = $this->medios_model->getMedio($id_medio);
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        
        if ($modo == 0) {
            $data['medio'] = $medio;
            $data['correcto'] = $this->session->flashdata('correcto');
            $data['page'] = 'administrador/editar_medio';
            $this->load->view('administrador_container', array_merge($data));
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
                $data['page'] = 'administrador/editar_medio';
                $this->load->view('administrador_container', array_merge($data));
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
                    $data['page'] = 'administrador/nuevo_medio';
                    $this->load->view('administrador_container', array_merge($data));
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
                    $data['page'] = 'administrador/nuevo_medio';
                    $this->load->view('administrador_container', array_merge($data));
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
                
                redirect('administrador/editarMedio/' . $id_medio);
            }
        }
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
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'ofertas';
        $oferta = $this->ofertas_model->getOfertaAdmin($id_oferta);
        $data['tipos_medio'] = $this->medios_model->getTiposMedios();
        $data['provincias'] = $this->clientes_model->getProvincias();
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['tipos_oferta'] = $this->clientes_model->getTiposOferta();
        $data['oferta'] = $oferta;
        $data['page'] = 'administrador/editar_oferta';
        
        if ($modo == 1) {
            $this->form_validation->set_rules('titulo', 'T&iacute;tulo', 'trim|max_length[40]|required');
            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n breve', 'trim|max_length[5000]|required');
            $this->form_validation->set_rules('detalle', 'Detalles', 'trim|required|max_length[5000]');
            $this->form_validation->set_rules('condiciones', 'Condiciones', 'trim|required|max_length[5000]');
            $this->form_validation->set_rules('duracion_camp', 'Duracion campa&ntilde;a', 'trim|required');
            $this->form_validation->set_rules('det_duracion_camp', 'Detalle Limite Campaña', 'trim|max_length[50]');
            $this->form_validation->set_rules('precio_anterior', 'Precio anterior', 'trim|required|numeric|callback_checkMayorQueCero|callback_checkMayorQue[' . $this->input->post('precio_oferta') . ']');
            $this->form_validation->set_rules('descuento', 'Descuento', 'trim');
            $this->form_validation->set_rules('coste_real', 'Coste real', 'trim|required|numeric|callback_checkMayorQueCero');
            $this->form_validation->set_rules('precio_oferta', 'Precio oferta', 'trim|required|numeric|callback_checkMayorQueCero');
            $this->form_validation->set_rules('fecha_fin_pub', 'Fecha fin publicación', 'trim|callback_checkFechaFinPub');
            $this->form_validation->set_rules('fecha_fin_pub_indef', 'Check pub indef', 'trim');
            $this->form_validation->set_rules('fecha_insercion', 'Fecha Creación', 'trim|required');
            $this->form_validation->set_rules('id_tipo_medio', 'Tipo de medio', 'trim|required');
            $this->form_validation->set_rules('id_medio', 'Medio', 'trim|required');
            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');
            $this->form_validation->set_rules('sector', 'Sector', 'trim');
            $this->form_validation->set_rules('id_tipo_oferta', 'Tipo Oferta', 'trim');
            $this->form_validation->set_rules('publicada', 'Publicar', 'trim');
            $this->form_validation->set_rules('galeria_img', 'Galería Imágenes', 'trim');
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
                        'id_medio' => $this->input->post('id_medio'),
                        'id_provincia' => $this->input->post('provincia'),
                        'id_sector' => $this->input->post('sector'),
                        'id_tipo_oferta' => $this->input->post('id_tipo_oferta'),
                        'descripcion' => $this->input->post('descripcion'),
                        'detalle' => $this->input->post('detalle'),
                        'galeria_img' => $this->input->post('galeria_img'),
                        'condiciones' => $this->input->post('condiciones'),
                        'precio_anterior' => $this->input->post('precio_anterior'),
                        'precio_oferta' => $this->input->post('precio_oferta'),
                        'coste_real' => $this->input->post('coste_real'),
                        'descuento' => 100 - ((0.0 + $this->input->post('precio_oferta')) / (0.0 + $this->input->post('precio_anterior')) * 100),
                        'fecha_fin_pub' => ($this->input->post('fecha_fin_pub_indef') == 1) ? NULL : $this->input->post('fecha_fin_pub'),
                        'detalle_fin_camp' => $this->input->post('det_duracion_camp'),
                        'fecha_insercion' => $this->input->post('fecha_insercion'),
                        'duracion_camp' => $this->input->post('duracion_camp'),
                        'publicada' => ($this->input->post('publicada') == '') ? 0 : 1,
                        'link' => $this->input->post('link')
                    );
                    
                    if (! empty($archivo))
                        $datos_oferta['imagen'] = $archivo;
                    
                    $this->ofertas_model->updateOferta($id_oferta, $datos_oferta);
                    
                    redirect('administrador/ofertas');
                }
            }
        }
        
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Comprueba que el email no esta siendo usado por ningun otro usuario
     *
     * @param string $email
     *            Email a comprobar
     * @param integer $id_usuario
     *            Id del usuario a excluir de la comprobacion
     * @return boolean true si no esta siendo usado, false si lo esta siendo
     */
    public function emailEsUnico($email, $id_usuario = 0)
    {
        $this->load->model('inicio_model');
        
        return $this->inicio_model->emailEsUnico($email, $id_usuario);
    }

    /**
     * Envia una newsletter de prueba a nolimitsmedia antes de confirmar su envio a los anunciantes o agencias seleccionados
     */
    function emailNewsletterPrueba()
    {
        $this->load->model('medios_model');
        $this->load->model('ofertas_model');
        
        $newsletter = $this->session->userdata('datos_newsletter');
        
        if (empty($newsletter['ofertas']))
            redirect('administrador/nuevaNewsletterConfirmacion');
        
        $data['descripcion'] = $newsletter['descripcion'];
        
        $ofertas = (empty($newsletter['ofertas'])) ? array() : explode(' ', $newsletter['ofertas']);
        
        $tipos = $this->medios_model->getTiposMedios();
        
        $ofertas_tipos = array();
        
        $filtro['ofertas'] = $ofertas;
        $filtro['datosPorPagina'] = 100;
        $filtro['pagina'] = 1;
        $data['aOfertas'] = array();
        $data['aTipos'] = $tipos;
        
        foreach ($tipos as $tipo) {
            $filtro['tipo_medio'] = $tipo->id_tipo;
            $data['aOfertas'][$tipo->id_tipo] = $this->ofertas_model->getOfertas($filtro);
        }
        
        $mensaje = $this->load->view('administrador/email_ofertas_newsletter', $data, TRUE);
        
        $this->load->library('email');
        $this->email->from(EMAIL_OFICIAL, 'BIMADS');
        $this->email->to(EMAIL_OFICIAL);
        
        $this->email->subject($newsletter['asunto']);
        $this->email->message($mensaje);
        
        if ($this->email->send())
            
            $this->session->set_flashdata('mensaje_newsletter_confirmacion', 'Newsletter de prueba enviada');
        else
            $this->session->set_flashdata('mensaje_newsletter_confirmacion', 'Error en el envío');
        
        redirect('administrador/nuevaNewsletterConfirmacion');
    }

    /**
     * Envia los datos de acceso de nuevo al medio especificado
     *
     * @param integer $id_medio
     *            Id del medio al que enviar los datos de acceso
     */
    function enviarDatosAcceso($id_medio)
    {
        $this->load->model('medios_model');
        $this->load->model('administrador_model');
        
        $medio = $this->medios_model->getMedio($id_medio);
        
        if (empty($medio))
            redirect('administrador/editarMedio/' . $id_medio);
        
        $pass = $this->generarCodigo();
        
        $datos_usuario['pass'] = md5($pass);
        
        $this->administrador_model->updateUsuario($medio->id_usuario, $datos_usuario);
        
        // Registramos en el log la acción
        $registroLog = array(
            'id_usuario' => $medio->id_usuario,
            'fecha' => date("Y-m-d H:i:s"),
            'accion' => 'Envío de datos de acceso'
        
        );
        $this->administrador_model->insertLogAccion($registroLog);
        
        // enviar email
        $mensaje = $this->load->view('agencias/email_datos_acceso', array(
            'usuario' => $medio->nick,
            'pass' => $pass
        ), TRUE);
        
        $this->load->library('email');
        $this->email->from(EMAIL_OFICIAL);
        $this->email->to($medio->email);
        
        $this->email->subject('Bimads: Nuevos datos de acceso');
        $this->email->message($mensaje);
        
        $this->email->send();
        
        $this->session->set_flashdata('correcto', 'Los datos de acceso se han enviado correctamente');
        
        redirect('administrador/editarMedio/' . $id_medio);
    }

    /**
     * Envia los datos de acceso de nuevo al cliente especificado
     *
     * @param integer $id_cliente
     *            Id del medio al que enviar los datos de acceso
     */
    function enviarDatosAccesoCliente($id_cliente)
    {
        $this->load->model('clientes_model');
        $this->load->model('administrador_model');
        
        $cliente = $this->clientes_model->getClienteAdmin($id_cliente);
        
        if (empty($cliente))
            redirect('administrador/editarAnunciante/' . $id_cliente);
        
        $pass = $this->generarCodigo();
        
        $datos_usuario['pass'] = md5($pass);
        
        $this->administrador_model->updateUsuario($cliente->id_usuario, $datos_usuario);
        
        // Registramos en el log la acción
        $registroLog = array(
            'id_usuario' => $cliente->id_usuario,
            'fecha' => date("Y-m-d H:i:s"),
            'accion' => 'Envío de datos de acceso'
        
        );
        $this->administrador_model->insertLogAccion($registroLog);
        
        // enviar email
        $mensaje = $this->load->view('agencias/email_datos_acceso', array(
            'usuario' => $cliente->nick,
            'pass' => $pass
        ), TRUE);
        
        $this->load->library('email');
        $this->email->from(EMAIL_OFICIAL);
        $this->email->to($cliente->email);
        
        $this->email->subject('Bimads: Nuevos datos de acceso');
        $this->email->message($mensaje);
        
        $this->email->send();
        
        $this->session->set_flashdata('correcto', 'Los datos de acceso se han enviado correctamente');
        
        redirect('administrador/editarAnunciante/' . $id_cliente);
    }

    /**
     * Muestra las estadisticas de la newsletter especificada
     *
     * @param integer $id_newsletter
     *            Id de la newsletter
     */
    function estadisticasNewsletter($id_newsletter)
    {
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $estadisticasGenerales = $this->newsletters_model->getEstadisticasGenerales(array(
            'newsletter' => $id_newsletter
        ));
        $estadisticasClientes = $this->newsletters_model->getEstadisticasClientes(array(
            'newsletter' => $id_newsletter
        ));
        $newsletter = $this->newsletters_model->getNewsletter($id_newsletter);
        
        // Ver todas las consultas que se realizan como un benchmark
        // $this->output->enable_profiler(TRUE);
        
        $data['opc'] = 'newsletters';
        $data['newsletter'] = $newsletter;
        $data['estadisticasGenerales'] = (empty($estadisticasGenerales)) ? NULL : $estadisticasGenerales[0];
        $data['estadisticasClientes'] = $estadisticasClientes;
        $data['page'] = 'administrador/estadisticasNewsletter';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Genera un codigo de 12 caracteres alfanumericos aleatorio
     *
     * @return string Codigo generado
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
     * Muestra el listado de los gestores de medios
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los medios
     */
    function gestorMedios($modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('gestores_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            // Por defecto siempre mostramos los dados de alta
            $filtro['estado'] = 0;
            $this->session->set_userdata('filtro_gestor', $filtro);
        } else if ($modo === '1') {
            
            $filtro['estado'] = $this->input->post('estado');
            $filtro['nombre'] = $this->input->post('nombre');
            
            $this->session->set_userdata('filtro_gestor', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_gestor') === false) ? array() : $this->session->userdata('filtro_gestor');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        
        $gestores = $this->gestores_model->getGestoresAdmin($filtro);
        $numGestores = $this->gestores_model->getNumGestoresAdmin();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/gestorMedios/2";
        $config["total_rows"] = $numGestores;
        $config["per_page"] = $filtro['datosPorPagina'];
        $config["uri_segment"] = 4;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'gestor_medios';
        $data['gestores'] = $gestores;
        $data['total_gestores'] = $numGestores;
        $data['filtro'] = $filtro;
        $data['page'] = 'administrador/gestor_medios';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Obtiene las agencias a mostrar en la vista de creacion de la newsletter, llamada por ajax
     *
     * Parametros Post:
     * array agencias - Array de agencias ya seleccionadas a excluir del resultado obtenido
     * string palabra - Palabra por la que filtrar las agencias
     */
    function getAgenciasNewsletter()
    {
        $this->load->model('newsletters_model');
        
        $filtro['agencias'] = ($this->input->post('agencias') == '') ? array() : explode(' ', $this->input->post('agencias'));
        $filtro['palabra'] = $this->input->post('palabra');
        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 50;
        
        $data['agencias'] = $this->newsletters_model->getAgenciasNewsletter($filtro);
        $data['num_agencias'] = $this->newsletters_model->getNumAgenciasNewsletter();
        
        $this->load->view('administrador/agencias_newsletter', $data);
    }

    /**
     * Obtiene los anunciantes a mostrar en la vista de creacion de la newsletter, llamada por ajax
     *
     * Parametros Post:
     * array clientes - Array de anunciantes ya seleccionados a excluir del resultado obtenido
     * string palabra - Palabra por la que filtrar los anunciantes
     */
    function getClientesNewsletter()
    {
        $this->load->model('newsletters_model');
        
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        
        $filtro['clientes'] = ($this->input->post('clientes') == '') ? array() : explode(' ', $this->input->post('clientes'));
        $filtro['ofertas'] = (empty($datos_newsletter['ofertas'])) ? array() : explode(' ', $datos_newsletter['ofertas']);
        $filtro['palabra'] = $this->input->post('palabra');
        //$filtro['agencia'] = 1;
        $filtro['pagina'] = 1;
        
        $filtro['datosPorPagina'] = 300;
        
        $data['clientes'] = $this->newsletters_model->getClientesNewsletter($filtro);
        $data['num_clientes'] = $this->newsletters_model->getNumClientesNewsletter();
        
        $this->load->view('administrador/clientes_newsletter', $data);
    }

    /**
     * Obtiene el select de medios para la vista de creacion y edicion de oferta
     *
     * @param integer $tipo_medio
     *            Id del tipo medio por el que filtrar los medios
     * @param integer $medio
     *            Id del medio que debe aparecer seleccionado en el select
     */
    function getMedios($tipo_medio, $medio)
    {
        $this->load->model('medios_model');
        
        $filtro['tipo_medio'] = $tipo_medio;
        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 100;
        
        $data['medios'] = $this->medios_model->getMediosAdmin($filtro);
        $data['seleccionado'] = $medio;
        
        $this->load->view('administrador/select_medios', array_merge($data));
    }

    /**
     * Obtiene los medios a mostrar en la vista de creacion de la newsletter, llamada por ajax
     *
     * Parametros Post:
     * array medios - Array de medios ya seleccionados a excluir del resultado obtenido
     * string palabra - Palabra por la que filtrar los medios
     */
    function getMediosNewsletter()
    {
        $this->load->model('newsletters_model');
        
        $filtro['medios'] = ($this->input->post('medios') == '') ? array() : explode(' ', $this->input->post('medios'));
        $filtro['palabra'] = $this->input->post('palabra');
        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 50;
        
        $data['medios'] = $this->newsletters_model->getMediosNewsletter($filtro);
        $data['num_medios'] = $this->newsletters_model->getNumMediosNewsletter();
        
        $this->load->view('administrador/medios_newsletter', $data);
    }

    /**
     * Obtiene las ofertas a mostrar en la vista de creacion de la newsletter, llamada por ajax
     *
     * Parametros Post:
     * array ofertas - Array de ofertas ya seleccionadas a excluir del resultado obtenido
     * string palabra - Palabra por la que filtrar las ofertas
     */
    function getOfertasNewsletter()
    {
        $this->load->model('newsletters_model');
        
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        
        $filtro['ofertas'] = ($this->input->post('ofertas') == '') ? array() : explode(' ', $this->input->post('ofertas'));
        $filtro['medios'] = (empty($datos_newsletter['medios'])) ? array() : explode(' ', $datos_newsletter['medios']);
        $filtro['palabra'] = $this->input->post('palabra');
        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 50;
        
        $data['ofertas'] = $this->newsletters_model->getOfertasNewsletter($filtro);
        $data['num_ofertas'] = $this->newsletters_model->getNumOfertasNewsletter();
        
        $this->load->view('administrador/ofertas_newsletter', $data);
    }

    /**
     * Muestra el panel de control del administrador
     */
    function index()
    {
        $this->load->model('administrador_model');
        
        if ($this->session->userdata('tipo_usuario') == 'admin') {
            $data['usuarios_pendientes'] = $this->administrador_model->getNumUsuariosPendientes();
            $data['usuarios_permisos_pendientes'] = $this->administrador_model->getNumUsuariosPermisosPendientes();
            $data['ofertas_gestionar'] = $this->administrador_model->getNumOfertasGestionar();
            $data['ofertas_publicadas'] = $this->administrador_model->getNumOfertasPublicadas();
            $data['ofertas_caducan_hoy'] = $this->administrador_model->getNumOfertasCaducanHoy();
            $data['ofertas_caducan_10_dias'] = $this->administrador_model->getNumOfertasCaducan10Dias();
            
            $data['opc'] = 'home';
            $data['page'] = 'administrador/panel_control';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            redirect('/inicio/index/6');
        }
    }

    /**
     * Muestra los anunciantes inscritos a la oferta especifica
     *
     * @param integer $id_oferta
     *            Id de la oferta por la que filtrar las inscripciones
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de las inscripciones
     */
    function inscripcionesOferta($id_oferta, $modo = 0, $pagina = 1)
    {
        $this->load->model('ofertas_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $filtro = array();
        
        if ($modo == 1) {
            $filtro['estado'] = $this->input->post('estado');
        } else if ($modo == 2) {
            $filtro = $this->session->userdata('filtro_inscripciones');
            
            if (empty($filtro))
                $filtro = array();
        }
        
        $this->session->set_userdata('filtro_inscripciones', $filtro);
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 15;
        $filtro['oferta'] = $id_oferta;
        
        $data['opc'] = 'ofertas';
        $inscripciones = $this->ofertas_model->getInscripciones($filtro);
        $numInscripciones = $this->ofertas_model->getNumInscripciones();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/inscripcionesOferta/" . $id_oferta . "/2/";
        $config["total_rows"] = $numInscripciones;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 5;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['oferta'] = $this->ofertas_model->getOfertaAdmin($id_oferta);
        $data['filtro'] = $filtro;
        $data['opc'] = 'inscripciones';
        $data['inscripciones'] = $inscripciones;
        $data['page'] = 'administrador/inscripciones_oferta';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de logs del medio especificado
     *
     * @param integer $id_medio
     *            Id del medio por el que filtrar los permisos
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los permisos
     */
    function logsMedio($id_medio, $modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('medios_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '1') {
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        $filtro['medio'] = $id_medio;
        
        $acciones = $this->medios_model->getMedioLogs($filtro);
        $permisos = $this->medios_model->getPermisosMedioLog($id_medio);
        $numAcciones = count($acciones);
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/logsMedio/" . $id_medio . "/2";
        $config["total_rows"] = $numAcciones;
        $config["per_page"] = $filtro['datosPorPagina'];
        $config["uri_segment"] = 5;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'medios';
        $data['filtro'] = $filtro;
        $data['medio'] = $this->medios_model->getMedio($id_medio);
        $data['acciones'] = $acciones;
        $data['permisos'] = $permisos;
        $data['page'] = 'administrador/logs_medio';
        
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de medios
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los medios
     */
    function medios($modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('medios_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            // Por defecto siempre mostramos los dados de alta
            $filtro['estado'] = 1;
            $filtro['order_by_campo']='id_medio';
            $filtro['order_by_sentido']='desc';
            
            $this->session->set_userdata('filtro_medios', $filtro);
        } else if ($modo === '1') {
            $filtro['medio'] = $this->input->post('medio');
            $filtro['estado'] = $this->input->post('estado');
            $filtro['permisos'] = $this->input->post('permisos');
            $filtro['tipo_medio'] = $this->input->post('tipo_medio');
            $filtro['order_by_campo']=$this->input->post('order_by_campo');
            $filtro['order_by_sentido']=$this->input->post('order_by_sentido');
            
            $this->session->set_userdata('filtro_medios', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_medios') === false) ? array() : $this->session->userdata('filtro_medios');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        
        $medios = $this->medios_model->getMediosAdmin($filtro);
        $numMedios = $this->medios_model->getNumMediosAdmin();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/medios/2";
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
        $data['page'] = 'administrador/medios';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra los detalles de la newsletter especificada
     *
     * @param integer $id_newsletter
     *            Id de la newsletter a mostrar en detalle
     */
    function newsletter($id_newsletter)
    {
        $this->load->library('ckeditor', array(
            'instanceName' => 'CKEDITOR1',
            'basePath' => base_url() . 'ckeditor/',
            'outPut' => true
        ));
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $newsletter = $this->newsletters_model->getNewsletter($id_newsletter);
        
        $data['opc'] = 'newsletters';
        $data['newsletter'] = $newsletter;
        $data['page'] = 'administrador/newsletter';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra la lista de newsletters
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param pagina $pagina
     *            Pagina a mostrar del listado de newsletters
     */
    function newsletters($modo = '0', $pagina = 1)
    {
        $this->load->model('newsletters_model');
        $this->load->library('pagination');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_newsletters', $filtro);
        } else if ($modo === '1') {
            $filtro['estado'] = $this->input->post('estado');
            $filtro['palabra'] = $this->input->post('palabra');
            
            $this->session->set_userdata('filtro_newsletters', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_newsletters') === false) ? array() : $this->session->userdata('filtro_newsletters');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 15;
        
        $newsletters = $this->newsletters_model->getNewsletters($filtro);
        $numNewsletters = $this->newsletters_model->getNumNewsletters();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/newsletters/2";
        $config["total_rows"] = $numNewsletters;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 4;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'newsletters';
        $data['newsletters'] = $newsletters;
        $data['filtro'] = $filtro;
        $data['page'] = 'administrador/newsletters';
        $this->load->view('administrador_container', array_merge($data));
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
     * Muestra el formulario de creacion de una nueva agencia
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function nuevaAgencia($modo = 0)
    {
        $this->load->model('agencias_model');
        $this->load->library('form_validation');
        $this->load->model('administrador_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'agencias';
        
        if ($modo == 0) {
            $data['page'] = 'administrador/nueva_agencia';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('direccion', 'Dirección', 'trim|max_length[200]');
            $this->form_validation->set_rules('poblacion', 'Población', 'trim|max_length[100]');
            $this->form_validation->set_rules('cp', 'Código Postal', 'trim|max_length[20]');
            $this->form_validation->set_rules('porcentaje', 'Porcentaje de beneficio', 'trim|required');
            $this->form_validation->set_rules('cif', 'CIF', 'trim|max_length[20]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como m�ximo %s caracteres.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            $this->form_validation->set_message('emailEsUnico', 'Este email ya est� siendo utilizado, por favor utilice otro.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['page'] = 'administrador/nueva_agencia';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $password = $this->generarCodigo();
                
                $usuario = array(
                    'nick' => $this->input->post('email'),
                    'pass' => md5($password),
                    'tipo_usuario' => 'agencia',
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                    'estado' => 0
                );
                
                $id_usuario = $this->administrador_model->insertUsuario($usuario);
                
                $datos_agencia = array(
                    'nombre' => $this->input->post('nombre'),
                    'codigo' => '',
                    'email' => $this->input->post('email'),
                    'telefono' => $this->input->post('telefono'),
                    'direccion' => $this->input->post('direccion'),
                    'poblacion' => $this->input->post('poblacion'),
                    'cp' => $this->input->post('cp'),
                    'cif' => $this->input->post('cif'),
                    'porcentaje' => $this->input->post('porcentaje'),
                    'id_provincia' => 1,
                    'id_pais' => 1,
                    'fecha_alta' => date('Y-m-d H:i:s'),
                    'id_usuario' => $id_usuario
                );
                
                $id_agencia = $this->agencias_model->insertAgencia($datos_agencia);
                
                // enviar email
                $mensaje = $this->load->view('administrador/email_nueva_agencia', array(
                    'nombre' => $this->input->post('nombre'),
                    'usuario' => $this->input->post('email'),
                    'pass' => $password
                ), TRUE);
                
                $this->load->library('email');
                $this->email->from(EMAIL_OFICIAL);
                $this->email->to($this->input->post('email'));
                
                $this->email->subject('Bimads: Su agencia, ' . $this->input->post('nombre') . ', ha sido dada de alta');
                $this->email->message($mensaje);
                
                $this->email->send();
                
                $this->session->set_flashdata('mensajeNuevaAgencia', 'La agencia se creo correctamente. Los datos de acceso han sido enviados a la agencia.');
                
                redirect('administrador/editarAgencia/' . $id_agencia);
            }
        }
    }

    /**
     * Muestra el formulario para confirmar una newsletter
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 confirma la newsletter
     */
    function nuevaNewsletterConfirmacion($modo = 0)
    {
        $this->load->library('ckeditor', array(
            'instanceName' => 'CKEDITOR1',
            'basePath' => base_url() . 'ckeditor/',
            'outPut' => true
        ));
        $this->load->library('form_validation');
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'newsletters';
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        $data['mensaje_newsletter_confirmacion'] = $this->session->flashdata('mensaje_newsletter_confirmacion');
        
        $ofertas = (empty($datos_newsletter['ofertas'])) ? array() : explode(' ', $datos_newsletter['ofertas']);
        $medios = (empty($datos_newsletter['medios'])) ? array() : explode(' ', $datos_newsletter['medios']);
        $clientes = (empty($datos_newsletter['clientes'])) ? array() : explode(' ', $datos_newsletter['clientes']);
        $agencias = (empty($datos_newsletter['agencias'])) ? array() : explode(' ', $datos_newsletter['agencias']);
        
        if ($modo == 0) {
            $clientes = (empty($clientes)) ? array() : $this->newsletters_model->getClientes($ofertas, $clientes);
            $agencias = (empty($agencias)) ? array() : $this->newsletters_model->getAgencias($agencias);
            $ofertas = (empty($ofertas)) ? array() : $this->newsletters_model->getOfertas($medios, $ofertas);
            $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
            
            $data['datos_newsletter'] = $datos_newsletter;
            $data['clientes'] = $clientes;
            $data['agencias'] = $agencias;
            $data['medios'] = $medios;
            $data['ofertas'] = $ofertas;
            $data['page'] = 'administrador/nueva_newsletter_confirmacion';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('confirmacion', 'Confirmaci�n', 'trim|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $clientes = (empty($clientes)) ? array() : $this->newsletters_model->getClientes($ofertas, $clientes);
                $agencias = (empty($agencias)) ? array() : $this->newsletters_model->getAgencias($agencias);
                $ofertas = (empty($ofertas)) ? array() : $this->newsletters_model->getOfertas($medios, $ofertas);
                $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
                
                $data['datos_newsletter'] = $datos_newsletter;
                $data['clientes'] = $clientes;
                $data['agencias'] = $agencias;
                $data['medios'] = $medios;
                $data['ofertas'] = $ofertas;
                $data['validado'] = true;
                $data['page'] = 'administrador/nueva_newsletter_confirmacion';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $datos_newsletter = $this->session->userdata('datos_newsletter');
                
                $newsletter = array(
                    'nombre' => $datos_newsletter['nombre'],
                    'asunto' => $datos_newsletter['asunto'],
                    'descripcion' => $datos_newsletter['descripcion'],
                    'id_agencia' => 0,
                    'medios' => $datos_newsletter['medios'],
                    'ofertas' => $datos_newsletter['ofertas'],
                    'estado' => 'p',
                    'confirmada' => ($modo == 1) ? 1 : 0
                );
                
                $id_newsletter = 0;
                
                if (empty($datos_newsletter['id_newsletter'])) {
                    $newsletter['fecha'] = date('y-m-d H:i:s');
                    
                    $id_newsletter = $this->newsletters_model->insertNewsletter($newsletter);
                } else {
                    $id_newsletter = $datos_newsletter['id_newsletter'];
                    
                    $this->newsletters_model->deleteDestinatariosNewsletter($id_newsletter);
                    $this->newsletters_model->updateNewsletter($id_newsletter, $newsletter);
                }
                
                if (! empty($datos_newsletter['clientes']))
                    $this->newsletters_model->insertClientesNewsletter($id_newsletter, explode(' ', $datos_newsletter['clientes']));
                
                if (! empty($datos_newsletter['agencias']))
                    $this->newsletters_model->insertAgenciasNewsletter($id_newsletter, explode(' ', $datos_newsletter['agencias']));
                
                $this->session->unset_userdata('datos_newsletter');
                
                redirect('administrador/newsletter/' . $id_newsletter);
            }
        }
    }

    /**
     * Muestra el formulario para crear o editar una newsletter y la selecion de destinatarios (anunciantes y agencias)
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si, 2 valida y guarda los datos en la bd para continuar en otro momento, 3 elimina cualquier dato de newsletter guardado en la sesion
     */
    function nuevaNewsletterDestinatarios($modo = 0)
    {
        $this->load->library('ckeditor', array(
            'instanceName' => 'CKEDITOR1',
            'basePath' => base_url() . 'ckeditor/',
            'outPut' => true
        ));
        $this->load->library('form_validation');
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        
        $data['opc'] = 'newsletters';
        $ofertas = (empty($datos_newsletter['ofertas'])) ? array() : explode(' ', $datos_newsletter['ofertas']);
        $medios = (empty($datos_newsletter['medios'])) ? array() : explode(' ', $datos_newsletter['medios']);
        
        if ($modo == 0) {
            $clientes = (empty($datos_newsletter['clientes'])) ? array() : $this->newsletters_model->getClientes($ofertas, explode(' ', $datos_newsletter['clientes']));
            $agencias = (empty($datos_newsletter['agencias'])) ? array() : $this->newsletters_model->getAgencias(explode(' ', $datos_newsletter['agencias']));
            $ofertas = (empty($ofertas)) ? array() : $this->newsletters_model->getOfertas($medios, $ofertas);
            $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
            
            $data['datos_newsletter'] = $datos_newsletter;
            $data['clientes'] = $clientes;
            $data['agencias'] = $agencias;
            $data['medios'] = $medios;
            $data['ofertas'] = $ofertas;
            $data['page'] = 'administrador/nueva_newsletter_destinatarios';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('clientes', 'Clientes', 'trim');
            $this->form_validation->set_rules('agencias', 'Agencias', 'trim');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE || ($this->input->post('clientes') == '' && $this->input->post('agencias') == '')) {
                $clientes = ($this->input->post('clientes') == '') ? array() : $this->newsletters_model->getClientes($ofertas, explode(' ', $this->input->post('clientes')));
                $agencias = ($this->input->post('agencias') == '') ? array() : $this->newsletters_model->getAgencias(explode(' ', $this->input->post('agencias')));
                $ofertas = (empty($ofertas)) ? array() : $this->newsletters_model->getOfertas($medios, $ofertas);
                $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
                
                $data['datos_newsletter'] = $datos_newsletter;
                $data['clientes'] = $clientes;
                $data['agencias'] = $agencias;
                $data['medios'] = $medios;
                $data['ofertas'] = $ofertas;
                $data['validado'] = true;
                $data['page'] = 'administrador/nueva_newsletter_destinatarios';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                if ($modo == 1) {
                    $datos_newsletter['clientes'] = $this->input->post('clientes');
                    $datos_newsletter['agencias'] = $this->input->post('agencias');
                    
                    $this->session->set_userdata('datos_newsletter', $datos_newsletter);
                    
                    redirect('administrador/nuevaNewsletterConfirmacion');
                } else {
                    $newsletter = array(
                        'nombre' => $datos_newsletter['nombre'],
                        'asunto' => $datos_newsletter['asunto'],
                        'descripcion' => $datos_newsletter['descripcion'],
                        'id_agencia' => 0,
                        'medios' => $datos_newsletter['medios'],
                        'ofertas' => $datos_newsletter['ofertas'],
                        'estado' => 'p',
                        'confirmada' => 0
                    );
                    
                    $id_newsletter = $datos_newsletter['id_newsletter'];
                    
                    if (empty($datos_newsletter['id_newsletter'])) {
                        $newsletter['fecha'] = date('y-m-d H:i:s');
                        
                        $this->newsletters_model->insertNewsletter($newsletter);
                    } else {
                        $this->newsletters_model->deleteDestinatariosNewsletter($id_newsletter);
                        $this->newsletters_model->updateNewsletter($id_newsletter, $newsletter);
                    }
                    
                    if ($this->input->post('clientes') != '')
                        $this->newsletters_model->insertClientesNewsletter($id_newsletter, explode(' ', $this->input->post('clientes')));
                    
                    if ($this->input->post('agencias') != '')
                        $this->newsletters_model->insertAgenciasNewsletter($id_newsletter, explode(' ', $this->input->post('agencias')));
                    
                    $this->session->unset_userdata('datos_newsletter');
                    
                    redirect('administrador/newsletters');
                }
            }
        }
    }

    /**
     * Muestra el formulario para crear o editar una newsletter y la selecion de medios
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si, 2 valida y guarda los datos en la bd para continuar en otro momento, 3 elimina cualquier dato de newsletter guardado en la sesion
     */
    function nuevaNewsletterMedios($modo = 0)
    {
        $this->load->library('ckeditor', array(
            'instanceName' => 'CKEDITOR1',
            'basePath' => base_url() . 'ckeditor/',
            'outPut' => true
        ));
        $this->load->library('form_validation');
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo == 3) {
            $this->session->unset_userdata('datos_newsletter');
            redirect('administrador/nuevaNewsletterMedios');
        }
        
        $this->load->model('newsletters_model');
        
        $data['opc'] = 'newsletters';
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        $data['descripcion_defecto'] = 'Bimads es la agencia de medios que ofrece <b>espacios publicitarios de &uacute;ltima hora</b> en los grandes medios nacionales.<br>Te ofrecemos la posibilidad de estar presente en los <b>medios</b> de <b>primera l&iacute;nea</b> nacionales <b>con presupuestos peque&ntilde;os</b>. <b>Con muy poco conseguimos mucho</b>.<br>Estas son las <b>ofertas destacadas</b> que tenemos actualmente. Sino encuentras lo que buscas en este mail, ll&aacute;manos y buscaremos ofertas <b>adaptadas a tus necesidades</b>.';
        
        if ($modo == 0) {
            $medios = (empty($datos_newsletter['medios'])) ? array() : $this->newsletters_model->getMedios(explode(' ', $datos_newsletter['medios']));
            
            $data['datos_newsletter'] = $datos_newsletter;
            $data['medios'] = $medios;
            $data['page'] = 'administrador/nueva_newsletter_medios';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required');
            $this->form_validation->set_rules('asunto', 'Asunto', 'trim|required');
            $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|required');
            $this->form_validation->set_rules('medios', 'Medios', 'trim|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $medios = ($this->input->post('medios') == '') ? array() : $this->newsletters_model->getMedios(explode(' ', $this->input->post('medios')));
                
                $data['datos_newsletter'] = $datos_newsletter;
                $data['validado'] = true;
                $data['medios'] = $medios;
                $data['page'] = 'administrador/nueva_newsletter_medios';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                if ($modo == 1) {
                    if (empty($datos_newsletter))
                        $datos_newsletter = array();
                    
                    $datos_newsletter['nombre'] = $this->input->post('nombre');
                    $datos_newsletter['asunto'] = $this->input->post('asunto');
                    $datos_newsletter['descripcion'] = $this->input->post('descripcion');
                    $datos_newsletter['medios'] = $this->input->post('medios');
                    
                    $this->session->set_userdata('datos_newsletter', $datos_newsletter);
                    
                    redirect('administrador/nuevaNewsletterOfertas');
                } else {
                    $newsletter = array(
                        'nombre' => $this->input->post('nombre'),
                        'asunto' => $this->input->post('asunto'),
                        'descripcion' => $this->input->post('descripcion'),
                        'id_agencia' => 0,
                        'medios' => $this->input->post('medios'),
                        'ofertas' => '',
                        'estado' => 'p',
                        'confirmada' => 0
                    );
                    
                    $id_newsletter = $datos_newsletter['id_newsletter'];
                    
                    if (empty($datos_newsletter['id_newsletter'])) {
                        $newsletter['fecha'] = date('y-m-d H:i:s');
                        
                        $this->newsletters_model->insertNewsletter($newsletter);
                    } else {
                        $this->newsletters_model->deleteDestinatariosNewsletter($id_newsletter);
                        $this->newsletters_model->updateNewsletter($id_newsletter, $newsletter);
                    }
                    
                    $this->session->unset_userdata('datos_newsletter');
                    
                    redirect('administrador/newsletters');
                }
            }
        }
    }

    /**
     * Muestra el formulario para crear o editar una newsletter y la selecion de ofertas
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si, 2 valida y guarda los datos en la bd para continuar en otro momento, 3 elimina cualquier dato de newsletter guardado en la sesion
     */
    function nuevaNewsletterOfertas($modo = 0)
    {
        $this->load->library('ckeditor', array(
            'instanceName' => 'CKEDITOR1',
            'basePath' => base_url() . 'ckeditor/',
            'outPut' => true
        ));
        $this->load->library('form_validation');
        $this->load->model('newsletters_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $datos_newsletter = $this->session->userdata('datos_newsletter');
        
        $medios = (empty($datos_newsletter['medios'])) ? array() : explode(' ', $datos_newsletter['medios']);
        
        $data['opc'] = 'newsletters';
        if ($modo == 0) {
            $ofertas = (empty($datos_newsletter['ofertas'])) ? array() : $this->newsletters_model->getOfertas($medios, explode(' ', $datos_newsletter['ofertas']));
            $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
            
            $data['datos_newsletter'] = $datos_newsletter;
            $data['ofertas'] = $ofertas;
            $data['medios'] = $medios;
            $data['page'] = 'administrador/nueva_newsletter_ofertas';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('ofertas', 'Ofertas', 'trim|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $ofertas = ($this->input->post('ofertas') == '') ? array() : $this->newsletters_model->getOfertas($medios, explode(' ', $this->input->post('ofertas')));
                $medios = (empty($medios)) ? array() : $this->newsletters_model->getMedios($medios);
                
                $data['datos_newsletter'] = $datos_newsletter;
                $data['validado'] = true;
                $data['ofertas'] = $ofertas;
                $data['medios'] = $medios;
                $data['page'] = 'administrador/nueva_newsletter_ofertas';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                if ($modo == 1) {
                    $datos_newsletter['ofertas'] = $this->input->post('ofertas');
                    
                    $this->session->set_userdata('datos_newsletter', $datos_newsletter);
                    
                    redirect('administrador/nuevaNewsletterDestinatarios');
                } else {
                    $newsletter = array(
                        'nombre' => $datos_newsletter['nombre'],
                        'asunto' => $datos_newsletter['asunto'],
                        'descripcion' => $datos_newsletter['descripcion'],
                        'id_agencia' => 0,
                        'medios' => $datos_newsletter['medios'],
                        'ofertas' => $this->input->post('ofertas'),
                        'estado' => 'p',
                        'confirmada' => 0
                    );
                    
                    $id_newsletter = $datos_newsletter['id_newsletter'];
                    
                    if (empty($datos_newsletter['id_newsletter'])) {
                        $newsletter['fecha'] = date('y-m-d H:i:s');
                        
                        $this->newsletters_model->insertNewsletter($newsletter);
                    } else {
                        $this->newsletters_model->deleteDestinatariosNewsletter($id_newsletter);
                        $this->newsletters_model->updateNewsletter($id_newsletter, $newsletter);
                    }
                    
                    $this->session->unset_userdata('datos_newsletter');
                    
                    redirect('administrador/newsletters');
                }
            }
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
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo == 2) {
            $this->session->unset_userdata('datos_oferta');
            redirect('administrador/nuevaOferta');
        }
        
        $data['opc'] = 'ofertas';
        $data['tipos_medio'] = $this->medios_model->getTiposMedios();
        $data['provincias'] = $this->clientes_model->getProvincias();
        $data['sectores'] = $this->clientes_model->getSectores();
        $data['tipos_oferta'] = $this->clientes_model->getTiposOferta();
        $data['page'] = 'administrador/nueva_oferta';
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
            $this->form_validation->set_rules('id_tipo_medio', 'Tipo de medio', 'trim|required');
            $this->form_validation->set_rules('id_medio', 'Medio', 'trim|required');
            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');
            $this->form_validation->set_rules('sector', 'Sector', 'trim');
            $this->form_validation->set_rules('publicada', 'Publicar', 'trim');
            $this->form_validation->set_rules('galeria_img', 'Galeria Imágenes', 'trim');
            $this->form_validation->set_rules('link', 'Link medio', 'trim|callback_urlCheck');
            $this->form_validation->set_rules('id_tipo_oferta', 'Tipo Oferta', 'trim');
            
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
                        'id_medio' => $this->input->post('id_medio'),
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
                        'galeria_img' => $this->input->post('galeria_img'),
                        'publicada' => ($this->input->post('publicada') == '') ? 0 : 1,
                        'fecha_insercion' => date('Y-m-d H:i:s'),
                        'duracion_camp' => $this->input->post('duracion_camp'),
                        'link' => $this->input->post('link')
                    );
                    
                    $id_oferta = $this->ofertas_model->insertOferta($datos_oferta);
                    
                    redirect('administrador/ofertas');
                }
            }
        }
        
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el formulario de creacion de un nuevo anunciante
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function nuevoAnunciante($modo = 0)
    {
        $this->load->model('agencias_model');
        $this->load->library('form_validation');
        $this->load->model('administrador_model');
        $this->load->model('clientes_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'anunciantes';
        $data['agencias'] = $this->agencias_model->getAgencias();
        $data['provincias'] = $this->clientes_model->getProvincias();
        $data['sectores'] = $this->clientes_model->getSectores();
        
        if ($modo == 0) {
            $data['page'] = 'administrador/nuevo_anunciante';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Empresa', 'trim|required');
            $this->form_validation->set_rules('nick', 'Nick', 'trim|required|callback_emailEsUnico');
            $this->form_validation->set_rules('telefono', 'Tel�fono', 'trim');
            $this->form_validation->set_rules('provincia', 'Provincia', 'trim');
            $this->form_validation->set_rules('sector', 'Sector', 'trim');
            $this->form_validation->set_rules('email', 'Email', 'trim');
            $this->form_validation->set_rules('web', 'Web', 'trim');
            $this->form_validation->set_rules('agencia', 'Agencia', 'trim|required');
            $this->form_validation->set_rules('notificacion', 'Notificar', 'trim');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('emailEsUnico', 'Este email ya está siendo utilizado, por favor utilice otro.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['page'] = 'administrador/nuevo_anunciante';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $pass = $this->generarCodigo();
                
                $datos_usuario = array(
                    'nick' => $this->input->post('nick'),
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
                    'id_provincia' => $this->input->post('provincia'),
                    'id_sector' => $this->input->post('sector'),
                    'email' => $this->input->post('email'),
                    'web' => $this->input->post('web'),
                    'id_usuario' => $id_usuario,
                    'condiciones' => 1,
                    'id_agencia' => $this->input->post('agencia')
                );
                
                $id_cliente = $this->clientes_model->insertCliente($datos_cliente);
                
                if ($this->input->post('notificacion') != '') {
                    
                    $this->load->library('email');
                    $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                    $this->email->to($this->input->post('email'));
                    
                    $this->email->subject('Bimads: Su cuenta de anunciante ha sido dada de alta');
                    $mensaje = $this->load->view('administrador/email_nuevo_anunciante', array(
                        'nombre' => $this->input->post('nombre'),
                        'usuario' => $this->input->post('email'),
                        'pass' => $pass
                    ), TRUE);
                    
                    $this->email->message($mensaje);
                    
                    $this->email->send();
                }
                
                redirect('administrador/editarAnunciante/' . $id_cliente);
            }
        }
    }

    /**
     * Muestra el formulario de creacion de una nueva agencia
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function nuevoGestorMedio($modo = 0)
    {
        $this->load->model('gestores_model');
        $this->load->library('form_validation');
        $this->load->model('administrador_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'gestor_medios';
        
        if ($modo == 0) {
            $data['page'] = 'administrador/nuevo_gestor';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[100]|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_emailEsUnico|required');
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
            $this->form_validation->set_message('valid_email', 'El email no tiene un formato correcto.');
            $this->form_validation->set_message('emailEsUnico', 'Este email ya está siendo utilizado, por favor utilice otro.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['page'] = 'administrador/nuevo_gestor';
                $this->load->view('administrador_container', array_merge($data));
            } else {
                $password = $this->generarCodigo();
                
                $usuario = array(
                    'nick' => $this->input->post('email'),
                    'pass' => md5($password),
                    'tipo_usuario' => 'gestor',
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                    'estado' => 0
                );
                
                $id_usuario = $this->administrador_model->insertUsuario($usuario);
                
                $datos_gestor = array(
                    'nombre' => $this->input->post('nombre'),
                    'email' => $this->input->post('email'),
                    'id_usuario' => $id_usuario
                );
                
                $id_gestor = $this->gestores_model->insertGestor($datos_gestor);
                
                if ($this->input->post('notificacion') != '') {
                    // enviar email
                    $mensaje = $this->load->view('administrador/email_nuevo_gestor', array(
                        'nombre' => $this->input->post('nombre'),
                        'usuario' => $this->input->post('email'),
                        'pass' => $password
                    ), TRUE);
                    
                    $this->load->library('email');
                    $this->email->from(EMAIL_OFICIAL);
                    $this->email->to($this->input->post('email'));
                    
                    $this->email->subject('Bimads: Su Gestor de Medios ha sido dado de alta');
                    $this->email->message($mensaje);
                    
                    $this->email->send();
                }
                
                $this->session->set_flashdata('mensajeNuevoGestor', 'El Gestor de Medios se creó correctamente.');
                
                redirect('administrador/editarGestorMedio/' . $id_gestor);
            }
        }
    }

    /**
     * Muestra el fomrulario de creacion de un nuevo medio
     *
     * @param integer $modo
     *            Modo de validacion: 0 no valida, 1 si
     */
    function nuevoMedio($modo = 0)
    {
        $this->load->model('medios_model');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('subida');
        $this->load->model('administrador_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        $data['opc'] = 'medios';
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        
        if ($modo == 0) {
            $data['page'] = 'administrador/nuevo_medio';
            $this->load->view('administrador_container', array_merge($data));
        } else {
            $this->form_validation->set_rules('nombre', 'Nombre', 'trim|max_length[255]|required');
            $this->form_validation->set_rules('descripcion', 'Descripci&oacute;n breve', 'trim|required|max_length[2000]');
            $this->form_validation->set_rules('nick', 'Nick', 'trim|required|callback_nickEsUnico');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('tipo_medio', 'Tipo medio', 'trim|required');
            if ($this->input->post('web') !== '') {
                $this->form_validation->set_rules('web', 'Web', 'trim|callback_urlCheck');
            }
            
            // Mensajes de error
            $this->form_validation->set_message('required', '%s es un dato necesario.');
            $this->form_validation->set_message('max_length', '%s admite como máximo %s caracteres.');
            $this->form_validation->set_message('comprobarEmail', 'El email tiene un formato incorrecto.');
            $this->form_validation->set_message('nickEsUnico', 'Ese nick ya está siendo usado.');
            $this->form_validation->set_message('urlCheck', 'La página web tiene un formato incorrecto.');
            
            // Formato del contenedor del error
            $this->form_validation->set_error_delimiters('<span style="font-size: 10pt; font-weight: bold;color:red;">', '</span>');
            
            // Si la validaci�n del formulario falla, cargamos la vista de modificaci�n del perfil con los datos del formulario
            if ($this->form_validation->run() == FALSE) {
                $data['validado'] = true;
                $data['page'] = 'administrador/nuevo_medio';
                $this->load->view('administrador_container', array_merge($data));
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
                    $data['page'] = 'administrador/nuevo_medio';
                    $this->load->view('administrador_container', array_merge($data));
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
                    $data['page'] = 'administrador/nuevo_medio';
                    $this->load->view('administrador_container', array_merge($data));
                    return;
                }
                
                if (empty($logo)) {
                    $logo = 'images/medios/logo/medio_logo_default.png';
                }
                
                $password = $this->generarCodigo();
                
                $usuario = array(
                    'nick' => $this->input->post('nick'),
                    'pass' => md5($password),
                    'tipo_usuario' => 'medio',
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'fecha_ultima_conexion' => date("Y-m-d H:i:s"),
                    'estado' => 0
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
                    'id_usuario' => $id_usuario,
                    'id_tipo_medio' => $this->input->post('tipo_medio')
                );
                
                $id_medio = $this->medios_model->insertMedio($datos_medio);
                
                redirect('administrador/editarMedio/' . $id_medio);
            }
        }
    }

    /**
     * Muestra la lista de ofertas
     *
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de las ofertas
     */
    function ofertas($modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('ofertas_model');
        $this->load->library('pagination');
        $this->load->model('medios_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            // Por defecto siempre mostramos las Vigentes y publicadas
            $filtro['caducidad'] = 1;
            $filtro['publicada'] = 1;
            $filtro['order_by_campo']='id_oferta';
            $filtro['order_by_sentido']='desc';
            
            $this->session->set_userdata('filtro_ofertas', $filtro);
        } else if ($modo === '1') {
            $filtro['estado'] = $this->input->post('select_estado');
            $filtro['caducidad'] = $this->input->post('select_caducidad');
            $filtro['publicada'] = $this->input->post('select_publicada');
            $filtro['destacada'] = $this->input->post('select_destacada');
            $filtro['medio'] = $this->input->post('select_medio');
            $filtro['tipo_medio'] = $this->input->post('tipo_medio');
            $filtro['order_by_campo']=$this->input->post('order_by_campo');
            $filtro['order_by_sentido']=$this->input->post('order_by_sentido');
            
            $this->session->set_userdata('filtro_ofertas', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_ofertas') === false) ? array() : $this->session->userdata('filtro_ofertas');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        
        $ofertas = $this->ofertas_model->getOfertasAdmin($filtro);
        $numOfertas = $this->ofertas_model->getNumOfertasAdmin();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/ofertas/2";
        $config["total_rows"] = $numOfertas;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 4;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'ofertas';
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        $data['ofertas'] = $ofertas;
        $data['filtro'] = $filtro;
        $data['medios'] = $this->medios_model->getMedios();
        $data['total_ofertas'] = $numOfertas;
        $data['page'] = 'administrador/ofertas';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de permisos para medios del anunciante especificado
     *
     * @param integer $id_cliente
     *            Id del anunciante por el que filtrar los permisos
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los permisos
     */
    function permisosAnunciante($id_cliente, $modo = '0', $pagina = 1)
    {
        $this->load->model('clientes_model');
        $this->load->library('pagination');
        $this->load->model('medios_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '1') {
            $filtro['tipo_medio'] = $this->input->post('tipo_medio');
            $filtro['estado'] = $this->input->post('estado');
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = 30;
        $filtro['cliente'] = $id_cliente;
        
        $permisos = $this->clientes_model->getPermisosCliente($filtro);
        $numPermisos = $this->clientes_model->getNumPermisosCliente();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/permisosAnunciante/" . $id_cliente . "/2";
        $config["total_rows"] = $numPermisos;
        $config["per_page"] = $filtro['datosPorPagina'];
        ;
        $config["uri_segment"] = 5;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'anunciantes';
        $data['filtro'] = $filtro;
        $data['cliente'] = $this->clientes_model->getClienteAdmin($id_cliente);
        $data['permisos'] = $permisos;
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        $data['page'] = 'administrador/permisos_anunciante';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de permisos para los medios del gestor especificado
     *
     * @param integer $id_gestor
     *            Id del gestor por el que filtrar los permisos
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los permisos
     */
    function permisosGestorMedio($id_gestor, $modo = '0', $pagina = 1, $datosporpagina = 15)
    {
        $this->load->model('gestores_model');
        $this->load->library('pagination');
        $this->load->model('medios_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '1') {
            $filtro['tipo_medio'] = $this->input->post('tipo_medio');
            $filtro['estado'] = $this->input->post('estado');
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        $filtro['gestor'] = $id_gestor;
        
        $permisos = $this->gestores_model->getPermisosGestorMedios($filtro);
        $numPermisos = $this->gestores_model->getNumPermisosGestor();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/permisosGestorMedio/" . $id_gestor . '/2';
        $config["total_rows"] = $numPermisos;
        $config["per_page"] = $filtro['datosPorPagina'];
        $config["uri_segment"] = 5;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['filtro'] = $filtro;
        $data['opc'] = 'gestor_medios';
        $data['gestor'] = $this->gestores_model->getGestor($id_gestor);
        $data['permisos'] = $permisos;
        $data['tipos_medios'] = $this->medios_model->getTiposMedios();
        $data['page'] = 'administrador/permisos_gestor_medio';
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Muestra el listado de permisos de los anunciantes para medio especificado
     *
     * @param integer $id_medio
     *            Id del medio por el que filtrar los permisos
     * @param integer $modo
     *            Modo de filtrado: 0 filtro vacio, 1 filtro nuevo, 2 filtro anterior
     * @param integer $pagina
     *            Pagina a mostrar de los permisos
     */
    function permisosMedio($id_medio, $modo = '0', $pagina = 1, $datosporpagina = 30)
    {
        $this->load->model('clientes_model');
        $this->load->model('medios_model');
        $this->load->library('pagination');
        $this->load->model('agencias_model');
        
        if ($this->session->userdata('tipo_usuario') != 'admin')
            redirect('inicio');
        
        if ($modo === '0') {
            $filtro = array();
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '1') {
            $filtro['agencia'] = $this->input->post('agencia');
            $filtro['estado'] = $this->input->post('estado');
            $filtro['anunciante'] = $this->input->post('anunciante');
            $filtro['provincia'] = $this->input->post('provincia');
            
            $this->session->set_userdata('filtro_permisos', $filtro);
        } else if ($modo === '2') {
            $filtro = ($this->session->userdata('filtro_permisos') === false) ? array() : $this->session->userdata('filtro_permisos');
        }
        
        $filtro['pagina'] = $pagina;
        $filtro['datosPorPagina'] = $datosporpagina;
        $filtro['medio'] = $id_medio;
        
        $permisos = $this->clientes_model->getPermisosMedio($filtro);
        $numPermisos = $this->clientes_model->getNumPermisosMedio();
        
        $config = array();
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "administrador/permisosMedio/" . $id_medio . "/2";
        $config["total_rows"] = $numPermisos;
        $config["per_page"] = $filtro['datosPorPagina'];
        $config["uri_segment"] = 5;
        
        $this->pagination->initialize($config);
        
        $data["paginacion"] = $this->pagination->create_links();
        
        $data['opc'] = 'medios';
        $data['filtro'] = $filtro;
        $data['medio'] = $this->medios_model->getMedio($id_medio);
        $data['permisos'] = $permisos;
        $data['agencias'] = $this->agencias_model->getAgencias();
        $data['page'] = 'administrador/permisos_medio';
        
        $data['provincias'] = $this->clientes_model->getProvincias();
        
        $this->load->view('administrador_container', array_merge($data));
    }

    /**
     * Corrige todos los caracteres no estandares de la cadena pasada
     *
     * @param string $String
     *            Cadena a corregir
     * @return string Cadena corregida
     */
    function stripAccents($String)
    {
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�',
            '�',
            '�'
        ), "a", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�',
            '�'
        ), "A", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "I", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "i", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "e", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "E", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�',
            '�',
            '�'
        ), "o", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�',
            '�'
        ), "O", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "u", $String);
        $String = str_replace(array(
            '�',
            '�',
            '�',
            '�'
        ), "U", $String);
        $String = str_replace(array(
            '[',
            '^',
            '�',
            '`',
            '�',
            '~',
            ']',
            '/',
            ' ',
            '.',
            ','
        ), "", $String);
        $String = str_replace("�", "c", $String);
        $String = str_replace("�", "C", $String);
        $String = str_replace("�", "n", $String);
        $String = str_replace("�", "N", $String);
        $String = str_replace("�", "Y", $String);
        $String = str_replace("�", "y", $String);
        
        return $String;
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
}
?>