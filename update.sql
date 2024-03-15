-- Mettre à jour l'état d'une voiture
UPDATE voitures
SET Etat = 'Moyen'
WHERE numero_voiture = 5;

-- Changer le nombre de places dans une voiture pour les trajets à venir
UPDATE voitures
SET nombres_places = nombres_places - 1
WHERE numero_voiture IN (
    SELECT numero_voiture
    FROM propositions_trajet
    WHERE date_trajet > CURRENT_DATE
);

-- Mettre à jour la date de naissance d'un étudiant
UPDATE Etudiants
SET date_de_naissance = TO_DATE('1995-08-25', 'YYYY-MM-DD')
WHERE numero_etudiant = 3;

-- Modifier la couleur d'une voiture pour les conducteurs actifs
UPDATE voitures
SET couleur = 'Noir'
WHERE numero_voiture IN (
    SELECT numero_voiture
    FROM conducteurs
    WHERE numero_etudiant IN (
        SELECT numero_etudiant
        FROM inscriptions
    )
);

-- Augmenter le prix des propositions de trajet pour les trajets longs
UPDATE propositions_trajet
SET prix = prix * 1.2
WHERE numero_trajet IN (
    SELECT numero_trajet
    FROM trajets
    WHERE distance > 200
);

-- Changer le type de voiture pour les conducteurs qui ont reçu des avis négatifs
UPDATE conducteurs
SET numero_voiture = 1
WHERE numero_etudiant IN (
    SELECT numero_etudiant1
    FROM avis
    WHERE note < 3
);

-- Mettre à jour la distance d'un trajet en ajoutant la distance d'un nouvel arrêt
UPDATE trajets
SET distance = distance + (
    SELECT distance
    FROM arrets
    WHERE numero_arret = 4
)
WHERE numero_trajet = 4;
