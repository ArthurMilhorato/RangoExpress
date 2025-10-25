<?php
$title = 'Admin - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>🍽️ Painel Administrativo</h1>
        <p style="color: #FFD700; margin: 10px 0 0 0; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Controle total do seu negócio culinário! ⚡🍽️
        </p>
    </div>

    <!-- Gerenciar Itens -->
    <div class="admin-section">
        <h2 style="color: #8B0000; margin: 30px 0 20px 0; font-size: 1.8rem; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 2rem;">🍽️</span> Gerenciar Cardápio
        </h2>

        <div class="add-item-form" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 25px; border-radius: 15px; margin-bottom: 30px; border: 2px solid #DAA520; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(45deg, #8B0000, #DAA520); opacity: 0.1; border-radius: 0 15px 0 50px;"></div>
            <h3 style="color: #8B0000; margin-bottom: 20px; font-size: 1.4rem; position: relative; z-index: 1;">➕ Adicionar Novo Item</h3>
            <form method="POST" action="/admin/create-item" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="name" style="font-weight: 600; color: #8B0000;">Nome do Item:</label>
                        <input type="text" id="name" name="name" class="form-control" required style="border: 2px solid #DAA520;">
                    </div>
                    <div class="form-group">
                        <label for="price" style="font-weight: 600; color: #8B0000;">Preço (R$):</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" required style="border: 2px solid #DAA520;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="description" style="font-weight: 600; color: #8B0000;">Descrição:</label>
                    <textarea id="description" name="description" class="form-control" rows="3" style="border: 2px solid #DAA520; resize: vertical;"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="image_file" style="font-weight: 600; color: #8B0000;">📁 Upload de Imagem:</label>
                        <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*" style="border: 2px solid #DAA520;">
                        <small class="form-text text-muted">Selecione uma imagem (JPG, PNG, GIF - máx. 5MB)</small>
                    </div>
                    <div class="form-group">
                        <label for="image_url" style="font-weight: 600; color: #8B0000;">🔗 Ou URL da Imagem:</label>
                        <input type="url" id="image_url" name="image_url" class="form-control" placeholder="https://exemplo.com/imagem.jpg" style="border: 2px solid #DAA520;">
                        <small class="form-text text-muted">Cole o link de uma imagem online</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" style="font-size: 16px; font-weight: 600; padding: 12px 30px;">✨ Adicionar Item</button>
            </form>
        </div>

        <div class="items-table-container" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <table class="table">
                <thead>
                    <tr>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Nome</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Descrição</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Preço</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr style="transition: all 0.2s ease;">
                            <td style="font-weight: 600; color: #8B0000;"><?= htmlspecialchars($item->name) ?></td>
                            <td style="color: #666;"><?= htmlspecialchars($item->description) ?></td>
                            <td style="font-weight: 700; color: #DAA520; font-size: 1.1rem;">R$ <?= number_format($item->price, 2, ',', '.') ?></td>
                            <td>
                                <button data-id="<?= $item->id ?>"
                                        data-name="<?= htmlspecialchars($item->name) ?>"
                                        data-description="<?= htmlspecialchars($item->description) ?>"
                                        data-price="<?= $item->price ?>"
                                        data-image="<?= htmlspecialchars($item->image ?? '') ?>"
                                        onclick="openEditModal(this)"
                                        class="btn btn-primary" style="margin-right: 8px; padding: 8px 16px; font-size: 14px;">✏️ Editar</button>
                                <a href="/admin/delete-item?id=<?= $item->id ?>"
                                   onclick="return confirmDelete('Tem certeza que deseja excluir este item?')"
                                   class="btn btn-danger" style="padding: 8px 16px; font-size: 14px;">🗑️ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gerenciar Pedidos -->
    <div class="admin-section">
        <h2 style="color: #8B0000; margin: 40px 0 20px 0; font-size: 1.8rem; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 2rem;">📦</span> Gerenciar Pedidos
        </h2>

        <div class="orders-table-container" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
            <table class="table">
                <thead>
                    <tr>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">ID</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Cliente</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Total</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Status</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Data</th>
                        <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="transition: all 0.2s ease;">
                            <td style="font-weight: 700; color: #8B0000;">#<?= $order->id ?></td>
                            <td style="font-weight: 600; color: #495057;"><?= htmlspecialchars($order->user_name) ?></td>
                            <td style="font-weight: 700; color: #DAA520; font-size: 1.1rem;">R$ <?= number_format($order->total, 2, ',', '.') ?></td>
                            <td><span class="status status-<?= $order->status ?>" style="font-weight: 600;"><?= ucfirst($order->status) ?></span></td>
                            <td style="color: #666;"><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                            <td>
                                <form method="POST" action="/admin/update-order" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding: 8px 12px; border: 2px solid #DAA520; border-radius: 8px; background: #fafafa; font-weight: 600; color: #8B0000;">
                                        <option value="pendente" <?= $order->status === 'pendente' ? 'selected' : '' ?>>⏳ Pendente</option>
                                        <option value="processado" <?= $order->status === 'processado' ? 'selected' : '' ?>>👨‍🍳 Processando</option>
                                        <option value="entregue" <?= $order->status === 'entregue' ? 'selected' : '' ?>>✅ Entregue</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Link para Relatório de Vendas -->
    <div class="sales-report-section" style="text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; border: 2px solid #DAA520;">
        <h3 style="color: #8B0000; margin-bottom: 15px; font-size: 1.5rem;">📊 Relatórios e Analytics</h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 1.1rem;">Acompanhe o desempenho do seu negócio com relatórios detalhados</p>
        <a href="/admin/sales-report" class="btn btn-primary" style="background: linear-gradient(135deg, #DAA520 0%, #8B0000 100%); border: none; padding: 15px 30px; font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
            📈 Ver Relatório de Vendas
        </a>
    </div>
</div>

<style>
/* Enhanced Admin Styles */
.admin-section {
    margin-bottom: 40px;
}

.add-item-form {
    position: relative;
}

.items-table-container,
.orders-table-container {
    border: 2px solid #DAA520;
}

.table tr:hover {
    background: linear-gradient(135deg, #fff8dc, #f5f5dc) !important;
    transform: scale(1.01);
}

.btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

select {
    transition: all 0.3s ease;
}

select:focus {
    border-color: #8B0000 !important;
    box-shadow: 0 0 0 3px rgba(139,0,0,0.1) !important;
    transform: scale(1.05);
}

/* Enhanced Modal Styles */
.modal-content {
    border-radius: 15px;
    border: 2px solid #DAA520;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .add-item-form div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }

    .table {
        font-size: 14px;
    }

    .table th,
    .table td {
        padding: 10px 8px;
    }

    .btn {
        padding: 10px 15px;
        font-size: 14px;
    }
}
</style>

<script>
function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const description = button.getAttribute('data-description');
    const price = button.getAttribute('data-price');
    const image = button.getAttribute('data-image');

    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-price').value = price;

    // Limpar campos de imagem
    document.getElementById('edit-image_file').value = '';
    document.getElementById('edit-image_url').value = '';

    // Mostrar preview da imagem atual se existir
    const previewDiv = document.getElementById('current-image-preview');
    if (image) {
        if (image.match(/^https?:\/\//)) {
            // URL externa
            previewDiv.innerHTML = '<p><strong>Imagem atual:</strong></p><img src="' + image + '" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">';
        } else {
            // Arquivo local
            previewDiv.innerHTML = '<p><strong>Imagem atual:</strong></p><img src="/public/images/' + image + '" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">';
        }
    } else {
        previewDiv.innerHTML = '<p><em>Nenhuma imagem definida</em></p>';
    }

    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function confirmDelete(message) {
    return confirm('⚠️ ' + message + '\n\nEsta ação não pode ser desfeita!');
}
</script>

<!-- Modal de Edição -->
<div id="editModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
    <div class="modal-content" style="background: white; margin: 5% auto; padding: 30px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
        <div style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 24px; color: #8B0000;" onclick="closeModal()">×</div>
        <h3 style="color: #8B0000; margin-bottom: 25px; font-size: 1.5rem; display: flex; align-items: center; gap: 10px;">
            <span>✏️</span> Editar Item
        </h3>
        <form method="POST" action="/admin/update-item" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id">
            <div class="form-group">
                <label for="edit-name" style="font-weight: 600; color: #8B0000;">Nome do Item:</label>
                <input type="text" id="edit-name" name="name" class="form-control" required style="border: 2px solid #DAA520;">
            </div>
            <div class="form-group">
                <label for="edit-description" style="font-weight: 600; color: #8B0000;">Descrição:</label>
                <textarea id="edit-description" name="description" class="form-control" rows="3" style="border: 2px solid #DAA520; resize: vertical;"></textarea>
            </div>
            <div class="form-group">
                <label for="edit-price" style="font-weight: 600; color: #8B0000;">Preço (R$):</label>
                <input type="number" id="edit-price" name="price" class="form-control" step="0.01" required style="border: 2px solid #DAA520;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="edit-image_file" style="font-weight: 600; color: #8B0000;">📁 Nova Imagem:</label>
                    <input type="file" id="edit-image_file" name="image_file" class="form-control" accept="image/*" style="border: 2px solid #DAA520;">
                    <small class="form-text text-muted">Selecione uma nova imagem</small>
                </div>
                <div class="form-group">
                    <label for="edit-image_url" style="font-weight: 600; color: #8B0000;">🔗 Ou URL:</label>
                    <input type="url" id="edit-image_url" name="image_url" class="form-control" placeholder="https://..." style="border: 2px solid #DAA520;">
                    <small class="form-text text-muted">Link de imagem online</small>
                </div>
            </div>
            <div id="current-image-preview" style="margin: 15px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;"></div>
            <div style="text-align: right; border-top: 2px solid #DAA520; padding-top: 20px;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="margin-right: 15px; padding: 10px 20px;">❌ Cancelar</button>
                <button type="submit" class="btn btn-success" style="padding: 10px 20px;">💾 Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
