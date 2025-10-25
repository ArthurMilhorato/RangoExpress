// Função para adicionar item ao carrinho
function addToCart(itemId) {
    const quantity = document.getElementById(`quantity-${itemId}`).value;

    fetch('/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item adicionado ao carrinho!', 'success');
            updateCartCount();
            // Salvar carrinho no localStorage
            saveCartToLocalStorage();
        } else {
            showAlert('Erro ao adicionar item', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Função para mostrar alertas
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Função para atualizar contador do carrinho
function updateCartCount() {
    // Implementar se necessário
}

// Remover item do carrinho
function removeFromCart(itemId) {
    if (!confirm('Remover este item do carrinho?')) {
        return;
    }

    fetch('/remove-from-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item removido do carrinho!', 'success');
            // Remover item do DOM e atualizar totais
            const itemElement = document.getElementById(`cart-item-${itemId}`);
            if (itemElement) {
                itemElement.remove();
            }
            updateCartTotal();
            updateCartCount();
            saveCartToLocalStorage();
        } else {
            showAlert('Erro ao remover item', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Atualizar quantidade do item
function updateQuantity(itemId, change) {
    const quantityInput = document.getElementById(`quantity-${itemId}`);
    let newQuantity = parseInt(quantityInput.value) + change;
    
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 10) newQuantity = 10;
    
    quantityInput.value = newQuantity;
    changeQuantity(itemId, newQuantity);
}

// Alterar quantidade do item
function changeQuantity(itemId, quantity) {
    if (quantity < 1 || quantity > 10) {
        showAlert('Quantidade deve ser entre 1 e 10', 'error');
        return;
    }

    fetch('/update-cart-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateItemTotal(itemId, quantity);
            updateCartTotal();
            saveCartToLocalStorage();
        } else {
            showAlert(data.message || 'Erro ao atualizar quantidade', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Atualizar total do item
function updateItemTotal(itemId, quantity) {
    const itemTotalElement = document.getElementById(`item-total-${itemId}`);
    if (itemTotalElement) {
        const price = parseFloat(itemTotalElement.getAttribute('data-price'));
        const newTotal = price * quantity;
        itemTotalElement.textContent = `R$ ${newTotal.toFixed(2).replace('.', ',')}`;
    }
}

// Atualizar total do carrinho
function updateCartTotal() {
    let total = 0;
    const itemTotalElements = document.querySelectorAll('[id^="item-total-"]');

    itemTotalElements.forEach(element => {
        const priceText = element.textContent.replace('R$ ', '').replace(',', '.');
        const itemTotal = parseFloat(priceText);
        if (!isNaN(itemTotal)) {
            total += itemTotal;
        }
    });

    const cartTotalElement = document.querySelector('.total-amount');
    if (cartTotalElement) {
        cartTotalElement.textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;
    }
}

// Função para confirmar exclusão
function confirmDelete(message) {
    return confirm(message || 'Tem certeza que deseja excluir?');
}

// Validação de formulários
document.addEventListener('DOMContentLoaded', function() {
    // Validação de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#dc3545';
                showAlert('Email inválido', 'error');
            } else {
                this.style.borderColor = '#ddd';
            }
        });
    });
    
    // Validação de preço
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9.,]/g, '');
            value = value.replace(',', '.');
            this.value = value;
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Função para salvar carrinho no localStorage
function saveCartToLocalStorage() {
    if (typeof(Storage) !== "undefined") {
        try {
            // Obter dados do carrinho da sessão PHP via AJAX
            fetch('/get-cart-data', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.cart) {
                    localStorage.setItem('cart', JSON.stringify(data.cart));
                }
            })
            .catch(error => {
                console.log('Erro ao salvar carrinho:', error);
            });
        } catch (e) {
            console.log('Erro ao salvar carrinho no localStorage:', e);
        }
    }
}

// Função para carregar carrinho do localStorage
function loadCartFromLocalStorage() {
    if (typeof(Storage) !== "undefined") {
        const cartData = localStorage.getItem('cart');
        const cartRestored = localStorage.getItem('cartRestored');

        // Só restaurar se há dados do carrinho e não foi restaurado ainda nesta sessão
        if (cartData && cartRestored !== 'true') {
            try {
                const cart = JSON.parse(cartData);
                // Enviar dados para o servidor para restaurar a sessão
                fetch('/restore-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cart: cart })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Marcar como restaurado para evitar loop infinito
                        localStorage.setItem('cartRestored', 'true');
                        // Não recarregar a página, apenas atualizar o DOM se necessário
                        console.log('Carrinho restaurado com sucesso');
                    }
                })
                .catch(error => {
                    console.log('Erro ao restaurar carrinho:', error);
                });
            } catch (e) {
                console.log('Erro ao carregar carrinho do localStorage:', e);
            }
        }
    }
}

// Carregar carrinho quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    // Removido para evitar refresh infinito
    // loadCartFromLocalStorage();
});

// Função para modal de edição de item (admin) - REMOVIDA PARA EVITAR CONFLITOS
// A função openEditModal está definida em views/admin.php

