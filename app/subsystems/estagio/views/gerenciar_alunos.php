<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../models/select_model.php');
require_once('../models/sessions.php');
$select_model = new select_model();
$session = new sessions;
$session->autenticar_session();

if (isset($_POST['layout'])) {
    $session->quebra_session();
}

// Detectar modal aberto via GET
$modal = isset($_GET['modal']) ? $_GET['modal'] : '';
$editId = isset($_GET['id']) ? intval($_GET['id']) : null;
$editAluno = null;
$verAluno = null;
if (($modal === 'editar' || $modal === 'ver') && $editId) {
    // Buscar dados do aluno para edição ou visualização
    $alunos_para_modal = $select_model->alunos_aptos_curso(); // Fetch all students for modal data
    foreach ($alunos_para_modal as $a) {
        if ($a['id'] == $editId) {
            if ($modal === 'editar') {
                $editAluno = $a;
            } else {
                $verAluno = $a;
            }
            break;
        }
    }
}

// Obter parâmetros de filtro e busca da URL
$search_term_php = isset($_GET['search']) ? $_GET['search'] : '';
$perfil_filtro_php = isset($_GET['perfil']) ? $_GET['perfil'] : '';

// Buscar dados dos alunos com filtros aplicados pelo PHP
$dados_alunos_filtrados = $select_model->alunos_aptos_curso($perfil_filtro_php, $search_term_php);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="description" content="Gerenciamento de Alunos - Sistema de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Gerenciar Alunos - Sistema de Estágio</title>

    <script>
        // Funções globais
        function abrirAdicionarAluno() {
            console.log('Opening add student modal');
            const modal = document.getElementById('adicionarAlunoModal');
            if (!modal) {
                console.error('Add student modal not found');
                return;
            }
            
            const form = document.getElementById('addAlunoForm');
            if (form) {
                console.log('Resetting form');
                form.reset();
            }
            
            console.log('Adding show class to modal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function fecharModais() {
            console.log('Closing all modals');
            document.querySelectorAll('.modal-base').forEach(modal => {
                modal.classList.remove('show');
            });
            document.body.style.overflow = '';
            alunoIdParaExcluir = null;
        }

        // Dados dos alunos (agora carregados via PHP com filtros)
        const alunos = <?php echo json_encode($dados_alunos_filtrados); ?>;

        console.log('Dados dos alunos carregados:', alunos);

        // Variável global para controle de exclusão
        let alunoIdParaExcluir = null;

        // Função para ver detalhes do aluno
        function verDetalhes(id) {
            console.log('Viewing details for ID:', id);
            const aluno = alunos.find(a => a.id === parseInt(id));
            if (aluno) {
                const detalhesContent = document.getElementById('detalhesContent');

                // Helper para determinar classe e ícone para o modal de detalhes
                const getAreaHtmlDetalhes = (area) => {
                    if (!area || area === '-') return '<span class="text-gray-500">-</span>';
                    const lowerArea = area.toLowerCase();
                    let areaClass = '';
                    let iconClass = '';

                    if (lowerArea.includes('desenvolvimento')) { areaClass = 'area-desenvolvimento'; iconClass = 'fas fa-code'; }
                    else if (lowerArea.includes('design') || lowerArea.includes('midia')) { areaClass = 'area-design'; iconClass = 'fas fa-paint-brush'; }
                    else if (lowerArea.includes('tutoria')) { areaClass = 'area-tutoria'; iconClass = 'fas fa-chalkboard-teacher'; }
                    else if (lowerArea.includes('suporte') || lowerArea.includes('redes')) { areaClass = 'area-redes'; iconClass = 'fas fa-network-wired'; }
                    else { areaClass = ''; iconClass = 'fas fa-question'; }

                    return `<span class="detail-value status-pill ${areaClass}"><i class="${iconClass} text-xs mr-1"></i>${area}</span>`;
                };

                detalhesContent.innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">ID</span>
                        <span class="detail-value">${aluno.id}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nome</span>
                        <span class="detail-value font-medium">${aluno.nome}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Score</span>
                        <span class="detail-value">${aluno.score || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Contato</span>
                        <span class="detail-value">${aluno.contato}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Médias</span>
                        <span class="detail-value">${aluno.medias}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">${aluno.email}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Projetos</span>
                        <span class="detail-value">${aluno.projetos || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Opção 1</span>
                        ${getAreaHtmlDetalhes(aluno.perfil_opc1)}
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Opção 2</span>
                        ${getAreaHtmlDetalhes(aluno.perfil_opc2)}
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ocorrência</span>
                        <span class="detail-value">${aluno.ocorrencia}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Custeio</span>
                        <span class="detail-value">${aluno.custeio == 1 ? 'Sim' : 'Não'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Entregas Individuais</span>
                        <span class="detail-value">${aluno.entregas_individuais}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Entregas do Grupo</span>
                        <span class="detail-value">${aluno.entregas_grupo}</span>
                    </div>
                `;

                const modal = document.getElementById('detalhesModal');
                modal.classList.add('show');
            } else {
                console.error(`Aluno com ID ${id} não encontrado.`);
            }
        }

        // Função para editar aluno
        function editarAluno(id) {
            console.log('Editing student with ID:', id);
            const aluno = alunos.find(a => a.id === parseInt(id));
            if (aluno) {
                document.getElementById('editAlunoId').value = aluno.id;
                document.getElementById('editNome').value = aluno.nome;
                document.getElementById('editContato').value = aluno.contato === '-' ? '' : aluno.contato;
                document.getElementById('editMedias').value = aluno.medias === '-' ? '' : aluno.medias;
                document.getElementById('editEmail').value = aluno.email === '-' ? '' : aluno.email;
                document.getElementById('editProjetos').value = aluno.projetos === '-' ? '' : aluno.projetos;
                document.getElementById('editPerfilOpc1').value = aluno.perfil_opc1;
                document.getElementById('editPerfilOpc2').value = aluno.perfil_opc2;
                document.getElementById('editOcorrencia').value = aluno.ocorrencia === '-' ? '' : aluno.ocorrencia;
                document.getElementById('editCusteio').value = aluno.custeio.toString();
                document.getElementById('editEntregasIndividuais').value = aluno.entregas_individuais === '-' ? '' : aluno.entregas_individuais;
                document.getElementById('editEntregasGrupo').value = aluno.entregas_grupo === '-' ? '' : aluno.entregas_grupo;

                const modal = document.getElementById('editarAlunoModal');
                modal.classList.add('show');
            } else {
                console.error(`Aluno com ID ${id} não encontrado.`);
            }
        }

        // Função para confirmar exclusão
        function confirmarExclusao(id) {
            console.log('Confirming deletion for ID:', id);
            alunoIdParaExcluir = id;
            document.getElementById('confirmacaoExclusaoModal').classList.add('show');
        }

        // Função para efetivar a exclusão
        function efetivarExclusaoAluno() {
            console.log('Effecting deletion for ID:', alunoIdParaExcluir);
            if (alunoIdParaExcluir) {
                window.location.href = `../controllers/controller_editar_excluir.php?id_aluno=${alunoIdParaExcluir}`;
            }
        }

        // Função para adicionar event listeners aos botões
        function adicionarEventListeners() {
            console.log('Adding event listeners to buttons');
            
            // Event listeners para os botões de ação na tabela desktop
            document.querySelectorAll('.ver-detalhes-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    console.log('View details clicked for ID:', id);
                    verDetalhes(id);
                });
            });

            document.querySelectorAll('.editar-aluno-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    console.log('Edit clicked for ID:', id);
                    editarAluno(id);
                });
            });

            document.querySelectorAll('.deletar-aluno-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    console.log('Delete clicked for ID:', id);
                    confirmarExclusao(id);
                });
            });
        }

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready, initializing...');
            
            // Add event listener for the "Add Student" button
            const addButton = document.getElementById('addAlunoBtn');
            if (addButton) {
                console.log('Add button found, adding listener');
                addButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Add button clicked');
                    abrirAdicionarAluno();
                });
            } else {
                console.error('Add button not found');
            }

            // Initialize table and add event listeners
            if (alunos && alunos.length > 0) {
                console.log('Initializing tables with', alunos.length, 'students');
                renderizarTabelaDesktop();
                renderizarCardsMobile();
            } else {
                console.log('No students found in data');
            }
            
            // Add event listeners for all close buttons
            document.querySelectorAll('.close-modal, #fecharAdicionarBtn, #cancelarExclusaoBtn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Close button clicked');
                    fecharModais();
                });
            });

            // Add event listeners for modal background clicks
            document.querySelectorAll('.modal-base').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        console.log('Modal background clicked');
                        fecharModais();
                    }
                });
            });

            // Add ESC key listener
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    console.log('ESC key pressed');
                    fecharModais();
                }
            });

            // Initialize other event listeners
            adicionarEventListeners();
        });

        // Expose functions to global scope
        window.abrirAdicionarAluno = abrirAdicionarAluno;
        window.fecharModais = fecharModais;
        window.verDetalhes = verDetalhes;
        window.editarAluno = editarAluno;
        window.confirmarExclusao = confirmarExclusao;
        window.efetivarExclusaoAluno = efetivarExclusaoAluno;

        // Função para aplicar filtros (considera busca e perfil)
        function aplicarFiltros() {
            const searchTerm = document.getElementById('searchAluno').value.toLowerCase().trim();
            const perfilFiltro = document.getElementById('filterPerfil').value.toLowerCase();

            const urlParams = new URLSearchParams();
            if (searchTerm) {
                urlParams.set('search', searchTerm);
            }
            if (perfilFiltro) {
                urlParams.set('perfil', perfilFiltro);
            }

            // Construir a nova URL e navegar
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        }

        // Adicionar event listeners para os filtros e busca
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado, inicializando...');

            const searchInput = document.getElementById('searchAluno');
            const filterPerfil = document.getElementById('filterPerfil');

            // Preencher campos de busca e filtro com valores da URL (ao carregar a página)
            const urlParams = new URLSearchParams(window.location.search);
            if (searchInput) {
                searchInput.value = urlParams.get('search') || '';
                 searchInput.addEventListener('input', aplicarFiltros);
            } else {
                console.error('Elemento de busca não encontrado');
            }

            if (filterPerfil) {
                filterPerfil.value = urlParams.get('perfil') || '';
                 filterPerfil.addEventListener('change', aplicarFiltros);
            } else {
                console.error('Elemento de filtro de perfil não encontrado');
            }

            // Não chame aplicarFiltros() aqui, pois o PHP já carregou os dados filtrados inicialmente
            // aplicarFiltros(); 

            // Inicializar outros event listeners
            adicionarEventListeners();
        });

    </script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#007A33',
                            '50': '#00FF6B',
                            '100': '#00EB61',
                            '200': '#00C250',
                            '300': '#00993F',
                            '400': '#00802F',
                            '500': '#007A33',
                            '600': '#00661F',
                            '700': '#00521A',
                            '800': '#003D15',
                            '900': '#002910'
                        },
                        secondary: {
                            DEFAULT: '#FFA500',
                            '50': '#FFE9C0',
                            '100': '#FFE1AB',
                            '200': '#FFD183',
                            '300': '#FFC15A',
                            '400': '#FFB232',
                            '500': '#FFA500',
                            '600': '#C78000',
                            '700': '#8F5C00',
                            '800': '#573800',
                            '900': '#1F1400'
                        },
                        dark: {
                            DEFAULT: '#1a1a1a',
                            '50': '#2d2d2d',
                            '100': '#272727',
                            '200': '#232323',
                            '300': '#1f1f1f',
                            '400': '#1a1a1a',
                            '500': '#171717',
                            '600': '#141414',
                            '700': '#111111',
                            '800': '#0e0e0e',
                            '900': '#0a0a0a'
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.36)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px RGBA(0, 0, 0, 0.2)'
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(0, 122, 51, 0.03) 0%, rgba(0, 122, 51, 0) 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 165, 0, 0.03) 0%, rgba(255, 165, 0, 0) 20%);
            transition: all 0.3s ease;
        }

        .sidebar {
            background-color: rgba(45, 45, 45, 0.95);
            background-image: linear-gradient(to bottom, #2d2d2d, #222222);
            border-right: 1px solid rgba(0, 122, 51, 0.2);
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            color: #ffffff;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 122, 51, 0.2);
            color: #00C250;
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background-color: rgba(0, 122, 51, 0.3);
            color: #00FF6B;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 122, 51, 0.15);
        }

        .dashboard-card,
        .table-container {
            background-color: #2d2d2d;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .dashboard-card:hover,
        .table-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 122, 51, 0.2);
        }

        thead {
            background: rgba(35, 35, 35, 0.95);
            backdrop-filter: blur(5px);
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background-color: rgba(0, 122, 51, 0.1);
        }

        .status-pill,
        .mobile-badge {
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.3s ease;
            /* Adicionado para padronizar a largura */
            min-width: 140px; /* Ajuste este valor conforme necessário */
            justify-content: center; /* Centraliza o conteúdo */
        }

        .status-ativo {
            background: linear-gradient(135deg, rgba(0, 194, 80, 0.2) 0%, rgba(0, 122, 51, 0.2) 100%);
            color: #00FF6B;
            border: 1px solid rgba(0, 194, 80, 0.3);
        }

        .status-inativo {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(153, 27, 27, 0.2) 100%);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-estagiando {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .area-desenvolvimento {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(29, 78, 216, 0.2) 100%);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
            width: 170px;
        }

        .area-tutoria {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%); /* Usando estilo de midia para tutoria */
            color: #6ee7b7; /* Usando estilo de midia para tutoria */
            /* Removendo a borda roxa */
            /* Adicionando uma borda escura */
            border: 1px solid rgba(5, 150, 105, 0.3);
            width: 170px;
        }

        .area-design {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(126, 34, 206, 0.2) 100%);
            color: #c4b5fd;
            border: 1px solid rgba(168, 85, 247, 0.3);
            width: 170px;
        }

        .area-midia {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
            width: 170px;
        }

        .area-redes {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
            width: 170px;
        }

        input,
        select,
        textarea {
            background-color: #232323 !important;
            border-color: #3d3d3d !important;
            color: #ffffff !important;
            padding: 0.5rem 1rem !important;
            width: 100% !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            min-width: 180px !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
            border-radius: 0.375rem !important;
        }

        .custom-input {
            background-color: rgba(35, 35, 35, 0.8) !important;
            border: 2px solid rgba(61, 61, 61, 0.8) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            padding: 0.75rem 2.5rem 0.75rem 1rem !important;
            width: 100% !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            min-width: 180px !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
        }

        select.custom-input {
            min-width: 180px !important;
            max-width: 100% !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
        }

        .relative select.custom-input {
            padding-right: 2.5rem !important;
        }

        .relative {
            min-width: 180px;
        }

        @media (max-width: 640px) {

            .custom-input,
            select.custom-input {
                min-width: 100% !important;
                font-size: 1rem !important;
            }

            .relative {
                min-width: 100%;
            }
        }

        .custom-input:focus {
            border-color: #00C250 !important;
            box-shadow: 0 0 0 2px rgba(0, 194, 80, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            background-color: rgba(40, 40, 40, 0.9) !important;
        }


        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .search-input-container {
            position: relative;
            transition: all 0.3s ease;
        }

        .search-input-container:focus-within {
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .search-input-container:focus-within .search-icon {
            color: #00C250;
        }

        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .custom-btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #009940 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 122, 51, 0.3);
        }

        .custom-btn-primary:hover {
            background: linear-gradient(135deg, #00993F 0%, #00B64B 100%);
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 122, 51, 0.4);
        }

        .custom-btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .custom-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .custom-btn:hover::before {
            transform: translateX(100%);
        }

        .btn-icon {
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .custom-btn:hover .btn-icon {
            transform: translateX(3px);
            opacity: 1;
        }

        .action-bar {
            background-color: rgba(45, 45, 45, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .candidatura-modal {
            background: linear-gradient(135deg, rgba(49, 49, 49, 0.95) 0%, rgba(37, 37, 37, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            z-index: 1000;
        }

        .modal-show {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #3d3d3d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #007A33;
        }

        .mobile-card {
            background: rgba(45, 45, 45, 0.9);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 122, 51, 0.2);
            transition: transform 0.3s ease;
        }

        .mobile-card:hover {
            transform: translateY(-4px);
        }

        .mobile-card-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mobile-card-item:last-child {
            border-bottom: none;
        }

        .mobile-card-label {
            font-weight: 500;
            color: #a0aec0;
            font-size: 0.9rem;
        }

        .mobile-card-value {
            font-weight: 400;
            color: #ffffff;
            font-size: 0.9rem;
            text-align: right;
            flex: 1;
            margin-left: 1rem;
        }

        .mobile-card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .mobile-card-actions button {
            flex: 1;
            padding: 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .mobile-card-actions button.info-btn {
            color: #93c5fd;
        }

        .mobile-card-actions button.edit-btn {
            color: #00FF6B;
        }

        .mobile-card-actions button.delete-btn {
            color: #f87171;
        }

        .mobile-card-actions button:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .table-container.desktop-table th,
        .table-container.desktop-table td {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .table-container.desktop-table .action-icons i {
            font-size: 1.25rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.4s ease-out forwards;
        }

        @media (max-width: 768px) {
            .table-container.desktop-table {
                display: none;
            }

            .mobile-cards-container {
                display: block;
            }

            .mobile-cards-container {
                padding: 0 0.5rem;
            }

            .action-bar {
                padding: 1rem;
            }

            .search-input-container {
                width: 100%;
            }

            .custom-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
        }

        @media (min-width: 769px) {
            .mobile-cards-container {
                display: none;
            }

            .table-container.desktop-table {
                display: block;
            }
        }

        /* Estilos para os modais */
        .modal-base {
            display: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 50;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-base.show {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .modal-content {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 32rem;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-base.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.2s ease;
        }

        .close-modal:hover {
            color: #ffffff;
        }

        /* Ajustes específicos para cada tipo de modal */
        .modal-content.detalhes {
            max-width: 36rem;
        }

        .modal-content.editar {
            max-width: 40rem;
        }

        .modal-content.adicionar {
            max-width: 40rem;
        }

        .modal-content.excluir {
            max-width: 28rem;
        }

        /* Estilização da barra de rolagem do modal */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .mobile-card .custom-btn-primary {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin-top: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        /* Estilos para botões */
        .custom-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .custom-btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .custom-btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .custom-btn-secondary {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            color: white;
        }

        .custom-btn-secondary:hover {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        }

        .custom-btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .custom-btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .btn-icon {
            font-size: 0.875rem;
        }

        /* Botões personalizados */
        .custom-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .custom-btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #009940 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 122, 51, 0.3);
        }

        .custom-btn-primary:hover {
            background: linear-gradient(135deg, #00993F 0%, #00B64B 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 122, 51, 0.4);
        }

        .custom-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Ajustes para os modais */
        .modal-content {
            max-height: 85vh;
            overflow-y: auto;
        }
        
        /* Grid para formulários */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Ajustes para campos de formulário */
        .form-field {
            margin-bottom: 0.75rem;
        }
        
        .form-field label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        
        /* Ajustes para o modal de detalhes */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        @media (max-width: 640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Ajustes específicos para o modal de detalhes */
        .modal-content.detalhes {
            max-width: 36rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #9ca3af;
            font-weight: 500;
        }

        .detail-value {
            font-size: 1rem;
            color: #ffffff;
            word-break: break-word;
        }

        .detail-value.status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        @media (max-width: 640px) {
            .modal-content.detalhes {
                max-width: 100%;
                margin: 1rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .detail-item {
                padding: 0.75rem;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 0.5rem;
            }

            .detail-label {
                font-size: 0.75rem;
            }

            .detail-value {
                font-size: 0.875rem;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .modal-content.detalhes {
                max-width: 90%;
            }

            .details-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }
    </style>
</head>

<body class="select-none">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-64 hidden md:block">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                        <div class="h-0.5 bg-primary-500/20 rounded-full mt-1"></div>
                    </div>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link active">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div class="md:hidden fixed top-4 left-4 z-50">
            <button id="sidebarToggle" class="bg-dark-50 p-2 rounded-lg shadow-md hover:bg-dark-100 transition-all">
                <i class="fas fa-bars text-primary-400"></i>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden sidebar">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                    </div>
                    <button id="closeSidebar" class="p-2 text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link active">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-dark-400">
            <!-- Header -->
            <header class="bg-dark-50 shadow-md sticky top-0 z-30 border-b border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <h1 class="text-xl font-bold text-white text-center sm:text-left w-full">Gerenciamento de Alunos</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400">
                            <i class="fas fa-user-circle mr-1"></i> Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Gerenciar Alunos</span>
                </div>

                <!-- Actions Bar -->
                <div class="mb-8 action-bar p-4 sm:p-5 fade-in">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <a href="?modal=adicionar" class="custom-btn custom-btn-primary" id="addAlunoBtn">
                            <i class="fas fa-plus btn-icon"></i>
                            <span>Adicionar Aluno</span>
                        </a>
                        <div class="search-input-container relative w-full sm:w-64">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="searchAluno" placeholder="Buscar aluno..." class="custom-input pl-10 pr-4 py-2.5 w-full">
                        </div>
                         <!-- Novo filtro de perfil -->
                         <div class="relative w-full sm:w-48">
                             <select id="filterPerfil" class="custom-input pl-4 pr-10 py-2.5 appearance-none w-full">
                                 <option value="">Todos os perfis</option>
                                 <option value="desenvolvimento">Desenvolvimento</option>
                                 <option value="design">Design/Mídias</option>
                                 <option value="tutoria">Tutoria</option>
                                 <option value="suporte/redes">Suporte/Redes</option>
                             </select>
                             <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                         </div>
                    </div>
                </div>
                <!-- Table Desktop -->
                <div class="table-container desktop-table overflow-x-auto slide-up">
                    <table class="min-w-full divide-y divide-gray-700 text-sm">
                        <thead>
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nome</th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Área 1</th> <!-- Adicionada coluna para Área 1 -->
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Área 2</th> <!-- Adicionada coluna para Área 2 -->
                                <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="alunosTableBody">
                            <?php
                            // Helper para determinar classe e ícone (duplicação intencional para clareza)
                            $getAreaHtmlPHP = function($area) {
                                if (!$area || $area === '') return '<span class="text-gray-500">-</span>';
                                $lowerArea = strtolower($area);
                                $areaClass = '';
                                $iconClass = '';

                                if (strpos($lowerArea, 'desenvolvimento') !== false) {
                                    $areaClass = 'area-desenvolvimento';
                                    $iconClass = 'fas fa-code';
                                } else if (strpos($lowerArea, 'design') !== false || strpos($lowerArea, 'midia') !== false) {
                                    $areaClass = 'area-design';
                                    $iconClass = 'fas fa-paint-brush';
                                } else if (strpos($lowerArea, 'tutoria') !== false) {
                                    $areaClass = 'area-tutoria';
                                    $iconClass = 'fas fa-chalkboard-teacher';
                                } else if (strpos($lowerArea, 'suporte') !== false || strpos($lowerArea, 'redes') !== false) {
                                    $areaClass = 'area-redes';
                                    $iconClass = 'fas fa-network-wired';
                                } else {
                                    $areaClass = '';
                                    $iconClass = 'fas fa-question';
                                }

                                // Garantir que a classe status-pill seja aplicada aqui
                                return '<span class="status-pill ' . $areaClass . '">' . ('' !== $iconClass ? '<i class="' . $iconClass . ' text-xs mr-1"></i>' : '') . htmlspecialchars($area) . '</span>';
                            };

                            foreach ($dados_alunos_filtrados as $index => $dado) {
                                $area1Html = $getAreaHtmlPHP($dado['perfil_opc1'] ?? '');
                                $area2Html = $getAreaHtmlPHP($dado['perfil_opc2'] ?? '');
                                $statusClass = $dado['custeio'] == 1 ? 'status-ativo' : (strtolower($dado['ocorrencia']) === 'estagiando' ? 'status-estagiando' : 'status-inativo');
                            ?>
                                <tr class="hover:bg-dark-50 transition-colors slide-up" style="animation-delay: <?= $index * 50 ?>ms;">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-white"><?= htmlspecialchars($dado['nome']) ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-300 text-center"><?= $area1Html ?></td> <!-- Nova coluna Área 1 centralizada -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-300 text-center"><?= $area2Html ?></td> <!-- Nova coluna Área 2 centralizada -->
                                    <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium action-icons">
                                        <button type="button" class="ver-detalhes-btn text-blue-400 hover:text-blue-300 mr-2 transition-colors" data-id="<?= $dado['id'] ?>">
                                            <a href="?modal=ver&id=<?= $dado['id'] ?>" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </button>
                                        <a href="?modal=editar&id=<?= $dado['id'] ?>" class="editar-aluno-btn text-primary-400 hover:text-primary-300 mr-2 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?modal=excluir&id=<?= $dado['id'] ?>" class="deletar-aluno-btn text-red-400 hover:text-red-300 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="detalhes-<?= $dado['id'] ?>" class="hidden bg-dark-50">
                                    <td colspan="2" class="px-6 py-4">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div>
                                                <span class="text-gray-400 text-sm">Contato:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['contato'] ?: '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Médias:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['medias'] ?: '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Email:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['email'] ?: '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Projetos:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['projetos'] ?: '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Opção 2:</span>
                                                <p class="text-white">
                                                    <span class="status-pill <?= $areaClassOpc2 ?>">
                                                        <i class="fas fa-<?= $dado['perfil_opc2'] === 'desenvolvimento' ? 'code' : ($dado['perfil_opc2'] === 'design' ? 'paint-brush' : ($dado['perfil_opc2'] === 'midia' ? 'video' : 'network-wired')) ?> text-xs mr-1"></i>
                                                        <?= htmlspecialchars($dado['perfil_opc2']) ?>
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Ocorrência:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['ocorrencia'] ?: '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Custeio:</span>
                                                <p class="text-white"><?= $dado['custeio'] == 1 ? 'Sim' : 'Não' ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Entregas Individuais:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['entregas_individuais'] ?? '-') ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-400 text-sm">Entregas do Grupo:</span>
                                                <p class="text-white"><?= htmlspecialchars($dado['entregas_grupo'] ?? '-') ?></p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cards Mobile -->
                <div id="alunosMobileCards" class="mobile-cards-container space-y-4">
                    <?php
                    foreach ($dados_alunos_filtrados as $dado) {
                        // Helper para determinar classe e ícone (duplicação intencional para clareza entre renderizadores)
                        $getAreaHtmlPHP = function($area) {
                            if (!$area || $area === '') return '<span class="text-gray-500">-</span>';
                            $lowerArea = strtolower($area); // Use a função PHP strtolower
                            $areaClass = '';
                            $iconClass = '';

                            // Use a função PHP strpos para verificar a existência da substring
                            if (strpos($lowerArea, 'desenvolvimento') !== false) {
                                $areaClass = 'area-desenvolvimento';
                                $iconClass = 'fas fa-code';
                            } else if (strpos($lowerArea, 'design') !== false || strpos($lowerArea, 'midia') !== false) {
                                $areaClass = 'area-design';
                                $iconClass = 'fas fa-paint-brush';
                            } else if (strpos($lowerArea, 'tutoria') !== false) {
                                $areaClass = 'area-tutoria';
                                $iconClass = 'fas fa-chalkboard-teacher';
                            } else if (strpos($lowerArea, 'suporte') !== false || strpos($lowerArea, 'redes') !== false) {
                                $areaClass = 'area-redes';
                                $iconClass = 'fas fa-network-wired';
                            } else {
                                $areaClass = '';
                                $iconClass = 'fas fa-question';
                            }

                            // Garantir que a classe status-pill seja aplicada aqui
                            return '<span class="status-pill ' . $areaClass . '">' . ('' !== $iconClass ? '<i class="' . $iconClass . ' text-xs mr-1"></i>' : '') . htmlspecialchars($area) . '</span>';
                        };

                        $area1Html = $getAreaHtmlPHP($dado['perfil_opc1'] ?? '');
                        $area2Html = $getAreaHtmlPHP($dado['perfil_opc2'] ?? '');
                        $statusClass = $dado['custeio'] == 1 ? 'status-ativo' : (strtolower($dado['ocorrencia']) === 'estagiando' ? 'status-estagiando' : 'status-inativo');
                    ?>
                        <div class="mobile-card bg-dark-300 rounded-lg p-4 shadow-md">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-white"><?= htmlspecialchars($dado['nome']) ?></h3>
                                <div class="flex items-center gap-2">
                                    <button class="ver-detalhes-btn text-blue-400 hover:text-blue-300 transition-colors" data-id="<?= $dado['id'] ?>">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="editar-aluno-btn text-green-400 hover:text-green-300 transition-colors" data-id="<?= $dado['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="deletar-aluno-btn text-red-400 hover:text-red-300 transition-colors" data-id="<?= $dado['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Exibir opções de perfil diretamente no card mobile -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?= $area1Html ?>
                                <?= $area2Html ?>
                            </div>
                            <div id="detalhes-mobile-<?= $dado['id'] ?>" class="hidden space-y-3 mt-4 pt-4 border-t border-gray-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mobile-card-item">
                                        <span class="mobile-card-label">Contato:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['contato'] ?: '-') ?></span>
                                    </div>
                                     <div class="mobile-card-item">
                                        <span class="mobile-card-label">Médias:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['medias'] ?: '-') ?></span>
                                    </div>
                                    <div class="mobile-card-item">
                                        <span class="mobile-card-label">Email:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['email'] ?: '-') ?></span>
                                    </div>
                                    <div class="mobile-card-item">
                                        <span class="mobile-card-label">Projetos:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['projetos'] ?: '-') ?></span>
                                    </div>
                                    <!-- Opções de perfil removidas daqui, pois já são exibidas acima -->
                                     <div class="mobile-card-item">
                                        <span class="mobile-card-label">Ocorrência:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['ocorrencia'] ?: '-') ?></span>
                                    </div>
                                    <div class="mobile-card-item">
                                        <span class="mobile-card-label">Custeio:</span>
                                        <span class="mobile-card-value"><?= $dado['custeio'] == 1 ? 'Sim' : 'Não' ?></span>
                                    </div>
                                     <div class="mobile-card-item">
                                        <span class="mobile-card-label">Entregas Individuais:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['entregas_individuais'] ?? '-') ?></span>
                                    </div>
                                    <div class="mobile-card-item">
                                        <span class="mobile-card-label">Entregas do Grupo:</span>
                                        <span class="mobile-card-value"><?= htmlspecialchars($dado['entregas_grupo'] ?? '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </main>
        </div>

        <!-- Modal de Detalhes -->
        <div id="detalhesModal" class="modal-base">
            <div class="modal-content detalhes">
                <div class="modal-header">
                    <h2>Detalhes do Aluno</h2>
                    <button type="button" class="close-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detalhesContent" class="details-grid">
                        <!-- O conteúdo será preenchido dinamicamente via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-btn custom-btn-secondary close-modal">
                        <i class="fas fa-times"></i>
                        <span>Fechar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div id="editarAlunoModal" class="modal-base">
            <div class="modal-content editar">
                <div class="modal-header">
                    <h2>Editar Aluno</h2>
                    <button type="button" class="close-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAlunoForm" action="../controllers/controller_editar_excluir.php" method="post" class="space-y-4">
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" id="editAlunoId" name="id_aluno" value="<?= $editAluno['id'] ?? '' ?>">
                        
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="editNome" class="block text-sm font-medium text-gray-300">Nome</label>
                                <input type="text" id="editNome" name="nome" required class="custom-input" value="<?= htmlspecialchars($editAluno['nome'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editContato" class="block text-sm font-medium text-gray-300">Contato</label>
                                <input type="text" id="editContato" name="contato" class="custom-input" value="<?= htmlspecialchars($editAluno['contato'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editMedias" class="block text-sm font-medium text-gray-300">Médias</label>
                                <input type="number" id="editMedias" name="medias" min="0" max="10" step="0.1" required class="custom-input" value="<?= htmlspecialchars($editAluno['medias'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editEmail" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" id="editEmail" name="email" required class="custom-input" value="<?= htmlspecialchars($editAluno['email'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editProjetos" class="block text-sm font-medium text-gray-300">Projetos</label>
                                <input type="text" id="editProjetos" name="projetos" class="custom-input" value="<?= htmlspecialchars($editAluno['projetos'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editPerfilOpc1" class="block text-sm font-medium text-gray-300">Opção 1</label>
                                <select id="editPerfilOpc1" name="perfil_opc1" required class="custom-input">
                                    <option value="desenvolvimento" <?= (isset($editAluno['perfil_opc1']) && $editAluno['perfil_opc1'] == 'desenvolvimento') ? 'selected' : '' ?>>Desenvolvimento</option>
                                    <option value="design" <?= (isset($editAluno['perfil_opc1']) && $editAluno['perfil_opc1'] == 'design') ? 'selected' : '' ?>>Design/Mídias</option>
                                    <option value="tutoria" <?= (isset($editAluno['perfil_opc1']) && $editAluno['perfil_opc1'] == 'tutoria') ? 'selected' : '' ?>>Tutoria</option>
                                    <option value="suporte/redes" <?= (isset($editAluno['perfil_opc1']) && $editAluno['perfil_opc1'] == 'suporte/redes') ? 'selected' : '' ?>>Suporte/Redes</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="editPerfilOpc2" class="block text-sm font-medium text-gray-300">Opção 2</label>
                                <select id="editPerfilOpc2" name="perfil_opc2" required class="custom-input">
                                    <option value="desenvolvimento" <?= (isset($editAluno['perfil_opc2']) && $editAluno['perfil_opc2'] == 'desenvolvimento') ? 'selected' : '' ?>>Desenvolvimento</option>
                                    <option value="design" <?= (isset($editAluno['perfil_opc2']) && $editAluno['perfil_opc2'] == 'design') ? 'selected' : '' ?>>Design/Mídias</option>
                                    <option value="tutoria" <?= (isset($editAluno['perfil_opc2']) && $editAluno['perfil_opc2'] == 'tutoria') ? 'selected' : '' ?>>Tutoria</option>
                                    <option value="suporte/redes" <?= (isset($editAluno['perfil_opc2']) && $editAluno['perfil_opc2'] == 'suporte/redes') ? 'selected' : '' ?>>Suporte/Redes</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="editOcorrencia" class="block text-sm font-medium text-gray-300">Ocorrência</label>
                                <input type="text" id="editOcorrencia" name="ocorrencia" class="custom-input" value="<?= htmlspecialchars($editAluno['ocorrencia'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editCusteio" class="block text-sm font-medium text-gray-300">Custeio</label>
                                <select id="editCusteio" name="custeio" required class="custom-input">
                                    <option value="1" <?= (isset($editAluno['custeio']) && $editAluno['custeio'] == 1) ? 'selected' : '' ?>>Sim</option>
                                    <option value="0" <?= (isset($editAluno['custeio']) && $editAluno['custeio'] == 0) ? 'selected' : '' ?>>Não</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="editEntregasIndividuais" class="block text-sm font-medium text-gray-300">Entregas Individuais</label>
                                <input type="text" id="editEntregasIndividuais" name="entregas_individuais" class="custom-input" value="<?= htmlspecialchars($editAluno['entregas_individuais'] ?? '') ?>">
                            </div>
                            <div class="form-field">
                                <label for="editEntregasGrupo" class="block text-sm font-medium text-gray-300">Entregas do Grupo</label>
                                <input type="text" id="editEntregasGrupo" name="entregas_grupo" class="custom-input" value="<?= htmlspecialchars($editAluno['entregas_grupo'] ?? '') ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-btn custom-btn-secondary close-modal">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" form="editAlunoForm" class="custom-btn custom-btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Salvar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Adicionar Aluno -->
        <div id="adicionarAlunoModal" class="modal-base">
            <div class="modal-content adicionar">
                <div class="modal-header">
                    <h2>Adicionar Aluno</h2>
                    <button type="button" class="close-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAlunoForm" action="../controllers/controller_adicionar.php" method="post" class="space-y-4">
                        <input type="hidden" name="acao" value="adicionar">
                        
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="addNome" class="block text-sm font-medium text-gray-300">Nome</label>
                                <input type="text" id="addNome" name="nome" required class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addContato" class="block text-sm font-medium text-gray-300">Contato</label>
                                <input type="text" id="addContato" name="contato" class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addMedias" class="block text-sm font-medium text-gray-300">Médias</label>
                                <input type="number" id="addMedias" name="medias" min="0" max="10" step="0.1" required class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addEmail" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" id="addEmail" name="email" required class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addProjetos" class="block text-sm font-medium text-gray-300">Projetos</label>
                                <input type="text" id="addProjetos" name="projetos" class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addPerfilOpc1" class="block text-sm font-medium text-gray-300">Opção 1</label>
                                <select id="addPerfilOpc1" name="perfil_opc1" required class="custom-input">
                                    <option value="desenvolvimento">Desenvolvimento</option>
                                    <option value="design">Design/Mídias</option>
                                    <option value="tutoria">Tutoria</option>
                                    <option value="suporte/redes">Suporte/Redes</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="addPerfilOpc2" class="block text-sm font-medium text-gray-300">Opção 2</label>
                                <select id="addPerfilOpc2" name="perfil_opc2" required class="custom-input">
                                    <option value="desenvolvimento">Desenvolvimento</option>
                                    <option value="design">Design/Mídias</option>
                                    <option value="tutoria">Tutoria</option>
                                    <option value="suporte/redes">Suporte/Redes</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="addOcorrencia" class="block text-sm font-medium text-gray-300">Ocorrência</label>
                                <input type="text" id="addOcorrencia" name="ocorrencia" class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addCusteio" class="block text-sm font-medium text-gray-300">Custeio</label>
                                <select id="addCusteio" name="custeio" required class="custom-input">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="addEntregasIndividuais" class="block text-sm font-medium text-gray-300">Entregas Individuais</label>
                                <input type="text" id="addEntregasIndividuais" name="entregas_individuais" class="custom-input">
                            </div>
                            <div class="form-field">
                                <label for="addEntregasGrupo" class="block text-sm font-medium text-gray-300">Entregas do Grupo</label>
                                <input type="text" id="addEntregasGrupo" name="entregas_grupo" class="custom-input">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-btn custom-btn-secondary close-modal">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" form="addAlunoForm" class="custom-btn custom-btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Adicionar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div id="confirmacaoExclusaoModal" class="modal-base">
            <div class="modal-content excluir">
                <div class="modal-header">
                    <h2>Confirmar Exclusão</h2>
                    <button type="button" class="close-modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-gray-300">Tem certeza que deseja excluir este aluno? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="custom-btn custom-btn-secondary close-modal">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="button" class="custom-btn custom-btn-danger" onclick="efetivarExclusaoAluno()">
                        <i class="fas fa-trash"></i>
                        <span>Excluir</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Ver Dados -->
        <?php if ($modal === 'ver' && $verAluno): ?>
        <div id="verDadosModal" class="modal-base fixed inset-0 bg-black bg-opacity-60 z-50 show flex items-center justify-center animate-fadeIn">
            <div class="modal-content bg-gradient-to-br from-dark-400 via-dark-300 to-dark-600 rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6 border-2 border-primary-500 animate-slideUp relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-primary-400">Dados do Aluno</h2>
                    <a href="gerenciar_alunos.php" class="close-modal text-gray-400 hover:text-white transition-colors text-2xl">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="details-grid">
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">ID:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['id']) ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Nome:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['nome']) ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Score:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['score'] ?? '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Contato:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['contato'] ?: '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Médias:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['medias'] ?: '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Email:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['email'] ?: '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Projetos:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['projetos'] ?: '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Opção 1:</span>
                        <!-- Usar a helper PHP para exibir o perfil com estilo -->
                        <?= $getAreaHtmlPHP($verAluno['perfil_opc1'] ?? '') ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Opção 2:</span>
                        <!-- Usar a helper PHP para exibir o perfil com estilo -->
                        <?= $getAreaHtmlPHP($verAluno['perfil_opc2'] ?? '') ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Ocorrência:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['ocorrencia'] ?: '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Custeio:</span>
                        <span class="text-white"><?= isset($verAluno['custeio']) ? ($verAluno['custeio'] == 1 ? 'Sim' : 'Não') : '-' ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Entregas Individuais:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['entregas_individuais'] ?? '-') ?></span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold text-gray-300 text-sm">Entregas do Grupo:</span>
                        <span class="text-white"><?= htmlspecialchars($verAluno['entregas_grupo'] ?? '-') ?></span>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="gerenciar_alunos.php" class="custom-btn custom-btn-primary px-4 py-2 rounded-lg shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Fechar
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Variável global para controle de exclusão
        window.alunoIdParaExcluir = null;

        // Função para adicionar event listeners aos botões
        function adicionarEventListeners() {
            // Event listeners para os botões de ação na tabela desktop
            document.querySelectorAll('.ver-detalhes-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    window.verDetalhes(this.getAttribute('data-id'));
                });
            });

            document.querySelectorAll('.editar-aluno-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    window.editarAluno(this.getAttribute('data-id'));
                });
            });

            document.querySelectorAll('.deletar-aluno-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    window.confirmarExclusao(this.getAttribute('data-id'));
                });
            });

            // Event listeners para fechar modais
            document.querySelectorAll('.modal-base').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        window.fecharModais();
                    }
                });
            });

            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', window.fecharModais);
            });

            // Event listener para o botão de confirmar exclusão
            const confirmarExclusaoBtn = document.getElementById('confirmarExclusaoBtn');
            if (confirmarExclusaoBtn) {
                confirmarExclusaoBtn.addEventListener('click', window.efetivarExclusaoAluno);
            }

            // Event listener para o botão de cancelar exclusão
            const cancelarExclusaoBtn = document.getElementById('cancelarExclusaoBtn');
            if (cancelarExclusaoBtn) {
                cancelarExclusaoBtn.addEventListener('click', window.fecharModais);
            }

            // Tecla ESC para fechar modais
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.fecharModais();
                }
            });
        }

        // Função para renderizar a tabela de alunos (desktop)
        function renderizarTabelaDesktop(alunosFiltrados = alunos) {
            console.log('Rendering desktop table');
            const tbody = document.getElementById('alunosTableBody');
            if (!tbody) {
                console.error('Table body element not found');
                return;
            }

            tbody.innerHTML = '';

            if (!Array.isArray(alunosFiltrados) || alunosFiltrados.length === 0) {
                const tr = document.createElement('tr');
                // Atualizar colspan para 4, pois adicionamos 2 colunas
                tr.innerHTML = '<td colspan="4" class="px-3 py-4 text-center text-sm text-gray-400">Nenhum aluno encontrado</td>';
                tbody.appendChild(tr);
                return;
            }

            alunosFiltrados.forEach((aluno, index) => {
                if (!aluno || !aluno.nome) {
                    console.error('Invalid student data:', aluno);
                    return;
                }

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-dark-50 transition-colors slide-up';
                tr.style.animationDelay = `${index * 50}ms`;

                // Helper para determinar classe e ícone
                const getAreaHtml = (area) => {
                    if (!area || area === '-') return '<span class="text-gray-500">-</span>';
                    const lowerArea = area.toLowerCase();
                    let areaClass = '';
                    let iconClass = '';

                    if (lowerArea.includes('desenvolvimento')) { areaClass = 'area-desenvolvimento'; iconClass = 'fas fa-code'; }
                    else if (lowerArea.includes('design') || lowerArea.includes('midia')) { areaClass = 'area-design'; iconClass = 'fas fa-paint-brush'; }
                    else if (lowerArea.includes('tutoria')) { areaClass = 'area-tutoria'; iconClass = 'fas fa-chalkboard-teacher'; }
                    else if (lowerArea.includes('suporte') || lowerArea.includes('redes')) { areaClass = 'area-redes'; iconClass = 'fas fa-network-wired'; }
                    else { areaClass = ''; iconClass = 'fas fa-question'; }

                    // Garantir que a classe status-pill seja aplicada aqui
                    return `<span class="status-pill ${areaClass}"><i class="${iconClass} text-xs mr-1"></i>${area}</span>`;
                };

                const area1Html = getAreaHtml(aluno.perfil_opc1);
                const area2Html = getAreaHtml(aluno.perfil_opc2);

                tr.innerHTML = `
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-white">${aluno.nome}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-300 text-center">${area1Html}</td> <!-- Nova coluna Área 1 centralizada -->
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-300 text-center">${area2Html}</td> <!-- Nova coluna Área 2 centralizada -->
                    <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium action-icons">
                        <button type="button" class="ver-detalhes-btn text-blue-400 hover:text-blue-300 mr-2 transition-colors" data-id="${aluno.id}">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button type="button" class="editar-aluno-btn text-primary-400 hover:text-primary-300 mr-2 transition-colors" data-id="${aluno.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="deletar-aluno-btn text-red-400 hover:text-red-300 transition-colors" data-id="${aluno.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Re-add event listeners after rendering
            adicionarEventListeners();
        }

        // Função para renderizar os cards de alunos (mobile)
        function renderizarCardsMobile(alunosFiltrados = alunos) {
            console.log('Rendering mobile cards');
            const container = document.getElementById('alunosMobileCards');
            if (!container) {
                console.error('Mobile cards container not found');
                return;
            }
            container.innerHTML = '';

            if (!Array.isArray(alunosFiltrados) || alunosFiltrados.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-4">Nenhum aluno encontrado</div>';
                return;
            }

            alunosFiltrados.forEach(aluno => {
                const card = document.createElement('div');
                card.className = 'mobile-card bg-dark-300 rounded-lg p-4 shadow-md';

                // Helper para determinar classe e ícone (duplicação intencional para clareza entre renderizadores)
                const getAreaHtml = (area) => {
                    if (!area || area === '-') return '<span class="text-gray-500">-</span>';
                    const lowerArea = area.toLowerCase();
                    let areaClass = '';
                    let iconClass = '';

                    if (lowerArea.includes('desenvolvimento')) { areaClass = 'area-desenvolvimento'; iconClass = 'fas fa-code'; }
                    else if (lowerArea.includes('design') || lowerArea.includes('midia')) { areaClass = 'area-design'; iconClass = 'fas fa-paint-brush'; }
                    else if (lowerArea.includes('tutoria')) { areaClass = 'area-tutoria'; iconClass = 'fas fa-chalkboard-teacher'; }
                    else if (lowerArea.includes('suporte') || lowerArea.includes('redes')) { areaClass = 'area-redes'; iconClass = 'fas fa-network-wired'; }
                    else { areaClass = ''; iconClass = 'fas fa-question'; }

                    // Garantir que a classe status-pill seja aplicada aqui
                    return `<span class="status-pill ${areaClass}"><i class="${iconClass} text-xs mr-1"></i>${area}</span>`;
                };

                const area1Html = getAreaHtml(aluno.perfil_opc1);
                const area2Html = getAreaHtml(aluno.perfil_opc2);

                card.innerHTML = `
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-white">${aluno.nome}</h3>
                        <div class="flex items-center gap-2">
                            <button class="ver-detalhes-btn text-blue-400 hover:text-blue-300 transition-colors" data-id="${aluno.id}">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button class="editar-aluno-btn text-green-400 hover:text-green-300 transition-colors" data-id="${aluno.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="deletar-aluno-btn text-red-400 hover:text-red-300 transition-colors" data-id="${aluno.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Exibir opções de perfil diretamente no card mobile -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        ${area1Html}
                        ${area2Html}
                    </div>
                    <div id="detalhes-mobile-${aluno.id}" class="hidden space-y-3 mt-4 pt-4 border-t border-gray-700">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mobile-card-item">
                                <span class="mobile-card-label">Contato:</span>
                                <span class="mobile-card-value">${aluno.contato}</span>
                            </div>
                             <div class="mobile-card-item">
                                <span class="mobile-card-label">Médias:</span>
                                <span class="mobile-card-value">${aluno.medias}</span>
                            </div>
                            <div class="mobile-card-item">
                                <span class="mobile-card-label">Email:</span>
                                <span class="mobile-card-value">${aluno.email}</span>
                            </div>
                            <div class="mobile-card-item">
                                <span class="mobile-card-label">Projetos:</span>
                                <span class="mobile-card-value">${aluno.projetos}</span>
                            </div>
                            <!-- Opções de perfil removidas daqui, pois já são exibidas acima -->
                             <div class="mobile-card-item">
                                <span class="mobile-card-label">Ocorrência:</span>
                                <span class="mobile-card-value">${aluno.ocorrencia}</span>
                            </div>
                            <div class="mobile-card-item">
                                <span class="mobile-card-label">Custeio:</span>
                                <span class="mobile-card-value">${aluno.custeio == 1 ? 'Sim' : 'Não'}</span>
                            </div>
                             <div class="mobile-card-item">
                                <span class="mobile-card-label">Entregas Individuais:</span>
                                <span class="mobile-card-value">${aluno.entregas_individuais}</span>
                            </div>
                            <div class="mobile-card-item">
                                <span class="mobile-card-label">Entregas do Grupo:</span>
                                <span class="mobile-card-value">${aluno.entregas_grupo}</span>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            // Re-add event listeners after rendering
            adicionarEventListeners();
        }

        // Função para aplicar filtros (considera busca e perfil)
        function aplicarFiltros() {
            const searchTerm = document.getElementById('searchAluno').value.toLowerCase().trim();
            const perfilFiltro = document.getElementById('filterPerfil').value.toLowerCase();

            const urlParams = new URLSearchParams();
            if (searchTerm) {
                urlParams.set('search', searchTerm);
            }
            if (perfilFiltro) {
                urlParams.set('perfil', perfilFiltro);
            }

            // Construir a nova URL e navegar
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        }

        // Adicionar event listeners para os filtros e busca
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado, inicializando...');

            const searchInput = document.getElementById('searchAluno');
            const filterPerfil = document.getElementById('filterPerfil');

            // Preencher campos de busca e filtro com valores da URL (ao carregar a página)
            const urlParams = new URLSearchParams(window.location.search);
            if (searchInput) {
                searchInput.value = urlParams.get('search') || '';
                 searchInput.addEventListener('input', aplicarFiltros);
            } else {
                console.error('Elemento de busca não encontrado');
            }

            if (filterPerfil) {
                filterPerfil.value = urlParams.get('perfil') || '';
                 filterPerfil.addEventListener('change', aplicarFiltros);
            } else {
                console.error('Elemento de filtro de perfil não encontrado');
            }

            // Não chame aplicarFiltros() aqui, pois o PHP já carregou os dados filtrados inicialmente
            // aplicarFiltros(); 

            // Inicializar outros event listeners
            adicionarEventListeners();
        });

    </script>

</body>

</html>