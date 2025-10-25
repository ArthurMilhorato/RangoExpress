<?php
// Script para corrigir a senha do admin
try {
    $pdo = new PDO("mysql:host=localhost;dbname=rango_do_rei", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Gerar hash correto para "admin123"
    $password = "admin123";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "Hash gerado: " . $hash . "\n";
    
    // Atualizar a senha do admin
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@cotemig.com'");
    $stmt->execute([$hash]);
    
    echo "✅ Senha do admin atualizada com sucesso!\n";
    echo "👑 Login: admin@cotemig.com\n";
    echo "🔑 Senha: admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>