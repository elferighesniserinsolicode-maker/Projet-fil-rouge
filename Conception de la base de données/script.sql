
CREATE DATABASE gestion_transport;
USE gestion_transport;

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(100),
    adresse TEXT
);



CREATE TABLE expeditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(50) UNIQUE NOT NULL,
    client_id INT NOT NULL,
    ville_depart VARCHAR(100) NOT NULL,
    ville_arrivee VARCHAR(100) NOT NULL,
    poids DECIMAL(10,2) NOT NULL,
    frais_transport DECIMAL(10,2) NOT NULL,
    date_depart DATE NOT NULL,
    statut ENUM('En attente','En cours de route','Livrée') DEFAULT 'En attente',

    FOREIGN KEY (client_id)
    REFERENCES clients(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);