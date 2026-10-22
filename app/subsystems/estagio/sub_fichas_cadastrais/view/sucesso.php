<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro Recebido</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        :root {
            --site-bg: #1a1a1a;
            --site-panel: #232323;
            --site-green: #007a33;
            --site-green-bright: #00c250;
            --site-border: rgba(255, 255, 255, .1);
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

    </style>

</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-2xl bg-[#232323] border border-white/10 rounded-3xl p-10 card-shadow">

        <div class="text-center">

            <div class="mb-6">
                <i class="fa-solid fa-circle-check text-7xl text-[#00c250]"></i>
            </div>

            <h1 class="text-4xl font-semibold text-white mb-4">
                Cadastro Recebido!
            </h1>

            <p class="text-gray-300 text-lg leading-relaxed mb-8">
                Seus dados foram recebidos com sucesso.
                <br>
                Em breve nossa equipe realizará a análise das informações enviadas.
            </p>

            <div class="bg-[#007a33]/20 border border-[#00c250]/30 rounded-xl p-5 mb-8">
                <p class="text-green-300">
                    Obrigado por participar do programa de estágios da
                    Escola Salaberga.
                </p>
            </div>

            <button
                onclick="window.location.href='https://salaberga.com';"
                class="w-full h-14 rounded-xl text-white font-medium
                       bg-gradient-to-r from-[#007a33] to-[#00c250]
                       hover:from-[#00661f] hover:to-[#00a844] transition">

                <i class="fa-solid fa-check mr-2"></i>
                OK
            </button>

        </div>

    </div>

</body>
</html>
