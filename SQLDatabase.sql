-- -----------------------------------------------------
-- Para comenzar a usar Easy Reports v1.0, se proporciona este modelo de base de datos para comenzar a crear tu base de datos de empresa, en la que ya cuentas con las tablas basicas, Departamentos, Empleados, Proyectos, Clientes, Materiales y Mantenimientos. Puedes crear mas tablas si lo deseas pero estas no seran reflejadas en los informes.
-- Para crear su base de datos, abra este documento con su gestor de base de datos y reemplace todo (Ctrl+H) 'easyDatabase' por el nombre que prefiera para su base de datos, y seguidamente ejecute el script.
-- -----------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `easyDatabase` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `easyDatabase` ;

-- -----------------------------------------------------
-- Table `easyDatabase`.`DEPARTAMENTOS`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`DEPARTAMENTOS` (
  `Id_Departamento` INT NOT NULL AUTO_INCREMENT ,
  `Nombre` VARCHAR(45) NOT NULL ,
  `Descripcion` VARCHAR(100) NULL ,
  `EMPLEADO_JEFE_DNI` VARCHAR(9) NOT NULL ,
  `EMPLEADO_JEFE_Nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`Id_Departamento`, `Nombre`) ,
  UNIQUE INDEX `Id_Departamento_UNIQUE` (`Id_Departamento` ASC) ,
  INDEX `fk_DEPARTAMENTOS_EMPLEADOS1_idx` (`EMPLEADO_JEFE_DNI` ASC, `EMPLEADO_JEFE_Nombre` ASC) ,
  CONSTRAINT `fk_DEPARTAMENTOS_EMPLEADOS1`
    FOREIGN KEY (`EMPLEADO_JEFE_DNI` , `EMPLEADO_JEFE_Nombre` )
    REFERENCES `easyDatabase`.`EMPLEADOS` (`DNI` , `Nombre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`EMPLEADOS`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`EMPLEADOS` (
  `Id_Empleado` INT(11) NOT NULL AUTO_INCREMENT ,
  `DNI` VARCHAR(9) NOT NULL ,
  `Nombre` VARCHAR(45) NOT NULL ,
  `Pais` VARCHAR(45) NOT NULL ,
  `Ciudad` VARCHAR(45) NOT NULL ,
  `Fecha_Nac` DATE NOT NULL ,
  `Sexo` VARCHAR(6) NOT NULL ,
  `Telefono` VARCHAR(9) NULL ,
  `Email` VARCHAR(45) NULL ,
  `Cargo` VARCHAR(45) NOT NULL ,
  `Fecha_Alta` DATE NOT NULL ,
  `Fecha_Baja` DATE NULL ,
  `Sueldo` DECIMAL(9,2) NOT NULL DEFAULT 0 ,
  `DEPARTAMENTOS_Id_Departamento` INT(11) NOT NULL ,
  `DEPARTAMENTOS_Nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`Id_Empleado`, `DNI`, `Nombre`) ,
  UNIQUE INDEX `DNI_UNIQUE` (`DNI` ASC) ,
  UNIQUE INDEX `Id_Empleado_UNIQUE` (`Id_Empleado` ASC) ,
  INDEX `fk_EMPLEADOS_DEPARTAMENTOS1_idx` (`DEPARTAMENTOS_Id_Departamento` ASC, `DEPARTAMENTOS_Nombre` ASC) ,
  CONSTRAINT `fk_EMPLEADOS_DEPARTAMENTOS1`
    FOREIGN KEY (`DEPARTAMENTOS_Id_Departamento` , `DEPARTAMENTOS_Nombre` )
    REFERENCES `easyDatabase`.`DEPARTAMENTOS` (`Id_Departamento` , `Nombre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`PROYECTOS`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`PROYECTOS` (
  `Id_Proyecto` INT NOT NULL AUTO_INCREMENT ,
  `Nombre` VARCHAR(45) NOT NULL ,
  `Descripcion` VARCHAR(100) NULL ,
  `Fecha_Inicio` DATE NULL ,
  `Fecha_Fin` DATE NULL ,
  PRIMARY KEY (`Id_Proyecto`, `Nombre`) ,
  UNIQUE INDEX `Id_Proyecto_UNIQUE` (`Id_Proyecto` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`PROYECTO_has_CLIENTES`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`PROYECTO_has_CLIENTES` (
  `CLIENTES_Id_Cliente` INT NOT NULL ,
  `PROYECTOS_Id_Proyecto` INT NOT NULL ,
  `PROYECTOS_Nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`CLIENTES_Id_Cliente`, `PROYECTOS_Id_Proyecto`, `PROYECTOS_Nombre`) ,
  INDEX `fk_PROYECTO_has_CLIENTES_CLIENTES1_idx` (`CLIENTES_Id_Cliente` ASC) ,
  INDEX `fk_PROYECTO_has_CLIENTES_PROYECTOS1_idx` (`PROYECTOS_Id_Proyecto` ASC) ,
  INDEX `fk_PROYECTO_has_CLIENTES_PROYECTOS2_idx` (`PROYECTOS_Nombre` ASC) ,
  CONSTRAINT `fk_PROYECTO_has_CLIENTES_CLIENTES1`
    FOREIGN KEY (`CLIENTES_Id_Cliente` )
    REFERENCES `easyDatabase`.`CLIENTES` (`Id_Cliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROYECTO_has_CLIENTES_PROYECTOS1`
    FOREIGN KEY (`PROYECTOS_Id_Proyecto` )
    REFERENCES `easyDatabase`.`PROYECTOS` (`Id_Proyecto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PROYECTO_has_CLIENTES_PROYECTOS2`
    FOREIGN KEY (`PROYECTOS_Nombre` )
    REFERENCES `easyDatabase`.`PROYECTOS` (`Nombre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`CLIENTES`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`CLIENTES` (
  `Id_Cliente` INT NOT NULL AUTO_INCREMENT ,
  `Nombre` VARCHAR(45) NULL ,
  `Pais` VARCHAR(45) NULL ,
  `Ciudad` VARCHAR(45) NULL ,
  `DNI` VARCHAR(9) NULL ,
  `Fecha_Alta` DATE NULL ,
  `Fecha_Baja` DATE NULL ,
  `Proyecto` VARCHAR(45) NULL ,
  `Articulo` VARCHAR(100) NULL ,
  `Precio` DECIMAL(9,2) NULL ,
  `Cuota_Mensual` DECIMAL(9,2) NULL ,
  PRIMARY KEY (`Id_Cliente`) ,
  UNIQUE INDEX `Id_Cliente_UNIQUE` (`Id_Cliente` ASC) ,
  INDEX `fk_CLIENTES_idx` (`Proyecto` ASC) ,
  CONSTRAINT `fk_CLIENTES`
    FOREIGN KEY (`Proyecto` )
    REFERENCES `easyDatabase`.`PROYECTO_has_CLIENTES` (`PROYECTOS_Nombre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`MATERIAL`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`MATERIAL` (
  `Id_Material` INT(11) NOT NULL AUTO_INCREMENT ,
  `Tipo` VARCHAR(45) NOT NULL ,
  `Marca` VARCHAR(45) NULL ,
  `Ref` VARCHAR(10) NULL ,
  `Coste` DECIMAL(9,2) NULL ,
  `Fecha_Compra` DATE NULL ,
  `Estado` ENUM('Bueno','Regular','Malo') NOT NULL ,
  `EMPLEADOS_Id_Empleado` INT(11) NOT NULL ,
  PRIMARY KEY (`Id_Material`) ,
  UNIQUE INDEX `Id_Material_UNIQUE` (`Id_Material` ASC) ,
  INDEX `fk_MATERIAL_EMPLEADOS1_idx` (`EMPLEADOS_Id_Empleado` ASC) ,
  CONSTRAINT `fk_MATERIAL_EMPLEADOS1`
    FOREIGN KEY (`EMPLEADOS_Id_Empleado` )
    REFERENCES `easyDatabase`.`EMPLEADOS` (`Id_Empleado` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`MANTENIMIENTO`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`MANTENIMIENTO` (
  `Id_Mantenimiento` INT(11) NOT NULL ,
  `Fecha` DATE NOT NULL ,
  `Tipo` VARCHAR(45) NULL ,
  `Coste` DECIMAL(9,2) NULL ,
  `Detalles` VARCHAR(100) NULL ,
  `MATERIAL_Id_Material` INT(11) NOT NULL ,
  PRIMARY KEY (`Id_Mantenimiento`) ,
  INDEX `fk_MANTENIMIENTO_MATERIAL1_idx` (`MATERIAL_Id_Material` ASC) ,
  CONSTRAINT `fk_MANTENIMIENTO_MATERIAL1`
    FOREIGN KEY (`MATERIAL_Id_Material` )
    REFERENCES `easyDatabase`.`MATERIAL` (`Id_Material` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`AUX_MES`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`AUX_MES` (
  `Id_Mes` INT(11) NOT NULL ,
  `Mes` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`Id_Mes`, `Mes`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyDatabase`.`Login`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `easyDatabase`.`Login` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `hostdb` VARCHAR(45) NULL ,
  `userdb` VARCHAR(45) NULL ,
  `passdb` VARCHAR(45) NULL ,
  `namedb` VARCHAR(45) NULL ,
  `Empresa` VARCHAR(45) NULL ,
  `ObjetoSocial` VARCHAR(100) NULL ,
  `NIF` VARCHAR(9) NULL ,
  `Fecha_Alta` DATE NULL ,
  `Domicilio` VARCHAR(100) NULL ,
  `Telefono` VARCHAR(9) NULL ,
  `Logincol` VARCHAR(45) NULL ,
  `Email` VARCHAR(45) NULL ,
  `URL` VARCHAR(45) NULL ,
  `Tbl_Departamentos` VARCHAR(45) NULL ,
  `Tbl_Empleados` VARCHAR(45) NULL ,
  `Tbl_Material` VARCHAR(45) NULL ,
  `Tbl_Mantenimiento` VARCHAR(45) NULL ,
  `Tbl_Proyectos` VARCHAR(45) NULL ,
  `Tbl_Clientes` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`, `username`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;

USE `easyDatabase` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
