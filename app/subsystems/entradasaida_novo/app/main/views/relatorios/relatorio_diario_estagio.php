<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../index.php");
    exit();
}

require_once __DIR__ . "/../../models/Aluno.php";
require_once __DIR__ . '/../partials/system_layout.php';

$alunoModel = new Aluno();

// Agora listamos por série/turma para garantir que cada painel mostre apenas sua turma
$saidasEnfermagem = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'A');
$saidasInformatica = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'B');
$saidasAdministracaoMeio = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'C');
$saidasEdificacao = $alunoModel->listarSaidasEstagioPorTurma('3 ano', 'D');

function renderTable(array $dados)
{
    if (empty($dados)) {
        return '<p class="text-sm text-gray-400 italic py-2">Nenhum registro encontrado para esta categoria.</p>';
    }

    $html = '<div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">';
    $html .= '<table class="min-w-full divide-y divide-gray-200 bg-white text-left text-sm text-gray-600 tabela-estagio">';
    $html .= '<thead class="bg-gray-50">';
    $html .= '<tr class="text-xs font-semibold uppercase text-gray-500">';
    $html .= '<th class="px-6 py-4">Aluno</th>';
    $html .= '<th class="px-6 py-4">Matrícula</th>';
    $html .= '<th class="px-6 py-4">Turma</th>';
    $html .= '<th class="px-6 py-4">Data</th>';
    $html .= '<th class="px-6 py-4">Hora</th>';
    $html .= '<th class="px-6 py-4 text-center"><i class="fas fa-cogs text-gray-500"></i> Ações</th>';
    $html .= '</tr></thead><tbody class="divide-y divide-gray-200">';

    foreach ($dados as $registro) {
        $turma = trim($registro['serie'] . ' ' . $registro['turma']);
        $html .= '<tr class="hover:bg-gray-50/80 transition duration-150 linha-registro">';
        $html .= '<td class="px-6 py-4 font-semibold text-gray-900 coluna-nome">' . htmlspecialchars($registro['nome'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td class="px-6 py-4 font-medium text-gray-600 coluna-matricula">' . htmlspecialchars($registro['matricula'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-800 border border-green-200">' . htmlspecialchars($turma, ENT_QUOTES, 'UTF-8') . '</span></td>';
        $html .= '<td class="px-6 py-4 text-gray-600 font-medium">' . date('d/m/Y', strtotime($registro['data_saida'])) . '</td>';
        $html .= '<td class="px-6 py-4 text-gray-600 font-medium">' . date('H:i', strtotime($registro['hora_saida'])) . '</td>';
        
        // Renderização dinâmica dos botões de ação para cada registro
        $html .= '<td class="px-6 py-4 text-center whitespace-nowrap">';
        $html .= '<div class="inline-flex items-center gap-2">';
        $html .= '<a href="../registros/editar_saida_estagio.php?id=' . $registro['id'] . '" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition shadow-sm" title="Editar Registro">';
        $html .= '<i class="fas fa-pencil-alt text-xs"></i>';
        $html .= '</a>';
        
        // CORREÇÃO AQUI: Alterado de tabela=registro_estagio para tabela=registro_saida_estagio
        $html .= '<a href="../../controllers/controller_alunos.php?action=apagar_registro&id=' . $registro['id'] . '&tabela=registro_saida_estagio&origem=relatorio_diario_estagio.php" ';
        $html .= 'onclick="return confirm(\'Atenção!\nVocê tem certeza que deseja deletar permanentemente este registro de estágio?\')" ';
        $html .= 'class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition shadow-sm" title="Apagar Registro">';
        $html .= '<i class="fas fa-trash-alt text-xs"></i>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</td>';
        
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';
    return $html;
}

$extraHead = '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .gradient-text { background: linear-gradient(45deg, #008C45, #3CB371); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .report-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .report-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08); }
    </style>
';

// Renderiza o cabeçalho global do sistema
renderHead('Relatório de Saídas-Estágio - Salaberga', $extraHead);
?>

<div class="lg:pl-72">
    <?php 
        // Renderiza a barra lateral nativa do sistema
        renderSidebar('relatorios');
    ?>

    <div class="min-h-screen pt-12 px-4 sm:px-6 lg:px-8">
        <main class="max-w-6xl mx-auto py-6 w-full flex-1 space-y-8">
            
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'apagado'): ?>
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-2 text-sm font-semibold shadow-sm">
                    <i class="fas fa-check-circle text-green-600 text-base"></i> Registro de estágio removido com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'editado'): ?>
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-2 text-sm font-semibold shadow-sm">
                    <i class="fas fa-check-circle text-green-600 text-base"></i> Alterações de estágio salvas com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'apagar'): ?>
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-2 text-sm font-semibold shadow-sm">
                    <i class="fas fa-exclamation-circle text-red-600 text-base"></i> Ocorreu um erro ao apagar o registro. Contate o administrador.
                </div>
            <?php endif; ?>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight"><span class="gradient-text">Saídas para Estágio</span></h1>
                    <p class="text-sm text-gray-500 mt-1">Registros em tempo real agrupados por categoria técnica</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto self-stretch md:self-auto">
                    <div class="relative w-full sm:w-64">
                        <i class="fas fa-search absolute left-3.5 top-3 text-sm text-gray-400"></i>
                        <input 
                            type="text" 
                            id="buscaGlobalEstagio" 
                            placeholder="Buscar por aluno ou matrícula..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm transition"
                        >
                    </div>
                    
                    <a href="../relatorio.php" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 border border-gray-200 rounded-xl text-sm font-semibold transition shadow-sm w-full sm:w-auto justify-center">
                        <i class="fas fa-arrow-left text-xs"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
                <div class="report-card rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">3A - Enfermagem</p>
                            <p class="mt-2 text-3xl font-extrabold text-gray-900"><?= count($saidasEnfermagem) ?></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">Enfermagem</span>
                    </div>
                </div>
                
                <div class="report-card rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">3B - Informática</p>
                            <p class="mt-2 text-3xl font-extrabold text-gray-900"><?= count($saidasInformatica) ?></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">Informática</span>
                    </div>
                </div>

                <div class="report-card rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">3C - Admin / Meio</p>
                            <p class="mt-2 text-3xl font-extrabold text-gray-900"><?= count($saidasAdministracaoMeio) ?></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Admin / Meio</span>
                    </div>
                </div>

                <div class="report-card rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">3D - Edificação</p>
                            <p class="mt-2 text-3xl font-extrabold text-gray-900"><?= count($saidasEdificacao) ?></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">Edificação</span>
                    </div>
                </div>
            </div>

            <section class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-user-nurse text-purple-600"></i> 3A - Enfermagem
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Histórico de saídas de alunos associados ao estágio de saúde</p>
                        </div>
                    </div>
                    <?= renderTable($saidasEnfermagem) ?>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-laptop-code text-blue-600"></i> 3B - Informática
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Histórico de saídas de alunos associados ao estágio de tecnologia</p>
                        </div>
                    </div>
                    <?= renderTable($saidasInformatica) ?>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-chart-pie text-emerald-600"></i> 3C - Administração / Meio Ambiente
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Histórico de saídas voltadas a gestão corporativa e sustentabilidade</p>
                        </div>
                    </div>
                    <?= renderTable($saidasAdministracaoMeio) ?>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-compass-drafting text-slate-600"></i> 3D - Edificação
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Histórico de saídas vinculadas à área de infraestrutura e construção civil</p>
                        </div>
                    </div>
                    <?= renderTable($saidasEdificacao) ?>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputBusca = document.getElementById("buscaGlobalEstagio");

    if (inputBusca) {
        inputBusca.addEventListener("keyup", function () {
            const termo = inputBusca.value.toLowerCase().trim();
            const linhas = document.querySelectorAll(".linha-registro");

            linhas.forEach(linha => {
                const nomeAluno = linha.querySelector(".coluna-nome").textContent.toLowerCase();
                const matriculaAluno = linha.querySelector(".coluna-matricula").textContent.toLowerCase();

                if (nomeAluno.includes(termo) || matriculaAluno.includes(termo)) {
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