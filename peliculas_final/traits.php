<?php
    trait formatear{


        public function toHTML(){
            return "<p>> $this->$titulo ( $this->anio )</p>";

        }

        public function toJSON(){
            return json_encode($this);
        }
    }

    trait FormatoFecha {
    public function formatearFecha($fecha) {
       
        return date('d/m/Y', strtotime($fecha));
  }
    
    public function obtenerAnio($fecha) {
        return intval(substr($fecha, 0, 4));
    }
}



?>