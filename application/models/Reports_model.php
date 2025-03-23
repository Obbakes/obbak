    <?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

   
    public function clearClientes(){
      $r =   $this->db->query("truncate table clientes_envios");
          print_r("truncate clientes_envios: ".$r);
    }
    public function loadClientes(){
      $r =    $this->db->query("INSERT INTO clientes_envios SELECT c.* FROM clientes AS c WHERE 1=1");
        print_r("INSERT clientes: ".$r);
    }
    public function clearMedios(){
       $r =  $this->db->query("truncate table medios_envio");
          print_r("truncate medios_envio: ".$r);
    }
    public function loadMedios(){
        $r =   $this->db->query("INSERT INTO medios_envio select med.* from medios as med where 1=1");
          print_r("INSERT medios_envio: ".$r);
    }
    public function clearOfertasDestinatarios(){
       $r =   $this->db->query("truncate table ofertas_destinatarios_aper");
            print_r("truncate ofertas_destinatarios_aper: ".$r);
    }
    public function loadOfertasDestinatarios(){
     //print_r("INSERT INTO `bimad_report`.ofertas_destinatarios SELECT * FROM ofertas_destinatarios WHERE fecha BETWEEN '".date("Y")."-01-01 00:00:00' AND '".date("Y")."-12-31 23:59:59'");

       $r =   $this->db->query("INSERT INTO ofertas_destinatarios_aper SELECT * FROM ofertas_destinatarios WHERE fecha BETWEEN '".date("2022")."-01-01 00:00:00' AND '".date("2024")."-12-31 23:59:59'");
           print_r("INSERT ofertas_destinatarios_aper: ".$r);
    }
    public function clearUsuarios(){
        $r =   $this->db->query("truncate usuarios_envios");
         print_r("truncate usuarios_envios: ".$r);
    }
    public function loadUsuarios(){
        $this->db->query("INSERT INTO usuarios_envios SELECT usu.* from usuarios as usu WHERE 1=1");

    }
   
    public function clearTiposOferta(){
      $r =   $this->db->query("truncate table tipos_oferta_envio");
          print_r("truncate tipos_oferta_envio: ".$r);
    }
    public function loadTiposOferta(){
        $r = $this->db->query("INSERT INTO tipos_oferta_envio SELECT tdo.* FROM tipos_oferta AS tdo WHERE 1=1");
                print_r("INSERT tipos_oferta_envio
                : ".$r);
    }
     
    public function clearReport(){
       $r = $this->db->query("truncate table report2022");
        print_r("truncate report2022: ".$r);
    }

    public function loadReport(){
        $this->db->query("INSERT INTO report2022 select Distinct od.id_oferta_destinatario,od.id_oferta,od.id_cliente,od.fecha as fecha_envio,c.id_usuario,concat(od.id_oferta,c.id_usuario) as concatenacion,EXISTS(SELECT * FROM pixel WHERE concatenado  = concat(od.id_oferta,c.id_usuario)) as apertura,c.nombre as cuenta, DATE_FORMAT(u.fecha_registro,'%Y-%c') as fecha_registro, IFNULL(u.id_origen,0) as origen , o.id_medio, IFNULL(s.nombre,NULL) as industria, t.nombre_tipo_oferta as tipo_oferta, m.nombre_medio as medio,od.precio_oferta,od.descuento,od.relacion_medio from ofertas_destinatarios_aper od inner join clientes_envios c on c.id_cliente = od.id_cliente inner join usuarios_envios u on u.id_usuario = c.id_usuario inner join ofertas o on o.id_oferta = od.id_oferta  left join sector s on s.id = c.id_sector inner join tipos_oferta_envio t on t.id_tipo_oferta = o.id_tipo_oferta inner join medios_envio m on m.id_medio = o.id_medio");
        print_r("INSERT INTO report2022 select od.id_oferta_destinatario,od.id_oferta,od.id_cliente,od.fecha as fecha_envio,c.id_usuario,concat(od.id_oferta,c.id_usuario) as concatenacion,EXISTS(SELECT * FROM pixel WHERE concatenado  = concat(od.id_oferta,c.id_usuario)) as apertura,c.nombre as cuenta, DATE_FORMAT(u.fecha_registro,'%Y-%c') as fecha_registro, IFNULL(u.id_origen,0) as origen , o.id_medio, IFNULL(s.nombre,NULL) as industria, t.nombre_tipo_oferta as tipo_oferta, m.nombre_medio as medio,od.precio_oferta,od.descuento,od.relacion_medio from ofertas_destinatarios_aper od inner join clientes_envio c on c.id_cliente = od.id_cliente inner join usuarios_envios u on u.id_usuario = c.id_usuario inner join ofertas o on o.id_oferta = od.id_oferta  left join sector s on s.id = c.id_sector inner join tipos_oferta_envio t on t.id_tipo_oferta = o.id_tipo_oferta inner join medios_envio m on m.id_medio = o.id_medio");
    }


}