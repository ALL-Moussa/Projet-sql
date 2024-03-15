-- Deleting data from Etudiants table
DELETE FROM Etudiants;
-- Add more delete statements as needed

-- Deleting data from Passagers table
DELETE FROM passagers;
-- Add more delete statements as needed

-- Deleting data from Conducteurs table
DELETE FROM conducteurs;
-- Add more delete statements as needed

-- Deleting data from voitures table
DELETE FROM voitures;
-- Add more delete statements as needed

-- Deleting data from avis table
DELETE FROM avis;
-- Add more delete statements as needed

-- Deleting data from trajets table
DELETE FROM trajets;
-- Add more delete statements as needed

-- Deleting data from arrets table
DELETE FROM arrets;
-- Add more delete statements as needed

-- Deleting data from propositions_trajet table
DELETE FROM propositions_trajet;
-- Add more delete statements as needed

-- Deleting data from propositions_arret table
DELETE FROM propositions_arret;
-- Add more delete statements as needed

-- Deleting data from inscriptions table
DELETE FROM inscriptions;
-- Add more delete statements as needed
TRUNCATE Etudiants, passagers, conducteurs, voitures, avis, trajets, arrets, propositions_trajet, propositions_arret, inscriptions RESTART IDENTITY;
-- Committing the changes
COMMIT;

-- Inserting more data into Etudiants table
INSERT INTO Etudiants ( nom_etudiant, prenom_etudiant, date_de_naissance)
VALUES
    ('Williams', 'Alice', TO_DATE('1995-08-12', 'YYYY-MM-DD')),
    ('Johnson', 'Robert', TO_DATE('1988-04-05', 'YYYY-MM-DD')),
    ( 'Miller', 'Emma', TO_DATE('1993-11-30', 'YYYY-MM-DD')),
    ('Davis', 'William', TO_DATE('1999-02-18', 'YYYY-MM-DD')),
    ('Doe', 'John', TO_DATE('1997-01-01', 'YYYY-MM-DD'));

-- Inserting more data into Passagers table
INSERT INTO passagers (numero_etudiant)
VALUES
    (1),
    (2),
    (3),
    (4),
    (5);

-- Inserting more data into voitures table
INSERT INTO voitures ( type_voiture, couleur, nombres_places, Etat)
VALUES
    ( 'Hatchback', 'Green', 4, 'Good'),
    ( 'Convertible', 'Silver', 2, 'Excellent'),
    ( 'Minivan', 'Black', 7, 'Fair'),
    ( 'Truck', 'White', 3, 'Excellent');

-- Inserting more data into trajets table
INSERT INTO trajets ( ville_depart, ville_arrive, distance)
VALUES
    ( 'Paris', 'Lyon', 120),
    ( 'Paris', 'Barcelona', 180),
    ( 'Berlin', 'Lyon', 90),
    ( 'Rome', 'Florence', 200);

-- Inserting more data into conducteurs table
INSERT INTO conducteurs ( numero_etudiant, numero_voiture)
VALUES
    ( 1, 1),
    ( 2, 2),
    ( 3, 3),
    ( 4, 4);

-- Inserting more data into propositions_trajet table
INSERT INTO propositions_trajet (numero_conducteur, numero_trajet, date_trajet, heure_trajet, prix)
VALUES
    (1, 1, TO_DATE('2024-03-01', 'YYYY-MM-DD'), '14:00', 30),
    (2, 2, TO_DATE('2024-03-01', 'YYYY-MM-DD'), '16:00', 35),
    (3, 3, TO_DATE('2024-05-01', 'YYYY-MM-DD'), '18:00', 40),
    (4, 4, TO_DATE('2024-06-01', 'YYYY-MM-DD'), '20:00', 45);


-- Inserting more data into arrets table
INSERT INTO arrets ( ville)
VALUES
    ( 'Amsterdam'),
    ( 'Brussels'),
    ( 'Vienna'),
    ( 'Prague');

-- Inserting more data into propositions_arret table
INSERT INTO propositions_arret (numero_proposition, numero_arret)
VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4);

-- Inserting more data into inscriptions table
INSERT INTO inscriptions (numero_passager, numero_proposition)
VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 1);

-- Inserting more data into avis table
INSERT INTO avis ( numero_etudiant1, numero_etudiant2, numero_proposition, note)
VALUES
    ( 1, 2, 1, 1),
    ( 2, 3, 2, 2),
    ( 3, 4, 3, 3),
    ( 4, 1, 4, 2),
    (5, 1, 1, 3);
