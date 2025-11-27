<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar variáveis de mensagem
$mensagem = "";
$tipo_mensagem = "";

// Verificar se o utilizador está logado (necessário para o id_utilizador)
if (!isset($_SESSION['id_utilizador'])) {
    // Redireciona para login se não estiver logado (ajusta o link conforme necessário)
    // header("Location: login.php"); 
    // exit();
    
    // Para teste, vamos assumir um ID fixo se não houver sessão (REMOVER EM PRODUÇÃO)
    $id_utilizador_atual = 1; 
} else {
    $id_utilizador_atual = $_SESSION['id_utilizador'];
}

// Processar o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ligação à Base de Dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "easypark";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $mensagem = "Erro de conexão: " . $conn->connect_error;
        $tipo_mensagem = "erro";
    } else {
        // Receber e limpar dados
        $avaliacao = $_POST['avaliacao'] ?? 0; // Valor de 1 a 5
        $sugestao = trim($_POST['sugestao']);
        $data_hora = date('Y-m-d H:i:s');

        // Validação simples
        if ($avaliacao < 1 || $avaliacao > 5) {
            $mensagem = "Por favor, selecione uma classificação em estrelas.";
            $tipo_mensagem = "erro";
        } elseif (empty($sugestao)) {
            $mensagem = "Por favor, escreva a sua sugestão.";
            $tipo_mensagem = "erro";
        } else {
            // Inserir na tabela 'formulario'
            $sql = "INSERT INTO formulario (avaliacao_form, mensagem_form, data_hora_form, id_utilizador) VALUES (?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssi", $avaliacao, $sugestao, $data_hora, $id_utilizador_atual);
                
                if ($stmt->execute()) {
                    $mensagem = "Obrigado! A sua sugestão foi enviada com sucesso.";
                    $tipo_mensagem = "sucesso";
                } else {
                    $mensagem = "Erro ao enviar: " . $stmt->error;
                    $tipo_mensagem = "erro";
                }
                $stmt->close();
            } else {
                $mensagem = "Erro na preparação da consulta.";
                $tipo_mensagem = "erro";
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Sugestões</title>
    <!-- Adicionar FontAwesome para as Estrelas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            min-height: 100vh;
            color: #334155;
            display: flex;
            flex-direction: column;
        }

        /* Reutilizando estilos do header/footer se existirem, senão mantemos simples */
        .wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 40px;
            width: 100%;
            max-width: 550px;
            animation: slideUp 0.6s ease-out;
        }

        .header-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-form h1 {
            color: #1e3a8a;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .header-form p {
            color: #64748b;
            line-height: 1.5;
        }

        /* --- Estilo das Estrelas --- */
        .rating-box {
            display: flex;
            justify-content: center;
            flex-direction: row-reverse; /* Inverte para o CSS funcionar corretamente */
            gap: 10px;
            margin-bottom: 25px;
        }

        .rating-box input {
            display: none;
        }

        .rating-box label {
            font-size: 35px;
            color: #cbd5e1;
            cursor: pointer;
            transition: all 0.2s;
        }

        /* Ao passar o rato ou selecionar */
        .rating-box label:hover,
        .rating-box label:hover ~ label,
        .rating-box input:checked ~ label {
            color: #fbbf24; /* Amarelo Ouro */
            transform: scale(1.1);
        }

        /* --- Campos de Texto --- */
        .input-group {
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e3a8a;
        }

        textarea {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: inherit;
        }

        textarea:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* --- Botões --- */
        .buttons {
            display: flex;
            gap: 15px;
        }

        button {
            flex: 1;
            padding: 14px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-submit {
            background-color: #1e3a8a;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.4);
        }

        .btn-submit:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-back:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        /* --- Feedback (Sucesso/Erro) --- */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .alert.sucesso { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.erro { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .buttons { flex-direction: column; }
            .form-container { padding: 25px; }
            .rating-box label { font-size: 30px; }
        }
    </style>
</head>
<body>
    
    <!-- Incluir Header se existir -->
    <?php include('header.php'); ?>

    <div class="wrapper">
        <div class="form-container">
            <div class="header-form">
                <h1>Sua Opinião Conta</h1>
                <p>Ajude-nos a melhorar a experiência EasyPark.</p>
            </div>

            <!-- Mensagens de Feedback -->
            <?php if (!empty($mensagem)): ?>
                <div class="alert <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
                <!-- Se for sucesso, podemos esconder o form ou redirecionar. Aqui mantemos. -->
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                
                <!-- Avaliação com Estrelas (Mapeia para 1-5 na DB) -->
                <div class="input-group" style="text-align: center;">
                    <label>Como avalia o funcionamento da cancela?</label>
                    <div class="rating-box">
                        <!-- Ordem inversa no HTML para o CSS funcionar com o seletor ~ -->
                        <input type="radio" name="avaliacao" value="5" id="star5"><label for="star5" class="fas fa-star"></label>
                        <input type="radio" name="avaliacao" value="4" id="star4"><label for="star4" class="fas fa-star"></label>
                        <input type="radio" name="avaliacao" value="3" id="star3"><label for="star3" class="fas fa-star"></label>
                        <input type="radio" name="avaliacao" value="2" id="star2"><label for="star2" class="fas fa-star"></label>
                        <input type="radio" name="avaliacao" value="1" id="star1"><label for="star1" class="fas fa-star"></label>
                    </div>
                </div>

                <div class="input-group">
                    <label for="sugestao">Comentários ou Sugestões:</label>
                    <textarea id="sugestao" name="sugestao" placeholder="O sistema é rápido? A leitura da matrícula falhou? Conte-nos..." required></textarea>
                </div>

                <div class="buttons">
                    <button type="button" class="btn-back" onclick="window.location.href='index.php'">Voltar</button>
                    <button type="submit" class="btn-submit">Enviar Sugestão</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>
</html>