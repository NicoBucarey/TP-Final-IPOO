<?php
Class Viaje{
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $importe;
    private $colObjPasajero;
    private $mensajeOperacion;
    
    public function __construct(){
        $this->idViaje=null;
        $this->destino="";
        $this->cantMaxPasajeros=0;
        // $this->objEmpresa=null;
        // $this->objResponsable= null;
        $this->importe=0;
        $this->colObjPasajero=[];
    }
    public function cargar($destino,$cantMaxPasajeros, $objEmpresa, $objResponsable,$importe,$colObjPasajero){		
        // $this->setIdViaje($idViaje);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setImporte($importe);
        $this->setColObjPasajero($colObjPasajero);
    }



    public function getIdViaje() {
    	return $this->idViaje;
    }
    public function setIdViaje($idViaje) {
    	$this->idViaje = $idViaje;
    }

    public function getDestino() {
    	return $this->destino;
    }
    public function setDestino($destino) {
    	$this->destino = $destino;
    }

    public function getCantMaxPasajeros() {
    	return $this->cantMaxPasajeros;
    }
    public function setCantMaxPasajeros($cantMaxPasajeros) {
    	$this->cantMaxPasajeros = $cantMaxPasajeros;
    }

    public function getObjEmpresa() {
    	return $this->objEmpresa;
    }
    public function setObjEmpresa($objEmpresa) {
    	$this->objEmpresa = $objEmpresa;
    }

    public function getObjResponsable() {
    	return $this->objResponsable;
    }
    public function setObjResponsable($objResponsable) {
    	$this->objResponsable = $objResponsable;
    }

    public function getImporte() {
    	return $this->importe;
    }
    public function setImporte($importe) {
    	$this->importe = $importe;
    } 
    public function getColObjPasajero() {
    	return $this->colObjPasajero;
    }
    public function setColObjPasajero($colObjPasajero) {
    	$this->colObjPasajero = $colObjPasajero;
    }

    public function getMensajeOperacion() {
    	return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
    	$this->mensajeOperacion = $mensajeOperacion;
    }


     /** AGREGAR LA COLECCION DE PASAJEROS 
	 * Recupera los datos de un viaje
	 * @param int $id
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($id){
		$base=new BaseDatos();
		$consultaPersona="SELECT * FROM viaje WHERE idviaje=". $id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    $this->setIdViaje($id);
					$this->setDestino($row2['vdestino']);
					$this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $resp = true;
                    $empresa= new Empresa();
                    $empresa ->Buscar($row2['idempresa']);          
                    $this->setObjEmpresa($empresa);

                    $responsable= new Responsable();
                    $responsable ->BuscarPorNroEmpleado($row2['rnumeroempleado']);
                    $this->setObjResponsable($responsable);

                    $this->setImporte($row2['vimporte']);
                    //crear un objeto y listar los pasajeros en base al id 
                    $objPasajero = new Pasajero();
                    $coleccionPasajeros =  $objPasajero->listar('idviaje='.$id);
                    $this->setColObjPasajero($coleccionPasajeros);
                     
                }				
                
            }else {
                $this->setMensajeOperacion($base->getError());                    
            }
        }else {
            $this->setMensajeOperacion($base->getError());
        }		
        return $resp;
    }
    public function listar($condicion=""){
	    $arregloViaje = null;
		$base=new BaseDatos();
		$consultaViajes="SELECT * FROM viaje ";
        //enviar todos *
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' WHERE '.$condicion;
		}
            $consultaViajes.=" order by vdestino ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViaje= array();
				while($row2=$base->Registro()){
					$viaje=new Viaje();
                    $viaje->setIdViaje($row2['idviaje']);
                    $viaje->setDestino($row2['vdestino']);
                    $arregloViaje[] = $viaje;
				}						
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());	 	
		 }	
		 return $arregloViaje;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
				VALUES('".$this->getDestino()."', ".$this->getCantMaxPasajeros().",".$this->getObjEmpresa()->getIdEmpresa().",".$this->getObjResponsable()->getNroEmpleado().",".$this->getImporte().")";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdViaje($id);
			    $resp=  true;

			}	else {
					$this->setMensajeOperacion($base->getError());
					
			}

		} else {
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}

    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros=".$this->getCantMaxPasajeros().",vimporte=".$this->getImporte()."
                            WHERE idviaje=".$this->getIdViaje()."";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setMensajeOperacion($base->getError());
				
			}
		}else{
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp;
	}
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idViaje=".$this->getIdViaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setMensajeOperacion($base->getError());
					
				}
		}else{
				$this->setMensajeOperacion($base->getError());
			
		}
		return $resp; 
	}
   
    public function __toString(){
        // $cadena="";
        // foreach($this->getColObjPasajero() as $pasajero){
        //     $cadena = $cadena. $pasajero; 
        //     "----------------------------------------------------\n";
        // }
        $cadena="";
        $n=count($this->getColObjPasajero());
       for($i=0;$i<$n;$i++){
            $cadena= $cadena . "\n[Pasajero N° ".($i+1)."]\n".  $this->getColObjPasajero()[$i];
            "\n----------------------------------------\n";
       }
        return "\n[Id Viaje: ". $this->getIdViaje()."]\n". "[Destino: ". $this->getDestino(). "][Cantidad maxima de pasajeros: ". $this->getCantMaxPasajeros()."][Importe: ". $this->getImporte(). "]\nEmpresa a cargo: \n". $this->getObjEmpresa() ."\nResponsable a cargo: \n". $this->getObjResponsable()."\n".$cadena;
    }

}