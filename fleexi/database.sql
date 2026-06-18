DROP DATABASE IF EXISTS fleexi;

CREATE DATABASE fleexi CHARACTER SET utf8 COLLATE utf8_general_ci;

USE fleexi;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    mail VARCHAR(150) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    photo_profil VARCHAR(255)
);

CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    photo VARCHAR(255) NOT NULL,
    categorie VARCHAR(100),
    id_user INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_annonce INT NOT NULL
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_expediteur INT NOT NULL,
    id_destinataire INT NOT NULL,
    id_annonce INT NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu TINYINT DEFAULT 0
);

INSERT INTO users (nom, prenom, mail, mdp) VALUES
('Dupont', 'Alice', 'alice@mail.com', '$2b$12$QBUm0RersSlkxAg2i/62d.AzcriR0XcjRzLQ1OwSov96byJEjhGA6'),
('Martin', 'Bob', 'bob@mail.com', '$2b$12$QBUm0RersSlkxAg2i/62d.AzcriR0XcjRzLQ1OwSov96byJEjhGA6');

INSERT INTO annonces (titre, prix, description, photo, categorie, id_user) VALUES
('Velo de ville', 120.00, 'Velo en bon etat, peu servi, ideal pour la ville.', 'velo2.jpg', 'Sport', 1),
('Canape 3 places', 250.00, 'Canape gris confortable, a venir chercher sur place.', 'canape2.png', 'Maison', 1),
('Iphone 11', 300.00, 'Telephone fonctionnel, ecran nickel, batterie 85%.', 'iphone2.png', 'High-Tech', 2);

INSERT INTO favoris (id_user, id_annonce) VALUES
(2, 1);

INSERT INTO messages (id_expediteur, id_destinataire, id_annonce, contenu) VALUES
(2, 1, 1, 'Bonjour, le velo est-il toujours disponible ?');
