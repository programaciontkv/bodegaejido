<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asiento_model extends CI_Model {

	public function lista_asientos(){
		$this->db->order_by('con_asiento');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}

	public function ultimo_asiento(){
		$query ="SELECT * FROM erp_asientos_contables where con_asiento like 'AS%' ORDER BY con_asiento DESC LIMIT 1";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function siguiente_asiento() {
        
            $rst = $this->ultimo_asiento();
            if (!empty($rst)) {
                $sec = (substr($rst->con_asiento, -10) + 1);
                $n_sec = 'AS' . substr($rst->con_asiento, 2, (10 - strlen($sec))) . $sec;
            } else {
                $n_sec = 'AS0000000001';
            }
            return $n_sec;
        
    }

    public function insert($data){
		
		return $this->db->insert("erp_asientos_contables",$data);
			
	}


	public function lista_asientos_modulo($id,$mod){
		$this->db->where('doc_id',$id);
		$this->db->where('mod_id',$mod);
		$this->db->order_by('con_id');
		$resultado=$this->db->get('erp_asientos_contables');
		return $resultado->result();
			
	}


	
}

?>