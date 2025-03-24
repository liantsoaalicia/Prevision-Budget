DROP DATABASE PrevisionBudget;

DROP TABLE solde;
DROP TABLE prevision;
DROP TABLE categorie;
DROP TABLE periode;
DROP TABLE departement;

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