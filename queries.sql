# Query 2a
SELECT userid
FROM utilizador, login
WHERE utilizador.userid = login.userid
GROUP BY utilizador.userid
HAVING count(CASE WHEN sucesso = 1 THEN 1 END) < count(contador_login) - count(CASE WHEN sucesso = 1 THEN 1 END);

SELECT utilizador.userid
FROM utilizador, login
WHERE utilizador.userid = login.userid
GROUP BY utilizador.userid
HAVING sum(sucesso = 1) < count(contador_login) - sum(sucesso = 1); 

# Query 2b
-- Premite obter registos de um utilizador
CREATE TEMPORARY TABLE IF NOT EXISTS utilizador_registos
SELECT utilizador.userid as userid, registo.regcounter as regcounter
FROM utilizador, tipo_registo, registo
WHERE utilizador.userid = tipo_registo.userid AND
      tipo_registo.typecnt = registo.typecounter;

-- Permite obter paginas de um utilizador
CREATE TEMPORARY TABLE IF NOT EXISTS utilizador_paginas
SELECT utilizador.userid as userid, pagina.pagecounter as pagecounter
FROM utilizador, pagina
WHERE utilizador.userid = pagina.userid;

-- Associa utilizador as suas paginas e aos seus registos
CREATE TEMPORARY TABLE IF NOT EXISTS utilizador_paginas_registos
SELECT utilizador.userid as userid, reg_pag.pageid as pageid, registo.regcounter as regcounter
FROM utilizador, pagina, reg_pag, registo
WHERE utilizador.userid = pagina.userid AND
      pagina.userid = reg_pag.userid AND
      pagina.pagecounter = reg_pag.pageid AND
      reg_pag.userid = registo.userid AND
      reg_pag.regid = registo.regcounter AND
      reg_pag.typeid = registo.typecounter;

CREATE TEMPORARY TABLE IF NOT EXISTS registos_do_userlogin
SELECT reg_pag.userid, reg_pag.regid, reg_pag.pageid
FROM reg_pag, registo
WHERE reg_pag.userid = registo.userid AND
      reg_pag.regid = registo.regcounter;

SELECT rdu.regid
FROM registos_do_user rdu
WHERE NOT EXISTS ( -- onde nao existem pag do utilizador que nao tem esse registo
    SELECT pagecounter
    FROM pagina
    WHERE pagina.userid = rdu.userid AND
          pagina.pagecounter NOT IN ( -- pagina do utilizador que nao esta associada ao registo rdu.regid
            SELECT pageid
            FROM reg_pag
            WHERE reg_pag.regid = rdu.regid
            )
);

# DATA WAREHOUSE

#d utilizador(email, nome, pa´ıs, categoria)
#debug, don't include in final
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS facts_login;
DROP TABLE IF EXISTS d_utilizador;
DROP TABLE IF EXISTS d_tempo;
SET FOREIGN_KEY_CHECKS=1;

#end debug


CREATE TABLE IF NOT EXISTS d_tempo(
  timeid INT NOT NULL AUTO_INCREMENT,
    dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
PRIMARY KEY(timeid)
);

CREATE TABLE IF NOT EXISTS d_utilizador(
    userid INT NOT NULL  AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    pais VARCHAR(45) NOT NULL,
    categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (userid)
);

CREATE TABLE IF NOT EXISTS facts_login(
  userid INT NOT NULL,
    timeid INT NOT NULL,
    num_login_attempts INT NOT NULL,
PRIMARY KEY (userid, timeid),
FOREIGN KEY (timeid) REFERENCES d_tempo(timeid) ON DELETE CASCADE,
FOREIGN KEY (userid) REFERENCES d_utilizador(userid) ON DELETE CASCADE
);


INSERT INTO d_utilizador(userid, email, nome, pais, categoria)
SELECT DISTINCT utilizador.userid, utilizador.email, utilizador.nome, utilizador.pais, utilizador.categoria
FROM utilizador, login
WHERE utilizador.userid = login.userid;

INSERT INTO d_tempo (timeid, dia, mes, ano)
SELECT contador_login, DAYOFYEAR(moment) * YEAR(moment), MONTH(moment)*YEAR(moment), YEAR(moment)
FROM login;


INSERT INTO facts_login(userid, timeid, num_login_attempts)
SELECT utilizador.userid, login.contador_login, count(login.moment)
FROM utilizador, login
WHERE utilizador.userid = login.userid
GROUP BY DAY(moment) * YEAR(moment);

# Warehouse ROLLUP Query
SELECT d_utilizador.categoria, d_tempo.ano, d_tempo.mes, d_utilizador.pais, avg(num_login_attempts) as avg_login_attempts
FROM d_utilizador, d_tempo, facts_login
WHERE d_utilizador.userid = facts_login.userid AND
      d_tempo.timeid = facts_login.timeid AND
      d_utilizador.pais = "Portugal"
GROUP BY d_utilizador.categoria, d_tempo.ano, d_tempo.mes WITH ROLLUP;

# Misc Queries
-- Quais sao as paginas sem registos?
SELECT pagina.pagecounter
FROM pagina
WHERE pagina.pagecounter NOT IN (
      SELECT reg_pag.pageid
      FROM reg_pag);

-- Quais sao os utilizadores sem paginas?
SELECT utilizador.userid
FROM utilizador
WHERE utilizador.userid NOT IN (
      SELECT pagina.userid
      FROM pagina);
