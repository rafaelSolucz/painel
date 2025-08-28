CREATE DATABASE IF NOT EXISTS plataforma_dashboards;
USE plataforma_dashboards;

-- Tabela de 'usuarios'
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role ENUM('admin', 'planejamento', 'control') DEFAULT 'control'
);

-- Tabela de 'dashboards'
CREATE TABLE IF NOT EXISTS dashboards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    url_iframe TEXT NOT NULL
);

-- Tabela de 'acessos_dashboard'
CREATE TABLE IF NOT EXISTS acessos_dashboard (
    id_dashboard INT NOT NULL,
    role ENUM('admin', 'planejamento', 'control') NOT NULL,
    PRIMARY KEY (id_dashboard, role),
    FOREIGN KEY (id_dashboard) REFERENCES dashboards(id) ON DELETE CASCADE
);

-- População inicial com um usuário administrador
INSERT INTO usuarios (email, senha, role)
VALUES ('admin@dashboard.com', '$2y$10$70Lt/yt6M/NCvbocqqDCh.NaHkNx4KMa33QoJ.hN45CzZKaeQh4ze', 'admin');

-- A senha para o usuário 'admin@dashboard.com' é 'admin01'
-- Nota: Esta senha é um hash gerado com password_hash('admin01', PASSWORD_DEFAULT)