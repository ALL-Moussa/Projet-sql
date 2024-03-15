-- ============================================================
--   SELECT                                          
-- ============================================================

SELECT 
    E.numero_etudiant AS numero_etudiant_passager,
    E.nom_etudiant AS nom_passager,
    E.prenom_etudiant AS prenom_passager
FROM passagers P
JOIN Etudiants E ON P.numero_etudiant = E.numero_etudiant;


SELECT 
    E.numero_etudiant AS numero_etudiant_conducteur,
    E.nom_etudiant AS nom_conducteur,
    E.prenom_etudiant AS prenom_conducteur,
    V.numero_voiture AS numero_voiture,
    V.type_voiture AS type_voiture,
    V.couleur AS couleur_voiture
FROM conducteurs C
JOIN Etudiants E ON C.numero_etudiant = E.numero_etudiant
JOIN voitures V ON C.numero_voiture = V.numero_voiture;


SELECT
    V.numero_voiture,
    V.type_voiture,
    V.couleur,
    V.nombres_places
FROM
    propositions_trajet P
JOIN
    conducteurs C ON P.numero_conducteur = C.numero_conducteur
JOIN
    voitures V ON C.numero_voiture = V.numero_voiture
JOIN
    trajets T ON P.numero_trajet = T.numero_trajet 
WHERE
    T.ville_depart = 'Paris' -- Remplacez par la ville souhaitée
    AND P.date_trajet = '2024-03-01' -- Remplacez par la date souhaité
;
SELECT
    T.ville_depart,
    T.ville_arrive,
    T.numero_trajet,
    P.date_trajet,
    P.heure_trajet,
    A.ville AS ville_intermediaire
FROM
    propositions_trajet P
JOIN
    trajets T ON P.numero_trajet = T.numero_trajet
LEFT JOIN
    propositions_arret PA ON P.numero_proposition = PA.numero_proposition
LEFT JOIN
    arrets A ON PA.numero_arret = A.numero_arret 

WHERE
    P.date_trajet BETWEEN '2024-01-01' AND '2024-12-10' -- Remplacez par votre intervalle de dates
    AND T.ville_depart = 'Paris' -- Remplacez par le point de départ souhaité
    AND T.ville_arrive = 'Lyon' -- Remplacez par la ville d'arrivée souhaitée
;
SELECT
    P.numero_proposition,
    T.numero_trajet,
    T.ville_depart,
    T.ville_arrive,
    P.date_trajet,
    P.heure_trajet,
    A.ville AS ville_intermediaire
FROM
    propositions_trajet P
JOIN
    trajets T ON P.numero_trajet = T.numero_trajet
LEFT JOIN
    propositions_arret PA ON P.numero_proposition = PA.numero_proposition
LEFT JOIN
    arrets A ON PA.numero_arret = A.numero_arret
WHERE
    T.ville_arrive = 'Florence' -- Remplacez par la ville souhaitée
    AND P.heure_trajet BETWEEN '16:00' AND '20:00' -- Remplacez par l'intervalle horaire souhaité
    AND P.date_trajet = '2024-06-01' -- Remplacez par la date souhaitée
;
-- tout ce qui est en commentaire doit être changé sur php 


-- ============================================================
--   STAT                                          
-- ============================================================
 
-- moyenne des passagers sur l’ensemble des trajets effectués
SELECT AVG(nombre_passagers) as moyenne_passagers
FROM (
    SELECT COUNT(numero_passager) as nombre_passagers
    FROM inscriptions i
    INNER JOIN propositions_trajet p ON i.numero_proposition = p.numero_proposition
    GROUP BY p.numero_trajet
) x;


-- moyenne des distances parcourues en covoiturage par jour,

SELECT
    AVG(distance) AS moyenne_distances_parcourues
FROM
    trajets;
-- classement des meilleurs conducteurs d’après les avis
SELECT
    C.numero_conducteur,
    E.nom_etudiant || ' ' || E.prenom_etudiant AS nom_prenom,
    AVG(A.note) AS moyenne_notes
FROM
    conducteurs C
JOIN
    Etudiants E ON C.numero_etudiant = E.numero_etudiant
LEFT JOIN
    avis A ON C.numero_conducteur = A.numero_etudiant1
GROUP BY
    C.numero_conducteur, E.nom_etudiant, E.prenom_etudiant
ORDER BY
    AVG(A.note) DESC;


--  classement des villes selon le nombre de trajets qui les dessert.

SELECT
    ville_arrive,
    COUNT(numero_trajet) AS nombre_trajets_desservis
FROM
    trajets
GROUP BY
    ville_arrive
ORDER BY
    COUNT(numero_trajet) DESC;
