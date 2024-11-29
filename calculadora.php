<?php
session_start(); 

if (isset($_POST['limpar_historico'])) {
   
    unset($_SESSION['historico']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora Simples com Histórico</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6e7dff, #4c62f5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease;
            
        }

        .container:hover {
            transform: scale(1.05);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #4c62f5;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input, select, button {
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s ease, background-color 0.3s ease;
        }

        input:focus, select:focus, button:hover {
            border-color: #4c62f5;
            background-color: #f5faff;
        }

        button {
            background-color: #4c62f5;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
            margin-bottom: 5px;
        }

        button:hover {
            background-color: #395ce2;
        }

        .result {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            background-color: #f7f9ff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .result.error {
            color: #ff4d4d;
            background-color: #ffe6e6;
        }

        .history {
            margin-top: 30px;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f7f7f7;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .history ul {
            list-style-type: none;
            padding: 0;
        }

        .history li {
            background-color: #fff;
            color: #333;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Calculadora Simples com Histórico</h1>
        <form method="POST" action="">
            <input type="number" name="num1" placeholder="Digite o primeiro número" required>
            <input type="number" name="num2" placeholder="Digite o segundo número" required>
            <select name="operacao" required>
                <option value="soma">Soma</option>
                <option value="subtracao">Subtração</option>
                <option value="multiplicacao">Multiplicação</option>
                <option value="divisao">Divisão</option>
                <option value="exponenciacao">Exponenciação</option>
                <option value="raiz_quadrada">Raiz Quadrada</option>
                <option value="fatorial">Fatorial</option>
                <option value="modulo">Módulo</option>
            </select>
            <button type="submit">Calcular</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['limpar_historico'])) {
            $num1 = $_POST['num1'];
            $num2 = $_POST['num2'];
            $operacao = $_POST['operacao'];

            $resultado = null;
            $errorClass = '';  

            switch ($operacao) {
                case 'soma':
                    $resultado = $num1 + $num2;
                    break;
                case 'subtracao':
                    $resultado = $num1 - $num2;
                    break;
                case 'multiplicacao':
                    $resultado = $num1 * $num2;
                    break;
                case 'divisao':
                    if ($num2 != 0) {
                        $resultado = $num1 / $num2;
                    } else {
                        $resultado = "Erro: divisão por zero!";
                        $errorClass = 'error';
                    }
                    break;
                case 'exponenciacao':
                    $resultado = pow($num1, $num2);
                    break;
                case 'raiz_quadrada':
                    if ($num1 < 0) {
                        $resultado = "Erro: número negativo para raiz quadrada!";
                        $errorClass = 'error';
                    } else {
                        $resultado = sqrt($num1);
                    }
                    break;
                case 'fatorial':
                    if ($num1 < 0 || $num1 != floor($num1)) {
                        $resultado = "Erro: fatorial só para números inteiros não negativos!";
                        $errorClass = 'error';
                    } else {
                        $resultado = 1;
                        for ($i = 1; $i <= $num1; $i++) {
                            $resultado *= $i;
                        }
                    }
                    break;
                case 'modulo':
                    $resultado = $num1 % $num2;
                    break;
                default:
                    $resultado = "Operação inválida!";
                    $errorClass = 'error';
            }

            $historico = isset($_SESSION['historico']) ? $_SESSION['historico'] : [];
            $operacaoFormatada = ucfirst(str_replace('_', ' ', $operacao));
            $historico[] = "{$num1} {$operacaoFormatada} {$num2} = {$resultado}";
            $_SESSION['historico'] = $historico;

            echo "<div class='result $errorClass'>Resultado: $resultado</div>";
        }
        ?>

        <form method="POST" action="">
            <button type="submit" name="limpar_historico">Limpar Histórico</button>
        </form>

        <div class="history">
            <h3>Histórico de Cálculos:</h3>
            <ul>
                <?php
                if (isset($_SESSION['historico'])) {
                    foreach ($_SESSION['historico'] as $registro) {
                        echo "<li>$registro</li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
