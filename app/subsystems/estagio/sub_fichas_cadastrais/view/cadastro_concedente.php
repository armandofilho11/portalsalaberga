<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro Instituição</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Ícones -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        :root {
            --site-bg: #1a1a1a;
            --site-panel: #232323;
            --site-input: #2d2d2d;
            --site-border: rgba(255, 255, 255, .1);
            --site-green: #007a33;
            --site-green-bright: #00c250;
            --site-text-muted: #a3a3a3;
        }

        body{
            font-family:'Poppins',sans-serif;
            background: var(--site-bg);
            color: #fff;
        }

        .card-shadow{
            box-shadow:
            0 18px 45px rgba(0,0,0,.35),
            0 2px 10px rgba(0,0,0,.2);
        }

        .left-gradient{
            background:
            linear-gradient(
                135deg,
                #00521a 10%,
                #007a33 55%,
                #00c250 100%
            );
        }

        section input[type="text"],
        section input[type="email"],
        section textarea {
            background: var(--site-input) !important;
            border-color: var(--site-border) !important;
            color: #fff;
        }

        section input::placeholder,
        section textarea::placeholder {
            color: #a3a3a3;
        }

        section input:focus,
        section textarea:focus {
            outline: none;
            border-color: var(--site-green-bright) !important;
            box-shadow: 0 0 0 2px rgba(0, 194, 80, .2);
        }

        section input[type="radio"] {
            accent-color: var(--site-green-bright);
        }

        .institution-guidance {
            background: rgba(0, 122, 51, .16);
            border-color: rgba(0, 194, 80, .35);
            color: #d1fae5;
        }

        .institution-guidance h4,
        .institution-heading {
            color: #00c250;
        }

        .institution-muted {
            color: var(--site-text-muted);
        }

        .input-style{
            width: 100%;
            height: 3rem;
            padding: 0 1rem;
            border: 1px solid var(--site-border);
            border-radius: .75rem;
            background: var(--site-input);
            color: #fff;
        }

    </style>

</head>




<body class="min-h-screen flex items-center justify-center p-6 sm:p-12">

<div class="w-full max-w-7xl bg-[#232323] border border-white/10 rounded-3xl overflow-hidden card-shadow">

<div class="grid lg:grid-cols-12">
    


        <!-- LADO DIREITO -->
<!-- LADO DIREITO -->
<section class="lg:col-span-12 p-10 lg:p-14 overflow-y-auto">
            <div class="text-center mb-10">

                <h2 class="text-4xl font-semibold text-white">
                    Cadastro Institucional
                </h2>

                <p class="institution-muted mt-2">
                    Informações da instituição concedente de estágio
                </p>

            </div>
            
            
            
            















            <form method="POST" action="../control/Controller.php">
            
                <input type="hidden" name="acao" value="cadastrar_concedente">
                
                
                
                
    
    
                <!-- INSTITUIÇÃO -->

<div class="grid md:grid-cols-2 gap-8 mb-2">



    <div class="institution-guidance border rounded-xl p-5 mb-2">
        <h4 class="font-semibold mb-2">
            Orientações para preenchimento
        </h4>
        <ul class="text-sm space-y-2">
            <li><strong>• Utilize o campo "Observações" para: </strong>
                <ol class="mt-2 ml-6 list-decimal space-y-1">
                    <li>Apresentar brevemente a instituição e sua área de atuação.</li>
                    <li>Descrever as atividades que o(a) estagiário(a) desenvolverá.</li>
                    <li>Informar a natureza dos serviços prestados pela organização.</li>
                </ol>
            </li>
        </ul>
    </div>




    <!-- Tipo de Instituição -->
    <div>
        <h3 class="institution-heading text-xl font-semibold mb-3">
            Classificação da Instituição
        </h3>
        
        
            <label class="block font-medium mb-2">
                Tipo de Instituição
            </label>
            
            <div class="flex gap-8">
                <label class="flex items-center gap-2">
                    <input type="radio" name="instituicao" value="Publica">
                    Pública
                </label>
                <label class="flex items-center gap-2 ">
                    <input type="radio" name="instituicao" value="Privada" >
                    Privada
                </label>
            </div>
            
            <div class="md:mt-[16px]">
                
            </div>    

        
            <label class="block font-medium mb-2">
                Rede
            </label>
            
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2">
                    <input type="radio" name="rede" value="Federal">
                    Federal
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="rede" value="Estadual">
                    Estadual
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="rede" value="Municipal">
                    Municipal
                </label>
            </div>
    </div>






    <!-- Rede -->
    <div class="md:mt-[0px]">
    </div>
    
 
</div>



    <h3 class="institution-heading text-xl font-semibold mb-3">
            Dados da Instituição
        </h3>
        
                <!-- DADOS -->

                <div class="grid md:grid-cols-3 gap-2">

                    <div>

                        <input type="text"
                               name="razao_social"
                               placeholder="Razão Social"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                    <div>
                        <input type="text"
                               name="nome_fantasia"
                               placeholder="Nome Fantasia"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                    <div>
                        <input type="text"
                               name="cnpj"
                               placeholder="CNPJ"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                    <div>
                        <input type="text"
                               name="especificacao_atividade"
                               placeholder="Ramo de Atividade"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                    <div>
                        <input type="email"
                               name="email_institucional"
                               placeholder="Email institucional"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                    <div>
                        <input type="text"
                               name="telefone_institucional"
                               placeholder="Fone / WhatsApp"
                               class="w-full h-12 px-4 border rounded-xl">
                    </div>

                </div>
                
                


                <!-- REPRESENTANTE -->

                <h3 class="institution-heading mt-6 mb-3 text-lg font-semibold">
                        Dados do(a) Representante Legal
                </h3>

                <div class="grid md:grid-cols-2 gap-2">

                    <input type="text"
                        name="representante_legal"
                        placeholder="Representante"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="email"
                        name="email_representante"
                        placeholder="E-mail"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="cpf_representante"
                        placeholder="CPF"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="rg_representante"
                        placeholder="RG"
                        class="w-full h-12 px-4 border rounded-xl">

                </div>

                <!-- ENDEREÇO -->

                <h3 class="institution-heading mt-6 mb-3 text-lg font-semibold">
                    Endereço
                </h3>

                <div class="grid md:grid-cols-3 gap-2">

                    <input type="text"
                        name="rua"
                        placeholder="Rua, Avenida, Travessa..."
                        class="md:col-span-2 w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="numero"
                        placeholder="Número"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="bairro"
                        placeholder="Bairro"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="municipio"
                        placeholder="Município"
                        class="w-full h-12 px-4 border rounded-xl">

                    <input type="text"
                        name="cep"
                        placeholder="CEP"
                        class="w-full h-12 px-4 border rounded-xl">

                </div>

                <!-- OBSERVAÇÕES -->

                <div class="mt-8">

                    <label class="block mb-2">
                        Observações
                    </label>

                    <textarea
                        name="observacoes"
                        rows="3"
                        class="w-full border rounded-xl p-4"></textarea>

                </div>

                <!-- BOTÃO -->

                <button
                    type="submit"
                    class="mt-8 w-full h-14 rounded-xl text-white font-medium
                    bg-gradient-to-r from-[#007a33] to-[#00c250]
                    hover:from-[#00661f] hover:to-[#00a844] transition">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Finalizar Cadastro

                </button>

            </form>

        </section>

    </div>

</div>

</body>
</html>