SELECT userid
FROM (SELECT userid, AVG(n_registos) as media FROM
          (SELECT reg_pag.userid, COUNT(reg_pag.regid) AS n_registos
          FROM reg_pag INNER JOIN registo INNER JOIN tipo_registo INNER JOIN pagina
          WHERE reg_pag.pageid = pagina.pagecounter
          AND tipo_registo.typecnt = registo.typecounter
                  AND reg_pag.regid = registo.regcounter
          AND registo.ativo = 1
          AND reg_pag.ativa = 1
AND pagina.ativa = 1
AND tipo_registo.ativo = 1
          GROUP BY reg_pag.pageid) AS TOTAL_REG_BY_USER
     GROUP BY userid) AS MEAN_BY_USER
WHERE media = (
     SELECT MAX(media)
    FROM
          (SELECT AVG(n_registos) as media FROM
               (SELECT reg_pag.userid, COUNT(reg_pag.regid) AS n_registos
          FROM reg_pag INNER JOIN registo INNER JOIN tipo_registo INNER JOIN pagina
          WHERE reg_pag.pageid = pagina.pagecounter
          AND tipo_registo.typecnt = registo.typecounter
                  AND reg_pag.regid = registo.regcounter
          AND registo.ativo = 1
          AND reg_pag.ativa = 1
AND pagina.ativa = 1
AND tipo_registo.ativo = 1
          GROUP BY reg_pag.pageid) AS TOTAL_REG
          GROUP BY userid
          ORDER BY media DESC) AS MEAN_BY_USER)
