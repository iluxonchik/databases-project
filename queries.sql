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

CREATE TEMPORARY TABLE IF NOT EXISTS registos_do_user
SELECT reg_pag.userid, reg_pag.regid, reg_pag.pageid
FROM reg_pag, registo
WHERE reg_pag.userid = registo.userid AND
      reg_pag.regid = registo.regcounter;

SELECT rdu.regid, rdu.userid, rdu.pageid
FROM registos_do_user rdu
WHERE NOT EXISTS ( -- onde n existem pag do user que n tem esse registo
    SELECT pagecounter
    FROM pagina
    WHERE pagina.userid = rdu.userid AND
          pagina.pagecounter NOT IN (
            SELECT pageid
            FROM reg_pag
            WHERE reg_pag.userid = rdu.userid
            )
);


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