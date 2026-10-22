# Sistema de Coins e Recompensas

## Estrutura dos Cursos

Cada curso possui:
- **2 módulos**
- Cada módulo tem:
  - **1 vídeo** (5 coins + 25 XP)
  - **5 questões** (10 coins + 50 XP cada)
- **Total por módulo**: 55 coins + 275 XP
- **Total por curso**: 110 coins + 550 XP
- **Bônus de conclusão**: 10-20 coins + 100-200 XP

## Preços dos Cursos

1. **Curso 1**: 80 coins (iniciante)
2. **Curso 2**: 40 coins (iniciante)
3. **Curso 3**: 100 coins (intermediário)
4. **Curso 4**: 80 coins (intermediário)

## Ganhos por Curso

### Curso 1
- 2 vídeos × 5 coins = **10 coins**
- 10 questões × 10 coins = **100 coins**
- Bônus conclusão = **10 coins**
- **TOTAL: 120 coins**
- **Custo próximo curso**: 40 coins
- **Sobra**: 80 coins

### Curso 2
- 2 vídeos × 5 coins = **10 coins**
- 10 questões × 10 coins = **100 coins**
- Bônus conclusão = **10 coins**
- **TOTAL: 120 coins**
- **Custo próximo curso**: 100 coins
- **Sobra**: 20 coins

### Curso 3
- 2 vídeos × 5 coins = **10 coins**
- 10 questões × 10 coins = **100 coins**
- Bônus conclusão = **20 coins**
- **TOTAL: 130 coins**
- **Custo próximo curso**: 80 coins
- **Sobra**: 50 coins

### Curso 4
- 2 vídeos × 5 coins = **10 coins**
- 10 questões × 10 coins = **100 coins**
- Bônus conclusão = **10 coins**
- **TOTAL: 120 coins**
- (último curso)

## Regras de Compra

1. **Primeiro curso**: Pode ser comprado se o usuário tiver coins suficientes
2. **Cursos seguintes**: Só podem ser comprados se:
   - O curso anterior foi **100% concluído** (todos os vídeos assistidos + todas as atividades completas)
   - O usuário tiver coins suficientes

## Como Configurar

### Opção 1: Usar Script PHP (Recomendado)

```bash
php app/main/scripts/configurar_recompensas.php
```

Este script:
- Limpa recompensas existentes
- Adiciona recompensas para todos os vídeos (5 coins + 25 XP)
- Adiciona recompensas para todas as atividades (10 coins + 50 XP)
- Atualiza preços dos cursos

### Opção 2: Usar SQL Manual

Execute o arquivo `ajustar_sistema_coins.sql` no phpMyAdmin, ajustando os IDs conforme seus dados.

## Verificação de Conclusão

O sistema verifica se um curso está 100% concluído considerando:
- Todos os vídeos assistidos (progresso >= 90%)
- Todas as atividades completas (com resposta salva)

Quando um curso é concluído:
1. Recompensa de conclusão é concedida automaticamente
2. Usuário pode comprar o próximo curso
3. Mensagem de sucesso é exibida

## Recompensas de Conclusão

As recompensas de conclusão são concedidas automaticamente quando:
- Todos os vídeos do curso foram assistidos
- Todas as atividades do curso foram completas

Recompensas por curso:
- Curso 1: 10 coins + 100 XP
- Curso 2: 10 coins + 100 XP
- Curso 3: 20 coins + 200 XP
- Curso 4: 10 coins + 100 XP

## Notas Importantes

1. **Primeiro curso**: O usuário precisa ter coins iniciais (pode ser ganho no registro ou presente inicial)
2. **Progresso**: O progresso é calculado como: (vídeos assistidos + atividades completas) / (total de vídeos + total de atividades)
3. **Validação**: O sistema valida automaticamente se o curso anterior foi concluído antes de permitir a compra
4. **Recompensas**: São concedidas apenas uma vez por vídeo/atividade/curso

## Estrutura do Banco

### Tabela `recompensas`
- `tipo_referencia`: 'video', 'atividade', 'curso_conclusao'
- `referencia_id`: ID do vídeo, atividade ou curso
- `coins`: Quantidade de coins
- `xp`: Quantidade de XP

### Tabela `transacoes`
- Registra todas as transações de coins e XP
- Usado para verificar se recompensa já foi recebida
- Usado para histórico do usuário

## Troubleshooting

### Usuário não consegue comprar curso
1. Verifique se concluiu 100% do curso anterior
2. Verifique se tem coins suficientes
3. Verifique se o curso anterior está marcado como concluído no banco

### Recompensas não estão sendo concedidas
1. Verifique se as recompensas estão configuradas no banco
2. Execute o script `configurar_recompensas.php`
3. Verifique se os vídeos/atividades têm IDs corretos

### Progresso não está sendo calculado
1. Verifique se os vídeos estão marcados como assistidos (progresso >= 90%)
2. Verifique se as atividades têm respostas salvas
3. Verifique se os módulos estão vinculados corretamente aos cursos

