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
