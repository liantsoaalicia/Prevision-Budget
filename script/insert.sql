INSERT INTO departement(nom, mdp) VALUES 
('Departement Administration', 'admin'),
('Departement Maintenance', 'maintenance'),
('Departement Finance', 'finance');

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
('Tablette de chocolat noir', 'Chocolat noir pur à 70%', 1, 4.50, 100),
('Savon au beurre de cacao', 'Savon naturel hydratant pour la peau', 2, 3.90, 50),
('Boisson cacao glacée', 'Boisson rafraîchissante au cacao', 3, 2.00, 200),
('Crème visage cacao', 'Crème nourrissante pour le visage', 2, 7.50, 30),
('Tarte au cacao', 'Pâtisserie artisanale au cacao', 5, 5.00, 20);

INSERT INTO clients (prenom, nom, email, age, sexe, classe) VALUES 
('Jean', 'Randri', 'jean.randri@example.com', 28, 'Homme', 'moyen'),
('Lucie', 'Rakoto', 'lucie.rakoto@example.com', 35, 'Femme', 'bas'),
('Tiana', 'Andria', 'tiana.andria@example.com', 22, 'Femme', 'eleve'),
('Hery', 'Ranaivo', 'hery.ranaivo@example.com', 41, 'Homme', 'moyen'),
('Soa', 'Ravelo', 'soa.ravelo@example.com', 30, 'Femme', 'bas');
