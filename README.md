# Sistema de Padaria Web
Este projeto tem como objetivo desenvolver um sistema web para gerenciamento e vendas de produtos de uma padaria, com foco didático. O sistema permite que clientes realizem pedidos online e que administradores gerenciam produtos e pedidos.

**Cliente**
- Cadastro e login
- Visualização de produtos
- Adição ao carrinho
- Finalização de pedidos

**Administrador**
- Gerenciamento de produtos
- Controle de estoque
- Visualização e atualização de pedidos

**Regras de Negócio**
- Apenas usuários autenticados podem realizar pedidos
- O sistema valida o estoque antes da compra
- Produtos inativos não são exibidos
- O pedido só é finalizado após confirmação do pagamento
- O estoque é atualizado automaticamente após a finalização

**Banco de Dados**

O sistema utiliza um banco de dados relacional contendo as seguintes entidades:
- Usuários
- Produtos
- Categorias
- Pedidos
- Itens do pedido
- Pagamentos

**Fluxo do Sistema**
- Usuário se cadastra/loga
- Navega pelos produtos
- Adiciona itens ao carrinho
- Finaliza pedido
- Sistema valida estoque
- Pedido é criado
- Pagamento é confirmado
- Pedido é finalizado
<br> </br>
<h5>Tecnologias Utilizadas</h5>
- Front-end: HTML, CSS, JavaScript (React)
- Back-end: PHP
- Banco de Dados: MySQL
- Ferramentas: XAMPP / phpMyAdmin
<br> </br>

**Considerações Finais**
<br> </br>
Este projeto foi desenvolvido com fins educacionais, simulando um sistema real de e-commerce para padaria, aplicando conceitos de desenvolvimento web, banco de dados e regras de negócio.


