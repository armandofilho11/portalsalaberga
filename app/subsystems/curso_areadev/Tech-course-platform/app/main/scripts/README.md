# Scripts para Inserir Vídeo do Google Drive

Este diretório contém scripts para inserir vídeos do Google Drive no banco de dados.

## Vídeo Configurado

- **Link**: https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/view?usp=drive_link
- **File ID**: `1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu`
- **Embed Link**: `https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/preview`

## Opções de Inserção

### Opção 1: Usar Script SQL (Recomendado)

1. Abra o arquivo `insert_video.sql`
2. Altere os valores conforme necessário:
   - `modulo_id`: ID do módulo (1-10)
   - `titulo`: Título do vídeo
   - `descricao`: Descrição do vídeo
   - `ordem`: Ordem do vídeo no módulo
3. Execute o SQL no phpMyAdmin ou no seu cliente MySQL

### Opção 2: Usar Script PHP

1. Abra o arquivo `insert_video.php`
2. Altere as configurações no array `$video_data`:
   ```php
   $video_data = [
       'modulo_id' => 1, // ID do módulo (1-10)
       'titulo' => 'Título do Vídeo',
       'descricao' => 'Descrição do vídeo',
       'ordem' => 1, // Ordem no módulo
       // ... outros campos
   ];
   ```
3. Execute via terminal:
   ```bash
   php app/main/scripts/insert_video.php
   ```

## Módulos Disponíveis

### Curso 1 - Git e Github
- **ID 1**: Fundamentos do Git
- **ID 2**: Trabalhando com Branches
- **ID 3**: Github e Colaboração

### Curso 2 - Padlet
- **ID 4**: Introdução ao Padlet
- **ID 5**: Gestão de Sprint com Padlet

### Curso 3 - Frameworks Frontend
- **ID 6**: Introdução ao React
- **ID 7**: Vue.js Essencial
- **ID 8**: Angular Fundamentals

### Curso 4 - FPDF
- **ID 9**: Introdução ao FPDF
- **ID 10**: Bibliotecas Avançadas

## Verificação

Após inserir o vídeo, você pode verificar se foi inserido corretamente executando:

```sql
SELECT v.*, m.titulo as modulo_titulo, c.titulo as curso_titulo 
FROM videos v
INNER JOIN modulos m ON v.modulo_id = m.id
INNER JOIN cursos c ON m.curso_id = c.id
WHERE v.drive_file_id = '1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu';
```

## Notas Importantes

1. O `drive_embed_link` deve usar `/preview` no final para funcionar corretamente no player
2. O `drive_file_id` é extraído do link do Google Drive (parte entre `/d/` e `/view`)
3. O vídeo só será exibido se o usuário tiver acesso ao curso correspondente
4. Certifique-se de que o vídeo no Google Drive está com permissão de visualização pública ou compartilhado adequadamente

