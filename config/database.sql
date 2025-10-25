CREATE DATABASE IF NOT EXISTS rango_do_rei;
USE rango_do_rei;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'processado', 'entregue') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Inserir admin padrão (senha: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES 
('Tia da Cantina', 'admin@cotemig.com', '$2y$10$A/9sqqxdG/eoXHYQIWshQOC7E00COK.Nc/XCZhxFKLmdAEM4YjYiC', TRUE);

-- Inserir alguns itens de exemplo
INSERT INTO items (name, description, price, image) VALUES 
('Pão de Açúcar', 'Delicioso pão de açúcar tradicional', 3.50, 'pao-acucar.jpg'),
('Coxinha Real', 'Coxinha especial da casa', 4.00, 'coxinha.jpg'),
('Suco do Rei', 'Suco natural de frutas', 5.00, 'suco.jpg');