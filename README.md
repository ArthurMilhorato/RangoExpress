# 🚀 Rango Express - Sistema de Cantina

Sistema web para gerenciamento da cantina do Colégio Cotemig, desenvolvido em PHP com padrão MVC e Repository.

## 🚀 Funcionalidades

### Para Alunos:
- Cadastro e login de usuários
- Visualização do cardápio
- Adição de itens ao carrinho
- Finalização de pedidos

### Para Administradores (Tias da Cantina):
- Gerenciamento completo do cardápio (CRUD)
- Visualização e processamento de pedidos
- Controle de status dos pedidos

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript
- **Banco de Dados**: MySQL
- **Arquitetura**: MVC com Repository Pattern
- **Segurança**: PDO com Prepared Statements

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)
- Extensão PDO MySQL habilitada

## 🔧 Instalação

1. **Clone o repositório**
   ```bash
   git clone [url-do-repositorio]
   cd rangodoreicotemig
   ```

2. **Configure o banco de dados**
   - Crie um banco MySQL chamado `rango_express`
   - Execute o script SQL em `config/database.sql`
   - Ajuste as credenciais em `config/database.php` se necessário

3. **Configure o servidor web**
   - Aponte o DocumentRoot para a pasta do projeto
   - Certifique-se de que o mod_rewrite está habilitado (Apache)

4. **Acesse o sistema**
   - URL: `http://localhost/`
   - Admin padrão: `admin@cotemig.com` / `admin123`

## 📁 Estrutura do Projeto

```
rangodoreicotemig/
├── config/
│   ├── database.php      # Configuração do banco
│   └── database.sql      # Script de criação do banco
├── controllers/
│   ├── AuthController.php    # Autenticação
│   ├── MenuController.php    # Cardápio e carrinho
│   └── AdminController.php   # Administração
├── models/
│   ├── User.php         # Modelo de usuário
│   ├── Item.php         # Modelo de item
│   └── Order.php        # Modelo de pedido
├── repositories/
│   ├── UserRepository.php    # Repositório de usuários
│   ├── ItemRepository.php    # Repositório de itens
│   └── OrderRepository.php   # Repositório de pedidos
├── views/
│   ├── layout.php       # Layout base
│   ├── login.php        # Página de login
│   ├── register.php     # Página de cadastro
│   ├── menu.php         # Cardápio
│   ├── cart.php         # Carrinho
│   └── admin.php        # Painel administrativo
├── public/
│   ├── css/
│   │   └── style.css    # Estilos principais
│   ├── js/
│   │   └── app.js       # JavaScript principal
│   └── images/          # Imagens dos produtos
├── index.php            # Arquivo principal de roteamento
├── .htaccess           # Configuração Apache
└── README.md           # Este arquivo
```

## 🎨 Design

O sistema utiliza um tema "real" com:
- **Cores principais**: Vermelho (#8B0000) e Dourado (#DAA520)
- **Design responsivo** com CSS Flexbox e Grid
- **Ícones temáticos** (👑, 🍽️, 🛒)
- **Interface intuitiva** e moderna

## 🔒 Segurança

- Senhas criptografadas com `password_hash()`
- Prepared Statements para prevenir SQL Injection
- Validação de dados no frontend e backend
- Controle de sessões e autenticação

## 📱 Responsividade

O sistema é totalmente responsivo e funciona em:
- Desktop
- Tablets
- Smartphones

## 🚀 Como Usar

### Para Alunos:
1. Acesse o sistema e faça seu cadastro
2. Faça login com suas credenciais
3. Navegue pelo cardápio
4. Adicione itens ao carrinho
5. Finalize seu pedido

### Para Administradores:
1. Faça login com credenciais de admin
2. Gerencie o cardápio (adicionar/editar/remover itens)
3. Visualize e processe pedidos
4. Atualize status dos pedidos

## 🤝 Contribuição

Este é um projeto educacional para o Colégio Cotemig. Sugestões e melhorias são bem-vindas!

## 📄 Licença

Este projeto é desenvolvido para fins educacionais.