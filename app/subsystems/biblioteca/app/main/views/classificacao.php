<?php
require_once __DIR__ . '/../models/select_model.php';
$selectModel = new select_model();

// Recuperar dados de categorias, gêneros e subgêneros
$categorias = $selectModel->select_categoria();
$generos = $selectModel->select_genero();
$subgeneros = $selectModel->select_subgenero();

// Contar totais
$totalCategorias = count($categorias);
$totalGeneros = count($generos);
$totalSubgeneros = count($subgeneros);
$totalItems = $totalCategorias + $totalGeneros + $totalSubgeneros;

// Cores para os cards
$colors = ['#008C45', '#FFA500', '#FFD700', '#FF6B6B', '#4ECDC4'];

// Construir hierarquia
$hierarchy = [];
foreach ($categorias as $categoria) {
    $cat_id = $categoria['id'];
    $hierarchy[$cat_id] = [
        'categoria' => $categoria['categorias'],
        'generos' => []
    ];
}

foreach ($generos as $genero) {
    $id_cat = $genero['id_categoria'] ?? null;
    if ($id_cat && isset($hierarchy[$id_cat])) {
        $hierarchy[$id_cat]['generos'][$genero['id']] = [
            'genero' => $genero['generos'],
            'subgeneros' => []
        ];
    }
}

foreach ($subgeneros as $subgenero) {
    $id_genero = $subgenero['id_genero'] ?? null;
    foreach ($hierarchy as &$cat_data) {
        if (isset($cat_data['generos'][$id_genero])) {
            $cat_data['generos'][$id_genero]['subgeneros'][] = $subgenero;
        }
    }
}

// Reordenar categorias para a ordem específica: Geral, Técnico, Didático, Paradidático
function __normalize_str($s) {
    if ($s === null) return '';
    $s = trim((string) $s);
    // tenta remover acentos para comparação mais confiável
    $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $s);
    if ($trans !== false) $s = $trans;
    return mb_strtolower($s, 'UTF-8');
}

$desiredOrder = ['Geral', 'Técnico', 'Didático', 'Paradidático'];
$orderedHierarchy = [];

// adicionar as categorias na ordem desejada se existirem
foreach ($desiredOrder as $desiredName) {
    $normDesired = __normalize_str($desiredName);
    foreach ($hierarchy as $id => $data) {
        if (__normalize_str($data['categoria']) === $normDesired) {
            $orderedHierarchy[$id] = $data;
            unset($hierarchy[$id]);
            break;
        }
    }
}

// anexar o restante das categorias na ordem original
foreach ($hierarchy as $id => $data) {
    $orderedHierarchy[$id] = $data;
}

$hierarchy = $orderedHierarchy;

$months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$currentDate = date('j') . ' de ' . $months[intval(date('n')) - 1] . ' de ' . date('Y');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/img/icon.png" type="image/x-icon">
    <title>Classificação - Sistema de Biblioteca</title>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        primary: '#4CAF50',
                        secondary: '#FFB74D',
                    },
                },
            },
        };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Anton&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-principal: #f8fafc;
            --azul-escuro: #0f172a;
            --texto-claro: #f8fafc;
            --texto-escuro: #1e293b;
            --texto-mutado: #64748b;
            --branco: #ffffff;
            --borda: #e2e8f0;
            --borda-clara: #bfdbd0;
            --sucesso: #4DA73A;
            --sucesso-claro: #F8FCF9;
        }

        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-principal);
            color: var(--texto-escuro);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .card-indicador {
            background-color: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .card-indicador.animate-panel {
            opacity: 0;
            transform: translateY(48px);
        }

        .card-indicador:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--texto-mutado);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background-color: rgba(0, 140, 69, 0.1);
            color: #008C45;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card-footer {
            font-size: 12px;
            color: var(--texto-mutado);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .painel-principal {
            background-color: var(--branco);
            border: 1px solid var(--borda);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .painel-principal.animate-panel {
            opacity: 0;
            transform: translateY(48px);
        }

        .painel-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--azul-escuro);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .show {
            opacity: 1 !important;
            transform: translateY(0) !important;
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .hierarchy-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            align-items: start;
        }

        .hierarchy-card {
            display: flex;
            flex-direction: column;
            min-height: none;
            max-height: 500px;
            overflow: hidden;
        }

        .hierarchy-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin-bottom: 14px;
            border: 1px solid #e2e8f0;
        }

        .hierarchy-card-header.category-card-header {
            background: #ffffff;
            border-color: #dbe2ea;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .hierarchy-card-header:hover {
            border-color: #cbd5e1;
        }

        .categoria-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            font-size: 14px;
            flex-shrink: 0;
        }

        .categoria-name {
            font-weight: 600;
            font-size: 15px;
            color: #1e293b;
            flex: 1;
        }

        .categoria-label {
            font-size: 12px;
            color: #94a3b8;
            margin-left: auto;
            font-weight: 500;
            white-space: nowrap;
        }

        .hierarchy-content {
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hierarchy-content::-webkit-scrollbar {
            width: 6px;
        }

        .hierarchy-content::-webkit-scrollbar-thumb {
            background: rgba(0, 140, 69, 0.25);
            border-radius: 999px;
        }

        .generos-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-left: 16px;
            padding-left: 12px;
            border-left: 2px solid rgba(0, 140, 69, 0.16);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .genero-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #fcfdff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            min-width: 0;
        }

        .genero-item:hover {
            background: #f8fafc;
            border-color: #008C45;
        }

        .genero-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            font-weight: 700;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
        }

        .genero-name {
            font-weight: 500;
            font-size: 14px;
            color: #1e293b;
            flex: 1;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .genero-label {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }

        .expand-btn {
            cursor: pointer;
            color: #94a3b8;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .expand-btn.expanded {
            color: #008C45;
            transform: rotate(90deg);
        }

        /* Animação de painel para elementos com classe Tailwind */
        .animate-panel {
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
    </style>
</head>

<body class="pr-0 lg:ml-0 xl:ml-6 2xl:pl-6 2xl:pt-6 lg:px-6">

    <div class="dashboard-header flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 px-6 sm:px-0 pt-24 lg:pt-4 md:pt-12 pb-2 sm:mr-0">

        <section class="flex-1 flex flex-col items-center lg:items-start text-center lg:text-start gap-6">
            <section class="pt-24 lg:pt-0 items-center text-center">
                <div class="space-y-4 flex flex-col items-center lg:items-start text-center lg:text-start animate-panel transform translate-y-12 opacity-0">
                    <h2
                        class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold gradient-text"
                        style="font-family: 'Anton', sans-serif;"
                    >
                        Gerenciamento de Classificação
                    </h2>
                    <p
                        class="text-sm sm:text-lg text-gray-600 max-w-2xl"
                        style="font-family: 'Poppins', sans-serif;"
                    >
                        Consulte todas categorias, gêneros e subgêneros do sistema.
                    </p>
                </div>
            </section>
        </section>

        <div class="flex justify-start lg:justify-end">
            <div class="date flex items-center gap-3 text-sm sm:text-base border border-[var(--borda-clara)] px-4 py-2 rounded-lg animate-panel transform translate-y-12 opacity-0 bg-[var(--sucesso-claro)] shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                <svg 
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 64 64"
                    fill="none"
                    stroke="#4DA73A"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                >
                    <rect x="10" y="14" width="44" height="40" rx="6"/>
                    <path d="M10 24H54"/>
                    <path d="M20 10V18"/>
                    <path d="M44 10V18"/>
                    <circle cx="20" cy="34" r="2"/>
                    <circle cx="32" cy="34" r="2"/>
                    <circle cx="44" cy="34" r="2"/>
                    <circle cx="20" cy="44" r="2"/>
                    <circle cx="32" cy="44" r="2"/>
                    <circle cx="44" cy="44" r="2"/>
                </svg>
                <span class="font-medium text-gray-500">
                    <?php echo $currentDate; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="cards-grid grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4 px-6">
        
        <div class="card-indicador animate-panel">
            <div class="card-header-box">
                <span class="card-title">Categorias</span>
                <div class="card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="card-value text-ceara-green counter" data-target="<?php echo $totalCategorias; ?>">0</div>
            <span class="card-footer">
                <i class="fas fa-layer-group text-xs text-ceara-green"></i>
                <span>Nível 1</span>
            </span>
        </div>

        <div class="card-indicador animate-panel">
            <div class="card-header-box">
                <span class="card-title">Gêneros</span>
                <div class="card-icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
            <div class="card-value text-ceara-green counter" data-target="<?php echo $totalGeneros; ?>">0</div>
            <span class="card-footer">
                <i class="fas fa-tags text-xs text-ceara-green"></i>
                <span>Nível 2</span>
            </span>
        </div>

        <div class="card-indicador animate-panel">
            <div class="card-header-box">
                <span class="card-title">Subgêneros</span>
                <div class="card-icon">
                    <i class="fas fa-list-ul"></i>
                </div>
            </div>
            <div class="card-value text-ceara-green counter" data-target="<?php echo $totalSubgeneros; ?>">0</div>
            <span class="card-footer">
                <i class="fas fa-list-ul text-xs text-ceara-green"></i>
                <span>Nível 3</span>
            </span>
        </div>

        <div class="card-indicador animate-panel">
            <div class="card-header-box">
                <span class="card-title">Total de Itens</span>
                <div class="card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="card-value text-ceara-green counter" data-target="<?php echo $totalItems; ?>">0</div>
            <span class="card-footer">
                <i class="fas fa-layer-group text-xs text-ceara-green"></i>
                <span>Toda a estrutura</span>
            </span>
        </div>
    </div>

    <!-- Estrutura de Classificação -->
    <div class="px-6">
        <!-- <div class="painel-principal animate-panel"> -->
            <!-- <div class="painel-title animate-panel">
                <span>
                    <i class="fas fa-sitemap text-ceara-green mr-3"></i>
                    Estrutura de Classificação
                </span>
                <button onclick="expandirTodos()" class="text-sm px-4 py-2 bg-ceara-green text-white rounded-lg hover:bg-green-700 transition">
                    Expandir todos
                </button>
            </div> -->

            <div class="hierarchy-grid">
                <?php
                $colorIndex = 0;
                foreach ($hierarchy as $cat_id => $cat_data):
                    $catColor = $colors[$colorIndex % count($colors)];
                    $generoCount = count($cat_data['generos']);
                ?>
                    <div class="painel-principal hierarchy-card animate-panel">
                        <div class="hierarchy-card-header category-card-header">
                            <div class="categoria-badge" style="background: <?php echo $catColor; ?>">
                                <?php echo $colorIndex + 1; ?>
                            </div>
                            <span class="categoria-name">
                                <i class="fas fa-folder mr-2" style="color: <?php echo $catColor; ?>"></i>
                                <?php echo htmlspecialchars($cat_data['categoria']); ?>
                            </span>
                            <span class="categoria-label">Categoria</span>
                            <!-- <span class="expand-btn expanded">
                                <i class="fas fa-chevron-right"></i>
                            </span> -->
                        </div>

                        <div class="hierarchy-content" style="display: flex;">
                            <div class="generos-container">
                                <?php
                                $generoIndex = 0;
                                foreach ($cat_data['generos'] as $gen_id => $gen_data):
                                    $subgeneroCount = count($gen_data['subgeneros']);
                                    $generoColor = $catColor;
                                ?>
                                    <div class="genero-item">
                                        <div class="genero-badge" style="background: <?php echo $generoColor; ?>">
                                            <?php echo $colorIndex + 1; ?>.<?php echo $generoIndex + 1; ?>
                                        </div>
                                        <span class="genero-name">
                                            <i class="fas fa-bookmark mr-2" style="color: <?php echo $generoColor; ?>"></i>
                                            <?php echo htmlspecialchars($gen_data['genero']); ?>
                                        </span>
                                        <span class="genero-label">Gênero</span>
                                        <span class="expand-btn" onclick="event.stopPropagation(); toggleSubgeneros(this)">
                                            <i class="fas fa-chevron-down"></i>
                                        </span>
                                    </div>

                                    <?php if ($subgeneroCount > 0): ?>
                                        <div class="subgeneros-list" style="display: block; margin-left: 16px; margin-top: 2px; border-left: 2px solid <?php echo $generoColor; ?>; padding-left: 12px;">
                                            <?php foreach ($gen_data['subgeneros'] as $subgen): ?>
                                                <div class="text-sm px-3 py-2 bg-gray-50 rounded-md mb-2 border border-gray-100">
                                                    <i class="fas fa-tag text-xs" style="color: <?php echo $generoColor; ?>; margin-right: 8px;"></i>
                                                    <span class="text-gray-700">
                                                        <?php echo htmlspecialchars($subgen['subgenero']); ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                <?php
                                    $generoIndex++;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                    $colorIndex++;
                endforeach;
                ?>
            </div>
        <!-- </div> -->
    </div>

    <script>
        function toggleGeneros(header) {
            const container = header.nextElementSibling;
            const expandBtn = header.querySelector('.expand-btn');
            
            if (container.style.display === 'none') {
                container.style.display = 'flex';
                expandBtn.classList.add('expanded');
            } else {
                container.style.display = 'none';
                expandBtn.classList.remove('expanded');
            }
        }

        function toggleSubgeneros(btn) {
            const generoItem = btn.closest('.genero-item');
            const subgenList = generoItem.nextElementSibling;
            
            if (subgenList && subgenList.classList.contains('subgeneros-list')) {
                if (subgenList.style.display === 'none') {
                    subgenList.style.display = 'block';
                    btn.classList.add('expanded');
                } else {
                    subgenList.style.display = 'none';
                    btn.classList.remove('expanded');
                }
            }
        }

        // function expandirTodos() {
        //     const headers = document.querySelectorAll('.categoria-header');
        //     headers.forEach(header => {
        //         const container = header.nextElementSibling;
        //         container.style.display = 'flex';
        //         header.querySelector('.expand-btn').classList.add('expanded');

        //         // Expandir subgêneros também
        //         const generoItems = container.querySelectorAll('.genero-item');
        //         generoItems.forEach(item => {
        //             const subgenList = item.nextElementSibling;
        //             if (subgenList && subgenList.classList.contains('subgeneros-list')) {
        //                 subgenList.style.display = 'block';
        //                 item.querySelector('.expand-btn').classList.add('expanded');
        //             }
        //         });
        //     });
        // }

        function animateCounters() {
            document.querySelectorAll('.counter').forEach(counter => {
                const target = +counter.dataset.target;
                if (target === 0) {
                    counter.textContent = '0';
                    return;
                }
                let start = 0;
                const duration = 1000; 
                const stepTime = Math.max(Math.floor(duration / Math.max(target, 1)), 15);

                const updateCount = () => {
                    start += Math.ceil(target / (duration / stepTime));
                    if (start >= target) {
                        counter.textContent = target.toLocaleString('pt-BR');
                        return;
                    }
                    counter.textContent = start.toLocaleString('pt-BR');
                    requestAnimationFrame(updateCount);
                };

                requestAnimationFrame(updateCount);
            });
        }

        function revealPanels() {
            document.querySelectorAll('.animate-panel').forEach((panel, index) => {
                setTimeout(() => {
                    panel.classList.add('show');
                }, 100 + index * 60);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            revealPanels();
            animateCounters();

            document.querySelectorAll('.hierarchy-content').forEach(content => {
                content.style.display = 'flex';
            });

            document.querySelectorAll('.subgeneros-list').forEach(list => {
                list.style.display = 'block';
            });

            document.querySelectorAll('.hierarchy-card-header .expand-btn').forEach(btn => {
                btn.classList.add('expanded');
            });

            document.querySelectorAll('.genero-item .expand-btn').forEach(btn => {
                btn.classList.add('expanded');
            });
        });
    </script>

</body>

</html>