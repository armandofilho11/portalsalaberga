-- ============================================================================
-- MIGRATION: ARQUITETURA DE PROJETOS, ADMINISTRADOR MASTER E VÍNCULOS
-- ============================================================================
-- Este arquivo migra o sistema de um modelo baseado em ENUM para um modelo
-- real de Projetos com relacionamento N:N de usuários.
--
-- IMPORTANTE: Executar em ordem. Backup do banco antes de executar!
-- Este arquivo deve ser usado somente sobre o schema legado anterior à arquitetura
-- de projetos. Para recriar o banco do zero, importe sistema_de_demandas.sql;
-- não execute esta migration depois dele.
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

ALTER TABLE `demandas`
ADD COLUMN IF NOT EXISTS `quantidade_alunos` INT NOT NULL DEFAULT 1 AFTER `orientador`;

-- ============================================================================
-- ETAPA 1: NORMALIZAR OS TIPOS GLOBAIS DE USUÁRIO
-- ============================================================================
-- Ampliar temporariamente o ENUM para permitir a conversão dos papéis legados.
ALTER TABLE `usuarios`
MODIFY COLUMN `tipo` ENUM('master','administrador','administrador_master','suporte','desenvolvedor','usuario') NOT NULL;

ALTER TABLE `usuarios`
ADD COLUMN IF NOT EXISTS `pode_criar_projeto` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tipo`;

ALTER TABLE `usuarios`
ADD COLUMN IF NOT EXISTS `pode_vincular_projeto` TINYINT(1) NOT NULL DEFAULT 0 AFTER `pode_criar_projeto`;

-- ============================================================================
-- ETAPA 2: CRIAR TABELA DE PROJETOS
-- ============================================================================
CREATE TABLE `projetos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` TEXT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `criado_por` INT NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_projetos_nome` (`nome`),
  KEY `idx_ativo` (`ativo`),
  KEY `idx_data_criacao` (`data_criacao`),
  
  CONSTRAINT `fk_projetos_criado_por`
    FOREIGN KEY (`criado_por`)
    REFERENCES `usuarios`(`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `projetos` (`nome`, `descricao`, `ativo`, `criado_por`) VALUES
('Desenvolvimento', 'Projeto de desenvolvimento.', 1, 1),
('Suporte', 'Projeto de suporte.', 1, 1);

-- ============================================================================
-- ETAPA 3: CRIAR TABELA DE VÍNCULO USUÁRIO ↔ PROJETO
-- ============================================================================
CREATE TABLE `usuario_projeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `id_projeto` INT NOT NULL,
  `data_vinculo` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vinculado_por` INT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_projeto` (`id_usuario`, `id_projeto`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_projeto` (`id_projeto`),
  KEY `idx_ativo` (`ativo`),
  
  CONSTRAINT `fk_usuario_projeto_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT `fk_usuario_projeto_projeto`
    FOREIGN KEY (`id_projeto`)
    REFERENCES `projetos`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT `fk_usuario_projeto_vinculado_por`
    FOREIGN KEY (`vinculado_por`)
    REFERENCES `usuarios`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- ETAPA 4: CRIAR TABELA DE SOLICITAÇÕES DE PARTICIPAÇÃO EM PROJETOS
-- ============================================================================
CREATE TABLE `solicitacoes_projeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `id_projeto` INT NOT NULL,
  `status` ENUM(
    'pendente',
    'aprovada',
    'recusada'
  ) NOT NULL DEFAULT 'pendente',

  `mensagem` TEXT NULL,

  `data_solicitacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `id_administrador_decisao` INT NULL,

  `data_decisao` DATETIME NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_solicitacao_usuario_projeto_pendente` (
    `id_usuario`,
    `id_projeto`,
    `status`
  ),
  KEY `idx_status` (`status`),
  KEY `idx_data_solicitacao` (`data_solicitacao`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_projeto` (`id_projeto`),
  
  CONSTRAINT `fk_solicitacao_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT `fk_solicitacao_projeto`
    FOREIGN KEY (`id_projeto`)
    REFERENCES `projetos`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT `fk_solicitacao_administrador`
    FOREIGN KEY (`id_administrador_decisao`)
    REFERENCES `usuarios`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- ETAPA 5: ADICIONAR COLUNA id_projeto À TABELA demandas
-- ============================================================================
-- Adicionar coluna temporária para armazenar o novo id
ALTER TABLE `demandas` 
ADD COLUMN `id_projeto` INT NULL AFTER `projeto`;

-- ============================================================================
-- ETAPA 6: CRIAR PROJETOS CORRESPONDENTES AOS VALORES ANTIGOS
-- ============================================================================
-- Criar projetos para "Desenvolvimento" e "Suporte"
-- O usuário 1 (Francisco Otávio) será o criador

-- ============================================================================
-- ETAPA 7: MAPEAR DEMANDAS ANTIGAS PARA OS NOVOS PROJETOS
-- ============================================================================
-- Atualizar demandas.id_projeto baseado no valor antigo de demandas.projeto
UPDATE `demandas` 
SET `id_projeto` = (
  SELECT `p`.`id` FROM `projetos` `p` 
  WHERE `p`.`nome` = 'Desenvolvimento' 
  LIMIT 1
)
WHERE `projeto` = 'desenvolvedor';

UPDATE `demandas` 
SET `id_projeto` = (
  SELECT `p`.`id` FROM `projetos` `p` 
  WHERE `p`.`nome` = 'Suporte' 
  LIMIT 1
)
WHERE `projeto` = 'suporte';

-- ============================================================================
-- ETAPA 8: VINCULAR USUÁRIOS AOS PROJETOS CORRESPONDENTES
-- ============================================================================
-- Vincular usuários cujo tipo era 'desenvolvedor' ao projeto Desenvolvimento
INSERT IGNORE INTO `usuario_projeto` (`id_usuario`, `id_projeto`, `vinculado_por`, `ativo`) 
SELECT `u`.`id`, `p`.`id`, 1, 1
FROM `usuarios` `u`
CROSS JOIN `projetos` `p`
WHERE `u`.`tipo` = 'desenvolvedor' 
AND `p`.`nome` = 'Desenvolvimento';

-- Vincular usuários cujo tipo era 'suporte' ao projeto Suporte
INSERT IGNORE INTO `usuario_projeto` (`id_usuario`, `id_projeto`, `vinculado_por`, `ativo`) 
SELECT `u`.`id`, `p`.`id`, 1, 1
FROM `usuarios` `u`
CROSS JOIN `projetos` `p`
WHERE `u`.`tipo` = 'suporte' 
AND `p`.`nome` = 'Suporte';

-- Vincular usuários 'administrador' a TODOS os projetos ativos
-- (para compatibilidade, até que a lógica no PHP seja ajustada)
INSERT IGNORE INTO `usuario_projeto` (`id_usuario`, `id_projeto`, `vinculado_por`, `ativo`) 
SELECT `u`.`id`, `p`.`id`, 1, 1
FROM `usuarios` `u`
CROSS JOIN `projetos` `p`
WHERE `u`.`tipo` = 'administrador' 
AND `p`.`ativo` = 1;

-- ============================================================================
-- ETAPA 9: DEFINIR PRIMEIRO USUÁRIO COMO ADMINISTRADOR MASTER
-- ============================================================================
-- O usuário com ID 1 (Francisco Otávio) passa a ser master
UPDATE `usuarios` SET `tipo` = 'master' WHERE `tipo` = 'administrador_master';
UPDATE `usuarios` SET `tipo` = 'usuario' WHERE `tipo` IN ('suporte', 'desenvolvedor');
UPDATE `usuarios` 
SET `tipo` = 'master'
WHERE `id` = 1 
LIMIT 1;
ALTER TABLE `usuarios`
MODIFY COLUMN `tipo` ENUM('master','administrador','usuario') NOT NULL;

-- ============================================================================
-- ETAPA 10: ADICIONAR FOREIGN KEY E ÍNDICES À TABELA demandas
-- ============================================================================
-- Agora que os dados foram migrados, adicionar a coluna id_projeto como NOT NULL
ALTER TABLE `demandas` 
MODIFY COLUMN `id_projeto` INT NOT NULL;

-- Adicionar Foreign Key
ALTER TABLE `demandas` 
ADD CONSTRAINT `fk_demandas_projeto`
  FOREIGN KEY (`id_projeto`)
  REFERENCES `projetos`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Adicionar índice
ALTER TABLE `demandas` 
ADD INDEX `idx_demandas_projeto` (`id_projeto`),
ADD INDEX `idx_demandas_projeto_status` (`id_projeto`, `status`);

-- ============================================================================
-- ETAPA 11: REMOVER COLUNA ANTIGA (OPCIONAL - somente após validação)
-- ============================================================================
-- Comentário: Manter a coluna 'projeto' como backup por 30 dias
-- Descomente quando tiver certeza de que a migração foi bem-sucedida

-- ALTER TABLE `demandas` 
-- DROP COLUMN `projeto`;

-- ============================================================================
-- ETAPA 12: CATEGORIAS CONFIGURÁVEIS POR PROJETO
-- ============================================================================
-- Categorias deixam de ser um ENUM global e passam a pertencer a um projeto.
CREATE TABLE IF NOT EXISTS `categorias_projeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_projeto` INT NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `criado_por` INT NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categoria_projeto_nome` (`id_projeto`, `nome`),
  KEY `idx_categorias_projeto` (`id_projeto`),
  CONSTRAINT `fk_categorias_projeto_projeto`
    FOREIGN KEY (`id_projeto`) REFERENCES `projetos`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categorias_projeto_criado_por`
    FOREIGN KEY (`criado_por`) REFERENCES `usuarios`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `demandas`
MODIFY COLUMN `categoria` VARCHAR(100) NOT NULL;

-- Preserva categorias já usadas pelas demandas existentes.
INSERT IGNORE INTO `categorias_projeto` (`id_projeto`, `nome`, `criado_por`)
SELECT DISTINCT d.`id_projeto`, d.`categoria`, COALESCE(p.`criado_por`, 1)
FROM `demandas` d
INNER JOIN `projetos` p ON p.`id` = d.`id_projeto`
WHERE d.`categoria` IS NOT NULL AND d.`categoria` <> '';

-- COMMIT DA TRANSAÇÃO
COMMIT;

-- ============================================================================
-- RESUMO DA MIGRAÇÃO
-- ============================================================================
-- ✓ ENUM final normalizado para master, administrador e usuario
-- ✓ Criada tabela 'projetos' com campos: id, nome, descricao, ativo, etc
-- ✓ Criada tabela 'usuario_projeto' com relacionamento N:N
-- ✓ Criada tabela 'solicitacoes_projeto' para fluxo de participação
-- ✓ Adicionada coluna 'id_projeto' à tabela 'demandas'
-- ✓ Criados projetos 'Desenvolvimento' e 'Suporte'
-- ✓ Mapeadas demandas antigas para novos projetos
-- ✓ Vinculados usuários aos projetos correspondentes
-- ✓ Usuário 1 transformado em 'master'
-- ✓ Adicionadas constraints e índices
--
-- PRÓXIMOS PASSOS:
-- 1. Criar models PHP (projeto_model.php, usuario_projeto_model.php, solicitacao_projeto_model.php)
-- 2. Criar controllers PHP (projeto_controller.php, solicitacao_projeto_controller.php)
-- 3. Implementar autorização centralizada (auth_helper.php)
-- 4. Adaptar demanda_model.php e demanda_controller.php
-- 5. Implementar views para seção Projetos
-- 6. Adaptar login e primeiro acesso
-- 7. Testar todos os cenários de permissão
-- ============================================================================
