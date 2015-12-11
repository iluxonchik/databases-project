DROP TRIGGER IF EXISTS valor_trigger;
DROP TRIGGER IF EXISTS type_reg_trigger;
DROP TRIGGER IF EXISTS pagina_trigger;
DROP TRIGGER IF EXISTS campo_trigger;
DROP TRIGGER IF EXISTS reg_trigger;
DROP PROCEDURE IF EXISTS checkvalue;

Delimiter ///
CREATE PROCEDURE checkvalue (valor INT)
BEGIN
 IF valor IN (
 SELECT tipo_registo.idseq, pagina.idseq, campo.idseq, registo.idseq, valor.idseq
  FROM tipo_registo, pagina, campo, registo, valor)
 THEN CALL daerro();
 END IF ;
END ///
Delimiter ;


Delimiter ///
CREATE TRIGGER pagina_trigger BEFORE INSERT ON pagina
FOR EACH ROW
BEGIN
 call checkvalue(NEW.idseq);
END; ///
Delimiter ;

Delimiter ///
CREATE TRIGGER type_reg_trigger BEFORE INSERT ON tipo_registo
FOR EACH ROW
BEGIN
 call checkvalue(NEW.idseq);
END; ///
Delimiter ;



Delimiter ///
CREATE TRIGGER campo_trigger BEFORE INSERT ON campo
FOR EACH ROW
BEGIN
 call checkvalue(NEW.idseq);
END; ///
Delimiter ;


Delimiter ///
CREATE TRIGGER valor_trigger BEFORE INSERT ON valor
FOR EACH ROW
BEGIN
 call checkvalue(NEW.idseq);
END; ///
Delimiter ;

Delimiter ///
CREATE TRIGGER reg_trigger BEFORE INSERT ON registo
FOR EACH ROW
BEGIN
 call checkvalue(NEW.idseq);
END; ///
Delimiter ;
