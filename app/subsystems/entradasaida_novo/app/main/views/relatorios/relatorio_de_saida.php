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

// 2. PASSA A BUSCA COMO PARÂMETRO PARA O MÉTODO DE SAÍDAS
$saidas = $usuarioModel->listarSaidasComDetalhes($busca);

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
        /* Alterado o degradê do cabeçalho para tons de vermelho institucional */
        .header-gradient-red { background: linear-gradient(90deg, #991b1b, #ef4444); }
    </style>
';

// Renderiza o cabeçalho global do sistema
renderHead('Relatório de Saídas - Salaberga', $extraHead);
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
                    <i class="fas fa-check-circle text-green-600 text-base"></i> Registro de saída apagado com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'editado'): ?>
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-check-circle text-green-600 text-base"></i> Registro modificado com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'apagar'): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-exclamation-circle text-red-600 text-base"></i> Falha ao apagar o registro. Tente novamente.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                <div class="header-gradient-red p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Relatório de Saídas</h2>
                        <p class="opacity-90">Controle de registros de saídas de alunos da instituição</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto self-stretch sm:self-auto">
                        <a href="../relatorio.php" class="bg-white/10 text-white hover:bg-white/20 border border-white/20 px-5 py-3 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <a href="PDF/relatorio_de_saida_pdf.php?busca=<?= urlencode($busca) ?>" class="bg-white text-red-700 hover:bg-red-50 px-5 py-3 rounded-xl font-bold shadow-md transition duration-300 flex items-center gap-2 w-full sm:w-auto justify-center text-sm">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i> Exportar PDF
                        </a>
                    </div>
                </div>

                <div class="p-8 pb-4">
                    <form method="GET" action="" id="formBuscaSaidas" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                id="inputBuscaSaidas"
                                name="busca" 
                                value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>" 
                                placeholder="Buscar por aluno, série, turma, responsável ou motivo..." 
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm text-sm transition"
                                autocomplete="off"
                            >
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-6 py-3 rounded-xl font-bold shadow-sm transition duration-150 text-sm flex items-center gap-2 justify-center w-full sm:w-auto">
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
                        <table class="w-full border-collapse bg-white text-left text-sm text-gray-600" id="tabelaSaidas">
                            <thead>
                                <tr class="bg-red-50/50 border-b border-gray-200">
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
                                        <i class="fas fa-user-shield text-red-600"></i> Responsável
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
                                    <th class="px-6 py-4 font-semibold text-gray-700 text-center">
                                        <i class="fas fa-cogs text-red-600"></i> Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($saidas)): ?>
                                    <tr class="sem-resultados">
                                        <td colspan="9" class="px-6 py-10 text-center text-gray-500 font-medium">
                                            <i class="fas fa-folder-open text-gray-300 text-4xl block mb-2"></i>
                                            Nenhum registro encontrado para a busca.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($saidas as $row): ?>
                                        <tr class="hover:bg-red-50/20 transition duration-150 linha-saida">
                                            <td class="px-6 py-4 font-semibold text-gray-900 align-top campo-aluno">
                                                <?= htmlspecialchars($row['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4 align-top campo-turma">
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
                                            <td class="px-6 py-4 align-top text-gray-600 campo-responsavel">
                                                <div class="text-xs font-semibold text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($row['tipo_responsavel'] ?? 'N/A', ENT_QUOTES, 'UTF-8')) ?>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    <?= htmlspecialchars($row['nome_responsavel'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top text-gray-500 campo-motivo">
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
                                            
                                            <td class="px-6 py-4 align-top text-center whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <a href="../registros/editar_saida.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition shadow-sm" title="Editar Registro">
                                                        <i class="fas fa-pencil-alt text-xs"></i>
                                                    </a>
                                                    
                                                    <a href="../../controllers/controller_alunos.php?action=apagar_registro&id=<?= $row['id'] ?>&tabela=registro_saida&origem=relatorio_de_saida.php" 
                                                       onclick="return confirm('Atenção!\nVocê tem certeza que deseja deletar permanentemente este registro de saída?')" 
                                                       class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition shadow-sm" 
                                                       title="Apagar Registro">
                                                        <i class="fas fa-trash-alt text-xs"></i>
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
    const inputBusca = document.getElementById("inputBuscaSaidas");
    const tabela = document.getElementById("tabelaSaidas");

    if (inputBusca && tabela) {
        inputBusca.addEventListener("input", function () {
            const termo = inputBusca.value.toLowerCase().trim();
            const linhas = tabela.querySelectorAll(".linha-saida");

            linhas.forEach(linha => {
                const aluno = linha.querySelector(".campo-aluno").textContent.toLowerCase();
                const turma = linha.querySelector(".campo-turma").textContent.toLowerCase();
                const responsavel = linha.querySelector(".campo-responsavel").textContent.toLowerCase();
                const motivo = linha.querySelector(".campo-motivo").textContent.toLowerCase();

                if (aluno.includes(termo) || turma.includes(termo) || responsavel.includes(termo) || motivo.includes(termo)) {
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
renderFooter();
?>