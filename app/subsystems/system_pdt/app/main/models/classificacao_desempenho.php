<?php

final class ClassificacaoDesempenho
{
    public static function classificar($media): ?string
    {
        if ($media === null || $media === '') {
            return null;
        }

        if (!is_numeric($media)) {
            return 'Nota inválida';
        }

        $media = (float) $media;
        if (!is_finite($media) || $media < 0 || $media > 10) {
            return 'Nota inválida';
        }

        if ($media >= 7.0) {
            return 'Adequado';
        }

        if ($media >= 5.9) {
            return 'Intermediário';
        }

        if ($media > 4.0) {
            return 'Crítico';
        }

        return 'Muito Crítico';
    }
}
