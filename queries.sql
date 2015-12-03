# Query 2a
SELECT userid
FROM utilizador NATURAL JOIN login
GROUP BY userid
HAVING count(CASE WHEN sucesso = 1 THEN 1 END) < count(contador_login) - count(CASE WHEN sucesso = 1 THEN 1 END);

SELECT userid
FROM utilizador NATURAL JOIN login
GROUP BY userid
HAVING sum(sucesso = 1) < count(contador_login) - sum(sucesso = 1); 
