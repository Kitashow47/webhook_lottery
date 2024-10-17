CREATE DATABASE IF NOT EXISTS webhook_db;

USE webhook_db;

CREATE TABLE IF NOT EXISTS webhook_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
