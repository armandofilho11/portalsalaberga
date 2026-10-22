<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../partials/system_layout.php';

// 1. CAPTURA O TERMO DE BUSCA DO FORMULÁRIO (Se não houver, assume vazio '')
$busca = $_GET['busca'] ?? '';

$usuarioModel = new Usuario();

// 2. PASSA A BUSCA COMO PARÂMETRO PARA O MÉTODO DO MODEL DO USUÁRIO
$entradas = $usuarioModel->listarEntradasComDetalhes($busca);

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
        .header-gradient { background: linear-gradient(90deg, #008C45, #3CB371); }
    </style>
';

// Renderiza o cabeçalho global do sistema
renderHead('Relatório de Entradas - Salaberga', $extraHead);
?>

<div class="lg:pl-72">
    <?php 
        // Renderiza a barra lateral nativa ativa no módulo de relatórios
        renderSidebar('relatorios');
    ?>

    <div class="min-h-screen pt-12 px-4 sm:px-6 lg:px-8">
        <main class="max-w-5xl mx-auto py-6 w-full flex-1">
            
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'apagado'): ?>
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-check-circle text-green-600 text-base"></i> Registro apagado com sucesso!
                </div>
            <?php  endif; ?>
            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'apagar'): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-exclamation-circle text-red-600 text-base"></i> Não foi possível apagar o registro. Tente novamente.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                <div class="header-gradient p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Relatório de Entradas</h2>
                        <p class="opacity-90">Controle de registros de entradas de alunos da instituição</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto self-stretch sm:self-auto">
                        <a href="../relatorio.php" class="bg-white/10 text-white hover:bg-white/20 border border-white/20 px-5 py-3 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <a href="PDF/relatorio_de_entrada_pdf.php?busca=<?= urlencode($busca) ?>" class="bg-white text-green-700 hover:bg-green-50 px-5 py-3 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i> Exportar PDF
                        </a>
                    </div>
                </div>

                <div class="p-8 pb-4">
                    <form method="GET" action="" id="formBuscaEntradas" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                id="inputBuscaEntradas"
                                name="busca" 
                                value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>" 
                                placeholder="Buscar por aluno, série, turma ou motivo..." 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-sm transition"
                                autocomplete="off"
                            >
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-emerald-600 text-white hover:bg-emerald-700 px-6 py-3 rounded-xl font-bold shadow-sm transition duration-150 text-sm flex items-center gap-2 justify-center w-full sm:w-auto">
                                Filtrar
                            </button>
                            <?php if (!empty($busca)): ?>
                                <a href="?" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-4 py-3 rounded-xl font-medium transition duration-150 text-sm flex items-center justify-center" title="Limpar busca">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="p-8 pt-2">
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full border-collapse bg-white text-left text-sm text-gray-600" id="tabelaEntradas">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-user-graduate text-emerald-600"></i> Aluno
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-layer-group text-emerald-600"></i> Turma / Série
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-calendar text-emerald-600"></i> Data
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-clock text-emerald-600"></i> Hora
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-question-circle text-orange-700"></i> Motivo
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">
                                        <i class="fas fa-comment-dots text-amber-500"></i> Observação
                                    </th>
                                    <th class="px-6 py-4 font-semibold text-gray-700 text-center">
                                        <i class="fas fa-cogs text-emerald-600"></i> Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($entradas)): ?>
                                    <tr class="sem-resultados">
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 font-medium">
                                            <i class="fas fa-folder-open text-gray-300 text-4xl block mb-2"></i>
                                            Nenhum registro encontrado.
                                        </td>
                                    </tr>
                                <?php index_or_empty: else: ?>
                                    <?php foreach ($entradas as $row): ?>
                                        <tr class="hover:bg-gray-50/80 transition duration-150 linha-entrada">
                                            <td class="px-6 py-4 font-semibold text-gray-900 align-top campo-aluno">
                                                <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4 align-top campo-turma">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-800 border border-green-200">
                                                    <?= htmlspecialchars($row['serie'] . ' / ' . $row['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('d/m/Y', strtotime($row['data_entrada'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-600 font-medium">
                                                <?= date('H:i', strtotime($row['hora_entrada'])) ?>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 campo-motivo">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-800 border border-orange-200">
                                                    <?= htmlspecialchars($row['motivo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 max-w-xs whitespace-normal break-words text-xs leading-relaxed">
                                                <?= $row['descricao'] ? nl2br(htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8')) : '<span class="text-gray-300 italic">Sem observações</span>' ?>
                                            </td>
                                            
                                            <td class="px-6 py-4 align-top text-center whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <a href="../registros/editar_entrada.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Editar Registro">
                                                        <i class="fas fa-pencil-alt text-sm"></i>
                                                    </a>

                                                    <a href="PDF/relatorio_de_autorizacao_entrada_pdf.php?id=<?= $row['id'] ?>" target="_blank" class="text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-lg transition" title="Imprimir Autorização de Entrada">
                                                        <i class="fas fa-file text-sm"></i>
                                                    </a>
                                                    
                                                    <a href="../../controllers/controller_alunos.php?action=apagar_registro&id=<?= $row['id'] ?>&tabela=registro_entrada" 
                                                       onclick="return confirm('Atenção!\nVocê tem certeza que deseja deletar permanentemente esta entrada de aluno?')" 
                                                       class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" 
                                                       title="Apagar Registro">
                                                        <i class="fas fa-trash-alt text-sm"></i>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputBusca = document.getElementById("inputBuscaEntradas");
    const tabela = document.getElementById("tabelaEntradas");

    if (inputBusca && tabela) {
        inputBusca.addEventListener("input", function () {
            const termo = inputBusca.value.toLowerCase().trim();
            const linhas = tabela.querySelectorAll(".linha-entrada");

            linhas.forEach(linha => {
                const aluno = linha.querySelector(".campo-aluno").textContent.toLowerCase();
                const turma = linha.querySelector(".campo-turma").textContent.toLowerCase();
                const motivo = linha.querySelector(".campo-motivo").textContent.toLowerCase();

                if (aluno.includes(termo) || turma.includes(termo) || motivo.includes(termo)) {
                    linha.style.display = "";
                } else {
                    linha.style.display = "none";
                }
            });
        });
    }
});
</script>

<?php
// Rodapé 
renderFooter();
?>