
-- 1. Table des clients
CREATE TABLE clients (
    idClient INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    age INT,
    sexe enum("Homme", "Femme"),
    classe enum ("eleve", "moyen", "bas")
    dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categorie (
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255)
)
-- 2. Table des produits
CREATE TABLE produits (
    idProduit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    description TEXT,
    idCategorie INT, 
    prix DECIMAL(10,2),
    quantiteStock INT
    FOREIGN KEY (idCategorie) REFERENCES categorie(idCategorie)
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

-- 6. Table des actions CRM (actions spécifiques réalisées)
CREATE TABLE actionsCrm (
    idAction INT AUTO_INCREMENT PRIMARY KEY,
    idClient INT,
    typeAction VARCHAR(100),            -- Exemple : Email, Appel, Enquete, Promotion
    etapeAction VARCHAR(50),            -- Avant, Pendant, Apres
    dateAction DATETIME,
    couts FLOAT,
    validationFinance Boolean,
    FOREIGN KEY (idClient) REFERENCES clients(idClient)
);

CREATE TABLE reactionActionCrm(
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
    avis enum("tsara", "ratsy")
    commentaire TEXT,
    FOREIGN KEY (idClient) REFERENCES clients(idClient),
    FOREIGN KEY (idCommande) REFERENCES commandes(idCommande)
);
