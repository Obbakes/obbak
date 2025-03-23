<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class CRM_model extends CI_Model
{

    private $crm;

    public function __construct()
    {
        parent::__construct();
        $this->crm = $this->load->database('crm', TRUE);
    }


    /**
     * Obtiene las ofertas que han sido creadas en vtiger para publicar y no están todavía en la plataforma de Bimads (id_oferta=0)
     *
     * @return resultSet ofertasPendientes
     */
    function getOfertasPendientes() {
        $this->crm->from('Ofertas_CRM o');
        $this->crm->where('o.publicada', 1);
        $this->crm->where('o.id_oferta', 0);
        $this->crm->order_by('o.productid', 'asc');
        $query =  $this->crm->get();
        return $query->result();
    }
    
    function getClientesSinSincronizar (){
        $this->db->select('cli.*, usu.fecha_registro, usu.fecha_ultima_conexion, usu.id_origen, usu.estado, sec.sector');
        $this->db->from ('dev-bimads.clientes cli');
        $this->db->join('dev-bimads.usuarios usu', 'cli.id_usuario = usu.id_usuario');
        $this->db->join('dev-bimads.sectores sec', 'cli.id_sector = sec.id_sector', 'left');
        /*$this->db->where('cli.id_cliente > 10'); //Id inicial en el momento de la sincro automatica-
        /*$this->db->where('cli.id_cliente NOT IN (SELECT cf_852 FROM crm2021.vtiger_accountscf)');*/
        $query =  $this->db->get();
        return $query->result();
    }
    
    function updateIdOfertaProduct($productid = 0, $id_oferta = 0){
        $this->crm->set('cf_759', $id_oferta);
        $this->crm->where('productid', $productid);
        $this->crm->update('vtiger_productcf');
    }
    
    function updateFechaEmailPublicacion($id_oferta = 0, $fechaEnvio){
        $this->crm->set('cf_976', $fechaEnvio);
        $this->crm->where('cf_759', $id_oferta);
        $this->crm->update('vtiger_productcf');
    }
    
    function updateNumEnviosEmailPublicacion($id_oferta = 0){
        
        $this->db->select('COUNT(des.id_oferta_destinatario) AS numEnvios');
        $this->db->from('ofertas_destinatarios des');
        $this->db->where('des.id_oferta', $id_oferta);
        $query = $this->db->get();
        $oResultado = $query->row();
        
        $this->crm->set('cf_1002', $oResultado->numEnvios);
        $this->crm->where('cf_759', $id_oferta);
        $this->crm->update('vtiger_productcf');
    }
    
    function sincronizarCliente ($account, $accountscf, $accountbillads) {
        $this->crm->insert('vtiger_account', $account);
       
        $this->crm->insert('vtiger_accountscf', $accountscf);
        
        $this->crm->insert('vtiger_accountbillads', $accountbillads);
    }
 
}