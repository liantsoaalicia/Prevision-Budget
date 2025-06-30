INSERT INTO departement(nom)
VALUES
('Departement Administration'),
('Departement Maintenance'),
('Departement Finance'),
('Departement Marketing');

INSERT INTO periode(nom, dateDebut, dateFin) VALUES
('Periode 1', '2025-01-01', '2025-01-31'),
('Periode 2', '2025-02-01', '2025-02-28'),
('Periode 3', '2025-03-01', '2025-03-31'),
('Periode 4', '2025-04-01', '2025-04-30'),
('Periode 5', '2025-05-01', '2025-05-31'),
('Periode 6', '2025-06-01', '2025-06-30'),
('Periode 7', '2025-07-01', '2025-07-31'),
('Periode 8', '2025-08-01', '2025-08-31'),
('Periode 9', '2025-09-01', '2025-09-30'),
('Periode 10', '2025-10-01', '2025-10-31'),
('Periode 11', '2025-11-01', '2025-11-30'),
('Periode 12', '2025-12-01', '2025-12-31');

--CRM
INSERT INTO categorieProduit (nom) VALUES 
('Chocolat'),
('Cosmétiques'),
('Boissons'),
('Produits de santé'),
('Pâtisseries');

INSERT INTO produits (nom, description, idCategorie, prix, quantiteStock) VALUES 
('Tablette de chocolat noir', 'Chocolat noir pur à 70%', 1, 5000, 100);
INSERT INTO produits (nom, description, idCategorie, prix, quantiteStock) VALUES 
('Savon au beurre de cacao', 'Savon naturel hydratant pour la peau', 2, 3900, 50),
('Boisson cacao glacée', 'Boisson rafraîchissante au cacao', 3, 20000, 200),
('Crème visage cacao', 'Crème nourrissante pour le visage', 2, 75000, 30),
('Tarte au cacao', 'Pâtisserie artisanale au cacao', 5, 15000, 20);

INSERT INTO clients (prenom, nom, email, age, sexe, classe) VALUES 
('Jean', 'Randri', 'jean.randri@example.com', 28, 'Homme', 'moyen'),
('Lucie', 'Rakoto', 'lucie.rakoto@example.com', 35, 'Femme', 'bas'),
('Tiana', 'Andria', 'tiana.andria@example.com', 22, 'Femme', 'eleve'),
('Hery', 'Ranaivo', 'hery.ranaivo@example.com', 41, 'Homme', 'moyen'),
('Soa', 'Ravelo', 'soa.ravelo@example.com', 30, 'Femme', 'bas');

-- Pour mettre la commande dans le budget
INSERT INTO categorie(categorie, types, nature) VALUES
('Recette', 'Vente', 'Vente des produits a base de cacao');

ALTER TABLE departement DROP COLUMN mdp;

INSERT INTO user(nom, mdp, idDepartement) VALUES ('randie', 'r123', 3);

--Ticket
INSERT INTO status_ticket(libelle) VALUES
('Arrive'),
('Ouvert'),
('En cours'),
('Résolu'),
('Fermé');

INSERT INTO tickets (idClient, idStatus, sujet, description, priorite, fichier, clientHasResponded)
VALUES 
(1, 1, 'Problème de connexion', 'Le client ne peut pas se connecter à son compte.', 'haute', NULL, FALSE),
(2, 1, 'Erreur de facturation', 'Le montant facturé ne correspond pas au devis.', 'normale', 'facture_client2.pdf', FALSE),
(3, 1, 'Demande de modification', 'Le client souhaite changer ladresse de livraison.', 'basse', NULL, FALSE),
(4, 1, 'Site indisponible', 'Le site web est inaccessible depuis ce matin.', 'haute', NULL, FALSE),
(5, 1, 'Assistance installation', 'Le client a besoin daide pour installer le logiciel.', 'normale', 'capture_installation.png', FALSE);

INSERT INTO ticket_status_history (idTicket, idStatus, commentaire) VALUES
(1, 1, 'Ticket créé et en attente de traitement.'),
(2, 1, 'Ticket créé et en attente de traitement.'),
(3, 1, 'Ticket créé et en attente de traitement.'),
(4, 1, 'Ticket créé et en attente de traitement.'),
(5, 1, 'Ticket créé et en attente de traitement.');

INSERT INTO agents (nom, email, coutHoraire) VALUES
('Alice Rakoto', 'alice.rakoto@example.com', 150000),
('Bob Randria', 'bob.randria@example.com', 100000),
('Carine Rasoa', 'carine.rasoa@example.com', 120000);

INSERT INTO agent_ticket (idAgent, idTicket) VALUES
(1, 1), -- Alice gère le ticket 1
(2, 2), -- Bob gère le ticket 2
(3, 3), -- Carine gère le ticket 3
(1, 4), -- Alice gère aussi le ticket 4
(2, 5); -- Bob gère le ticket 5

