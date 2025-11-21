CREATE DATABASE IF NOT EXISTS memorygame;
USE memorygame;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    score INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

create table if not exists cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL
);

INSERT INTO users (username, score) VALUES
('player1', 999999),
('player2', 888888),
('player3', 777777),
('player4', 766666),
('player5', 755555),
('player6', 744444),
('player7', 733333),
('player8', 722222),
('player9', 711111),
('player10', 700000),
('player11', 690000),
('player12', 680000),
('player13', 670000),
('player14', 660000),
('player15', 650000),
('player16', 640000),
('player17', 630000),
('player18', 620000),
('player19', 610000),
('player20', 600000),
('player21', 590000),
('player22', 580000),
('player23', 570000),
('player24', 560000),
('player25', 550000),
('player26', 540000),
('player27', 530000),
('player28', 520000),
('player29', 510000),
('player30', 500000);