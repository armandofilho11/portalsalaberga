Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                AND s.id_aluno IS NULL
                AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                AND s.id_aluno IS NULL
                AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                AND s.id_aluno IS NULL
                AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
Nome do perfil recebido (raw): Design/Mídias
Nome do perfil após remoção de acentos: design/midias
Nome do perfil após normalização: design
Query SQL: SELECT 
                    a.id,
                    a.nome,
                    a.medias,
                    a.projetos,
                    a.ocorrencia,
                    a.entregas_individuais,
                    a.entregas_grupo,
                    a.perfil_opc1,
                    a.perfil_opc2,
                    a.custeio,
                    (
                        a.medias + 
                        (CASE WHEN a.projetos != '' THEN 5 ELSE 0 END) -
                        (a.ocorrencia * 0.5) +
                        (a.entregas_individuais * 5) +
                        (a.entregas_grupo * 5)
                    ) AS score,
                    CASE 
                        WHEN LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) THEN 1
                        WHEN LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil) THEN 2
                        ELSE 3
                    END AS priority_group
                FROM aluno a
                LEFT JOIN selecionado s ON a.id = s.id_aluno
                LEFT JOIN selecao se ON a.id = se.id_aluno
                WHERE (
                    LOWER(a.perfil_opc1) = LOWER(:normalized_nome_perfil) 
                    OR 
                    LOWER(a.perfil_opc2) = LOWER(:normalized_nome_perfil)
                )
                -- AND s.id_aluno IS NULL
                -- AND se.id_aluno IS NULL
                ORDER BY 
                    priority_group ASC,
                    score DESC,
                    a.medias DESC,
                    COALESCE(a.ocorrencia, 0) ASC
Parâmetro normalized_nome_perfil: design
Número de alunos encontrados: 0
---
