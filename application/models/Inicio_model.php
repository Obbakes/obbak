<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    /**
     * Acepta las condiciones del anunciante logueado
     */
    function aceptaCondiciones()
    {
        $data = array(
            'condiciones' => 1
        );
        $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
        $this->db->update('clientes', $data);
    }

   
    /**
     * Obtiene los datos de la agencia cuyo codigo coincida con el especificado
     *
     * @param string $codigo
     *            Codigo por el que filtrar
     * @return resultSet Datos de la agencia obtenida
     */
    function check_agencia($codigo)
    {
        $this->db->select('id_agencia, nombre');
        $this->db->from('agencias');
        $this->db->where('codigo', $codigo);
        $query = $this->db->get();
        
        if ($query->num_rows > 0) {
            $query = $query->result_object();
            return $query[0];
        }
        
        return false;
    }

    /**
     * Obtiene el campo condiciones del anunciante logueado
     *
     * @return resultSet Datos de la condicion aceptada del anunciante logueado
     */
    function checkCondiciones()
    {
        $this->db->select('condiciones');
        $this->db->from('clientes');
        $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_object();
        }
        
        return false;
    }

    /**
     * Comprueba si el codigo esta siendo utilizado por otra agencia
     *
     * @param string $codigo
     *            Codigo a comprobar
     * @param integer $id_agencia
     *            Id de la agencia a excluir de la consulta
     * @return boolean true si no esta siendo usado, false si si
     */
    function codigoAgenciaUnico($codigo, $id_agencia)
    {
        $this->db->select('id_agencia');
        $this->db->from('agencias');
        $this->db->where('id_agencia !=', $id_agencia);
        $this->db->where('codigo', $codigo);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return false;
        
        return true;
    }

    /**
     * Comprueba que el codigo pertenece al medio especificado y que esta activo
     *
     * @param string $codigo
     *            Codigo a comprobar
     * @param integer $id_medio
     *            Id del medio a comprobar
     * @return boolean true si pertenece y esta activo, false si no
     */
    function comprobarCodigo($codigo, $id_medio)
    {
        $this->db->select('id_codigo');
        $this->db->from('codigos');
        $this->db->where('DATE_ADD(fecha_creacion, INTERVAL 48 HOUR) > NOW()');
        $this->db->where('id_medio', $id_medio);
        $this->db->where('codigo', $codigo);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

    

    /**
     * Comprueba que el email especificado no esta siendo usado
     *
     * @param string $email
     *            Email a comprobar
     * @param integer $id_usuario
     *            Id del usuario a excluir de la consulta
     * @return boolean true si no esta siendo usado, false si si
     */
    function emailEsUnico($email, $id_usuario)
    {
        $this->db->select('usu.id_usuario');
        $this->db->from('usuarios usu');
        $this->db->where('usu.id_usuario !=', $id_usuario);
        $this->db->where('usu.nick', $email);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return false;
        
        return true;
    }

    /**
     * Genera un codigo de 15 caracteres alfanumerico aleatorio
     *
     * @param integer $id_medio
     *            (Opcional) Id del medio para el que insertar el codigo obtenido
     * @return string Codigo obtenido
     */
    function generarCodigo($id_medio = 0)
    {
        $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codigo;
        
        do {
            $codigo = '';
            
            for ($i = 0; $i < 15; $i ++) {
                $codigo .= $pattern[rand(0, strlen($pattern) - 1)];
            }
            
            $this->db->select('id_codigo');
            $this->db->from('codigos');
            $this->db->where('codigo', $codigo);
            $query = $this->db->get();
        } while ($query->num_rows > 0);
        
        if ($id_medio != 0) {
            $data = array(
                'codigo' => $codigo,
                'id_medio' => $id_medio,
                'fecha_creacion' => date("Y-m-d H:i:s")
            );
            $this->db->insert('codigos', $data);
        }
        
        return $codigo;
    }

    /**
     * Obtiene los datos de un anunciante
     *
     * @param integer $id_cliente
     *            Id del anunciante a obtener
     * @return resultSet Datos del anunciante obtenidos
     */
    function getInfoCliente($id_cliente)
    {
        $this->db->select('nombre, nombre_contacto, apellidos_contacto, email, telefono, direccion, email, poblacion, pais, provincia, cp, cif');
        $this->db->from('clientes');
        $this->db->join('provincia', 'clientes.id_provincia = provincia.id_provincia', 'left');
        $this->db->join('pais', 'clientes.id_pais = pais.id_pais', 'left');
        $this->db->where('id_cliente', $id_cliente);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $query = $query->result_object();
            return $query[0];
        }
        
        return false;
    }

    /**
     * Obtiene los datos de un usuario a partir del email especificado
     *
     * @param string $email
     *            Email por el que filtrar
     * @return resultSet Datos del usuario obtenido
     */
    function getUsuarioEmail($email)
    {
        $this->db->select('age.id_usuario, age.email');
        $this->db->from('agencias age');
        $this->db->where('age.email', $email);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return $query->row();
        
        $this->db->select('cli.id_usuario, cli.email');
        $this->db->from('clientes cli');
        $this->db->where('cli.email', $email);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return $query->row();
        
        $this->db->select('ges.id_usuario, ges.email');
        $this->db->from('gestores ges');
        $this->db->where('ges.email', $email);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return $query->row();
        
        // Los medios tienen la peculiaridad que se utiliza el nickname como inicio de sesión y tienen más de un email
        
        $this->db->select('med.id_usuario, med.email');
        $this->db->from('medios med');
        $this->db->join('usuarios usu', 'usu.id_usuario = med.id_usuario', 'join');
        $this->db->or_where('med.email', $email);
        $this->db->or_where('usu.nick', $email);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return $query->row();
        
        return null;
    }

    /**
     * Comprueba si un email no esta siendo utilizado por otro anunciante
     *
     * @param string $email
     *            Email a comprobar
     * @return boolean true si no es siendo utilzado, false si si
     */
    function isUnique($email)
    {
        $this->db->select('email');
        $this->db->from('clientes');
        $this->db->where('email', strtolower($email));
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Comprueba los datos de login y si son correctos genera la variable de sesion
     *
     * @param string $email
     *            Email del usuario
     * @param string $password
     *            Password del usuario
     * @return boolean true si los datos son correctos, false si no
     */
    function login($email, $password)
    {
        $this->db->select('id_usuario, estado, tipo_usuario');
        $this->db->from('usuarios');
        $this->db->where('nick', $email);
        $this->db->where('pass', $password);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $usuario = $query->row();
            
            if ($usuario->tipo_usuario == 'admin') {
                $newdata = array(
                    'id_usuario' => $usuario->id_usuario,
                    'nombre' => 'administrador',
                    'nombre_contacto' => 'administrador',
                    'nombre_completo' => 'No Limits Media',
                    'estado' => $usuario->estado,
                    'tipo_usuario' => 'admin',
                    'logged_in' => TRUE,
                    'filtro_newsletters' => array(),
                    'filtro_ofertas' => array(),
                    'filtro_medios' => array(),
                    'filtro_clientes' => array(),
                    'filtro_agencias' => array()
                );
                

            } else if ($usuario->tipo_usuario == 'gestor') {
                $this->db->select('nombre, id_gestor');
                $this->db->from('gestores');
                $this->db->where('id_usuario', $usuario->id_usuario);
                $query = $this->db->get();
                
                if ($query->num_rows() == 0)
                    return false;
                
                $gestor = $query->row();
                
                $newdata = array(
                    'id_usuario' => $usuario->id_usuario,
                    'id_gestor' => $gestor->id_gestor,
                    'nombre' => $gestor->nombre,
                    'estado' => $usuario->estado,
                    'tipo_usuario' => 'gestor',
                    'logged_in' => TRUE
                );
                

            } else if ($usuario->tipo_usuario == 'medio') {
                $this->db->select('nombre, id_medio, id_tipo_medio');
                $this->db->from('medios');
                $this->db->where('id_usuario', $usuario->id_usuario);
                $query = $this->db->get();
                
                if ($query->num_rows() == 0)
                    return false;
                
                $medio = $query->row();
                
                $newdata = array(
                    'id_usuario' => $usuario->id_usuario,
                    'id_medio' => $medio->id_medio,
                    'tipo_medio' => $medio->id_tipo_medio,
                    'nombre' => $medio->nombre,
                    'nombre_contacto' => $medio->nombre,
                    'nombre_completo' => $medio->nombre,
                    'estado' => $usuario->estado,
                    'tipo_usuario' => 'medio',
                    'logged_in' => TRUE
                );


            } else if ($usuario->tipo_usuario == 'agencia') {
                $this->db->select('nombre, id_agencia, email, porcentaje');
                $this->db->from('agencias');
                $this->db->where('id_usuario', $usuario->id_usuario);
                $query = $this->db->get();
                
                if ($query->num_rows() == 0)
                    return false;
                
                $agencia = $query->row();
                
                $newdata = array(
                    'id_usuario' => $usuario->id_usuario,
                    'id_agencia' => $agencia->id_agencia,
                    'nombre' => $agencia->nombre,
                    'nombre_contacto' => $agencia->nombre,
                    'nombre_completo' => $agencia->nombre,
                    'estado' => $usuario->estado,
                    'email' => $agencia->email,
                    'tipo_usuario' => 'agencia',
                    'logged_in' => TRUE,
                    'porcentaje' => $agencia->porcentaje
                );

            } else {
                $this->db->select('cli.nombre_contacto, cli.apellidos_contacto, cli.nombre, cli.id_cliente, cli.email, cli.imagen, age.nombre agencia', false);
                $this->db->select('age.email email_agencia, age.telefono telefono_agencia', false);
                $this->db->from('clientes cli');
                $this->db->join('agencias age', 'cli.id_agencia = age.id_agencia', 'left');
                $this->db->where('cli.id_usuario', $usuario->id_usuario);
                $query = $this->db->get();
                
                if ($query->num_rows() == 0)
                    return false;
                
                $cliente = $query->row();
                
                $newdata = array(
                    'id_usuario' => $usuario->id_usuario,
                    'id_cliente' => $cliente->id_cliente,
                    'nombre' => $cliente->nombre,
                    'nombre_contacto' => $cliente->nombre_contacto,
                    'nombre_completo' => $cliente->nombre_contacto . ' ' . $cliente->apellidos_contacto,
                    'estado' => $usuario->estado,
                    'email' => $cliente->email,
                    'agencia' => (! empty($cliente->agencia)) ? $cliente->agencia : '',
                    'email_agencia' => (! empty($cliente->email_agencia)) ? $cliente->email_agencia : '',
                    'telefono_agencia' => (! empty($cliente->telefono_agencia)) ? $cliente->telefono_agencia : '',
                    'tipo_usuario' => 'cliente',
                    'imagen' => $cliente->imagen,
                    'logged_in' => TRUE
                );

                

            }
            
            $data = array(
                'fecha_ultima_conexion' => date("Y-m-d H:i:s")
            );
            $this->session->set_userdata($newdata);
            
            $this->db->where('id_usuario', $this->session->userdata('id_usuario'));
            $this->db->update('usuarios', $data);
            
            
            // Registramos en el log el login del medio
            $data = array(
                'id_usuario' => $usuario->id_usuario,
                'fecha' => date("Y-m-d H:i:s"),
                'accion' => 'login',
                'ip' => $this->get_client_ip_server()
            );
      
            $this->db->insert('log_acciones', $data);
            
            return true;
        }
        
        return false;
    }
    
    function get_client_ip_server() {
        $ipaddress = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if(!empty($_SERVER['HTTP_X_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                    else if(!empty($_SERVER['HTTP_FORWARDED_FOR']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                        else if(!empty($_SERVER['HTTP_FORWARDED']))
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                            else if(!empty($_SERVER['REMOTE_ADDR']))
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                                else
                                    $ipaddress = 'UNKNOWN';
                                    return $ipaddress;
    }
    

    /**
     * Comprueba que el email especificado no esta siendo usado
     *
     * @param string $nick
     *            Email a comprobar
     * @param integer $id_usuario
     *            Id del usuario a excluir de la consulta
     * @return boolean true si no esta siendo usado, false si si
     */
    function nickEsUnico($nick, $id_usuario)
    {
        $this->db->select('usu.id_usuario');
        $this->db->from('usuarios usu');
        $this->db->where('usu.id_usuario !=', $id_usuario);
        $this->db->where('usu.nick', $nick);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return false;
        
        return true;
    }
}