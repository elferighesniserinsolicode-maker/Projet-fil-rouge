-- =============================================
-- Atlas Transport Maroc — Base de données
-- =============================================

CREATE DATABASE IF NOT EXISTS atlas_transport
CHARACTER SET utf8 COLLATE utf8_general_ci;

USE atlas_transport;

-- Table clients
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(100),
    adresse TEXT
);

-- Table expeditions
CREATE TABLE IF NOT EXISTS expeditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(50) UNIQUE NOT NULL,
    client_id INT NOT NULL,
    ville_depart VARCHAR(100),
    ville_arrivee VARCHAR(100),
    poids DECIMAL(10,2),
    frais_transport DECIMAL(10,2),
    date_depart DATE,
    statut ENUM('En attente','En cours de route','Livrée') DEFAULT 'En attente',
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Données de test
INSERT INTO clients (nom, prenom, telephone, email, adresse) VALUES
('Benali',  'Karim',  '0612345678', 'karim@email.com',  'Casablanca'),
('Idrissi', 'Sara',   '0698765432', 'sara@email.com',   'Rabat'),
('Tazi',    'Omar',   '0711223344', 'omar@email.com',   'Marrakech'),
('Alaoui',  'Fatima', '0655443322', 'fatima@email.com', 'Fès');

INSERT INTO expeditions
(reference, client_id, ville_depart, ville_arrivee, poids, frais_transport, date_depart, statut) VALUES
('EXP-2026-001', 1, 'Casablanca', 'Marrakech', 42,  350, '2026-01-15', 'Livrée'),
('EXP-2026-002', 2, 'Rabat',      'Fès',       87,  520, '2026-01-18', 'En cours de route'),
('EXP-2026-003', 3, 'Tanger',     'Agadir',    120, 780, '2026-01-20', 'En attente'),
('EXP-2026-004', 4, 'Fès',        'Oujda',     65,  410, '2026-01-22', 'Livrée');