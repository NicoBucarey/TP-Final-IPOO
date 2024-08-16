CREATE DATABASE bdviajes; 
use bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

 CREATE TABLE persona(
    nrodocumento varchar (15) PRIMARY KEY, 
    apellido varchar (150),
    nombre varchar (150),
    telefono int
 )ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    nrodocumento varchar (15),
    rnumerolicencia bigint,
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (nrodocumento) REFERENCES persona (nrodocumento) ON UPDATE CASCADE ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	
    
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE 
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero (
    idpasajero bigint AUTO_INCREMENT,
    pdocumento varchar(15),
	idviaje bigint,
    PRIMARY KEY (idpasajero),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (pdocumento) REFERENCES persona(nrodocumento) ON UPDATE CASCADE ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
 

  
