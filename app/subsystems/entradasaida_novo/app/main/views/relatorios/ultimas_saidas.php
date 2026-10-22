<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../models/Aluno.php';
require_once __DIR__ . '/../../models/Usuario.php';

$alunoModel = new Aluno();
$usuarioModel = new Usuario();

// Chamando as funções especializadas para o dia de hoje
$saidas = $alunoModel->listarSaidasDoDia();
$entradasHoje = $usuarioModel->listarEntradasHoje();
$preEstagioHoje = $alunoModel->listarPreEstagioHoje();

// Obter saídas de estágio especificamente por série/turma
$hoje = date('Y-m-d');

// Lista de mini-tabelas por turma: 3º A/B/C/D
$enfermagem = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'A');
$informatica = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'B');
$administracao = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'C');
$edificacoes = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'D');

require_once __DIR__ . '/../partials/system_layout.php';

$extraHead = '
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ceara: { green: "#008C45", light: "#3CB371", olive: "#8CA03E", orange: "#FFA500" },
                    }
                }
            }
        };
    </script>
    <style>
        body { background: #f8fafc; }
        .header-gradient-red { background: linear-gradient(90deg, #b91c1c, #ef4444); }
        .header-gradient-amber { background: linear-gradient(90deg, #d97706, #f59e0b); }
        .header-gradient { background: linear-gradient(90deg, #008C45, #3CB371); }
    </style>
';

// Renderiza o cabeçalho global do sistema
renderHead('Sistema Escolar - Últimas Saídas', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php 
        // Renderiza a barra lateral nativa do sistema
        renderSidebar('ultimas');
    ?>

    <div class="flex-1 pt-12 px-4 sm:px-6 lg:px-8">
        <main class="max-w-5xl mx-auto py-6 w-full custom-main space-y-8">
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="header-gradient-red p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Últimas Saídas Registradas</h2>
                        <p class="opacity-90">Histórico dinâmico de saídas de alunos da instituição hoje</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto self-stretch md:self-auto">
                        <div class="relative w-full sm:w-64">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <input 
                                type="text" 
                                id="inputBuscaSaidas" 
                                placeholder="Buscar aluno ou turma..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 transition duration-200"
                            >
                        </div>

                        <a href="PDF/relatorio_de_ultimas_saidas_pdf.php" class="bg-white text-red-700 hover:bg-red-50 px-5 py-2.5 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm shrink-0">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i> Exportar PDF
                        </a>
                    </div>
                </div>

                <div class="p-8">
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full border-collapse bg-white text-left text-sm text-gray-600" id="tabelaUltimasSaidas">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-graduate text-red-600"></i> Aluno
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-layer-group text-red-600"></i> Turma
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-calendar text-red-600"></i> Data
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-clock text-red-600"></i> Hora
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-user-shield text-blue-700"></i> Responsável
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-question-circle text-orange-700"></i> Motivo
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-car text-purple-700"></i> Conducente
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-comment-dots text-amber-500"></i> Observação
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($saidas)): ?>
                                    <tr class="sem-registros-tr">
                                        <td colspan="8" class="px-6 py-10 text-center text-gray-500 font-medium">
                                            <i class="fas fa-folder-open text-gray-300 text-4xl block mb-2"></i>
                                            Nenhuma saída registrada hoje.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($saidas as $row): ?>
                                        <tr class="hover:bg-red-50/30 transition duration-150 linha-saida">
                                            <td class="px-6 py-4 font-semibold text-gray-900 align-top coluna-aluno">
                                                <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4 align-top coluna-turma">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-800 border border-red-200">
                                                    <?= htmlspecialchars($row['serie'] . ' ' . $row['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('d/m/Y', strtotime($row['data_saida'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('H:i', strtotime($row['hora_saida'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500">
                                                <div class="text-xs font-medium text-gray-700">
                                                    <?= ucfirst(htmlspecialchars($row['tipo_responsavel'] ?? 'N/A', ENT_QUOTES, 'UTF-8')) ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?= htmlspecialchars($row['nome_responsavel'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-800 border border-orange-200">
                                                    <?= htmlspecialchars($row['motivo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-800 border border-purple-200">
                                                    <?= ucfirst(htmlspecialchars($row['tipo_conducente'] ?? 'N/A', ENT_QUOTES, 'UTF-8')) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 max-w-xs whitespace-normal break-words text-xs leading-relaxed">
                                                <?= $row['descricao'] ? nl2br(htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8')) : '<span class="text-gray-300 italic">Sem observações</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4" style="background: linear-gradient(90deg, #0f766e, #14b8a6);">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Pré-Estágio Registrado Hoje</h2>
                        <p class="opacity-90">Histórico dos 4 momentos pós-almoço: almoço, retorno, lanche e retorno do lanche</p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full border-collapse bg-white text-left text-sm text-gray-600" id="tabelaPreEstagioHoje">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-user-graduate text-teal-600"></i> Aluno</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-layer-group text-teal-600"></i> Turma</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-calendar text-teal-600"></i> Data</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-clock text-teal-600"></i> Hora</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-stopwatch text-teal-600"></i> Momento</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700"><i class="fas fa-comment-dots text-amber-500"></i> Observação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($preEstagioHoje)): ?>
                                    <tr class="sem-registros-tr">
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-medium">
                                            <i class="fas fa-folder-open text-gray-300 text-4xl block mb-2"></i>
                                            Nenhum registro de pré-estágio hoje.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($preEstagioHoje as $row): ?>
                                        <tr class="hover:bg-teal-50/30 transition duration-150 linha-pre-estagio-hoje">
                                            <td class="px-6 py-4 font-semibold text-gray-900 align-top coluna-aluno-pre-estagio">
                                                <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4 align-top coluna-turma-pre-estagio">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-teal-50 text-teal-800 border border-teal-200">
                                                    <?= htmlspecialchars($row['serie'] . ' ' . $row['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('d/m/Y', strtotime($row['data_registro'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('H:i', strtotime($row['hora_registro'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 coluna-momento-pre-estagio">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                                    <?= htmlspecialchars(str_replace(['ida_almoco','retorno_almoco','ida_lanche','retorno_lanche'], ['Ida para o almoço','Retorno do almoço','Ida para o lanche','Retorno do lanche'], $row['momento']), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 max-w-xs whitespace-normal break-words text-xs leading-relaxed">
                                                <?= $row['descricao'] ? nl2br(htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8')) : '<span class="text-gray-300 italic">Sem observações</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="header-gradient p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Entradas Registradas Hoje</h2>
                        <p class="opacity-90">Registros de entrada do dia atual para análise rápida</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto self-stretch md:self-auto">
                        <div class="relative w-full sm:w-64">
                            <i class="fas fa-search absolute left-4 top-3.5 text-white/70"></i>
                            <input 
                                type="text" 
                                id="inputBuscaEntradasHoje" 
                                placeholder="Buscar aluno ou turma..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm bg-white/15 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white focus:text-gray-900 transition duration-200"
                            >
                        </div>

                        <a href="PDF/relatorio_de_entradas_hoje_pdf.php" class="bg-white text-green-700 hover:bg-green-50 px-5 py-2.5 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm shrink-0">
                            <i class="fas fa-file-pdf text-green-600 text-lg"></i> Exportar PDF
                        </a>
                    </div>
                </div>

                <div class="p-8">
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full border-collapse bg-white text-left text-sm text-gray-600" id="tabelaEntradasHoje">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-graduate text-green-600"></i> Aluno
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-layer-group text-green-600"></i> Turma
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-calendar text-green-600"></i> Data
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-clock text-green-600"></i> Hora
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-question-circle text-orange-700"></i> Motivo
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-comment-dots text-amber-500"></i> Observação
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($entradasHoje)): ?>
                                    <tr class="sem-registros-tr">
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-medium">
                                            <i class="fas fa-folder-open text-gray-300 text-4xl block mb-2"></i>
                                            Nenhuma entrada registrada hoje.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($entradasHoje as $row): ?>
                                        <tr class="hover:bg-green-50/30 transition duration-150 linha-entrada-hoje">
                                            <td class="px-6 py-4 font-semibold text-gray-900 align-top coluna-aluno-hoje">
                                                <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4 align-top coluna-turma-hoje">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-800 border border-green-200">
                                                    <?= htmlspecialchars($row['serie'] . ' ' . $row['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('d/m/Y', strtotime($row['data_entrada'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('H:i', strtotime($row['hora_entrada'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 coluna-motivo-hoje">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-800 border border-orange-200">
                                                    <?= htmlspecialchars($row['motivo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 max-w-xs whitespace-normal break-words text-xs leading-relaxed">
                                                <?= $row['descricao'] ? nl2br(htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8')) : '<span class="text-gray-300 italic">Sem observações</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="header-gradient-amber p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Saídas para Estágio Técnico</h2>
                        <p class="opacity-90">Frequência diária distribuída por curso técnico mapeado</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto self-stretch md:self-auto">
                        <div class="relative w-full sm:w-64">
                            <i class="fas fa-search absolute left-4 top-3.5 text-amber-700"></i>
                            <input 
                                type="text" 
                                id="inputBuscaEstagio" 
                                placeholder="Buscar aluno em estágios..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white focus:text-gray-900 focus:placeholder-gray-400 transition duration-200"
                            >
                        </div>

                        <a href="PDF/relatorio_de_estagios_hoje_pdf.php" class="bg-white text-amber-700 hover:bg-amber-50 px-5 py-2.5 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm shrink-0">
                            <i class="fas fa-file-pdf text-amber-600 text-lg"></i> Exportar PDF
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4" id="containerMiniTabelas">
                        
                        <div class="border border-red-100 rounded-xl bg-red-50/10 p-3 card-mini-tabela">
                            <div class="bg-red-600 text-white text-xs font-bold px-3 py-2 rounded-lg flex justify-between items-center mb-2 shadow-sm">
                                <span>Enfermagem</span>
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">3º A</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs min-tabela-dados">
                                    <thead>
                                        <tr class="text-gray-400 border-b border-gray-100"><th class="pb-1">Aluno</th><th class="pb-1 text-center">Hora</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php if(empty($enfermagem)): ?>
                                            <tr><td colspan="2" class="py-4 text-center italic text-gray-400">Nenhuma saída</td></tr>
                                        <?php else: foreach($enfermagem as $r): ?>
                                            <tr class="linha-estagio-mini"><td class="py-2 pr-2 font-medium text-gray-800 classe-nome-aluno"><?= htmlspecialchars($r['nome']??$r['aluno'], ENT_QUOTES, 'UTF-8') ?></td><td class="py-2 text-center text-red-600 font-bold"><?= isset($r['hora_saida']) ? date('H:i', strtotime($r['hora_saida'])) : '--:--' ?></td></tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border border-blue-100 rounded-xl bg-blue-50/10 p-3 card-mini-tabela">
                            <div class="bg-blue-600 text-white text-xs font-bold px-3 py-2 rounded-lg flex justify-between items-center mb-2 shadow-sm">
                                <span>Informática</span>
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">3º B</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs min-tabela-dados">
                                    <thead>
                                        <tr class="text-gray-400 border-b border-gray-100"><th class="pb-1">Aluno</th><th class="pb-1 text-center">Hora</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php if(empty($informatica)): ?>
                                            <tr><td colspan="2" class="py-4 text-center italic text-gray-400">Nenhuma saída</td></tr>
                                        <?php else: foreach($informatica as $r): ?>
                                            <tr class="linha-estagio-mini"><td class="py-2 pr-2 font-medium text-gray-800 classe-nome-aluno"><?= htmlspecialchars($r['nome']??$r['aluno'], ENT_QUOTES, 'UTF-8') ?></td><td class="py-2 text-center text-blue-600 font-bold"><?= isset($r['hora_saida']) ? date('H:i', strtotime($r['hora_saida'])) : '--:--' ?></td></tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border border-emerald-100 rounded-xl bg-emerald-50/10 p-3 card-mini-tabela">
                            <div class="bg-emerald-600 text-white text-xs font-bold px-3 py-2 rounded-lg flex justify-between items-center mb-2 shadow-sm">
                                <span>Administração / M.A</span>
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">3º C</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs min-tabela-dados">
                                    <thead>
                                        <tr class="text-gray-400 border-b border-gray-100"><th class="pb-1">Aluno</th><th class="pb-1 text-center">Hora</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php if(empty($administracao)): ?>
                                            <tr><td colspan="2" class="py-4 text-center italic text-gray-400">Nenhuma saída</td></tr>
                                        <?php else: foreach($administracao as $r): ?>
                                            <tr class="linha-estagio-mini"><td class="py-2 pr-2 font-medium text-gray-800 classe-nome-aluno"><?= htmlspecialchars($r['nome']??$r['aluno'], ENT_QUOTES, 'UTF-8') ?></td><td class="py-2 text-center text-emerald-600 font-bold"><?= isset($r['hora_saida']) ? date('H:i', strtotime($r['hora_saida'])) : '--:--' ?></td></tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl bg-gray-50 p-3 card-mini-tabela">
                            <div class="bg-gray-600 text-white text-xs font-bold px-3 py-2 rounded-lg flex justify-between items-center mb-2 shadow-sm">
                                <span>Edificações</span>
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">3º D</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs min-tabela-dados">
                                    <thead>
                                        <tr class="text-gray-400 border-b border-gray-100"><th class="pb-1">Aluno</th><th class="pb-1 text-center">Hora</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <?php if(empty($edificacoes)): ?>
                                            <tr><td colspan="2" class="py-4 text-center italic text-gray-400">Nenhuma saída</td></tr>
                                        <?php else: foreach($edificacoes as $r): ?>
                                            <tr class="linha-estagio-mini"><td class="py-2 pr-2 font-medium text-gray-800 classe-nome-aluno"><?= htmlspecialchars($r['nome']??$r['aluno'], ENT_QUOTES, 'UTF-8') ?></td><td class="py-2 text-center text-gray-600 font-bold"><?= isset($r['hora_saida']) ? date('H:i', strtotime($r['hora_saida'])) : '--:--' ?></td></tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </main>
    </div>

    <?php
    // Rodapé
    renderFooter();
    ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- FILTRO 1: BUSCA DE SAÍDAS REGULARES ---
    const inputBusca = document.getElementById("inputBuscaSaidas");
    const tabela = document.getElementById("tabelaUltimasSaidas");

    if (inputBusca && tabela) {
        inputBusca.addEventListener("keyup", function () {
            const termo = inputBusca.value.toLowerCase().trim();
            const lines = tabela.querySelectorAll(".linha-saida");

            lines.forEach(linha => {
                const nomeAluno = linha.querySelector(".coluna-aluno").textContent.toLowerCase();
                const turmaAluno = linha.querySelector(".coluna-turma").textContent.toLowerCase();

                if (nomeAluno.includes(termo) || turmaAluno.includes(termo)) {
                    linha.style.display = ""; 
                } else {
                    linha.style.display = "none"; 
                }
            });
        });
    }

    // --- FILTRO 2: BUSCA DE ENTRADAS HOJE ---
    const inputBuscaEntradasHoje = document.getElementById("inputBuscaEntradasHoje");
    const tabelaEntradasHoje = document.getElementById("tabelaEntradasHoje");

    if (inputBuscaEntradasHoje && tabelaEntradasHoje) {
        inputBuscaEntradasHoje.addEventListener("keyup", function () {
            const termo = inputBuscaEntradasHoje.value.toLowerCase().trim();
            const linhas = tabelaEntradasHoje.querySelectorAll(".linha-entrada-hoje");

            linhas.forEach(linha => {
                const aluno = linha.querySelector(".coluna-aluno-hoje").textContent.toLowerCase();
                const turma = linha.querySelector(".coluna-turma-hoje").textContent.toLowerCase();
                const motivo = linha.querySelector(".coluna-motivo-hoje").textContent.toLowerCase();

                if (aluno.includes(termo) || turma.includes(termo) || motivo.includes(termo)) {
                    linha.style.display = "";
                } else {
                    linha.style.display = "none";
                }
            });
        });
    }

    // --- FILTRO 3: BUSCA REATIVOS NAS MINI TABELAS DE ESTÁGIO ---
    const inputEstagio = document.getElementById("inputBuscaEstagio");
    const containerEstagio = document.getElementById("containerMiniTabelas");

    if (inputEstagio && containerEstagio) {
        inputEstagio.addEventListener("keyup", function () {
            const termo = inputEstagio.value.toLowerCase().trim();
            const linhasMini = containerEstagio.querySelectorAll(".linha-estagio-mini");

            linhasMini.forEach(linha => {
                const nomeAluno = linha.querySelector(".classe-nome-aluno").textContent.toLowerCase();
                
                if (nomeAluno.includes(termo)) {
                    linha.style.display = "";
                } else {
                    linha.style.display = "none";
                }
            });
        });
    }
});
</script>