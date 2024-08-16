<?php
Class Pasajero extends Persona{

    private $idPasajero;
    private $idViaje;
    private $mensajeOperacion;
    
    public function __construct(){
        parent::__construct();
       $this->idPasajero=null;
    }  

    public function cargar($nombre,$apellido,$nroDocumento,$telefono, $idViaje=null){
			parent::cargar($nombre,$apellido,$nroDocumento,$telefono);
			$this->setIdViaje($idViaje);	
    }

    public function getMensajeOperacion() {
    	return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
    	$this->mensajeOperacion = $mensajeOperacion;
    }
    public function getIdPasajero() {
    	return $this->idPasajero;
    }
    public function setIdPasajero($idPasajero) {
    	$this->idPasajero = $idPasajero;
    }
    public function getIdViaje() {
    	return $this->idViaje;
    }
    public function setIdViaje($idViaje) {
    	$this->idViaje = $idViaje;
    }
    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function BuscarPorDni($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from pasajero where pdocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    parent::Buscar($dni);
					$this->setIdPasajero($row2['idpasajero']);
                    $this->setIdViaje($row2['idviaje']);
					$resp= true;
				}							
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());		 	
		 }		
		 return $resp;
	}	
	/**
	 * Recupera los datos de una persona por id
	 * @param int $id
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function BuscarPorId($id){
		$base=new BaseDatos();
		$consultaPersona="Select * from pasajero where idpasajero=".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
					$dni = $row2['pdocumento'];	
				    parent::Buscar($dni);
					$this->setIdPasajero($row2['idpasajero']);
                    $this->setIdViaje($row2['idviaje']);
					$resp= true;
				}							
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());		 	
		 }		
		 return $resp;
	}	
    

	public function listar($condicion=""){
	    $arregloPasajero = null;
		$base=new BaseDatos();
		$consultaPasajeros="SELECT * FROM pasajero ";
		if ($condicion!=""){
		    $consultaPasajeros=$consultaPasajeros .'WHERE '.$condicion;
		}
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajeros)){				
				$arregloPasajero= array();
				while($row2=$base->Registro()){
					$pasajero=new Pasajero();
					$pasajero->BuscarPorDni($row2['pdocumento']);
					array_push($arregloPasajero,$pasajero);	
				}							
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());		 	
		 }	
		 return $arregloPasajero;
	}	

	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;		
        if(parent::insertar()){
            $consultaInsertar="INSERT INTO pasajero(pdocumento, idviaje) 
				VALUES('".$this->getNroDocumento()."', ".$this->getIdViaje().")";		
		if($base->Iniciar()){
            if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdPasajero($id);
			    $resp=  true;
			}	else {
					$this->setMensajeOperacion($base->getError());					
			}
            } else {
                    $this->setMensajeOperacion($base->getError());               
            }
        }
		return $resp;
	}
	
	
	
	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
        if(parent::modificar()){
            $consultaModificar="UPDATE pasajero SET idviaje=".$this->getIdViaje().
            " WHERE idpasajero=". $this->getIdPasajero()."";
            if($base->Iniciar()){
                if($base->Ejecutar($consultaModificar)){
                    $resp=  true;
                }else{
                    $this->setMensajeOperacion($base->getError());                 
                }
            }else{
                    $this->setMensajeOperacion($base->getError());               
            }
    }
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
        $consultaBorra="DELETE FROM pasajero WHERE idpasajero=".$this->getIdPasajero()."";        
		if($base->Iniciar()){
				if($base->Ejecutar($consultaBorra)){
                    if(parent::eliminar()){
                        $resp=  true;
                    }				    
				}else{
						$this->setMensajeOperacion($base->getError());					
				}
		}else{
				$this->setMensajeOperacion($base->getError());			
		}
		return $resp; 
	}
    

    public function __toString(){
        $cadena= parent::__toString();
        return "[Id pasajero: ". $this->getIdPasajero()."]".$cadena. "[ID del viaje: ". $this->getIdViaje(). "]";
    }
}