DROP DATABASE PrevisionBudget;
CREATE DATABASE PrevisionBudget;
USE PrevisionBudget;

-- CREATE TABLE departement (
--     idDepartement INT AUTO_INCREMENT PRIMARY KEY,
--     nom VARCHAR(150),
--     mdp VARCHAR(50)
-- );
CREATE TABLE departement (
    idDepartement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150)
);

CREATE TABLE user (
    idUser INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    mdp VARCHAR(20),
    idDepartement INT REFERENCES departement(idDepartement)
);

CREATE TABLE periode (
    idPeriode INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    dateDebut DATE,
    dateFin DATE
);

-- Par ex : 
-- Categorie : Recette
-- Type : Vente
-- Nature : Vente de produits agricoles
CREATE TABLE categorie (
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    categorie ENUM('Depense', 'Recette'),
    types VARCHAR(150),
    nature VARCHAR(150)
);

CREATE TABLE prevision (
    idPrevision INT AUTO_INCREMENT PRIMARY KEY,
    idDepartement INT,
    idPeriode INT,
    idCategorie INT,
    prevision FLOAT,
    realisation FLOAT,
    valide INT, -- 1: valide, 0: mbola tsy valide
    FOREIGN KEY (idDepartement) REFERENCES departement(idDepartement),
    FOREIGN KEY (idPeriode) REFERENCES periode(idPeriode),
    FOREIGN KEY (idCategorie) REFERENCES categorie(idCategorie)
);

CREATE TABLE solde (
    idSolde INT AUTO_INCREMENT PRIMARY KEY,
    idDepartement INT,
    soldeDebut FLOAT,
    soldeFin FLOAT,
    FOREIGN KEY (idDepartement) REFERENCES departement(idDepartement)
);

-- CRM

-- 1. Table des clients
CREATE TABLE clients (
    idClient INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    age INT,
    sexe enum("Homme", "Femme"),
    classe enum ("eleve", "moyen", "bas"),
    dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categorieProduit(
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255)
);
-- 2. Table des produits
CREATE TABLE produits (
    idProduit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    description TEXT,
    idCategorie INT, 
    prix DECIMAL(10,2),
    quantiteStock INT,
    FOREIGN KEY (idCategorie) REFERENCES categorieProduit(idCategorie)
);

-- 3. Table des commandes
CREATE TABLE commandes (
    idCommande INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT,
    dateCommande DATETIME DEFAULT CURRENT_TIMESTAMP,
    montantTotal DECIMAL(10,2),
    statut VARCHAR(50), -- Exemple : EnAttente, Terminee, Annulee
    FOREIGN KEY (idClient) REFERENCES clients(idClient)
);

-- 4. Table des lignes de commande
CREATE TABLE ligneCommandes (
    idLigneCommande INT AUTO_INCREMENT PRIMARY KEY,
    idCommande INT,
    idProduit INT,
    quantite INT,
    prixUnitaire DECIMAL(10,2),
    FOREIGN KEY (idCommande) REFERENCES commandes(idCommande),
    FOREIGN KEY (idProduit) REFERENCES produits(idProduit)
);

CREATE TABLE evenement (
    idEvenement INT AUTO_INCREMENT PRIMARY KEY,
    nomEvenement VARCHAR(255),
    dateDebut DATE,
    dateFin DATE
);
-- 6. Table des actions CRM (actions spécifiques réalisées)
CREATE TABLE actionsCrm (
    idAction INT AUTO_INCREMENT PRIMARY KEY,
    idDepartement INT,
    typeAction VARCHAR(100),            -- Exemple : Email, Appel, Enquete, Promotion
    etapeAction VARCHAR(50),            -- Avant, Pendant, Apres
    idEvenement INT,
    dateAction DATETIME,
    coutsPrevision FLOAT,
    coutsRealisation FLOAT,
    validationFinance BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (idDepartement) REFERENCES departement(idDepartement),
    FOREIGN KEY (idEvenement) REFERENCES evenement(idEvenement)
);

CREATE TABLE reactionActionCrm (
    idReaction INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT,
    reaction enum("tsara", "ratsy"),
    FOREIGN KEY (idClient) REFERENCES clients(idClient)
);
-- 7. Table des retours clients (Apres)
CREATE TABLE retoursClients (
    idRetour INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT,
    idCommande INT,
    dateRetour DATETIME DEFAULT CURRENT_TIMESTAMP,
    avis enum("tsara", "ratsy"),
    commentaire TEXT,
    FOREIGN KEY (idClient) REFERENCES clients(idClient),
    FOREIGN KEY (idCommande) REFERENCES commandes(idCommande)
);

-- TICKET

-- Table des statuts de ticket
CREATE TABLE status_ticket (
    idStatus INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) -- Exemple: Ouvert, En cours, Résolu, Fermé
);

-- Table des agents
CREATE TABLE agents (
    idAgent INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100),
    coutHoraire DECIMAL(10,2)
);

-- Table des tickets
CREATE TABLE tickets (
    idTicket INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT,
    idStatus INT,
    sujet VARCHAR(255),
    description TEXT,
    priorite ENUM('basse', 'normale', 'haute'),
    fichier VARCHAR(255),
    dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idClient) REFERENCES clients(idClient),
    FOREIGN KEY (idStatus) REFERENCES status_ticket(idStatus)
);

ALTER TABLE tickets
ADD COLUMN clientHasResponded BOOLEAN DEFAULT FALSE;

CREATE TABLE ticket_status_history (
    idHistory INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT,
    idStatus INT,
    dateChangement DATETIME DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT, -- optionnel : ex. raison du changement de statut
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket),
    FOREIGN KEY (idStatus) REFERENCES status_ticket(idStatus)
);

-- Table de liaison ticket <-> agent (un ticket peut être attribué à un agent)
CREATE TABLE agent_ticket (
    idAgent INT,
    idTicket INT,
    dateAffectation DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idAgent, idTicket),
    FOREIGN KEY (idAgent) REFERENCES agents(idAgent),
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket)
);

-- Table des évaluations de tickets
CREATE TABLE evaluation_ticket (
    idEvaluation INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT,
    note INT CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    dateEvaluation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket)
);

CREATE TABLE discussion (
    idDiscussion INT AUTO_INCREMENT PRIMARY KEY,
    sujet VARCHAR(255),
    dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE discussion
ADD COLUMN idAgent INT NULL,
ADD COLUMN idClient INT NULL,
ADD COLUMN idTicket INT NULL,
ADD FOREIGN KEY (idAgent) REFERENCES agents(idAgent),
ADD FOREIGN KEY (idClient) REFERENCES clients(idClient),
ADD FOREIGN KEY (idTicket) REFERENCES tickets(idTicket);

-- Table des messages dans une discussion
CREATE TABLE discussion_ticket (
    idMessage INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT,
    auteur ENUM('client', 'agent'),
    idClient INT NULL, -- pour les messages côté client
    idAgent INT NULL,  -- pour les messages côté agent
    message TEXT,
    fichier VARCHAR(255),
    dateMessage DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket),
    FOREIGN KEY (idClient) REFERENCES clients(idClient),
    FOREIGN KEY (idAgent) REFERENCES agents(idAgent)
);


-- A AJOUTER !!!
ALTER TABLE agents ADD COLUMN idDepartement INT;
ALTER TABLE agents ADD CONSTRAINT fk_agents_departement FOREIGN KEY (idDepartement) REFERENCES departement(idDepartement);
ALTER TABLE discussion_ticket
ADD COLUMN idDiscussion INT,
ADD FOREIGN KEY (idDiscussion) REFERENCES discussion(idDiscussion);

ALTER TABLE discussion_ticket
DROP FOREIGN KEY discussion_ticket_ibfk_1;

ALTER TABLE discussion_ticket
DROP COLUMN idTicket;

-- Table pour l'historique des changements de priorité des tickets
CREATE TABLE ticket_priority_history (
    idHistoryPriority INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT,
    anciennePriorite ENUM('basse', 'normale', 'haute'),
    nouvellePriorite ENUM('basse', 'normale', 'haute'),
    idUser INT,
    dateChangement DATETIME DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT, -- optionnel : raison du changement de priorité
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket),
    FOREIGN KEY (idUser) REFERENCES user(idUser)
);

CREATE TABLE budget_ticket (
    idBudgetTicket INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NOT NULL,
    budgetPrevisionnel DECIMAL(10,2) NOT NULL,
    coutReel DECIMAL(10,2) DEFAULT NULL,
    valideFinance BOOLEAN DEFAULT FALSE,
    dateValidation DATETIME DEFAULT NULL,
    FOREIGN KEY (idTicket) REFERENCES tickets(idTicket)
);


