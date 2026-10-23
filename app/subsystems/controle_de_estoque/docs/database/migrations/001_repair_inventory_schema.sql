-- Run once against the stock database after taking a full backup.
-- This migration preserves the incompatible legacy movement table by renaming it.

SET FOREIGN_KEY_CHECKS = 0;
RENAME TABLE `movimentacao` TO `movimentacao_legada`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `categorias` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome_categoria` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_categorias_nome_categoria` (`nome_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ambientes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome_ambiente` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ambientes_nome_ambiente` (`nome_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categorias` (`id`, `nome_categoria`)
VALUES (1, 'Sem categoria');

INSERT INTO `categorias` (`nome_categoria`)
SELECT DISTINCT `natureza`
FROM `produtos`
WHERE `natureza` IS NOT NULL AND `natureza` <> ''
ON DUPLICATE KEY UPDATE `nome_categoria` = VALUES(`nome_categoria`);

INSERT INTO `ambientes` (`id`, `nome_ambiente`)
VALUES (2, 'Ambiente padrao');

ALTER TABLE `produtos`
    ADD COLUMN `id_categoria` INT NULL,
    ADD COLUMN `vencimento` DATE NULL,
    ADD COLUMN `id_ambiente` INT NULL,
    ADD COLUMN `data` DATETIME NULL;

UPDATE `produtos` AS p
LEFT JOIN `categorias` AS c
    ON c.`nome_categoria` = COALESCE(NULLIF(p.`natureza`, ''), 'Sem categoria')
SET p.`id_categoria` = COALESCE(c.`id`, 1),
    p.`id_ambiente` = 2,
    p.`data` = CURRENT_TIMESTAMP
WHERE p.`id_categoria` IS NULL
   OR p.`id_ambiente` IS NULL
   OR p.`data` IS NULL;

ALTER TABLE `produtos`
    MODIFY COLUMN `id_categoria` INT NOT NULL DEFAULT 1,
    MODIFY COLUMN `id_ambiente` INT NOT NULL DEFAULT 2,
    MODIFY COLUMN `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE `movimentacao` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_produtos` INT NOT NULL,
    `liberador` VARCHAR(255) NOT NULL,
    `solicitador` VARCHAR(255) NULL,
    `tipo_movimentacao` VARCHAR(50) NOT NULL,
    `datareg` DATETIME NOT NULL,
    `quantidade_retirada` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_movimentacao_produto` (`id_produtos`),
    KEY `idx_movimentacao_datareg` (`datareg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `perdas_produtos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_produto` INT NOT NULL,
    `quantidade` INT NOT NULL,
    `tipo` VARCHAR(150) NOT NULL,
    `data` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_perdas_produto` (`id_produto`),
    KEY `idx_perdas_data` (`data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
