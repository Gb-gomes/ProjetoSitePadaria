<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politica de privacidade | PAD</title>
    <link rel="stylesheet" href="privacy-policy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>

<body>
    <section class="all_page">
             <header class="header">

        <section class="logo">
            <img src="../img/logo.png" alt="Logo da padaria">
        </section>

        <nav class="menu">
            <a href="../index.php">Home</a>
            <a href="./Produtos.php">Produtos</a>
            <a href="../sobre_nos/index_sobre_nos.php">Sobre Nós</a>
            <a href="../contato/index_contato.php">Contato</a>
        </nav>

        <section class="group">

            <div class="carrinho">
                <i class="fa-solid fa-cart-plus"></i>
            </div>

            <div class="cadastro_conteiner">

                <?php if (isset($_SESSION['usuario_id'])): ?>

                    <div class="user-info">

                        <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?>
                            <span class="admin">
                                ADMIN - <?php echo $_SESSION['usuario_nome']; ?>
                            </span>
                        <?php else: ?>
                            <span class="user">
                                Olá, <?php echo $_SESSION['usuario_nome']; ?>
                            </span>
                        <?php endif; ?>

                        <a href="../loginPHP/login.php" class="button-logout">Sair</a>

                    </div>

                <?php else: ?>
                    <a href="../loginPHP/login.php" class="button-login">Login</a>
                <?php endif; ?>

            </div>

        </section>

    </header>

        <main class="container">
        <h1>Política de Privacidade</h1>

        <p>
            A Padaria Artesanal Delícias valoriza a sua privacidade e se compromete a proteger os dados pessoais dos visitantes do nosso site. Este documento explica como coletamos, usamos e protegemos suas informações em conformidade com a Lei nº 13.709/2018 (Lei Geral de Proteção de Dados Pessoais – LGPD), que dispõe sobre o tratamento de dados pessoais, inclusive nos meios digitais, por pessoa natural ou jurídica, com o objetivo de proteger os direitos fundamentais de liberdade e de privacidade.</p>

        <h2>Informações que coletamos</h2>
        <p>Podemos coletar as seguintes informações quando você visita nosso site ou interage conosco:</p>
        <ul>
            <li>Informações de contato, como nome, e-mail e telefone (quando você preenche formulários de contato ou faz um pedido);</li>
            <li>Informações de navegação, como endereço IP, tipo de navegador, páginas acessadas e tempo de permanência no site;</li>
            <li>Cookies, que ajudam a melhorar sua experiência de navegação.</li>
        </ul>

        <h2>Como usamos suas informações</h2>
        <p>Utilizamos seus dados para:</p>
        <ul>
            <li>Responder às suas mensagens, pedidos ou solicitações;</li>
            <li>Enviar informações sobre produtos, promoções e novidades (caso você opte por recebê-las);</li>
            <li>Melhorar nossos produtos, serviços e conteúdo do site;</li>
            <li>Garantir a segurança e o bom funcionamento do site.</li>
        </ul>

        <h2>Compartilhamento de informações</h2>
        <p>
            A Padaria Artesanal Delícias não vende nem compartilha suas informações pessoais com terceiros, exceto:
        </p>
        <ul>
            <li>Quando for necessário para processar pedidos ou entregas (ex: empresas de entrega);</li>
            <li>Quando exigido por lei ou por autoridades competentes.</li>
        </ul>

        <h2>Cookies</h2>
        <p>
            Nosso site pode usar cookies para melhorar a navegação. Você pode configurar seu navegador para recusar cookies, mas isso pode afetar algumas funcionalidades do site.
        </p>

        <h2>Segurança das informações</h2>
        <p>
            Adotamos medidas de segurança para proteger seus dados contra acesso não autorizado, alteração ou destruição. No entanto, nenhum sistema é 100% seguro — por isso, recomendamos que você também adote boas práticas de segurança online.
        </p>

        <h2>Seus direitos</h2>
        <p>Você pode:</p>
        <ul>
            <li>Solicitar acesso, correção ou exclusão de seus dados pessoais;</li>
            <li>Cancelar o recebimento de comunicações a qualquer momento;</li>
            <li>Solicitar mais informações sobre como tratamos seus dados.</li>
        </ul>
        <p>Para exercer seus direitos, entre em contato pelo e-mail: <strong>contato@padariadelicias.com.br</strong></p>

        <h2>Alterações nesta política</h2>
        <p>
            Podemos atualizar esta Política de Privacidade periodicamente. A versão mais recente estará sempre disponível neste site, com a data da última atualização.
        </p>

        <h2>Contato</h2>
        <p>
            Se tiver dúvidas sobre esta política ou sobre o uso dos seus dados, entre em contato conosco:
        </p>
        <p>
            📧 <strong>contato@padariadelicias.com.br</strong><br>
            📍 Rua dos Pães, 123 – Cataguases/MG
        </p>
    </main>

<footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="/privacy-policy/privacy-policy.html">Privacy Policy</a>
                <a href="/terms-of-servicy/terms-of-servicy.html">Terms of Service</a>
            </div>

            <div class="footer-socials">
                <a href="https://www.instagram.com/familiadelicias.br/?igsh=M3F0M3BkaGNkMmd4#"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@artesanaldelicias?lang=pt-BR" target="_blank"><i class="fab fa-tiktok"></i></a>
            </div>

            <p class="footer-copy">&copy; 2025 Padaria Artesanal Delícias. All rights reserved.</p>
        </div>

    </footer>
</body>

</html>