```html
<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<title>Colinha</title>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;

        background-image: url('x.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        font-family: Arial, Helvetica, sans-serif;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .formulario {
        width: 500px;

        padding: 35px 40px;

        /* Cor levemente diferente do fundo */
        background: rgba(255, 255, 255, 0.88);

        border-radius: 15px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.30);
    }

    h2 {
        margin-top: 0;
        margin-bottom: 30px;

        text-align: center;

        font-size: 26px;
        color: #222;
    }

    .campo {
        display: flex;
        align-items: center;

        margin-bottom: 15px;
    }

    .campo label {
        width: 250px;

        font-size: 17px;
        font-weight: bold;

        color: #333;
    }

    .campo input {
        flex: 1;

        height: 42px;

        padding: 5px 10px;

        border: 1px solid #aaa;
        border-radius: 6px;

        background: #fff;

        font-size: 18px;
        text-align: center;

        outline: none;
    }

    .campo input:focus {
        border-color: #666;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.20);
    }

    .botao {
        width: 100%;

        height: 45px;

        margin-top: 15px;

        border: none;
        border-radius: 7px;

        background: #333;
        color: white;

        font-size: 17px;
        font-weight: bold;

        cursor: pointer;
    }

    .botao:hover {
        background: #555;
    }

</style>

</head>

<body>

    <div class="formulario">

        <h2>Seus candidatos</h2>

        <form action="gerar.php" method="POST">

            <div class="campo">
                <label>1. Deputado Federal:</label>
                <input type="text" name="n1" required>
            </div>

            <div class="campo">
                <label>2. Deputado Estadual:</label>
                <input type="text" name="n2" required>
            </div>

            <div class="campo">
                <label>3. Senador 1:</label>
                <input type="text" name="n3" required>
            </div>

            <div class="campo">
                <label>4. Senador 2:</label>
                <input type="text" name="n4" required>
            </div>

            <div class="campo">
                <label>5. Governador:</label>
                <input type="text" name="n5" required>
            </div>

            <div class="campo">
                <label>6. Presidente</label>
                <input type="text" name="n6" required>
            </div>

            <input
                type="submit"
                value="Gerar"
                class="botao"
            >

        </form>

    </div>

</body>

</html>
```
