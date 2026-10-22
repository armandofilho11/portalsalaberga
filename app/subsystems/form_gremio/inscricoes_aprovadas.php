<?php
// Incluir arquivo de configuração
require_once 'config.php';

// Conectar ao banco de dados
$pdo = getConnection();

// Função para buscar inscrições aprovadas
function getInscricoesAprovadas($pdo) {
    $sql = "SELECT 
                e.id as equipe_id,
                e.nome as nome_equipe,
                e.modalidade,
                GROUP_CONCAT(DISTINCT a.nome ORDER BY a.nome SEPARATOR ', ') as membros,
                COUNT(DISTINCT a.id) as total_membros
            FROM equipes e
            INNER JOIN equipe_membros em ON e.id = em.equipe_id
            INNER JOIN alunos a ON em.aluno_id = a.id
            INNER JOIN inscricoes i ON e.id = i.equipe_id AND i.status = 'aprovado'
            GROUP BY e.id, e.nome, e.modalidade
            ORDER BY e.modalidade, e.nome";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para formatar nomes (primeira letra maiúscula, resto minúscula)
function formatarNome($nome) {
    return ucwords(strtolower(trim($nome)));
}



$inscricoes = getInscricoesAprovadas($pdo);

// Agrupar por modalidade
$modalidades = [];
foreach ($inscricoes as $inscricao) {
    $modalidade = $inscricao['modalidade'];
    if (!isset($modalidades[$modalidade])) {
        $modalidades[$modalidade] = [];
    }
    $modalidades[$modalidade][] = $inscricao;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições Aprovadas - Copa Grêmio 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Design%20sem%20nome-MOpK2hbpuoqfoF8sir0Ue6SvciAArc.svg" type="image/svg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-500: #007d40;
            --primary-600: #006a36;
            --primary-700: #005A24;
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            line-height: 1.6;
            color: #1e293b;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 50%, #059669 100%);
        }
        
        .modalidade-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .modalidade-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .modalidade-header {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }
        
        .modalidade-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        }
        
        .equipes-container {
            padding: 2rem;
        }
        
        .equipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .equipe-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .equipe-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--sport-color, var(--primary-500));
            transition: width 0.3s ease;
        }
        
        .equipe-card:hover {
            border-color: var(--sport-color, var(--primary-500));
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .equipe-card:hover::before {
            width: 6px;
        }
        
        .equipe-card.futsal { --sport-color: #ef4444; }
        .equipe-card.teqvolei { --sport-color: #3b82f6; }
        .equipe-card.queimada { --sport-color: #f59e0b; }
        .equipe-card.x2futsal { --sport-color: #8b5cf6; }
        .equipe-card.volei { --sport-color: #10b981; }
        
        .sport-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .sport-badge.futsal { 
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); 
            color: #dc2626; 
            border: 1px solid #fca5a5;
        }
        .sport-badge.teqvolei { 
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); 
            color: #2563eb; 
            border: 1px solid #93c5fd;
        }
        .sport-badge.queimada { 
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
            color: #d97706; 
            border: 1px solid #fcd34d;
        }
        .sport-badge.x2futsal { 
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); 
            color: #7c3aed; 
            border: 1px solid #c4b5fd;
        }
        .sport-badge.volei { 
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
            color: #059669; 
            border: 1px solid #6ee7b7;
        }
        
        .equipe-name {
            font-weight: 700;
            color: var(--primary-700);
            font-size: 1.1rem;
            margin-bottom: 1rem;
            padding: 12px 16px;
            background: var(--primary-50);
            border-radius: 12px;
            border: 2px solid var(--primary-100);
            text-align: center;
        }
        
        .membros-list {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .membros-title {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
                 .membros-names {
             color: #6b7280;
             font-size: 0.85rem;
             line-height: 1.5;
         }
         
         .membros-lista {
             margin: 0;
             padding-left: 1.5rem;
             list-style-type: decimal;
         }
         
         .membros-lista li {
             margin-bottom: 0.25rem;
             padding: 0.25rem 0;
             border-bottom: 1px solid #f1f5f9;
         }
         
                   .membros-lista li:last-child {
              border-bottom: none;
              margin-bottom: 0;
          }
        
        .stats-badge {
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
            color: var(--primary-700);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--primary-200);
            display: inline-block;
            margin-top: 1rem;
        }
        
        @media (max-width: 768px) {
            .equipes-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .modalidade-header {
                padding: 1.5rem;
            }
            
            .equipes-container {
                padding: 1.5rem;
            }
            
                                       .equipe-name {
                  font-size: 1rem;
                  padding: 10px 14px;
              }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Main Content -->
    <main class="w-full px-4 py-8 max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="hero-gradient text-white rounded-3xl p-8 md:p-12 shadow-2xl mb-8">
            <div class="text-center relative">
                <div class="absolute inset-0  to-transparent rounded-3xl"></div>
                <div class="relative z-10">
                    <h1 class="hero-title text-2xl md:text-5xl font-bold mb-4 tracking-tight">
                        <i class="fas fa-check-circle text-yellow-300 mr-2 drop-shadow-lg"></i>
                        Inscrições Aprovadas
                    </h1>
                    <p class="text-green-200 text-lg">Copa Grêmio 2025</p>
                    <p class="text-green-200 text-sm mt-2">Total de inscrições aprovadas: <strong><?php echo count($inscricoes); ?></strong></p>
                </div>
            </div>
        </div>

        <!-- Inscrições por Modalidade -->
        <?php if (empty($modalidades)): ?>
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                <h2 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma inscrição aprovada encontrada</h2>
                <p class="text-gray-500">Não há inscrições com status "aprovado" no sistema.</p>
            </div>
        <?php else: ?>
            <?php foreach ($modalidades as $modalidade => $equipes): ?>
                <div class="modalidade-card">
                    <div class="modalidade-header text-white relative">
                        <h3 class="font-bold text-2xl text-center relative z-10">
                            <?php 
                            $icon = '';
                            $badgeClass = '';
                            switch(strtolower($modalidade)) {
                                case 'futsal':
                                    $icon = 'fas fa-futbol';
                                    $badgeClass = 'futsal';
                                    break;
                                case 'teqvolei':
                                    $icon = 'fas fa-volleyball-ball';
                                    $badgeClass = 'teqvolei';
                                    break;
                                case 'queimada':
                                    $icon = 'fas fa-fire';
                                    $badgeClass = 'queimada';
                                    break;
                                case 'x2':
                                case 'x2futsal':
                                    $icon = 'fas fa-running';
                                    $badgeClass = 'x2futsal';
                                    break;
                                case 'volei':
                                    $icon = 'fas fa-volleyball-ball';
                                    $badgeClass = 'volei';
                                    break;
                                default:
                                    $icon = 'fas fa-trophy';
                                    $badgeClass = 'futsal';
                            }
                            ?>
                            <i class="<?php echo $icon; ?> mr-3"></i>
                            <?php echo ucfirst($modalidade); ?>
                        </h3>
                        <p class="text-center text-green-100 text-lg relative z-10 mt-1">
                            <?php echo count($equipes); ?> equipe<?php echo count($equipes) > 1 ? 's' : ''; ?> aprovada<?php echo count($equipes) > 1 ? 's' : ''; ?>
                        </p>
                    </div>
                    <div class="equipes-container">
                        <div class="equipes-grid">
                            <?php foreach ($equipes as $equipe): ?>
                                <div class="equipe-card <?php echo $badgeClass; ?>">
                                    <span class="sport-badge <?php echo $badgeClass; ?>">
                                        <i class="<?php echo $icon; ?>"></i><?php echo ucfirst($modalidade); ?>
                                    </span>
                                                                         <div class="equipe-name">
                                         <?php echo htmlspecialchars(formatarNome($equipe['nome_equipe'])); ?>
                                     </div>
                                                                         <div class="membros-list">
                                         <div class="membros-title">
                                             <i class="fas fa-users text-gray-500"></i>
                                             Membros da Equipe (<?php echo $equipe['total_membros']; ?>)
                                         </div>
                                         <div class="membros-names">
                                             <?php 
                                             $membros = explode(', ', $equipe['membros']);
                                             $membros = array_map('trim', $membros);
                                             
                                                                                           // Criar lista numerada dos membros
                                              echo '<ol class="membros-lista">';
                                              foreach ($membros as $index => $membro) {
                                                  echo '<li>' . htmlspecialchars(formatarNome($membro)) . '</li>';
                                              }
                                              echo '</ol>';
                                             ?>
                                         </div>
                                                                           </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <script>
        // Adicionar animação de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.equipe-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html> 