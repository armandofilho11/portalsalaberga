





<?php


    require_once('../config/connect.php');
    require_once('../assets/fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetMargins(15, 15, 15);
// Cabeçalho com gradiente (reduzido)
$pdf->SetFillColor(93, 164, 67);
$pdf->Rect(0, 0, $pdf->GetPageWidth(), 40, 'F'); // Reduzido de 50 para 40

// Título Principal (mais compacto)
$pdf->SetY(10); // Posição Y inicial ajustada
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 255, 255);
// Título Principal com fonte mais moderna
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, utf8_decode('1ª Coordenadoria Regional de Desenvolvimento da Educação'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, utf8_decode('EEEP SALABERGA TORQUATO GOMES DE MATOS'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode('INEP 23081996 - Maranguape - CE'), 0, 1, 'C');

// Título da Seleção
$pdf->SetY(50); // Posição Y ajustada
$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 12, utf8_decode('SELEÇÃO DE ALUNOS - 2025'), 0, 1, 'C');
$pdf->Ln(1);

// Remover a cor de fundo, configurando a cor de preenchimento para branco (ou transparente).
$pdf->SetFillColor(255, 255, 255);  // Cor de fundo branca, ou use 255, 255, 255 para transparente.
$pdf->SetTextColor(0, 0, 0);  // Cor do texto preta.
$pdf->SetFont('Arial', 'B', 14);
$boxWidth = 140;
$xPos = ($pdf->GetPageWidth() - $boxWidth) / 2;
$pdf->SetX($xPos);
// Agora, o texto é apenas exibido sem fundo.
$pdf->Cell($boxWidth, 10, utf8_decode('RESULTADO PRELIMINAR'), 0, 1, 'C');

// Portaria
$pdf->SetTextColor(100, 100, 100);
$pdf->SetFont('Arial', '', 11);
$pdf->Ln(3);
$pdf->Cell(0, 6, utf8_decode('Conforme Portaria de Matrícula Nº 266/2024 - GAB'), 0, 1, 'C');

// Adicionar elementos decorativos verdes
$pdf->SetDrawColor($lightGreen[0], $lightGreen[1], $lightGreen[2]);
$pdf->Line(20, 85, $pdf->GetPageWidth()-20, 85); // Linha decorativa abaixo do título

// Cronograma
$pdf->SetY(120); // Posicionando o cronograma mais abaixo
$pdf->SetFillColor(93, 164, 67);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 11);

// Configurações da tabela do cronograma
$tableWidth = 180;
$col1Width = 120;
$col2Width = 60;
$xPosCrono = ($pdf->GetPageWidth() - $tableWidth) / 2;

// Título do Cronograma
$pdf->SetX($xPosCrono);
$pdf->Cell($tableWidth, 12, utf8_decode('CRONOGRAMA DE INSCRIÇÃO/MATRÍCULA PARA 1ª SÉRIE 2025'), 1, 1, 'C', true);

// Cabeçalho da tabela
$pdf->SetX($xPosCrono);
$pdf->Cell($col1Width, 9, utf8_decode('ATIVIDADES'), 1, 0, 'C', true);
$pdf->Cell($col2Width, 9, utf8_decode('PERÍODO'), 1, 1, 'C', true);

// Array do Cronograma
$cronograma = [
    'LANÇAMENTO DO EDITAL' => '29/11/2024',
    'PERÍODO DE INSCRIÇÃO ALUNO' => '02/12/2024 a 11/12/2024',
    'ANÁLISE DA DOCUMENTAÇÃO' => '12 a 17/12/2024',
    'RESULTADO PRELIMINAR' => '18/12/2024 (16h)',
    'PRAZO RECURSAL (Presencial na EEEP) ' => '19, 20, 26, 27/12/2024 e 02/01/2025',
    'RESULTADO FINAL (EEEP e site da CREDE 01)' => '03/01/2025',
    'MATRÍCULA e SEMINÁRIO (Na EEEP)' => '06 a 09/01/2025'
];

// Tabela do Cronograma
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(245, 245, 245);
$alt = true;

foreach ($cronograma as $atividade => $periodo) {
    $pdf->SetX($xPosCrono);
    $pdf->Cell($col1Width, 8, utf8_decode($atividade), 1, 0, 'L', $alt);
    $pdf->Cell($col2Width, 8, utf8_decode($periodo), 1, 1, 'C', $alt);
    $alt = !$alt;
}

// Linha de horários de atendimento
$pdf->SetX($xPosCrono);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($tableWidth, 8, utf8_decode('HORÁRIOS DE ATENDIMENTO NA EEEP: segunda a sexta de 8h às 12h, à tarde de 13h às 16h'), 1, 1, 'C', true);




//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//SÓ EM JANEIRO, COM O RESULTADO FINAL
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//

/*



// Título da Seleção
$pdf->AddPage();
$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 12, utf8_decode('SELEÇÃO DE ALUNOS - 2025'), 0, 1, 'C');
// Box para Resultado Final (ajustado)
$pdf->SetFillColor(255, 255, 255);  // Cor de fundo removida (branca ou transparente).
$pdf->SetTextColor(0, 0, 0);  // Cor do texto preta.
$pdf->SetFont('Arial', 'B', 14);

$xPos = ($pdf->GetPageWidth() - $boxWidth) / 2;
$pdf->SetX($xPos);
// Texto centralizado e sem fundo
$pdf->Cell($boxWidth, 10, utf8_decode('RESULTADO FINAL'), 0, 1, 'C');


// Portaria
$pdf->SetTextColor(100, 100, 100);
$pdf->SetFont('Arial', '', 11);
$pdf->Ln(3);
$pdf->Cell(0, 6, utf8_decode('Conforme Portaria de Matrícula Nº 266/2024 - GAB'), 0, 1, 'C');


// Cronograma de Matrícula
$pdf->Ln(8);
$pdf->SetFillColor(93, 164, 67);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 11);

// Título do Cronograma
$tableWidth = 180;
$col1Width = 120;
$col2Width = 60;
$xPosCrono = ($pdf->GetPageWidth() - $tableWidth) / 2;

$pdf->SetX($xPosCrono);
$pdf->Cell($tableWidth, 8, utf8_decode('CRONOGRAMA DE MATRÍCULA PARA CANDIDATOS CLASSIFICADOS'), 1, 1, 'C', true);

// Cabeçalho da tabela
$pdf->SetX($xPosCrono);
$pdf->Cell($col1Width, 8, utf8_decode('DATAS'), 1, 0, 'C', true);
$pdf->Cell($col2Width, 8, utf8_decode('HORÁRIO'), 1, 1, 'C', true);

// Dados do cronograma
$matriculas = [
    'Curso Tec. Enfermagem' => '06/01/2025',
    'Curso Tec. Informática' => '07/01/2025',
    'Curso Tec. Administração' => '08/01/2025',
    'Curso Tec. Edificações' => '09/01/2025'
];

// Tabela de Matrículas
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(245, 245, 245);
$alt = true;

foreach ($matriculas as $curso => $data) {
    $pdf->SetX($xPosCrono);
    $pdf->Cell($col1Width, 7, utf8_decode($data . ': ' . $curso), 1, 0, 'L', $alt);
    $pdf->Cell($col2Width, 7, utf8_decode('7h às 12h'), 1, 1, 'C', $alt);
    $alt = !$alt;
}

// Material Necessário
$pdf->Ln(10);
$pdf->SetFillColor(93, 164, 67);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode('MATERIAL NECESSÁRIO'), 1, 1, 'C', true);

// Array de Materiais
$material = [
    'A - Pasta Escolar (SEM PREENCHER)',
    'B - Cópia da Certidão de Nascimento do(a) Aluno(a)',
    'C - Declaração de CONCLUSÃO 9° ANO ou Certificado e Histórico Escolar de Conclusão do Ensino Fundamental (esses dois últimos, caso a escola já possa emitir)',
    'D - 2 (duas) Fotos 3x4 do(a) Aluno(a)',
    'E - Cópia do comprovante de endereço',
    'F - Cópia do Cartão de Vacinação, conforme Lei Estadual Nº 16.929, de 09/07/2019, para Estudantes com até 18 (dezoito) anos de idade do(a) Aluno(a)',
    'G - Cópia do Cartão de Vacinação contra Covid-19 do(a) Aluno(a) (ATUALIZADO)',
    'H - Cópia do Registro Geral (RG) ou da Carteira de Identidade Nacional (CIN) do(a) aluno(a)',
    'I - Cópia do comprovante do Cadastro de Pessoa Física (CPF) do(a) Aluno(a)',
    'J - Cópia do comprovante de Identificação Social (NIS DO(A) ALUNO(A)) para as famílias cadastradas no Cadastro Único para Programas Sociais do Governo Federal',
    'K - Laudo, relatório ou atestado que comprovem alergias alimentares, doenças, transtornos e/ou deficiência, caso possua',
    'L - Cópia do RG e CPF do pai, da mãe ou do(a) responsável legal'
];
$pdf->SetTextColor(50, 50, 50);
$pdf->Ln(2);


*/
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//


foreach ($material as $item) {
    $pdf->SetX($xPosCrono);
    
    // Separa a letra do texto
    $partes = explode(' - ', $item, 2);
    
    // Letra em fonte maior e negrito
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write(6, utf8_decode($partes[0] . ' - '));
    
    // Texto em fonte menor
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell($tableWidth - 15, 6, utf8_decode($partes[1]), 0, 'L');
    
    $pdf->Ln(1); // Espaçamento entre itens
}
$pdf->Ln(10); 





// Adiciona nova página para o conteúdo restante
$pdf->AddPage();

// Resetando X e Y para o topo esquerdo
$pdf->SetXY(10, 10);

// Cabeçalho com larguras ajustadas
$pdf->Image('../assets/images/logo.png', 12, 8, 15, 0, 'PNG');
$pdf->SetFont('Arial', 'B', 25);
$pdf->Cell(150, 6, utf8_decode('RESULTADO PRELIMINAR'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 10, ('PCD = PESSOA COM DEFICIENCIA | COTISTA = INCLUSO NA COTA DO BAIRRO | AC = AMPLA CONCORRENCIA'), 0, 1, 'C');
$pdf->Ln(10);

    //$pdf->Cell(64, 10, ('PCD = PESSOA COM DEFICIENCIA'), 1, 0, 'C');
    //$pdf->Cell(63, 10, ('COTISTA = INCLUSO NA COTA DO BAIRRO'), 1, 0, 'C');
    //$pdf->Cell(64, 10, ('AC = AMPLA CONCORRENCIA'), 1, 1, 'C');
    

// -----------------------------------------------------------------------------------------------------------------------------------//












//RELATÓRIO GERAL DO CURSO

$parametro_curso = 1; // ENFERMAGEM

// Iniciar a impressão do cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco (cabeçalho)
$pdf->Cell(181, -8, utf8_decode("Ensino Médio Integrado em Enfermagem"), 1, 1, 'C', true);
$pdf->ln(8);

// Consultas separadas para contar os totais por categoria

// Total de Inscritos
$stmtTotalInscritos = $conexao->prepare("
SELECT COUNT(*) AS total_inscritos
FROM candidato
WHERE candidato.id_curso1_fk = :curso1
");
$stmtTotalInscritos->bindValue(':curso1', $parametro_curso);
$stmtTotalInscritos->execute();
$totalInscritos = $stmtTotalInscritos->fetch(PDO::FETCH_ASSOC);


// Total Pública
$stmtTotalPublica = $conexao->prepare("
SELECT COUNT(*) AS total_publica
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1
");
$stmtTotalPublica->bindValue(':curso1', $parametro_curso);
$stmtTotalPublica->execute();
$totalPublica = $stmtTotalPublica->fetch(PDO::FETCH_ASSOC);


// Total Privada
$stmtTotalPrivada = $conexao->prepare("
SELECT COUNT(*) AS total_privada
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0
");
$stmtTotalPrivada->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivada->execute();
$totalPrivada = $stmtTotalPrivada->fetch(PDO::FETCH_ASSOC);


// Total Cota PCD
$stmtTotalPCD = $conexao->prepare("
SELECT COUNT(*) AS total_pcd
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.pcd = 1
");
$stmtTotalPCD->bindValue(':curso1', $parametro_curso);
$stmtTotalPCD->execute();
$totalPCD = $stmtTotalPCD->fetch(PDO::FETCH_ASSOC);


/*
// Total Pública Ampla Concorrência
$stmtTotalPublicaAC = $conexao->prepare("
SELECT COUNT(*) AS total_publica_ac
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1 AND candidato.bairro = 1
");
$stmtTotalPublicaAC->bindValue(':curso1', $parametro_curso);
$stmtTotalPublicaAC->execute();
$totalPublicaAC = $stmtTotalPublicaAC->fetch(PDO::FETCH_ASSOC);

// Total Privada Ampla Concorrência
$stmtTotalPrivadaAC = $conexao->prepare("
SELECT COUNT(*) AS total_privada_ac
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0 AND candidato.bairro = 1
");
$stmtTotalPrivadaAC->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivadaAC->execute();
$totalPrivadaAC = $stmtTotalPrivadaAC->fetch(PDO::FETCH_ASSOC);


// Total Pública Cotas
$stmtTotalPublicaCota = $conexao->prepare("
SELECT COUNT(*) AS total_publica_cota
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1 AND candidato.bairro = 1 AND candidato.pcd = 0
");
$stmtTotalPublicaCota->bindValue(':curso1', $parametro_curso);
$stmtTotalPublicaCota->execute();
$totalPublicaCota = $stmtTotalPublicaCota->fetch(PDO::FETCH_ASSOC);

// Total Privada Cotas
$stmtTotalPrivadaCota = $conexao->prepare("
SELECT COUNT(*) AS total_privada_cota
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0 AND candidato.bairro = 1 AND candidato.pcd = 0
");
$stmtTotalPrivadaCota->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivadaCota->execute();
$totalPrivadaCota = $stmtTotalPrivadaCota->fetch(PDO::FETCH_ASSOC);

// Total Pública Cotas AC
$stmtTotalPublicaAC_Cota = $conexao->prepare("
SELECT COUNT(*) AS total_publica_ac_cota
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1 AND candidato.bairro = 0 AND candidato.pcd = 0
");
$stmtTotalPublicaAC_Cota->bindValue(':curso1', $parametro_curso);
$stmtTotalPublicaAC_Cota->execute();
$totalPublicaAC_Cota = $stmtTotalPublicaAC_Cota->fetch(PDO::FETCH_ASSOC);

// Total Privada Cotas AC
$stmtTotalPrivadaAC_Cota = $conexao->prepare("
SELECT COUNT(*) AS total_privada_ac_cota
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0 AND candidato.bairro = 0 AND candidato.pcd = 0
");
$stmtTotalPrivadaAC_Cota->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivadaAC_Cota->execute();
$totalPrivadaAC_Cota = $stmtTotalPrivadaAC_Cota->fetch(PDO::FETCH_ASSOC);
*/


// Exibindo os totais no PDF
$pdf->SetFont('Arial', '', 10);

// Alterar a cor para preto antes de exibir o número
$pdf->SetTextColor(0, 0, 0); // Cor preta

// Exibir Total de Inscritos
$pdf->Cell(171, 7, utf8_decode('Total de Inscritos: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalInscritos['total_inscritos'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Pública
$pdf->Cell(171, 7, utf8_decode('Total Pública: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPublica['total_publica'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Privada
$pdf->Cell(171, 7, utf8_decode('Total Privada: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPrivada['total_privada'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Cota PCD
$pdf->Cell(171, 7, utf8_decode('Total Cota PCD: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPCD['total_pcd'], 0, ',', '.'), 1, 1, 'R');


$pdf->ln(12);




    

//--ENFERMAGEM
//--COTA - PCD - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Cota - PCD", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }

        // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}







//--ENFERMAGEM
//--COTA - PCD - LISTA DE ESPERA ------------------------------------------------------------------------------------------//
$parametro_curso=1; //ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 300 OFFSET 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

        // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }
        
        
// Verificar se há resultados
if (empty($result)) {
    
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 3; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }
        
                // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }


        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);









































    

//--ENFERMAGEM
//--PUBLICA - AC - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 24
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? ('PUB') : ('PRI');

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}





    
//--ENFERMAGEM
//--PUBLICA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 24;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 25; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--ENFERMAGEM
//--PUBLICA - COTA - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 10
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--ENFERMAGEM
//--PUBLICA - COTA - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 10;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 11; // A classificação começa de 11 (pois os primeiros 10 já foram classificados)
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);


































































    

//--ENFERMAGEM
//--PRIVADA - AC - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 6
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}





    
//--ENFERMAGEM
//--PRIVADA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 6;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 7; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--ENFERMAGEM
//--PRIVADA - COTA - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 3
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--ENFERMAGEM
//--PUBLICA - COTA BAIRRO - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 1; // ENFERMAGEM

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 3
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 4; // A classificação começa de 7
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}


$pdf->ln(12);
$pdf->AddPage();
$pdf->Ln(13);





























///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////-- INFORMÁTICA --//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//RELATÓRIO GERAL DO CURSO

$parametro_curso = 2; // INFORMÁTICA

// Iniciar a impressão do cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco (cabeçalho)
$pdf->Cell(181, -8, utf8_decode("Ensino Médio Integrado em  Informática"), 1, 1, 'C', true);
$pdf->ln(8);

// Consultas separadas para contar os totais por categoria

// Total de Inscritos
$stmtTotalInscritos = $conexao->prepare("
SELECT COUNT(*) AS total_inscritos
FROM candidato
WHERE candidato.id_curso1_fk = :curso1
");
$stmtTotalInscritos->bindValue(':curso1', $parametro_curso);
$stmtTotalInscritos->execute();
$totalInscritos = $stmtTotalInscritos->fetch(PDO::FETCH_ASSOC);


// Total Pública
$stmtTotalPublica = $conexao->prepare("
SELECT COUNT(*) AS total_publica
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1
");
$stmtTotalPublica->bindValue(':curso1', $parametro_curso);
$stmtTotalPublica->execute();
$totalPublica = $stmtTotalPublica->fetch(PDO::FETCH_ASSOC);


// Total Privada
$stmtTotalPrivada = $conexao->prepare("
SELECT COUNT(*) AS total_privada
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0
");
$stmtTotalPrivada->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivada->execute();
$totalPrivada = $stmtTotalPrivada->fetch(PDO::FETCH_ASSOC);


// Total Cota PCD
$stmtTotalPCD = $conexao->prepare("
SELECT COUNT(*) AS total_pcd
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.pcd = 1
");
$stmtTotalPCD->bindValue(':curso1', $parametro_curso);
$stmtTotalPCD->execute();
$totalPCD = $stmtTotalPCD->fetch(PDO::FETCH_ASSOC);



// Exibindo os totais no PDF
$pdf->SetFont('Arial', '', 10);

// Alterar a cor para preto antes de exibir o número
$pdf->SetTextColor(0, 0, 0); // Cor preta

// Exibir Total de Inscritos
$pdf->Cell(171, 7, utf8_decode('Total de Inscritos: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalInscritos['total_inscritos'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Pública
$pdf->Cell(171, 7, utf8_decode('Total Pública: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPublica['total_publica'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Privada
$pdf->Cell(171, 7, utf8_decode('Total Privada: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPrivada['total_privada'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Cota PCD
$pdf->Cell(171, 7, utf8_decode('Total Cota PCD: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPCD['total_pcd'], 0, ',', '.'), 1, 1, 'R');

$pdf->ln(12);




//--INFORMÁTICA
//--COTA - PCD - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Cota - PCD", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }

        // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}







//--INFORMÁTICA
//--COTA - PCD - LISTA DE ESPERA ------------------------------------------------------------------------------------------//
$parametro_curso = 2; //ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 300 OFFSET 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 3; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }
        
                // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }


        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);











































    

//--INFORMÁTICA
//--PUBLICA - AC - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 24
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? ('PUB') : ('PRI');

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}




    
//--INFORMÁTICA
//--PUBLICA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 24;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 25; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--INFORMÁTICA
//--PUBLICA - COTA - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 10
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--INFORMÁTICA
//--PUBLICA - COTA - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 10;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 11; // A classificação começa de 11 (pois os primeiros 10 já foram classificados)
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);


































































    

//--INFORMÁTICA
//--PRIVADA - AC - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 6
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}





    
//--INFORMÁTICA
//--PRIVADA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 6;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 7; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--INFORMÁTICA
//--PRIVADA - COTA - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 3
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }



        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--INFORMÁTICA
//--PUBLICA - COTA BAIRRO - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 2; // INFORMÁTICA

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 3
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 4; // A classificação começa de 7
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);
$pdf->AddPage();
$pdf->Ln(13);




















///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////-- ADMINISTRAÇÃO --////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//RELATÓRIO GERAL DO CURSO

$parametro_curso = 3; // ADMINISTRAÇÃO

// Iniciar a impressão do cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco (cabeçalho)
$pdf->Cell(181, -8, utf8_decode("Ensino Médio Integrado em  Administração"), 1, 1, 'C', true);
$pdf->ln(8);

// Consultas separadas para contar os totais por categoria

// Total de Inscritos
$stmtTotalInscritos = $conexao->prepare("
SELECT COUNT(*) AS total_inscritos
FROM candidato
WHERE candidato.id_curso1_fk = :curso1
");
$stmtTotalInscritos->bindValue(':curso1', $parametro_curso);
$stmtTotalInscritos->execute();
$totalInscritos = $stmtTotalInscritos->fetch(PDO::FETCH_ASSOC);


// Total Pública
$stmtTotalPublica = $conexao->prepare("
SELECT COUNT(*) AS total_publica
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1
");
$stmtTotalPublica->bindValue(':curso1', $parametro_curso);
$stmtTotalPublica->execute();
$totalPublica = $stmtTotalPublica->fetch(PDO::FETCH_ASSOC);


// Total Privada
$stmtTotalPrivada = $conexao->prepare("
SELECT COUNT(*) AS total_privada
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0
");
$stmtTotalPrivada->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivada->execute();
$totalPrivada = $stmtTotalPrivada->fetch(PDO::FETCH_ASSOC);


// Total Cota PCD
$stmtTotalPCD = $conexao->prepare("
SELECT COUNT(*) AS total_pcd
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.pcd = 1
");
$stmtTotalPCD->bindValue(':curso1', $parametro_curso);
$stmtTotalPCD->execute();
$totalPCD = $stmtTotalPCD->fetch(PDO::FETCH_ASSOC);



// Exibindo os totais no PDF
$pdf->SetFont('Arial', '', 10);

// Alterar a cor para preto antes de exibir o número
$pdf->SetTextColor(0, 0, 0); // Cor preta

// Exibir Total de Inscritos
$pdf->Cell(171, 7, utf8_decode('Total de Inscritos: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalInscritos['total_inscritos'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Pública
$pdf->Cell(171, 7, utf8_decode('Total Pública: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPublica['total_publica'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Privada
$pdf->Cell(171, 7, utf8_decode('Total Privada: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPrivada['total_privada'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Cota PCD
$pdf->Cell(171, 7, utf8_decode('Total Cota PCD: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPCD['total_pcd'], 0, ',', '.'), 1, 1, 'R');

$pdf->ln(12);




//--ADMINISTRAÇÃO
//--COTA - PCD - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Cota - PCD", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }

        // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}







//--ADMINISTRAÇÃO
//--COTA - PCD - LISTA DE ESPERA ------------------------------------------------------------------------------------------//
$parametro_curso = 3; //ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 300 OFFSET 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 3; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }
        
                // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }


        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);











































    

//--ADMINISTRAÇÃO
//--PUBLICA - AC - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 24
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? ('PUB') : ('PRI');

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}




    
//--ADMINISTRAÇÃO
//--PUBLICA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 24;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 25; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--ADMINISTRAÇÃO
//--PUBLICA - COTA - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 10
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--ADMINISTRAÇÃO
//--PUBLICA - COTA - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 10;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 11; // A classificação começa de 11 (pois os primeiros 10 já foram classificados)
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);


































































    

//--ADMINISTRAÇÃO
//--PRIVADA - AC - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 6
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}





    
//--ADMINISTRAÇÃO
//--PRIVADA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 6;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 7; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 6) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--ADMINISTRAÇÃO
//--PRIVADA - COTA - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 3
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--ADMINISTRAÇÃO
//--PUBLICA - COTA BAIRRO - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 3; // ADMINISTRAÇÃO

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 3
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 4; // A classificação começa de 7
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);
$pdf->AddPage();
$pdf->Ln(13);








///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////-- EDIFICAÇÕES --////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//RELATÓRIO GERAL DO CURSO

$parametro_curso = 4; // EDIFICAÇÕES

// Iniciar a impressão do cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco (cabeçalho)
$pdf->Cell(181, -8, utf8_decode("Ensino Médio Integrado em  Edificações"), 1, 1, 'C', true);
$pdf->ln(8);

// Consultas separadas para contar os totais por categoria

// Total de Inscritos
$stmtTotalInscritos = $conexao->prepare("
SELECT COUNT(*) AS total_inscritos
FROM candidato
WHERE candidato.id_curso1_fk = :curso1
");
$stmtTotalInscritos->bindValue(':curso1', $parametro_curso);
$stmtTotalInscritos->execute();
$totalInscritos = $stmtTotalInscritos->fetch(PDO::FETCH_ASSOC);


// Total Pública
$stmtTotalPublica = $conexao->prepare("
SELECT COUNT(*) AS total_publica
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 1
");
$stmtTotalPublica->bindValue(':curso1', $parametro_curso);
$stmtTotalPublica->execute();
$totalPublica = $stmtTotalPublica->fetch(PDO::FETCH_ASSOC);


// Total Privada
$stmtTotalPrivada = $conexao->prepare("
SELECT COUNT(*) AS total_privada
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.publica = 0
");
$stmtTotalPrivada->bindValue(':curso1', $parametro_curso);
$stmtTotalPrivada->execute();
$totalPrivada = $stmtTotalPrivada->fetch(PDO::FETCH_ASSOC);


// Total Cota PCD
$stmtTotalPCD = $conexao->prepare("
SELECT COUNT(*) AS total_pcd
FROM candidato
WHERE candidato.id_curso1_fk = :curso1 AND candidato.pcd = 1
");
$stmtTotalPCD->bindValue(':curso1', $parametro_curso);
$stmtTotalPCD->execute();
$totalPCD = $stmtTotalPCD->fetch(PDO::FETCH_ASSOC);



// Exibindo os totais no PDF
$pdf->SetFont('Arial', '', 10);

// Alterar a cor para preto antes de exibir o número
$pdf->SetTextColor(0, 0, 0); // Cor preta

// Exibir Total de Inscritos
$pdf->Cell(171, 7, utf8_decode('Total de Inscritos: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalInscritos['total_inscritos'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Pública
$pdf->Cell(171, 7, utf8_decode('Total Pública: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPublica['total_publica'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Privada
$pdf->Cell(171, 7, utf8_decode('Total Privada: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPrivada['total_privada'], 0, ',', '.'), 1, 1, 'R');

// Exibir Total Cota PCD
$pdf->Cell(171, 7, utf8_decode('Total Cota PCD: '), 1, 0, 'L', false);
$pdf->Cell(10, 7, number_format($totalPCD['total_pcd'], 0, ',', '.'), 1, 1, 'R');

$pdf->ln(12);




//--EDIFICAÇÕES
//--COTA - PCD - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Cota - PCD", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }

        // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}







//--EDIFICAÇÕES
//--COTA - PCD - LISTA DE ESPERA ------------------------------------------------------------------------------------------//
$parametro_curso = 4; //ENFERMAGEM

$stmtSelect_ac_publica = $conexao->prepare("
SELECT candidato.nome, candidato.id_curso1_fk, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.pcd = 1 
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 300 OFFSET 2
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 3; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'COTA - PCD';
        }
        
                // Definir situação
        if ($classificacao <= 2) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }


        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);











































    

//--EDIFICAÇÕES
//--PUBLICA - AC - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 24
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? ('PUB') : ('PRI');

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}




    
//--EDIFICAÇÕES
//--PUBLICA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 24;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 25; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 24) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--EDIFICAÇÕES
//--PUBLICA - COTA - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 10
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Publica - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--EDIFICAÇÕES
//--PUBLICA - COTA - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 1 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 10;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 11; // A classificação começa de 11 (pois os primeiros 10 já foram classificados)
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 10) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);


































































    

//--EDIFICAÇÕES
//--PRIVADA - AC - CLASSIFICADO(A) -----------------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 8
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - AC", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 8) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}





    
//--EDIFICAÇÕES
//--PRIVADA - AC - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 0
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 8;
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 9; // A classificação começa de 25
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }

        // Definir situação
        if ($classificacao <= 8) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

$pdf->Ln(12);





































































    

//--EDIFICAÇÕES
//--PRIVADA - COTA - CLASSIFICADOS -----------------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_ac_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica DESC LIMIT 3
");

$stmtSelect_ac_publica->bindValue(':curso', $parametro_curso);
$stmtSelect_ac_publica->execute();
$result = $stmtSelect_ac_publica->fetchAll(PDO::FETCH_ASSOC);

// Fonte do cabeçalho
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(181, -8, "Rede Privada - Cota Bairro", 1, 0, 'C', true);
$pdf->Ln(0);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(93, 164, 67); // fundo verde
$pdf->SetTextColor(255, 255, 255);  // texto branco
$pdf->Cell(8, 7, 'CH', 1, 0, 'C', true);
$pdf->Cell(79, 7, 'Nome', 1, 0, 'C', true);
$pdf->Cell(29, 7, 'Curso', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Origem', 1, 0, 'C', true);
$pdf->Cell(30, 7, utf8_decode('Situação'), 1, 0, 'C', true);
$pdf->Cell(15, 7, 'Media', 1, 1, 'C', true);

// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    $pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("Segmento sem candidatos na lista de espera."), 1, 0, 'L', true);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 1; // A classificação começa de 1
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}






    
//--EDIFICAÇÕES
//--PUBLICA - COTA BAIRRO - LISTA DE ESPERA ------------------------------------------------------------------------------------------//

$parametro_curso = 4; // EDIFICAÇÕES

$stmtSelect_bairro_publica = $conexao->prepare("
SELECT DISTINCT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
FROM candidato 
INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
AND candidato.id_curso1_fk = :curso1
AND candidato.publica = 0 
AND candidato.pcd = 0 
AND candidato.bairro = 1
ORDER BY nota.media DESC,
candidato.data_nascimento DESC,
nota.l_portuguesa DESC,
nota.matematica LIMIT 300 OFFSET 3
");

$stmtSelect_bairro_publica->bindValue(':curso1', $parametro_curso);
$stmtSelect_bairro_publica->execute();
$result = $stmtSelect_bairro_publica->fetchAll(PDO::FETCH_ASSOC);


// Resetar cor do texto para preto
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

// Verificar se há resultados
if (empty($result)) {
 
    // Definir cor da linha
    $cor = $classificacao % 2 ? 255 : 192;
    $pdf->SetFillColor($cor, $cor, $cor);
    
    //  Caso não haja candidatos, imprimir a mensagem
    //  $pdf->SetFont('Arial', 'I', 12);
    //  $pdf->Cell(181, 10, utf8_decode("Não houve candidatos para esta modalidade."), 1, 1, 'C', false);
    
    //$pdf->SetTextColor(255, 0, 0);  // texto em vermelho
    $pdf->Cell(8, 7, "002", 1, 0, 'C', true);
    $pdf->Cell(79, 7, utf8_decode("VAGA REMANEJADA*"), 1, 0, 'L', 1);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(30, 7, "- - -", 1, 0, 'C', true);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', true);
    
    $pdf->Cell(8, 7, "003", 1, 0, 'C', false);
    $pdf->Cell(79, 7, utf8_decode("VAGA REMANEJADA*"), 1, 0, 'L', false);
    $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', false);
    $pdf->Cell(20, 7, "- - -", 1, 0, 'C', false);
    $pdf->Cell(30, 7, "- - -", 1, 0, 'C', false);
    $pdf->Cell(15, 7, "- - -", 1, 1, 'C', false);

    
} else {
    // Caso haja resultados, imprimir as linhas com os dados dos candidatos
    $classificacao = 4; // A classificação começa de 7
    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = 'ENFERMAGEM';
                break;
            case 2:
                $curso = 'INFORMÁTICA';
                break;
            case 3:
                $curso = 'ADMINISTRAÇÃO';
                break;
            case 4:
                $curso = 'EDIFICAÇÕES';
                break;
            default:
                $curso = 'Não definido';
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? 'PUB' : 'PRI';

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = ' - PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else if ($row['publica'] == 0 && $row['bairro'] == 1) {
            $cota = ' - COTA';
        } else {
            $cota = ' - AC';
        }


        // Definir situação
        if ($classificacao <= 3) {
            $situacao = "CLASSIFICADO";
        } else {
            $situacao = "LISTA DE ESPERA";
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(8, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell(79, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(29, 7, utf8_decode($curso), 1, 0, 'L', true);
        $pdf->Cell(20, 7, $escola.$cota, 1, 0, 'L', true);
        $pdf->Cell(30, 7, $situacao, 1, 0, 'L', true);
        $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 1, 'C', true);

        $classificacao++;
    }
}

    $pdf->Cell(120, 7, utf8_decode("* Vaga remanejada para ampla concorrência conforme disposto na Portaria Nº 266/2024- GAB."), 0, 0, 'L', false);
 





    $pdf->Output('classificados.pdf', 'I');

