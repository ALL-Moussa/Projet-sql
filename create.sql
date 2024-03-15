-- ============================================================
--   Nom de la base   :  covoiturage du Campus                                
--   Nom de SGBD      :  ORACLE Version 7.0                    
--   Date de creation :  13/11/23  15:00                       
-- ============================================================

-- ============================================================
--   Table : Etudiant                                            
-- ============================================================

CREATE TABLE Etudiants
(
    numero_etudiant                  SERIAL                 NOT NULL,
    nom_etudiant                     VARCHAR(20)            NOT NULL,
    prenom_etudiant                   VARCHAR(20)                    ,
    date_de_naissance               DATE                           ,
    CONSTRAINT pk_etudiant PRIMARY KEY (numero_etudiant)
);


-- ============================================================
--   Table : Passager                                            
-- ============================================================

CREATE TABLE passagers
(
    numero_passager                  SERIAL                 NOT NULL,
    numero_etudiant                  INT                    NOT NULL,
    CONSTRAINT pk_passager PRIMARY KEY (numero_passager),
    FOREIGN KEY (numero_etudiant) REFERENCES Etudiants(numero_etudiant)
);
-- ============================================================
--   Table : Vehicule                                             
-- ============================================================

CREATE TABLE voitures
(
    numero_voiture              SERIAL                      NOT NULL,
    type_voiture                VARCHAR(20)                 NOT NULL,
    couleur                     VARCHAR(10)                 NOT NULL,
    nombres_places              INT                         NOT NULL,
    Etat                        VARCHAR(10)                         ,
    CONSTRAINT pk_voiture PRIMARY KEY (numero_voiture)
);
-- ============================================================
--   Table : Trajet                                             
-- ============================================================

CREATE TABLE trajets
(
    numero_trajet         SERIAL                  NOT NULL,
    ville_depart          VARCHAR(30)             NOT NULL,
    ville_arrive          VARCHAR(30)             NOT NULL,
    distance              INTEGER                 NOT NULL CHECK (distance > 0),
    CONSTRAINT pk_trajet PRIMARY KEY (numero_trajet)
);
-- ============================================================
--   Table : Conducteur                                          
-- ============================================================

CREATE TABLE conducteurs
(
    numero_conducteur                SERIAL                 NOT NULL,
    numero_etudiant                  INT                    NOT NULL,
    numero_voiture               INT                    NOT NULL,
    CONSTRAINT pk_conducteur PRIMARY KEY (numero_conducteur),
    FOREIGN KEY (numero_etudiant) REFERENCES Etudiants(numero_etudiant),
    FOREIGN KEY (numero_voiture) REFERENCES voitures(numero_voiture)
);

-- ============================================================
--   Table : Propositions_trajet                                             
-- ============================================================

CREATE TABLE propositions_trajet
(
    numero_proposition      SERIAL              NOT NULL,
    numero_conducteur       INTEGER             NOT NULL,
    numero_trajet           INTEGER             NOT NULL,
    date_trajet             DATE                NOT NULL CHECK (date_trajet > CURRENT_DATE),
    heure_trajet            TIME             NOT NULL,
    prix                    INTEGER              CHECK (prix > 0),
    CONSTRAINT pk_propositions PRIMARY KEY (numero_proposition),
    FOREIGN KEY (numero_conducteur) REFERENCES conducteurs(numero_conducteur),
    FOREIGN KEY (numero_trajet) REFERENCES trajets(numero_trajet)
);



--- ============================================================
---   Table : Arrets                                            
--- ============================================================
 
CREATE TABLE arrets
(
    numero_arret           SERIAL               NOT NULL,
    ville                  VARCHAR(30)          NOT NULL,
    CONSTRAINT pk_arrets PRIMARY KEY (numero_arret)
);
-- ===================================================
--   Table : Propositions_arret                                            
-- ============================================================

CREATE TABLE propositions_arret
(
    numero_proposition         INTEGER          NOT NULL,
    numero_arret               INTEGER          NOT NULL,
    CONSTRAINT pk_propositions_arret PRIMARY KEY (numero_proposition, numero_arret),
    FOREIGN KEY (numero_proposition) REFERENCES propositions_trajet(numero_proposition),
    FOREIGN KEY (numero_arret) REFERENCES arrets(numero_arret)
);
-- ============================================================
--   Table : Inscriptions                                            
-- ============================================================

CREATE TABLE inscriptions
(
    numero_passager         INTEGER                 NOT NULL,
    numero_proposition      INTEGER                 NOT NULL,
    CONSTRAINT pk_inscriptions PRIMARY KEY (numero_passager, numero_proposition),
    FOREIGN KEY (numero_passager) REFERENCES passagers(numero_passager),
    FOREIGN KEY (numero_proposition) REFERENCES propositions_trajet(numero_proposition)
);

-- ============================================================
--   Table : Avis                                             
-- ============================================================

CREATE TABLE avis
(
    numero_avis         SERIAL                  NOT NULL,
    numero_etudiant1     INTEGER                 NOT NULL,
    numero_etudiant2     INTEGER                 NOT NULL,
    numero_proposition       INTEGER                 NOT NULL,
    note                INTEGER                 NOT NULL CHECK (note >= 0 AND note <= 5),
    CONSTRAINT pk_avis PRIMARY KEY (numero_avis),
    FOREIGN KEY (numero_etudiant1) REFERENCES Etudiants(numero_etudiant),
    FOREIGN KEY (numero_etudiant2) REFERENCES Etudiants(numero_etudiant),
    FOREIGN KEY (numero_proposition) REFERENCES propositions_trajet(numero_proposition)
);
