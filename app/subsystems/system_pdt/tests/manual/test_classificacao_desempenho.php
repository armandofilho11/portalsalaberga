<?php

require_once __DIR__ . '/../../app/main/models/classificacao_desempenho.php';

$casos = [
    [3.9, 'Muito Crítico'],
    [4.0, 'Muito Crítico'],
    [4.1, 'Crítico'],
    [5.8, 'Crítico'],
    [5.9, 'Intermediário'],
    [6.0, 'Intermediário'],
    [6.9, 'Intermediário'],
    [7.0, 'Adequado'],
    [7.1, 'Adequado'],
    [null, null],
    ['', null],
    ['inválida', 'Nota inválida'],
    [-0.1, 'Nota inválida'],
    [10.1, 'Nota inválida'],
];

foreach ($casos as [$nota, $esperado]) {
    $resultado = ClassificacaoDesempenho::classificar($nota);
    if ($resultado !== $esperado) {
        fwrite(STDERR, sprintf(
            "Falha para nota %s: esperado %s, recebido %s\n",
            var_export($nota, true),
            var_export($esperado, true),
            var_export($resultado, true)
        ));
        exit(1);
    }
}

fwrite(STDOUT, count($casos) . " casos de classificação aprovados.\n");
