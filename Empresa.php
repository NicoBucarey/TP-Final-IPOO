<?php
Class Empresa{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    public function __construct(){
        $this->idEmpresa=null;
        $this->nombre="";
        $this->direccion="";
    }

    public function cargar($nombre,$direccion){	
		$this->setNombre($nombre);
		$this->setDireccion($direccion);
    }

    public function getIdEmpresa() {
    	return $this->idEmpresa;
    }
    public function setIdEmpresa($idEmpresa) {
    	$this->idEmpresa = $idEmpresa;
    }

    public function getNombre() {
    	return $this->nombre;
    }
    public function setNombre($nombre) {
    	$this->nombre = $nombre;
    }

    public function getDireccion() {
    	return $this->direccion;
    }
    public function setDireccion($direccion) {
    	$this->direccion = $direccion;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion=$mensajeOperacion;
    }
    /**
	 * Recupera los datos de una persona por id
	 * @param int $id
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($id){
		$base=new BaseDatos();
		$consultaPersona="SELECT * FROM empresa WHERE idempresa=".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    $this->setIdEmpresa($row2['idempresa']);
					$this->setNombre($row2['enombre']);
					$this->setDireccion($row2['edireccion']);
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
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){				
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
					$empresa=new Empresa();
					$empresa->Buscar($row2['idempresa']);
					array_push($arregloEmpresa,$empresa);
				}
		 	}else{
		 			$this->setmensajeoperacion($base->getError());		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());	 	
		 }	
		 return $arregloEmpresa;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion) 
				VALUES('".$this->getNombre()."', '".$this->getDireccion()."')";		
		if($base->Iniciar()){
			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdEmpresa($id);
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
		$consultaModifica="UPDATE empresa SET enombre='".$this->getNombre()."',edireccion='".$this->getDireccion()."'
                            WHERE idempresa=".$this->getIdEmpresa();
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
				$consultaBorra="DELETE FROM empresa WHERE idEmpresa=".$this->getIdEmpresa();
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
        return "[Id Empresa: ". $this->getIdEmpresa(). "][Nombre: ". $this->getNombre(). 
        "][Direccion: ". $this->getDireccion(). "]";
    }
}