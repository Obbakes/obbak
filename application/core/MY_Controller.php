<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $global;	
	var $tipo_lugar;

	function __construct()
	{
		parent::__construct();
	
		$this->load->model('anuncios');
		$this->load->model('descuentos');
		$this->load->model('galeria');
		$this->load->model('lugares');
		$this->load->model('buscador_avanzado');
		$this->load->model('promociones');
		$this->load->model('login');
		
		$this->global = array (
 			'imagenes' => $this->galeria->dameGaleria(),
			'lista_musica_buscador' => $this->buscador_avanzado->getMusica(),
			'lista_extras_buscador' => $this->buscador_avanzado->getListaExtras(),
			'lista_nombres_buscador' => $this->buscador_avanzado->getListaNombres(),
			'lista_ciudades_buscador' => $this->buscador_avanzado->getListaCiudades(),
			'lista_paises_buscador' => $this->buscador_avanzado->getListaPaises(),
			'lista_zonas_buscador' => $this->buscador_avanzado->getZonas(),
			'lista_especiales_buscador' => $this->buscador_avanzado->getListaTiposEspeciales(),
			'lista_tiposlocal_buscador' => $this->buscador_avanzado->getListaTiposLocal(),
			'lista_anuncios' => $this->anuncios->dameAnuncios(),
			'numero_locales' => $this->lugares->dameNumeroLugares(),
		);
		
		$newdata = array(
                   'username'  => '',
				   'nombre_sesion' => '',
                   'email'     => '',
				   'tipo_local' => 'antes',
                   'logged_in' => TRUE
				   
               );  
		//$this->session->set_userdata($newdata);

	}

	public function findID($slug){
		return $this->buscador_avanzado->getID($slug);
	}
	
}

?>