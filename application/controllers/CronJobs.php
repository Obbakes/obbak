<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    require_once('BaseController.php');
class CronJobs extends BaseController
{

    private $emailOficial = '';

    function __construct()
    {
        parent::__construct();
    }
/*
    function send_resumen_semanal_clientes()
    {
        $this->load->model('clientes_model');
        $resumen = $this->clientes_model->getResumenSemanalClientes();
        foreach ($resumen as $data_email) {
            $this->email->from(EMAIL_OFICIAL, 'BIMADS');
            $this->email->to('tecnologia@bimads.com');

            $this->email->subject('Bimads - Resumen semanal');

            $this->email->message($this->load->view('email/email_resumen_semanal_cliente', $data_email, TRUE));

            $this->email->send();
        }
    }
*/
    /**
     * Selecciona permisos automáticamente pasado un tiempo sin haberlos indicado
     */
    function aceptar_permisos_medios_no_configurados()
    {
        $this->load->model('medios_model');
        $this->load->model('clientes_model');

        $filtro['pagina'] = 1;
        $filtro['datosPorPagina'] = 100;

        $clientes = $this->clientes_model->getClientes();

        foreach ($clientes as $cliente) 
        {
            echo "<p>Analizando cliente " . $cliente->nombre . " (". $cliente->id_cliente .") - " . $cliente->email . "</p><ul>";

            $filtro['cliente'] = $cliente->id_cliente;
            $filtro['medio'] = 46;
            $permisos_medios = $this->clientes_model->getPermisosCliente($filtro);
            foreach ($permisos_medios as $medio) 
            {
                if (empty($medio->estado))
                {
                    $alta = strtotime($medio->fecha_alta);
                    $ahora = strtotime(date('Y-m-d H:i:s'));
                    $margen = 0.5 * 24 * 60 * 60; // dias * horas * minutos * segundos
                    if ($alta < $ahora - $margen)
                    {
                        echo "<li>Activamos " . $medio->nombre . " (" . $medio->id_medio . ") sin configurar desde " . $medio->fecha_alta . "</li>";
                        $this->clientes_model->crearPermisoClienteMedio($cliente->id_cliente, $medio->id_medio, 1);
                    }
                }
            }
            echo "</ul>";
        }
    }
    function cron_every_12_hours(){
        try{
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, 'http://app.bimads.com/cronJobs/aceptar_permisos_medios_no_configurados');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_exec($cURLConnection);
            curl_close($cURLConnection);
        }catch(Exception $ex){}
    }
    
    function cron_every_15_minutes(){
        
         try{
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, 'http://app.bimads.com/cronJobs/sincronizar_ofertas_crm');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_exec($cURLConnection);
            curl_close($cURLConnection);
        }catch(Exception $ex){}
    
        try{
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, 'https://app.bimads.com/cronJobs/send_nueva_oferta_publicada');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_exec($cURLConnection);
            curl_close($cURLConnection);
        }catch(Exception $ex){}
        
    }

    /**
     *
     */
    function send_permisos_medio_diario()
    {
        $this->load->model('clientes_model');
        $nuevos_clientes = $this->clientes_model->getClientesRegistroUltimoDia();
        if (! empty($nuevos_clientes)) {
            $this->send_permisos_medios($nuevos_clientes);
        } else {
            echo "ningún anunciante registrado en las últimas 24h";
        }
    }

    /**
     * Envía los emails a los medios para que pasen su filtro de anunciantes
     */
    function send_permisos_medios($nuevos_clientes)
    {
        $this->load->model('medios_model');
        $data_medios=array();
        $medios_sin_gestor = $this->medios_model->getMediosSinGestor();
        foreach ($medios_sin_gestor as $medio) {
            if (! empty($data_medios[$medio->email])) {
                $data_email = $data_medios[$medio->email];
                $medios = $data_medios[$medio->email]['medios'];
            } else {
                $data_email['id'] = $medio->id_medio;
                $data_email['nombre'] = $medio->nombre;
                $data_email['email'] = $medio->email;
                $medios = array();
            }
            array_push($medios, $medio);
            $data_email['medios'] = $medios;
            $data_medios[$medio->email] = $data_email;
        }

        $medios_con_gestor = $this->medios_model->getMediosConGestor();
        foreach ($medios_con_gestor as $gestor) {
            if (! empty($data_medios[$gestor->email])) {
                $data_email = $data_medios[$gestor->email];
                $medios = $data_medios[$gestor->email]['medios'];
            } else {
                $data_email['id'] = $gestor->id_gestor;
                $data_email['nombre'] = $gestor->nombre;
                $data_email['email'] = $gestor->email;
                $medios = array();
            }
            array_push($medios, $this->medios_model->getMedio($gestor->id_medio));
            $data_email['medios'] = $medios;
            $data_medios[$gestor->email] = $data_email;
        }
        $this->send_permisos_medios_aux($data_medios, $nuevos_clientes);
    }

    function send_permisos_medios_aux($data_medios, $nuevos_clientes)
    {
        $this->load->library('email');

        foreach ($data_medios as $data_email) {
            $data_email['clientes'] = $nuevos_clientes;

            $this->email->from(EMAIL_OFICIAL, 'BIMADS');
            $this->email->to($data_email['email']);

            $this->email->subject('Bimads - Nuevos anunciantes registrados para gestionar');

            $this->email->message($this->load->view('email/email_notifica_permisos_gestor_agrupado', $data_email, TRUE));

            $this->email->send();
        }
    }

    function sincronizar_ofertas_crm()
    {
        $this->load->model('CRM_model');
        $this->load->model('ofertas_model');
        $ofertas = $this->CRM_model->getOfertasPendientes();
        foreach ($ofertas as $oferta) {
            $productid = $oferta->productid;
            unset($oferta->productid); // lo eliminamos para hacer la inserción, este campo no pertenece a la tabla 'ofertas' en bimads
            $oferta->fecha_insercion = date('Y-m-d H:i:s');

            $id = $this->ofertas_model->insertOferta($oferta);
            echo $id;

            $this->CRM_model->updateIdOfertaProduct($productid, $id);
        }
    }
    
    function sincronizar_clientes_plataforma()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->load->model('CRM_model');    
        $rows = $this->CRM_model->getClientesSinSincronizarPlataforma();
        foreach ($rows as $row) {
            if($row->cf_860 > 1){
                $id_usuario  = $row->cf_860;
            }else{
                $usuarios = array(      
                    'nick' => $row->email1,
                    'pass' => '12345678',
                    'tipo_usuario' => 'cliente',
                    'fecha_registro' => $row->cf_858." 12:00:00",
                    'fecha_ultima_conexion' => '0000-00-00 00:00:00',
                    'id_origen' => $row->cf_856,
                    'estado' => $row->cf_862,
                    'fecha_baja' => '0000-00-00 00:00:00',
                    'motivo_baja' => ''
                );
                if($row->cf_864 > 0){
                    $usuarios["fecha_alta"] = date('Y-m-d H:i:s');
                }
                $id_usuario = $this->CRM_model->insertarUsuario($usuarios);
            }
           
            if($id_usuario == 0){
                print_r($this->CRM_model->db->error() );
            }else{           
                $id_provincia = 0;
                $provincia = "";
                if(!is_int($row->bill_state)){
                    $id_provincia = $this->CRM_model->getIdProvincia($row->bill_state);
                    $provincia  = $row->bill_state;
                }else{
                    
                    $id_provincia = $row->bill_state;
                    $provincia  = $this->CRM_model->getProvinciaFromId($row->bill_state);
                }
            
                $cliente = array(
                    'nombre_comercial' => $row->accountname,
                    'nombre' => $row->cf_893,
                    'apellidos_contacto' => $row->cf_975,
                    'nombre_contacto' => $row->cf_971,
                    'email' => $row->email1,
                    'telefono' => $row->phone,
                    'direccion' => $row->bill_street,
                    'cp' => $row->bill_code,
                    'poblacion' => $row->bill_city,
                    'id_provincia' => $id_provincia,
                    'provincia' => $provincia,
                    'id_pais' => $row->bill_country,
                    'id_sector' => $row->id_sector, 
                    'cif' => $row->siccode,
                    'id_usuario' => $id_usuario,
                    'condiciones' =>  1,
                    'newsletter' => $row->cf_973,
                    'notifica_oferta_nueva' => 1
                );
                
                $id_cliente = $this->CRM_model->insertarCliente($cliente);
                if($id_cliente == 0){
                    print_r($this->CRM_model->db->error() );
                }else{
                    $this->CRM_model->updateAccountCf($row->accountid,array("cf_862" =>$id_cliente,"cf_860" => $id_usuario));
                }    
                echo 'sincronizado el accountid: ' . $row->accountid . ' id_cliente: ' . $id_cliente . ' id_usuario: ' . $id_usuario . '\r';
                
            }
        }
    }
/*
    function sincronizar_clientes_crm()
    {
        $this->load->model('CRM_model');
        $this->load->model('clientes_model');
        $rows = $this->CRM_model->getClientesSinSincronizar();
  
        foreach ($rows as $row) {
            $accountid = ($row->id_cliente);
            $vtiger_accountscf = array(
                'accountid' => $accountid,
                'cf_852' => $row->id_cliente,
                'cf_975' => $row->apellidos_contacto,
                'cf_971' => $row->nombre_contacto,
                'cf_860' => $row->id_usuario,
                'cf_973' => $row->newsletter,
                'cf_858' => $row->fecha_registro,
                /*'cf_998' => $row->fecha_ultima_conexion,
                'cf_856' => $row->id_origen,
                'cf_866' => $row->nombre,
                'cf_862' => $row->estado
            );
            $vtiger_account = array(
                'accountid' => $accountid,
                'accountname' => $row->nombre_comercial,
                'account_no' => 'ACC' . $row->id_cliente,
                'email1' => $row->email,
                'phone' => $row->telefono,
                'industry' => $row->sector,
                'siccode' => $row->cif,
                'website' => $row->web
            );
            $vtiger_accountbillads = array(
                'accountaddressid' => $accountid,
                'bill_street' => $row->direccion,
                'bill_code' => $row->cp,
                'bill_city' => $row->poblacion,
                'bill_country' => $row->id_pais,
                'bill_state' => $row->provincia
            );

            $this->CRM_model->sincronizarCliente($vtiger_account, $vtiger_accountscf, $vtiger_accountbillads);

            echo 'sincronizado el cliente = ' . $row->id_cliente . '\r';
        }
    }
*/
     function send_nueva_oferta_publicada()
    {
 
	    ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->load->library('email');
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->email->to($this->input->post('email'));
        $ofertas = $this->ofertas_model->getOfertasPendientesEmailPublicacion();
        
        foreach ($ofertas as $row) {

        	 
            $fechaEnvio = date('Y-m-d H:i:s');
            $datos_oferta = array(
                'newsletter_fecha_envio' => $fechaEnvio
            );
            $this->ofertas_model->updateOferta($row->id_oferta, $datos_oferta);

            $filtro['id_oferta'] = $row->id_oferta;
            $filtro['notifica_oferta_nueva'] = 1;
            $clientes = $this->clientes_model->getClientesOferta($filtro);
            $num_envios = 0;
            $asunto = 'Nueva oferta de última hora';
            if (! empty($row->newsletter_asunto)) {
                $asunto = $row->newsletter_asunto;
            }

            $ofedest = array();
            array_push($ofedest, $row->id_oferta);

            $destinatariosMap = $this->getMapOfertasDestinatarios($this->ofertas_model->getClientesOfertasDestinatarios($ofedest));

            $destinatarios = array();

            if (isset($destinatariosMap[$row->id_oferta])) {
                $destinatarios = $destinatariosMap[$row->id_oferta];
            }
		  
            foreach ($clientes as $cliente) {
			 
                if (! in_array($cliente->id_cliente, $destinatarios)) {
                    // echo 'nuevo '.$cliente->id_cliente;

                    $data_email['clientes'] = $cliente;
                    $data_email['nombre_comercial'] = $this->input->post('nombre_comercial');
					$row_descuento = clone $row;  
                                       
                    
                    try {
					
                        $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                        
                        $envio_agencia = ($cliente->agencia_newsletter == 1);
                        if ($envio_agencia) {
                            $this->email->to($cliente->agencia_email);
                            $this->email->subject($cliente->nombre . ": " . $asunto);
                        } else {
                            $this->email->to($cliente->email);
                            $this->email->subject($asunto);
                        }
                        $this->email->message($this->load->view('email/prueba_1.php', $data_email, TRUE));
                        $this->email->send();

                       

                    
						 $num_envios ++;
						   array_push($destinatarios, $cliente->id_cliente);


                        // Insertamos en ofertas_destinatarios
                        $oferta_destinatario = array(
                            'id_oferta' => $row_descuento->id_oferta,
                            'id_cliente' => $cliente->id_cliente,
                            'fecha_envio' => date('Y-m-d H:i:s'),
                            'precio_oferta' => $row_descuento->precio_oferta,
                            'descuento' => $row_descuento->descuento,
                            'relacion_medio' => $cliente->relacion_medio
                        );

                        $this->ofertas_model->insertOfertaDestinatario($oferta_destinatario);
                    } catch (Exception $e) {
                        echo 'Excepción capturada: ', $e->getMessage(), "\n";
                    }
                    // echo 'Envío email de nueva oferta con id=' . $row->id_oferta;
                }
            }
            
            $this->ofertas_model->updateNumEnviosOferta($row->id_oferta);
            echo 'enviados ' . $num_envios;
        }
    }

   function send_nueva_oferta_publicada_grupo_medio()
    {
        $this->load->library('email');
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('CRM_model');

        $rows = $this->ofertas_model->getOfertasPendientesEmailPublicacion(true);

        $medios = array();

        foreach ($rows as $oferta) {
            if (! isset($medios[$oferta->id_medio])) {
                $ofertas = array();
            } else {
                $ofertas = $medios[$oferta->id_medio];
            }
            array_push($ofertas, $oferta);
            $medios[$oferta->id_medio] = $ofertas;
        }

        foreach ($medios as $id_medio => $ofertas) {

            reset($ofertas);
            $primera_oferta = current($ofertas); // obtenemos la primera para el asunto y el tipo_medio

            if (! empty($ofertas)) {
                $filtro['id_medio'] = $id_medio;
                $filtro['notifica_oferta_nueva'] = 1;
                $clientes = $this->clientes_model->getClientesOferta($filtro);

                $asunto = 'Nuevas ofertas de última hora';

                if (! empty($primera_oferta->newsletter_asunto)) {
                    $asunto = $primera_oferta->newsletter_asunto;
                }
                if (! empty($primera_oferta->tipo_medio)) {
                    $data_email['tipo_medio'] = strtolower($primera_oferta->tipo_medio);
                }

                $num_envios = 0;
                foreach ($clientes as $cliente) {
                    $data_email['cliente'] = $cliente;
                    $envio_agencia = ($cliente->agencia_newsletter == 1);
                    reset($ofertas);
                    $ofertas_clientes = $ofertas;

                    $id_oferta_arr = array_map(function ($e) {
                        return is_object($e) ? $e->id_oferta : $e['id_oferta'];
                    }, $ofertas_clientes);

                    $destinatariosMap = $this->getMapOfertasDestinatarios($this->ofertas_model->getClientesOfertasDestinatarios($id_oferta_arr));

                    foreach ($ofertas as $of) {
                        $destinatarios = array();
                        if (isset($destinatariosMap[$of->id_oferta])) {
                            $destinatarios = $destinatariosMap[$of->id_oferta];
                        }

                        if (! empty($destinatarios) && in_array($cliente->id_cliente, $destinatarios)) {

                            $id_oferta_arr = array_map(function ($e) {
                                return is_object($e) ? $e->id_oferta : $e['id_oferta'];
                            }, $ofertas_clientes);

                            if (($key = array_search($of->id_oferta, $id_oferta_arr)) !== false) {

                                unset($ofertas_clientes[$key]);
                                $ofertas_clientes = array_values($ofertas_clientes);
                            }
                        }
                    }
                    if (! empty($ofertas_clientes)) {
                        $data_email['ofertas'] = $ofertas_clientes;
                        try {
                            $this->email->from(EMAIL_OFICIAL, 'BIMADS');

                            if ($envio_agencia) {
                                $this->email->to($cliente->agencia_email);
                                $this->email->subject($cliente->nombre . ": " . $asunto);
                            } else {
                                $this->email->to($cliente->email);
                                $this->email->subject($asunto);
                            }

                            $this->email->message($this->load->view('email/nueva_oferta_publicada_agrupada', $data_email, TRUE));
                            $this->email->send();

                            $num_envios ++;
                            foreach ($ofertas_clientes as $oferta) {
                                // Insertamos en ofertas_destinatarios
                                $oferta_destinatario = array(
                                    'id_oferta' => $oferta->id_oferta,
                                    'id_cliente' => $cliente->id_cliente,
                                    'fecha' => date('Y-m-d H:i:s')
                                );
                                $this->ofertas_model->insertOfertaDestinatario($oferta_destinatario);
                            }
                        } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "\n";
                        }
                    }
                }
                
                $fechaEnvio = date('Y-m-d H:i:s');
                foreach ($ofertas as $oferta) {
                    $datos_oferta = array(
                        'newsletter_fecha_envio' => $fechaEnvio
                    );
                    $this->ofertas_model->updateOferta($oferta->id_oferta, $datos_oferta);
                    $this->ofertas_model->updateNumEnviosOferta($oferta->id_oferta);
                    $this->CRM_model->updateFechaEmailPublicacion($oferta->id_oferta, $fechaEnvio);
                    $this->CRM_model->updateNumEnviosEmailPublicacion($oferta->id_oferta);
                }
                echo 'enviados ' . $num_envios;
            }
        }
    }
    

    /*
     * Se enviara una vez al dia con los mails
     */
    function send_nueva_oferta_publicada_diaria() {
        $this->load->library('email');
        $this->load->model('ofertas_model');
        $this->load->model('clientes_model');
        $this->load->model('CRM_model');

        $clientes_ofertas = $this->clientes_model->getClientesNotificaNuevaOfertaDiario();
        if (! empty($clientes_ofertas)) {
            $ofertas_update = array();

            // get all the array keys
            $clientes = array_keys($clientes_ofertas);
            // get amount of values from array
            $count = count($clientes_ofertas);
            // loop through the keys
            for ($i = 0; $i < $count; $i ++) {
                $id_cliente = $clientes[$i];
                $objCliente = $this->clientes_model->getClienteById($id_cliente);
                if (! empty($objCliente)) {
                    $ofertas = $clientes_ofertas[$id_cliente];
                    $ofertas_email = array();
                    foreach ($ofertas as $id_oferta) {
                        $oferta = $this->ofertas_model->getOfertaById($id_oferta);
                        echo $oferta->titulo;
                        array_push($ofertas_email, $oferta);
                        if (! in_array($id_oferta, $ofertas_update)) {
                            array_push($ofertas_update, $id_oferta);
                        }
                    }
                    $objCliente["email"] = 'tecnologia@bimads.com';
                    $data_email['cliente'] = $objCliente;
                    $data_email['ofertas'] = $ofertas_email;
                    try {
                        $this->email->from(EMAIL_OFICIAL, 'BIMADS');
                       // $this->email->to($cliente->email);
                       /* if ($envio_agencia) {
                            $this->email->to($cliente->agencia_email);
                            $this->email->subject($cliente->nombre . ": " . $asunto);
                        } else {
                            $this->email->to($cliente->email);
                            $this->email->subject($asunto);
                        }*/
                        $this->email->to($objCliente["email"] );
                        $this->email->subject('Nuevas ofertas publicadas');

                        $this->email->message($this->load->view('email/nueva_oferta_publicada_agrupada', $data_email, TRUE));
                        $this->email->send();
                    } catch (Exception $e) {
                        echo 'Excepción capturada: ', $e->getMessage(), "\n";
                    }

                    foreach ($ofertas_email as $of_enviada) {
                        // Insertamos en ofertas_destinatarios
                        $oferta_destinatario = array(
                            'id_oferta' => $of_enviada->id_oferta,
                            'id_cliente' => $objCliente->id_cliente,
                            'fecha' => date('Y-m-d H:i:s')
                        );
                        $this->ofertas_model->insertOfertaDestinatario($oferta_destinatario);
                     
                    }
                    // echo 'Envío email de nueva oferta con id=' . $row->id_oferta;
                }
            }

            // actualizamos el flag de fecha de envio en las ofertas
            $fechaEnvio = date('Y-m-d H:i:s');
            foreach ($ofertas_update as $id_oferta) {
                $datos_oferta = array(
                    'newsletter_fecha_envio_diario' => $fechaEnvio
                );
                $this->ofertas_model->updateOferta($id_oferta, $datos_oferta);
                $this->ofertas_model->updateNumEnviosOferta($id_oferta);
            }
        }
    }

    function getMapOfertasDestinatarios($rows)
    {
        $destinatarios = array();
        if (! empty($rows)) {
            foreach ($rows as $row) {
                if (! isset($destinatarios[$row->id_oferta])) {
                    $clientes = array();
                } else {
                    $clientes = $destinatarios[$row->id_oferta];
                }
                array_push($clientes, $row->id_cliente);

                $destinatarios[$row->id_oferta] = $clientes;
            }
        }
        return $destinatarios;
    }

    function migrar_cliente_contacto()
    {
        $this->load->model('clientes_model');
        $clientes = $this->clientes_model->getClientes();

        foreach ($clientes as $cliente) {
            $email_arr = explode(',', $cliente->email);
            if (count($email_arr) > 1) {
                echo $cliente->email . '<br />';
                $primero = true;
                foreach ($email_arr as $email) {
                    if ($primero) {
                        $datos_cliente = array(
                            'email' => trim($email)
                        );
                        $this->clientes_model->updateCliente($cliente->id_cliente, $datos_cliente);
                        $primero = false;
                    } else {
                        $datos_contacto = array(
                            'id_cliente' => $cliente->id_cliente,
                            'email' => trim($email),
                            'estado' => '1'
                        );
                        $clientes = $this->clientes_model->insertClienteContacto($datos_contacto);
                    }
                }
            }
        }
    }

    function reports()
    {
        $this->load->model('reports_model');

        $this->reports_model->clearClientes();
        $this->reports_model->clearOfertasDestinatarios();
        $this->reports_model->clearUsuarios();
        $this->reports_model->clearMedios();
        $this->reports_model->clearTiposOferta();
        $this->reports_model->clearReport();
        $this->reports_model->loadClientes();
        $this->reports_model->loadOfertasDestinatarios();
        $this->reports_model->loadUsuarios();
        $this->reports_model->loadMedios();
        $this->reports_model->loadTiposOferta();
        $this->reports_model->loadReport();
    }
}
?>
