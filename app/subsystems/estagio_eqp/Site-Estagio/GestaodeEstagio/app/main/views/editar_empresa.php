<?php
if (!isset(
    $dados_empresa) || empty($dados_empresa)) {
    header('Location: dadosempresa.php?error=dados_nao_encontrados');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empresa</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../config/img/logo_Salaberga-removebg-preview.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
        }

        .form-container {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 1rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .input-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            line-height: 1.5;
            transition: all 0.2s ease;
            background: white;
            box-sizing: border-box;
        }

        .input-group input:focus {
            outline: none;
            border-color: #005A24;
            box-shadow: 0 0 0 3px rgba(0, 90, 36, 0.1);
        }

        .error-message {
            display: none;
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        .help-text {
            font-size: 0.75rem;
            color: #6B7280;
            margin-top: 0.5rem;
            line-height: 1.4;
            display: block;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .radio-label {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            min-width: 100px;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .radio-label input[type="radio"] {
            margin-right: 0.5rem;
            width: 16px;
            height: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .form-title p {
            color: #6B7280;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4B5563;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }

        .submit-button {
            width: 100%;
            padding: 1rem;
            background: #005A24;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 2rem;
            line-height: 1.5;
        }

        @media (max-width: 640px) {
            body {
                padding: 0;
                background: white;
            }

            .form-container {
                border-radius: 0;
                box-shadow: none;
                padding: 1.5rem 1rem;
                margin: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .input-group {
                margin-bottom: 1.25rem;
            }

            .input-group input {
                padding: 0.75rem;
                font-size: 1rem;
            }

            .radio-group {
                gap: 0.5rem;
            }

            .radio-label {
                padding: 0.5rem;
                font-size: 0.875rem;
                min-width: 80px;
            }

            .form-title {
                margin-bottom: 2rem;
            }

            .form-title h2 {
                font-size: 1.25rem;
            }

            .back-link {
                margin-bottom: 1.5rem;
            }

            .submit-button {
                margin-top: 1.5rem;
                padding: 0.875rem;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar</span>
        </a>

        <div class="form-title">
            <h2>Editar Empresa</h2>
            <p>Atualize os dados da empresa</p>
        </div>

        <form action="../controllers/Controller-Edicoes.php" method="POST">
            <input type="hidden" name="tipo" value="empresa">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($dados_empresa['id']) ?>">
            
            <div class="form-grid">
                <div class="input-group">
                    <label for="nome">Nome da Empresa</label>
                    <input type="text" id="nome" name="nome" required
                        placeholder="Digite o nome da empresa"
                        value="<?php echo htmlspecialchars($dados_empresa['nome']) ?>">
                    <span class="error-message" id="nomeError">Por favor, insira um nome válido</span>
                </div>

                <div class="input-group">
                    <label for="contato">Contato</label>
                    <input type="tel" id="contato" name="contato" required
                        placeholder="Digite o contato"
                        value="<?php echo htmlspecialchars($dados_empresa['contato']) ?>">
                    <span class="help-text">Digite apenas números (DDD + número)</span>
                    <span class="error-message" id="contatoError">Por favor, insira um número válido</span>
                </div>

                <div class="input-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" required
                        placeholder="Digite o endereço"
                        value="<?php echo htmlspecialchars($dados_empresa['endereco']) ?>">
                    <span class="error-message" id="enderecoError">Por favor, insira um endereço válido</span>
                </div>

                <div class="input-group">
                    <label for="vagas">Número de Vagas</label>
                    <input type="number" id="vagas" name="numero_vagas" required
                        placeholder="Quantidade de vagas disponíveis"
                        min="1" max="100"
                        value="<?php echo htmlspecialchars($dados_empresa['numero_vagas']) ?>">
                    <span class="error-message" id="vagasError">Por favor, insira um número válido de vagas</span>
                </div>
            </div>

            <div class="input-group">
                <label>Quantidade de Tipos de Perfil</label>
                <div class="radio-group">
                    <?php
                    $perfis = json_decode($dados_empresa['perfis'], true);
                    $num_perfis = is_array($perfis) ? count($perfis) : 1;
                    ?>
                    <label class="radio-label">
                        <input type="radio" name="quantidade_perfis" value="1" <?php echo $num_perfis == 1 ? 'checked' : ''; ?>>
                        <span>1 Perfil</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="quantidade_perfis" value="2" <?php echo $num_perfis == 2 ? 'checked' : ''; ?>>
                        <span>2 Perfis</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="quantidade_perfis" value="3" <?php echo $num_perfis == 3 ? 'checked' : ''; ?>>
                        <span>3 Perfis</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="quantidade_perfis" value="4" <?php echo $num_perfis == 4 ? 'checked' : ''; ?>>
                        <span>4 Perfis</span>
                    </label>
                </div>
            </div>

            <div id="perfis-container" class="input-group">
                <?php
                if (is_array($perfis) && !empty($perfis)) {
                    foreach ($perfis as $index => $perfil) {
                        $i = $index + 1;
                        echo "<div class='input-group'>";
                        echo "<label for='perfil{$i}'>Perfil {$i}</label>";
                        echo "<input type='text' id='perfil{$i}' name='perfis[]' required
                            placeholder='Digite o perfil {$i}'
                            value='" . htmlspecialchars($perfil) . "'>";
                        echo "<span class='error-message' id='perfil{$i}Error'>Por favor, insira um perfil válido</span>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='input-group'>";
                    echo "<label for='perfil1'>Perfil 1</label>";
                    echo "<input type='text' id='perfil1' name='perfis[]' required
                        placeholder='Digite o primeiro perfil'
                        value='" . htmlspecialchars($dados_empresa['perfis']) . "'>";
                    echo "<span class='error-message' id='perfil1Error'>Por favor, insira um perfil válido</span>";
                    echo "</div>";
                }
                ?>
            </div>

            <button type="submit" name="btn-editar" class="submit-button">
                Atualizar Empresa
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const perfisContainer = document.getElementById('perfis-container');
            const radioButtons = document.querySelectorAll('input[name="quantidade_perfis"]');

            function updatePerfisInputs() {
                const selectedValue = document.querySelector('input[name="quantidade_perfis"]:checked').value;
                perfisContainer.innerHTML = '';

                for (let i = 1; i <= selectedValue; i++) {
                    const div = document.createElement('div');
                    div.className = 'input-group';
                    div.innerHTML = `
                        <label for="perfil${i}" class="block text-sm font-medium text-gray-700 mb-1">Perfil ${i}</label>
                        <input type="text" id="perfil${i}" name="perfis[]" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite o perfil ${i}">
                        <span class="error-message" id="perfil${i}Error">Por favor, insira um perfil válido</span>
                    `;
                    perfisContainer.appendChild(div);
                }
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', updatePerfisInputs);
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                const inputs = form.querySelectorAll('input[required]');

                inputs.forEach(input => {
                    if (!input.value) {
                        input.parentElement.classList.add('error');
                        const errorId = input.id + 'Error';
                        const errorElement = document.getElementById(errorId);
                        if (errorElement) {
                            errorElement.textContent = 'Por favor, preencha este campo';
                        }
                        isValid = false;
                    } else {
                        input.parentElement.classList.remove('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Clear errors on input
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    this.parentElement.classList.remove('error');
                });
            });
        });
    </script>
</body>
</html>