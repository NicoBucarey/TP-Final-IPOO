<?php
include_once("BaseDatos.php");
include_once("Empresa.php");
include_once("Persona.php");
include_once("Responsable.php");
include_once("Viaje.php");
include_once("Pasajero.php");

// -------------------------------- M E N U    P R I N C I P A L ---------------------------------
function mostrarMenuPrincipal() {
    echo "\nMenu Principal :\n";
    echo "1. Empresa\n";
    echo "2. Pasajero\n";
    echo "3. Responsable\n";
    echo "4. Viaje\n";
    echo "5. Salir\n";
    echo "Seleccione una opción: ";
}

do{
    $valor=true;
mostrarMenuPrincipal();
$opcion= trim(fgets(STDIN));
switch ($opcion) {
        case 1:
            menuEmpresa();
        break;
        case 2:
            menuPasajero();
        break;
        case 3:
            menuResponsable();
        break;
        case 4:
            menuViaje();
        break;
        case 5:
            exit();
        break;
        default:
            echo "La opcion es incorrecta, ingrese nuevamente una opcion.";
        break;
}
}while($valor);
// -----------------------------------------Funciones especiales----------------------------------
function esNumero ($param){
    $numero= false;
    if(is_numeric($param)){
        $numero= true;
    }else{
        echo "Debe ingresar un numero \n";
    }
    return $numero;
}
// --------------------------------------E M P R E S A ------------------------------------------------
function mostrarMenuEmpresa() {
    echo "\nMenu de Empresa:\n";
    echo "1. Ingresar Empresa\n";
    echo "2. Modificar Empresa\n";
    echo "3. Eliminar Empresa\n";
    echo "4. Buscar Empresa\n";
    echo "5. Volver al menu principal\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}

function ingresarEmpresa() {
    echo "Ingrese el nombre de la empresa: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la direccion de la empresa: ";
    $direccion = trim(fgets(STDIN));

    $empresa = new Empresa();
    $empresa->cargar($nombre,$direccion);
    
    if ($empresa->insertar()) {
        echo "Empresa ingresada correctamente.\n";
        echo "ID de la empresa ingresada: " . $empresa->getIdEmpresa();
    } else {
        echo "Error al ingresar la empresa: " . $empresa->getMensajeOperacion() . "\n";
    }
}

function modificarEmpresa() {
    echo "Ingrese un numero de ID de la empresa a modificar: ";
    $id = trim(fgets(STDIN));
    $valor = esNumero($id);
    if($valor){
        $empresa = new Empresa();
        if ($empresa->Buscar($id)) {
            echo "Empresa encontrada. Ingrese los nuevos datos.\n";
            echo "Nuevo nombre de la empresa (actual: " . $empresa->getNombre() . "): ";
            $nombre = trim(fgets(STDIN));
            echo "Nueva direccion de la empresa (actual: " . $empresa->getDireccion() . "): ";
            $direccion = trim(fgets(STDIN));
            
            $empresa->setNombre($nombre);
            $empresa->setDireccion($direccion);
            
            if ($empresa->modificar()) {
                echo "Empresa modificada correctamente.\n";
            } else {
                echo "Error al modificar la empresa: " . $empresa->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Empresa no encontrada.\n";
        }
    }
    
}

function eliminarEmpresa() {

     echo "Ingrese el ID de la empresa a eliminar: ";
    $idEmpresa = trim(fgets(STDIN));
    $valor= esNumero($idEmpresa);
    if($valor){
        $empresa = new Empresa();
        $empresaEncontradaEnViaje=null;
        $viaje = new Viaje();
        $empresaEncontradaEnViaje= $viaje->listar('idempresa= '. $idEmpresa);
        if($empresaEncontradaEnViaje== null){
            if ($empresa->Buscar($idEmpresa)) {
                if ($empresa->eliminar()) {
                    echo "Empresa eliminada correctamente.\n";
                } else {
                    echo "Error al eliminar la empresa: " . $empresa->getMensajeOperacion() . "\n";
                }
            } else {
                echo "Empresa no encontrada.\n";
            }
        }else {
            echo "la Empresa no se puede eliminar debido a que ya tiene viajes asignados. Primero de debera elmininar los viajes ";
        }
    }
    
}


function buscarEmpresa() {
   $valor= true;
    while($valor){
       echo "Ingrese el ID de la empresa a buscar: ";
        $id = trim(fgets(STDIN));  
        $empresa = new Empresa();
        if(esNumero($id)){
           if ($empresa->Buscar($id)) {
            echo "Empresa encontrada:\n";
            echo $empresa . "\n";
            $valor= false;       
            } else {
            echo "Empresa no encontrada.\n";
            } 
        }     
    }       
}
function menuEmpresa(){
    do{
        $valor=true;
        mostrarMenuEmpresa();
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                ingresarEmpresa();
                break;
            case 2:
                modificarEmpresa();
                break;
            case 3:
                eliminarEmpresa();
                break;
            case 4:
                buscarEmpresa();
                break;
            case 5:
                $valor= false;
                break;
            case 6:
                echo "\nSaliendo...\n\n";
                exit();
                break;
            default:
                echo "Opción no válida\n";
            break;
        }
    }while ($valor);
}
// -------------------------------------------- P A S A J E R O --------------------------------------------

function mostrarMenuPasajero() {
    echo "\nMenu de Pasajero:\n";
    echo "1. Ingresar Pasajero\n";
    echo "2. Modificar Pasajero\n";
    echo "3. Eliminar Pasajero\n";
    echo "4. Buscar Pasajero\n";
    echo "5. Volver al menu principal\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}
// ----------------------funcion especial------------------------
function listarViaje(){
    $viaje = new Viaje();
    $listaViajes = $viaje->listar();
    echo "Estos son los viajes disponibles, cada uno con su respectivo ID\n";
    foreach ($listaViajes as $viajes) {
        echo "Destino: " . $viajes->getDestino() . "\n";
        echo "ID Viaje: " . $viajes->getIdViaje() . "\n";
        echo "----------------------------------------\n";
    }
}
// --------------------------------------------------------------
function ingresarPasajero() {
    echo "¿Ya tiene asignado un viaje? si/no:  ";
    $rta = trim((fgets(STDIN)));
    $valor= false;
    switch (strtolower($rta)) {
        case 'si':
            listarViaje();
            echo "Ingrese el ID del Viaje: ";
            $idViaje = trim(fgets(STDIN));
            $valor=esNumero($idViaje);
            if($valor){
                $viaje = new Viaje();   
                if($viaje->Buscar($idViaje)){
                    echo "Viaje encontrado.\n";
                    
                        $cantMaxPasajeros= $viaje->getCantMaxPasajeros();
                        $pasajerosCargados = count($viaje->getColObjPasajero());
                        if($pasajerosCargados<$cantMaxPasajeros){
                            echo "Ingrese el nombre del pasajero: ";
                            $nombre = trim(fgets(STDIN));
                            echo "Ingrese el apellido del pasajero: ";
                            $apellido = trim(fgets(STDIN));
                            echo "Ingrese el número de documento del pasajero: ";
                            $nroDocumento = trim(fgets(STDIN));
                            echo "Ingrese el teléfono del pasajero: ";
                            $telefono = trim(fgets(STDIN));

                            $pasajero = new Pasajero();
                            $pasajero->cargar($nombre, $apellido,$nroDocumento, $telefono, $idViaje);
                            if ($pasajero->insertar()) {
                                echo "Pasajero ingresado correctamente.\n";
                                echo $pasajero;
                            } else {
                                echo "Error al ingresar el pasajero: " . $pasajero->getMensajeOperacion() . "\n";
                            }           
                        }else{
                            echo "No se puede cargar porque no hay cupo para este viaje";
                        }
                    // }
                    
                }else{
                    echo "Viaje no encontrado, primero deberá cargar uno.";
                } 
            }         
            break;
        case 'no':
            echo "Primero debe crear un viaje.";
            break;
        default:
            echo "Opcion no valida, intente nuevamente.";
            break;
    }    
}

function modificarPasajero() {
    $valor= false;
    echo "Ingrese el ID del pasajero a modificar: ";
    $idPasajero = trim(fgets(STDIN));
    $valor= esNumero($idPasajero);
    if($valor){
        $pasajero = new Pasajero();
        if ($pasajero->BuscarPorId($idPasajero)) {
            echo "Pasajero encontrado. Ingrese los nuevos datos.(NOTA:el id del viaje y el Nro de doc no se podran modificar)\n";
            echo "Nuevo nombre del pasajero (actual: " . $pasajero->getNombre() . "): ";
            $nombre = trim(fgets(STDIN));
            echo "Nuevo apellido del pasajero (actual: " . $pasajero->getApellido() . "): ";
            $apellido = trim(fgets(STDIN));
            echo "Nuevo teléfono del pasajero (actual: " . $pasajero->getTelefono() . "): ";
            $telefono = trim(fgets(STDIN));
                    
            $pasajero->setNombre($nombre);
            $pasajero->setApellido($apellido);
            $pasajero->setTelefono($telefono);

            if ($pasajero->modificar()) {
                echo "Pasajero modificado correctamente.\n";
                echo $pasajero;
            } else {
                echo "Error al modificar el pasajero: " . $pasajero->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Pasajero no encontrado.\n";
        } 
    }
    
}

function eliminarPasajero() {
    $valor= false;
    echo "Ingrese el numero de ID del pasajero a eliminar: ";
    $idPasajero = trim(fgets(STDIN));
    $valor= esNumero($idPasajero);
    if($valor){
        $pasajero = new Pasajero();
        if ($pasajero->BuscarPorId($idPasajero)) {
            if ($pasajero->eliminar()) {
                echo "Pasajero eliminado correctamente.\n";
            } else {
                echo "Error al eliminar el pasajero: " . $pasajero->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Pasajero no encontrado.\n";
        }   
    }   
}

function buscarPasajero() {
    $valor=false;
    echo "Ingrese una opcion: \n";
    echo "1. Buscar Pasajero por ID: \n";
    echo "2. Buscar Pasajero por DNI: \n";
    $opcion= trim(fgets(STDIN));
    $pasajero = new Pasajero();
    switch ($opcion) {
        case 1:
            echo "Ingrese el ID del pasajero a buscar: ";
            $idPasajero = trim(fgets(STDIN));
            $valor=esNumero($idPasajero);
            if($valor){
                if ($pasajero->BuscarPorId($idPasajero)) {
                    echo "Pasajero encontrado:\n";
                    echo $pasajero . "\n";
                } else {
                    echo "Pasajero no encontrado.\n";
                }
                break;  
            }
            
        case 2:
            $valor=false;
            echo "Ingrese el DNI del pasajero a buscar: ";
            $nroDni = trim(fgets(STDIN));
            $valor=esNumero($nroDni);
            if($valor){
                if ($pasajero->BuscarPorDni($nroDni)) {
                    echo "Pasajero encontrado:\n";
                    echo $pasajero . "\n";
                } else {
                    echo "Pasajero no encontrado.\n";
                }  
            }        
            break;
        default:
            echo "Opcion no valida";
            break;
    }
}
function menuPasajero(){
    do{
        $valor=true;
        mostrarMenuPasajero();
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                ingresarPasajero();
                break;
            case 2:
                modificarPasajero();
                break;
            case 3:
                eliminarPasajero();
                break;
            case 4:
                buscarPasajero();
                break;
            case 5:
                $valor=false;
                break;
            case 6:
                echo "\nSaliendo...\n\n";
                exit();
            default:
                echo "Opción no válida\n";
                break;
        }
    }while($valor);
};


// -------------------------------------------- R E S P O N S A B L E -------------------------------------

function mostrarMenuResponsable() {
    echo "\nMenu de Responsable:\n";
    echo "1. Ingresar Responsable\n";
    echo "2. Modificar Responsable\n";
    echo "3. Eliminar Responsable\n";
    echo "4. Buscar Responsable\n";
    echo "5. Volver al menu principal\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}

function ingresarResponsable() {
    echo "Ingrese el nombre del responsable: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el apellido del responsable: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese el numero de documento: ";
    $nroDocumento = trim(fgets(STDIN));
    echo "Ingrese el numero de telefono: ";
    $telefono=trim(fgets(STDIN));
    echo "Ingrese el número de licencia del responsable: ";
    $nroLicencia = trim(fgets(STDIN));   

    $responsable = new Responsable();
    $responsable->cargar($nombre, $apellido,$nroDocumento,$telefono,$nroLicencia );
    
    if ($responsable->insertar()) {
        echo "\nResponsable ingresado correctamente.\n";
        echo "El numero de empleado del Responsable ingresado es: ". $responsable->getNroEmpleado() ."\n";
        echo $responsable;
    } else {
        echo "Error al ingresar el responsable: " . $responsable->getMensajeOperacion() . "\n";
    }
}

function modificarResponsable() {
    echo "Ingrese el número de empleado del responsable a modificar: ";
    $nroEmpleado = trim(fgets(STDIN));
    $valor= false;
    $valor=esNumero($nroEmpleado);
    if($valor){
        $responsable = new Responsable();
        if ($responsable->BuscarPorNroEmpleado($nroEmpleado)) {
            echo "Responsable encontrado. Ingrese los nuevos datos (El numero de DNI no se podrá actualizar).\n";
            // El nro de dni no se puede actualizar ya que si bien tiene como politica CASCADE ésta tiene que modificarse desde la tabla padre (persona) para que pueda actualizarse la tabla hija (responsable)
            echo "Nuevo nombre del responsable (actual: " . $responsable->getNombre() . "): ";
            $nombre = trim(fgets(STDIN));
            echo "Nuevo apellido del responsable (actual: " . $responsable->getApellido() . "): ";
            $apellido = trim(fgets(STDIN));
            echo "Nuevo Nro de telefono del responsable (actual: ". $responsable->getTelefono() . "): ";
            $telefono = trim(fgets(STDIN));
            echo "Nuevo número de licencia del responsable (actual: " . $responsable->getNroLicencia() . "): ";
            $nroLicencia = trim(fgets(STDIN));

            $responsable->setNombre($nombre);
            $responsable->setApellido($apellido);
            $responsable->setTelefono($telefono);
            $responsable->setNroLicencia($nroLicencia);
            $valor=true;
            if ($responsable->modificar()) {
                echo "Responsable modificado correctamente.\n";
                echo $responsable;
            } else {
                echo "Error al modificar el responsable: " . $responsable->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Responsable no encontrado.\n";
        } 
    }    
}

function eliminarResponsable() {
    echo "Ingrese el número de empleado del responsable a eliminar: ";
    $nroEmpleado = trim(fgets(STDIN));
    $valor= esNumero($nroEmpleado);
    if($valor){
        $responsable = new Responsable();
        $respEncontradoEnViaje=null;
        $viaje = new Viaje();
        $respEncontradoEnViaje= $viaje->listar('rnumeroempleado= '. $nroEmpleado);
        if($respEncontradoEnViaje== null){
            if ($responsable->BuscarPorNroEmpleado($nroEmpleado)) {
                if ($responsable->eliminar()) {
                    echo "Responsable eliminado correctamente.\n";
                } else {
                    echo "Error al eliminar el responsable: " . $responsable->getMensajeOperacion() . "\n";
                }
            } else {
                echo "Responsable no encontrado.\n";
            }
        }else {
            echo "el Responsable no se puede eliminar debido a que ya tiene viajes asignados. Primero de debera elmininar los viajes ";
        }
    }
    
}

function buscarResponsable() {
    echo "Ingrese una opcion: \n";
    echo "1. Buscar Responsable por Numero de empleado: \n";
    echo "2. Buscar Responsable por DNI: \n";
    $opcion= trim(fgets(STDIN));
    $responsable = new Responsable();
    switch ($opcion) {
        case 1:
            echo "Ingrese el número de empleado del responsable a buscar: ";
            $nroEmpleado = trim(fgets(STDIN));
            $valor= false;
            $valor=esNumero($nroEmpleado);
            if($valor){
                if ($responsable->BuscarPorNroEmpleado($nroEmpleado)) {
                    echo "Responsable encontrado:\n";
                    echo $responsable . "\n";
                } else {
                    echo "Responsable no encontrado.\n";
                }
            } 
            break;
        case 2:
            echo "Ingrese el DNI del responsable a buscar: ";
            $nroDni = trim(fgets(STDIN));
            $valor= false;
            $valor=esNumero($nroDni);
            if($valor){
                if ($responsable->BuscarPorDni($nroDni)) {
                        echo "Responsable encontrado:\n";
                        echo $responsable . "\n";
                    } else {
                        echo "Responsable no encontrado.\n";
                    }
            }   
            break;
        default:
            echo "Opcion no valida";
            break;
    }
    
}
function menuResponsable(){
    do{
        $valor=true;
        mostrarMenuResponsable();
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                ingresarResponsable();
                break;
            case 2:
                modificarResponsable();
                break;
            case 3:
                eliminarResponsable();
                break;
            case 4:
                buscarResponsable();
                break;
            case 5:
                $valor=false;
                break;
            case 6:
                echo "\nSaliendo...\n\n";
                exit();
                break;
            default:
                echo "Opción no válida\n";
                break;
        }
    }while ($valor);
}



// -------------------------------------------- V I A J E ---------------------------------------------

function mostrarMenuViaje() {
    echo "Menu de Viajes:\n";
    echo "1. Ingresar Viaje\n";
    echo "2. Modificar Viaje\n";
    echo "3. Eliminar Viaje\n";
    echo "4. Buscar Viaje\n";
    echo "5. Volver al menu principal\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}
// -------------------- funciones especiales--------------------------
function listarEmpresa(){
    $empresa = new Empresa();
    $listaEmpresa = $empresa->listar();
    echo "Estos son las Empresas disponibles, cada uno con su respectivo ID\n";
    foreach ($listaEmpresa as $empresas) {
        echo "ID Empresa: " . $empresas->getIdEmpresa() . "\n";
        echo "Nombre: " . $empresas->getNombre() . "\n";
        echo "----------------------------------------\n";
    }
}
function listarResponsable(){
    $responsable = new Responsable();
    $listaResponsable = $responsable->listar();
    echo "\nEstos son los Responsables disponibles, cada uno con su respectivo Numero de empleado\n";
    foreach ($listaResponsable as $responsables) {
        echo "Nro de empleado: " . $responsables->getNroEmpleado() . "\n";
        echo "Nombre y Apellido: " . $responsables->getNombre() ." ". $responsables->getApellido(). "\n";
        echo "----------------------------------------\n";
    }
}
// ----------------------------------------------------------------------
function ingresarViaje(){
    listarEmpresa();
    $encontrado=false;
    $valorEmpresa= false;  
        while(!$valorEmpresa){   
            echo "Ingrese el ID de la empresa: ";
            $id = trim(fgets(STDIN));  
            if(esNumero($id)){
                $objEmpresa = new Empresa();
                $valorEmpresa= true;
                if ($objEmpresa->Buscar($id)) {
                        echo "Empresa encontrada:\n";
                        $encontrado=true;
                        $empresa = $objEmpresa->listar('idempresa= '.$id);  
                        foreach($empresa as $emp){
                            echo $emp;
                        }                  
                    } else {
                        echo "Empresa no encontrada, primero deberá crear una.\n";
                    } 
            }           
        }       
    $valorResponsable = false;
    if($encontrado){
        listarResponsable();
        while(!$valorResponsable){   
            echo "Ingrese el Numero de empleado del Responsable: ";
            $nroEmpleado = trim(fgets(STDIN));    
            if(esNumero($nroEmpleado)){
                $objResponsable = new Responsable();  
                $valorResponsable= true;
                if ($objResponsable->BuscarPorNroEmpleado($nroEmpleado)) {
                        echo "Responsable del viaje encontrado:\n";
                        $responsable= $objResponsable->listar('rnumeroempleado= '.$nroEmpleado);
                        foreach($responsable as $resp){
                            echo $resp;
                        }              
                    } else {
                        echo "Responsable no encontrado, primero deberá crear uno.\n";
                    } 
            }     
        }  
    }
    if($valorEmpresa AND $valorResponsable){
        echo "\nIngrese el destino del viaje: ";
        $destino = trim(fgets(STDIN));
        echo "Ingrese la cantidad máxima de pasajeros: ";
        $cantMaxPasajeros = trim(fgets(STDIN));
        echo "Ingrese el importe del viaje: ";
        $importe = trim(fgets(STDIN));

        $viaje = new Viaje();
        $viaje->cargar($destino, $cantMaxPasajeros, $objEmpresa, $objResponsable, $importe, []);
        
        if ($viaje->insertar()) {
            echo "Viaje ingresado correctamente.\n";
            echo $viaje;
        } else {
            echo "Error al ingresar el viaje: " . $viaje->getMensajeOperacion() . "\n";
        }
    }    
}

function modificarViaje() {
    echo "Ingrese el ID del viaje a modificar: ";
    $idViaje = trim(fgets(STDIN));
    $valor= false;
    $valor=esNumero($idViaje);
    if($valor){
            $viaje = new Viaje();
        if ($viaje->Buscar($idViaje)) {
            echo "Viaje encontrado. Ingrese los nuevos datos.(NOTA: el ID empresa y el Responsable no se podrán modificar)\n";
            echo "Nueva cantidad máxima de pasajeros (actual: " . $viaje->getCantMaxPasajeros() . "): ";
            $cantMaxPasajeros = trim(fgets(STDIN));
            $cantActualPasajeros = count($viaje ->getColObjPasajero());
            if($cantMaxPasajeros>$cantActualPasajeros){
                echo "Nuevo destino del viaje (actual: " . $viaje->getDestino() . "): ";
                $destino = trim(fgets(STDIN));
                echo "Nuevo importe del viaje (actual: " . $viaje->getImporte() . "): ";
                $importe = trim(fgets(STDIN));
                $viaje->setDestino($destino);
                $viaje->setImporte($importe);
                $viaje->setCantMaxPasajeros($cantMaxPasajeros);
                if ($viaje->modificar()) {
                    echo "Viaje modificado correctamente.\n";
                    echo $viaje;
                } else {
                    echo "Error al modificar el viaje: " . $viaje->getMensajeOperacion() . "\n";
                }
            }else{
                echo "La cantidad maxima de pasajeros a modificar no es valida debido a que es inferior a la cantidad de pasajeros actual en el viaje (". $cantActualPasajeros . ") deberá eliminar pasajeros o bien ingresar una cantidad maxima de pasajeros mayor.";
            }
        } else {
            echo "Viaje no encontrado.\n";
        }    
    }
    
}

function eliminarViaje() {
    echo "Ingrese el ID del viaje a eliminar: ";
    $idViaje = trim(fgets(STDIN));
    $valor= false;
    $valor=esNumero($idViaje);
    if($valor){
        $viaje = new Viaje();
        if ($viaje->Buscar($idViaje)) {
            if ($viaje->eliminar()) {
                echo "Viaje eliminado correctamente.\n";
                $colPasajeros = $viaje->getColObjPasajero();
                if($colPasajeros!=null){
                    echo "El viaje eliminado tenia pasajeros, estos tambien fueron eliminados de la base de datos\n";
                }
            } else {
                echo "Error al eliminar el viaje: " . $viaje->getMensajeOperacion() . "\n";
            }
        } else {
            echo "Viaje no encontrado.\n";
        } 
    }
    
}

function buscarViaje() {
    echo "Ingrese el ID del viaje a buscar: ";
    $idViaje = trim(fgets(STDIN));
    $valor= false;
    $valor=esNumero($idViaje);
    if($valor){
        $viaje = new Viaje();
        if ($viaje->Buscar($idViaje)) {
            echo "Viaje encontrado:\n";
            echo $viaje . "\n";
            $colPasajeros = $viaje->getColObjPasajero();
            if ($colPasajeros== null){
                echo "Aun no hay pasajeros en este viaje\n";
            }
        } else {
            echo "Viaje no encontrado.\n";
        
        }  
    }
    
}
function menuViaje(){
   do{
    $valor=true;
    mostrarMenuViaje();
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
            ingresarViaje();
            break;
        case 2:
            modificarViaje();
            break;
        case 3:
            eliminarViaje();
            break;
        case 4:
            buscarViaje();
            break;
        case 5:
            $valor=false;
            break;
        case 6:
            echo "\nSaliendo...\n\n";
            exit();
            break;
        default:
            echo "Opción no válida\n";
            break;
    }
}while($valor);
}

?>
