<?php
// Script para inicializar o banco de dados
try {
    // Conectar ao MySQL sem especificar banco
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Ler e executar o script SQL
    $sql = file_get_contents('config/database.sql');
    
    // Dividir em comandos individuais
    $commands = explode(';', $sql);
    
    foreach ($commands as $command) {
        $command = trim($command);
        if (!empty($command)) {
            $pdo->exec($command);
        }
    }
    
    echo "✅ Banco de dados criado com sucesso!\n";
    echo "👑 Admin padrão: admin@cotemig.com / admin123\n";
    echo "🚀 Acesse: http://localhost:8000\n";
    
} catch (PDOException $e) {
    echo "❌ Erro ao criar banco: " . $e->getMessage() . "\n";
}
?>