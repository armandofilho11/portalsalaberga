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

        body{
            font-family:'Poppins',sans-serif;
        }

        .card-shadow{
            box-shadow:
            0 10px 25px rgba(0,0,0,.35),
            0 2px 10px rgba(0,0,0,.25);
        }

        .left-gradient{
            background:
            linear-gradient(
                135deg,
                #274f17 10%,
                #31591e 55%,
                #c97550 100%
            );
        }

        .input-style{
            @apply w-full h-12 px-4 border border-[#333b33]
            rounded-xl bg-[#22271f] text-gray-100
            focus:outline-none
            focus:ring-2
            focus:ring-[#22c55e];
        }

        /* inputs nativos de data/hora ficam com ícone branco no tema escuro */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
        }

    </style>

</head>




<body class="bg-[#0f110f] min-h-screen flex items-center justify-center p-4 sm:p-8 lg:p-12">

<div class="w-full max-w-7xl bg-[#1b1f1c] rounded-2xl sm:rounded-3xl overflow-hidden card-shadow">

<div class="grid lg:grid-cols-12">
    

<!-- ========================================================= -->
<!-- ======================= CABEÇALHO ======================= -->
<!-- ========================================================= -->
        

<section class="lg:col-span-12 p-4 sm:p-6 lg:p-10 lg:p-14 overflow-y-auto">
            <div class="text-center sm:text-left mb-6 sm:mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[#22c55e]">
                    Registro de Vagas
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 sm:mt-2">
                    Informe sobre vagas, perfil, data e horários de seleção.
                    </p>
                </div>
                <a href="dashboard.php" class="flex items-center justify-center gap-2 px-4 py-2 sm:py-2.5 bg-gray-700/50 hover:bg-gray-700 text-white rounded-lg sm:rounded-xl transition-all w-full sm:w-auto shrink-0 mt-4 sm:mt-0 text-sm sm:text-base font-medium">
                    <i class="fas fa-arrow-left text-xs sm:text-sm"></i>
                    <span>Voltar</span>
                </a>
            </div>
            
            
            
            
            
            
            

<form method="POST" action="../control/Controller.php">

    <input type="hidden" name="acao" value="cadastrar_vagas">

        
        
        
        
        
        
        
        
        
        

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10">

<!-- ========================================================= -->
<!-- ==================== COLUNA ESQUERDA ==================== -->
<!-- ========================================================= -->
    <div>
        







<div class="bg-[#1c2a21] border border-[#2f5233] rounded-lg sm:rounded-xl p-3 sm:p-5 mb-6 sm:mb-8">

    <h4 class="font-semibold text-[#86efac] mb-2 text-sm sm:text-base">
        Orientações para preenchimento
    </h4>

    <ul class="text-xs sm:text-sm text-gray-300 space-y-1 sm:space-y-2">
        <li>• Selecione o nome de sua empresa ou organização.</li>
        <li>• Informe a quantidade de vagas para cada perfil desejado.</li>
        <li>• Selecione como pretende realizar a escolha dos candidatos.</li>
        <li><strong class="text-gray-200">• Utilize o campo "Observações" para: </strong>
            <ol class="mt-2 ml-6 list-decimal space-y-1">
                <li>Apresentar brevemente a instituição e sua área de atuação.</li>
                <li>Descrever as atividades que o estagiário desenvolverá.</li>
                <li>Informar a natureza dos serviços prestados pela organização.</li>
            </ol>
        </li>
        
        
    </ul>

</div>





        
        <div class="mb-6 sm:mb-8">
        
                <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-[#22c55e] mb-3 sm:mb-5">
                    Empresa  /  Instituição
                </h3>
        
            <select
                name="concedente_id"
                required
                class="w-full h-10 sm:h-12 px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-200 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
        
                <option value="">
                    Selecione aqui o nome de sua empresa
                </option>

                                    <option value="61">
                        APAE - Maranguape                    </option>
                                    <option value="50">
                        CL Telecom                    </option>
                                    <option value="60">
                        CREDE 1                    </option>
                                    <option value="65">
                        CVC                    </option>
                                    <option value="53">
                        EEFM EUNICE WEAVER                    </option>
                                    <option value="54">
                        EEMTI ANCHIETA                    </option>
                                    <option value="64">
                        Estácio - Polo Maranguape                    </option>
                                    <option value="43">
                        Fênix Soluções - MAR                    </option>
                                    <option value="47">
                        Gráfica Speed                    </option>
                                    <option value="42">
                        Ideia Digital                    </option>
                                    <option value="46">
                        IFCE - Maranguape                    </option>
                                    <option value="39">
                        Instituto Mix                    </option>
                                    <option value="38">
                        Mallory                    </option>
                                    <option value="59">
                        Maranguape Cópias                     </option>
                                    <option value="41">
                        Netway                    </option>
                                    <option value="45">
                        PMM - EDUCAÇÃO                    </option>
                                    <option value="57">
                        PMM - FITEC                    </option>
                                    <option value="58">
                        PMM - SECULT                    </option>
                                    <option value="55">
                        R2 TELECOM                    </option>
                                    <option value="44">
                        Softway                    </option>
                                    <option value="63">
                        Vistel Telecom                    </option>
                                    <option value="40">
                        VP23 Telecom                    </option>
                            </select>
        

        </div>





        <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-[#22c55e] mb-3 sm:mb-5">
            Modelo de Seleção
        </h3>

        <div class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">

            <label class="flex items-center gap-2 text-gray-200">
                <input type="radio"
                    name="forma_selecao"
                    value="Selecao"
                    class="accent-[#22c55e]">
                Desejo realizar processo seletivo próprio.
            </label>

            <label class="flex items-center gap-2 text-gray-200">
                <input type="radio"
                    name="forma_selecao"
                    value="Entrevista"
                    class="accent-[#22c55e]">
                Desejo entrevistar os candidatos encaminhados.
            </label>

            <label class="flex items-center gap-2 text-gray-200">
                <input type="radio"
                    name="forma_selecao"
                    value="Indicacao"
                    class="accent-[#22c55e]">
                Desejo que a escola selecione e indique os estudantes conforme o perfil solicitado.
            </label>

        </div>
        
        
        
        
        
       
        
        
        
        
        

    </div>







<!-- ========================================================= -->
<!-- ===================== COLUNA DIREITA ==================== -->
<!-- ========================================================= -->    
    <div>




        <div class="bg-[#2a2417] border border-[#7c5e1b] rounded-lg sm:rounded-xl p-3 sm:p-5 mb-4 sm:mb-5">
            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                <strong class="text-[#facc15]">Importante:</strong> Não dispomos de alunos com perfil
                de saída voltado para atividades administrativas. Caso a
                instituição tenha interesse em estagiários com este perfil,
                recomendamos a busca junto ao curso técnico em Administração.
            </p>
        </div>
        
        
        
        
        
        
        <h3 class="mb-3 sm:mb-5 text-base sm:text-lg lg:text-xl font-semibold text-[#22c55e]">
            Perfil Desejado para os Estagiários
        </h3>


        <label class="block font-medium mb-4 sm:mb-6 text-gray-300 text-xs sm:text-sm">
            Preencha a quantidade de vagas que pretende ofertar no(s) perfil/perfis escolhido(s).
        </label>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 mb-6 sm:mb-8">

            <div>
            <h3 style="text-align:center;"  class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                Desenvolvedor
            </h3>
                <input type="number" min="0"
                    name="vagas_desenvolvedor"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>

            <div>
            <h3 style="text-align:center;"  class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                    Design/Mídias
            </h3>
                <input type="number" min="0"
                    name="vagas_design"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>

            <div>
                <h3 style="text-align:center;" class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                        Suporte/Redes
                </h3>
                <input type="number" min="0"
                    name="vagas_suporte"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>

            <div>
                <h3 style="text-align:center;" class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                    Tutoria
                </h3>
                <input type="number" min="0"
                    name="vagas_tutoria"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>

        </div>        





                
        <h3 class="mb-3 sm:mb-5 text-base sm:text-lg lg:text-xl font-semibold text-[#22c55e]">
            Sobre a seleção, responda:
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">

            <div>
                <h3 style="text-align:center;" class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                    Data
                </h3>
                <input type="date" 
                    name="data"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>


            <div>
                <h3 style="text-align:center;" class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                    Horário
                </h3>
                <input type="time" 
                    name="hora"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            </div>



            <div>
                <h3 style="text-align:center;" class="text-xs sm:text-sm lg:text-base font-semibold text-[#22c55e] mb-2">
                    Procurar por
                </h3>
                <input type="text" 
                    name="procurar_por"
                    class="w-full h-10 sm:h-12 px-2 sm:px-4 border border-[#333b33] rounded-lg sm:rounded-xl bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]\">
            </div>


        </div>      
        


    </div>
    
    
    
    
    

        


</div>




        <h3 class="mb-3 sm:mb-5 text-base sm:text-lg lg:text-xl font-semibold text-[#22c55e]">
            Observações
        </h3>

        <textarea
            name="observacoes"
            rows="3"
            class="w-full border border-[#333b33] rounded-lg sm:rounded-xl p-2 sm:p-3 bg-[#22271f] text-gray-100 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#22c55e]">
            
        </textarea>
        <!-- BOTÃO -->

<div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3">
    <a href="dashboard.php" class="flex items-center justify-center gap-2 px-4 py-2.5 sm:py-3 bg-gray-700/50 hover:bg-gray-700 text-white rounded-lg sm:rounded-xl transition-all text-sm sm:text-base font-medium">
        <i class="fas fa-arrow-left"></i>
        Cancelar
    </a>
    <button
        type="submit"
        class="flex-1 h-11 sm:h-14 rounded-lg sm:rounded-xl text-white font-medium text-sm sm:text-base
               bg-gradient-to-r from-[#16a34a] to-[#22c55e]
               hover:opacity-90 transition flex items-center justify-center gap-2">

        <i class="fa-solid fa-floppy-disk text-sm sm:text-base"></i>
        <span>Registrar Vagas</span>

    </button>
</div>

</form>

</section>

</div>

</div>

</body>
</html>