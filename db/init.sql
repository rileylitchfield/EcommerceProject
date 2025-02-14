-- Sample database initialization
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- Sample data (optional, remove for production)
INSERT INTO products (name, price) VALUES
('Sample Product 1', 19.99),
('Sample Product 2', 29.99),
('Sample Product 3', 39.99); 