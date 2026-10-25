-- Migracao da classificacao do acervo: Categoria -> Genero -> Subgenero -> Livro
-- Alvo: schema anterior descrito no guia de atualizacao, com catalogo.categoria
-- (ENUM/CHAR), genero sem id_categoria e catalogo.id_subgenero anulavel.
--
-- Antes de executar:
-- 1. Gere e valide um backup do banco.
-- 2. Confira os resultados das consultas de diagnostico abaixo.
-- 3. Execute uma unica vez no banco salaberga_biblioteca.
--
-- O dump versionado em docs/salaberga_biblioteca.sql ja possui o schema novo;
-- nao aplique esta migracao sobre esse dump.

-- Diagnostico: valores de categoria que nao serao mapeados pelos dados iniciais.
SELECT DISTINCT TRIM(categoria) AS categoria_sem_mapeamento
FROM catalogo
WHERE categoria IS NOT NULL
  AND LOWER(TRIM(categoria)) NOT IN ('geral', 'didatico', 'paradidatico', 'tecnico');

-- Diagnostico: generos utilizados por livros de mais de uma categoria.
-- A migracao atribui ao genero a categoria mais frequente; em empate, usa o
-- menor id de categoria. Os livros preservam individualmente sua categoria.
SELECT id_genero, COUNT(DISTINCT LOWER(TRIM(categoria))) AS total_categorias
FROM catalogo
WHERE categoria IS NOT NULL
GROUP BY id_genero
HAVING COUNT(DISTINCT LOWER(TRIM(categoria))) > 1;

-- Tabela de categorias e valores estaveis usados pelos relacionamentos.
CREATE TABLE categoria (
    id INT(11) NOT NULL AUTO_INCREMENT,
    categorias CHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO categoria (id, categorias) VALUES
    (1, 'geral'),
    (2, 'didatico'),
    (3, 'paradidatico'),
    (4, 'tecnico');

-- Criar os campos inicialmente anulaveis para permitir o preenchimento dos
-- dados existentes antes da aplicacao das restricoes NOT NULL e das FKs.
ALTER TABLE genero
    ADD COLUMN id_categoria INT(11) NULL;

ALTER TABLE catalogo
    ADD COLUMN id_categoria INT(11) NULL;

-- Preservar a categoria que cada livro possuia no campo legado.
UPDATE catalogo c
JOIN categoria cat
  ON LOWER(TRIM(cat.categorias)) = LOWER(TRIM(c.categoria))
SET c.id_categoria = cat.id;

-- Associar cada genero a categoria mais utilizada por seus livros. Generos sem
-- livros recebem "geral", pois nao ha dado legado que permita inferir outra.
UPDATE genero g
SET g.id_categoria = COALESCE(
    (
        SELECT cat.id
        FROM catalogo c
        JOIN categoria cat
          ON LOWER(TRIM(cat.categorias)) = LOWER(TRIM(c.categoria))
        WHERE c.id_genero = g.id
        GROUP BY cat.id
        ORDER BY COUNT(*) DESC, cat.id ASC
        LIMIT 1
    ),
    1
);

-- Garantir um subgenero selecionavel para cada genero usado por livros que
-- ainda nao tinham subgenero. Os ids no nome evitam colisoes entre generos.
INSERT INTO subgenero (subgenero, id_genero)
SELECT CONCAT('Subgenero padrao migracao ', g.id), g.id
FROM genero g
JOIN catalogo c
  ON c.id_genero = g.id
 AND c.id_subgenero IS NULL
LEFT JOIN subgenero sg
  ON sg.id_genero = g.id
GROUP BY g.id
HAVING COUNT(sg.id) = 0;

-- Preencher subgeneros ausentes com o primeiro subgenero existente do genero.
UPDATE catalogo c
JOIN (
    SELECT id_genero, MIN(id) AS id_subgenero
    FROM subgenero
    GROUP BY id_genero
) sg
  ON sg.id_genero = c.id_genero
SET c.id_subgenero = sg.id_subgenero
WHERE c.id_subgenero IS NULL;

-- A conversao para NOT NULL falha se houver valor legado nao reconhecido ou
-- livro sem subgenero/genero correspondente; interrompa e corrija esses dados.
ALTER TABLE genero
    MODIFY COLUMN id_categoria INT(11) NOT NULL;

ALTER TABLE catalogo
    MODIFY COLUMN id_categoria INT(11) NOT NULL,
    MODIFY COLUMN id_subgenero INT(11) NOT NULL,
    ADD KEY id_categoria (id_categoria);

ALTER TABLE genero
    ADD KEY id_categoria (id_categoria);

-- Remover a coluna textual/ENUM somente depois de preservar seus dados.
ALTER TABLE catalogo
    DROP COLUMN categoria;

ALTER TABLE catalogo
    ADD CONSTRAINT fk_catalogo_categoria
        FOREIGN KEY (id_categoria) REFERENCES categoria (id);

ALTER TABLE genero
    ADD CONSTRAINT fk_genero_categoria
        FOREIGN KEY (id_categoria) REFERENCES categoria (id);
