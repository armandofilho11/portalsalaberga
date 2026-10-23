SET @sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE registro_entrada ADD COLUMN justificativa_atraso TEXT DEFAULT NULL',
        'SELECT ''A coluna justificativa_atraso já existe.'' AS resultado'
    )
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'registro_entrada'
      AND COLUMN_NAME = 'justificativa_atraso'
);

PREPARE migration FROM @sql;
EXECUTE migration;
DEALLOCATE PREPARE migration;
