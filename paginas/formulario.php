<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_utilizador'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Sugestões</title>
    <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
    
    <!-- FontAwesome para os ícones das estrelas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="../css/formulario.css">
</head>
<body>
    
    <?php include('header.php'); ?>

    <div class="wrapper">
        <div class="form-container">
            <div class="header-form">
                <h1>Sua Opinião Conta</h1>
                <p>Ajude-nos a melhorar a experiência EasyPark.</p>
            </div>

            <!-- Div para mensagens de feedback (JS vai controla) INVISIVEL-->
            <div id="msg-feedback" class="alert" style="display:none;"></div>

            <form id="form-sugestao">
                
                <div class="input-group" style="text-align: center;">
                    <label>Como avalia o funcionamento da cancela?</label>
                    
                    <!-- 
                       Atenção:css está flex-direction: row-reverse.
                       Por isso, o HTML deve estar na ordem: 5, 4, 3, 2, 1.
                       Visualmente aparecerá 1, 2, 3, 4, 5.
                    -->
                    <div class="rating-box">
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
                    <button type="button" class="btn-back" onclick="window.location.href='perfil.php'">Voltar</button>
                    
                    <button type="submit" class="btn-submit">Enviar Sugestão</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script src="../js/formulario.js"></script>

</body>
</html>