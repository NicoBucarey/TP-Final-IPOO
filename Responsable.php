<?php
Class Responsable extends Persona{
   private $nroEmpleado;
   private $nroLicencia;
   private $mensajeOperacion;
   
   public function __construct(){
        parent::__construct();
        $this->nroEmpleado=null;
        $this->nroLicencia=null;
   }
   public function cargar($nombre,$apellido,$nroDocumento,$telefono, $nroLicencia=null){
    parent::cargar($nombre,$apellido,$nroDocumento,$telefono);
    $this->setNroLicencia($nroLicencia);
}

   public function getNroEmpleado() {
   	return $this->nroEmpleado;
   }
   public function setNroEmpleado($nroEmpleado) {
   	$this->nroEmpleado = $nroEmpleado;
   }

   public function getNroLicencia() {
   	return $this->nroLicencia;
   }
   public function setNroLicencia($nroLicencia) {
   	$this->nroLicencia = $nroLicencia;
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
    public function BuscarPorDni($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from responsable where nrodocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    parent::Buscar($dni);
					$this->setNroEmpleado($row2['rnumeroempleado']);
                    $this->setNroLicencia($row2['rnumerolicencia']);
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
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function BuscarPorNroEmpleado($nroEmpleado){
		$base=new BaseDatos();
		$consultaPersona="SELECT * FROM responsable WHERE rnumeroempleado=".$nroEmpleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
					$dni = 	$row2['nrodocumento'];		
				    parent::Buscar($dni);
					$this->setNroEmpleado($row2['rnumeroempleado']);
                    $this->setNroLicencia($row2['rnumerolicencia']);
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
	    $arregloResponsable = null;
		$base=new BaseDatos();
		$consultaResponsable="SELECT * FROM responsable ";
		if ($condicion!=""){
		    $consultaResponsable=$consultaResponsable.' WHERE '.$condicion;
		}
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){				
				$arregloResponsable= array();				
				while($row2=$base->Registro()){				
					$responsable=new Responsable();
					$responsable->BuscarPorDni($row2['nrodocumento']);
					array_push($arregloResponsable,$responsable);					
				}			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());	 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());		 	
		 }	
		 return $arregloResponsable;
	}
	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		
        if(parent::insertar()){
            $consultaInsertar="INSERT INTO responsable(nrodocumento, rnumerolicencia) 
				VALUES('".$this->getNroDocumento()."', ".$this->getNroLicencia().")"; 		
		if($base->Iniciar()){
            if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setNroEmpleado($id);
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
            $consultaModificar="UPDATE responsable SET rnumerolicencia=".$this->getNroLicencia().
            " WHERE rnumeroempleado=". $this->getNroEmpleado()."";
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
        $consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNroEmpleado()."";    
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
    return "[Nro empleado: ". $this->getNroEmpleado(). "]".$cadena. "[Nro licencia de empleado: ". $this->getNroLicencia(). "]";
   }
}