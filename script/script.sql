DROP DATABASE PrevisionBudget;
CREATE DATABASE PrevisionBudget;
USE PrevisionBudget;

CREATE TABLE departement (
    idDepartement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    mdp VARCHAR(50)
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
    validationFinance Boolean,
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


DROP TABLE solde;
DROP TABLE prevision;
DROP TABLE categorie;
DROP TABLE periode;
DROP TABLE departement;
DROP TABLE clients;
DROP TABLE categorieProduit;
DROP TABLE produits;
DROP TABLE commandes;
DROP TABLE ligneCommandes;
DROP TABLE actionsCrm;
DROP TABLE reactionActionCrm;
DROP TABLE retoursClients;
