<?php 
Class Persona{
    private $nombre;
    private $apellido;
    private $nroDocumento;
    private $telefono;
    private $mensajeOperacion;

    public function __construct(){
        $this->nombre="";
        $this->apellido="";
        $this->nroDocumento="";
        $this->telefono=0;
    }
    public function cargar($nombre,$apellido,$nroDocumento,$telefono){		
		$this->setNombre($nombre);
		$this->setApellido($apellido);
		$this->setNroDocumento($nroDocumento);
		$this->setTelefono($telefono);
    }
    public function getNombre() {
    	return $this->nombre;
    }
    public function setNombre($nombre) {
    	$this->nombre = $nombre;
    }

    public function getApellido() {
    	return $this->apellido;
    }
    public function setApellido($apellido) {
    	$this->apellido = $apellido;
    }

    public function getNroDocumento() {
    	return $this->nroDocumento;
    }
    public function setNroDocumento($nroDocumento) {
    	$this->nroDocumento = $nroDocumento;
    }
    public function getTelefono() {
    	return $this->telefono;
    }
    public function setTelefono($telefono) {
    	$this->telefono = $telefono;
    }

    public function getMensajeOperacion() {
    	return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
    	$this->mensajeOperacion = $mensajeOperacion;
    }

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from persona where nrodocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    $this->setNroDocumento($dni);
					$this->setNombre($row2['nombre']);
					$this->setApellido($row2['apellido']);
					$this->setTelefono($row2['telefono']);
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
	    $arregloPersona = null;
		$base=new BaseDatos();
		$consultaPersonas="Select * from persona ";
		if ($condicion!=""){
		    $consultaPersonas=$consultaPersonas.' where '.$condicion;
		}
		$consultaPersonas.=" order by apellido ";	
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){				
					$persona = new Persona();
					$persona->Buscar($row2['nrodocumento']);
					array_push($arregloPersona,$persona);
				}							
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());	 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());		 	
		 }	
		 return $arregloPersona;
	}	


	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO persona(nrodocumento, apellido, nombre,  telefono) 
				VALUES('".$this->getNroDocumento()."', '".$this->getApellido()."', '".$this->getNombre()."', ".$this->getTelefono().")";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){

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
		$consultaModificar="UPDATE persona SET apellido='".$this->getApellido()."',nombre='".$this->getNombre()."'
                           ,telefono=".$this->getTelefono()." WHERE nrodocumento='". $this->getNroDocumento()."'";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModificar)){
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
				$consultaBorra="DELETE FROM persona WHERE nrodocumento='".$this->getNroDocumento()."'";
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
        return "[Nombre: ". $this->getNombre(). "][Apellido: ". $this->getApellido(). 
        "][Numero de documento: ". $this->getNroDocumento(). "][Telefono: ". $this->getTelefono(). "]";
    }
}