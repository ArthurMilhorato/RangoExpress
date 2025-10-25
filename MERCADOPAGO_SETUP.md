# 🔧 Configuração Mercado Pago

## 📋 Pré-requisitos

1. **Conta no Mercado Pago**: Crie uma conta em [mercadopago.com.br](https://mercadopago.com.br)
2. **Aplicação**: Crie uma aplicação no [Painel do Desenvolvedor](https://www.mercadopago.com.br/developers)

## 🔑 Configuração das Credenciais

### 1. Obter Access Token

1. Acesse: https://www.mercadopago.com.br/developers/panel/app
2. Selecione sua aplicação
3. Vá em "Credenciais"
4. Copie o **Access Token** (Produção ou Teste)

### 2. Configurar no Sistema

Edite o arquivo `config/mercadopago.php`:

```php
// Para TESTE (sandbox)
$this->access_token = 'TEST-SEU_TOKEN_DE_TESTE_AQUI';

// Para PRODUÇÃO
$this->access_token = 'APP_USR-SEU_TOKEN_DE_PRODUCAO_AQUI';
```

## 🧪 Modo Teste vs Produção

### Teste (Sandbox)
- Use tokens que começam com `TEST-`
- Pagamentos são simulados
- Não há cobrança real

### Produção
- Use tokens que começam com `APP_USR-`
- Pagamentos são reais
- Cobrança efetiva

## 📱 Testando PIX

### Contas de Teste
O Mercado Pago fornece contas de teste para simular pagamentos:

1. **Pagador**: test_user_123456789@testuser.com
2. **Recebedor**: test_user_987654321@testuser.com

### Fluxo de Teste
1. Gere um PIX no sistema
2. Use o app do Mercado Pago em modo teste
3. Escaneie o QR Code ou cole o código PIX
4. Confirme o pagamento
5. O sistema detectará automaticamente

## 🔒 Segurança

- **NUNCA** commite tokens reais no código
- Use variáveis de ambiente em produção
- Mantenha tokens seguros e privados

## 📞 Suporte

- Documentação: https://www.mercadopago.com.br/developers/pt/docs
- Suporte: https://www.mercadopago.com.br/ajuda