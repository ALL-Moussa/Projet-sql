-- Supprimer les contraintes de clé étrangère pour éviter les erreurs
ALTER TABLE inscriptions DROP CONSTRAINT IF EXISTS inscriptions_numero_passager_fkey;
ALTER TABLE inscriptions DROP CONSTRAINT IF EXISTS inscriptions_numero_proposition_fkey;
ALTER TABLE propositions_arret DROP CONSTRAINT IF EXISTS propositions_arret_numero_proposition_fkey;
ALTER TABLE propositions_arret DROP CONSTRAINT IF EXISTS propositions_arret_numero_arret_fkey;
ALTER TABLE propositions_trajet DROP CONSTRAINT IF EXISTS propositions_trajet_numero_conducteur_fkey;
ALTER TABLE propositions_trajet DROP CONSTRAINT IF EXISTS propositions_trajet_numero_trajet_fkey;
ALTER TABLE avis DROP CONSTRAINT IF EXISTS avis_numero_etudiant1_fkey;
ALTER TABLE avis DROP CONSTRAINT IF EXISTS avis_numero_etudiant2_fkey;
ALTER TABLE avis DROP CONSTRAINT IF EXISTS avis_numero_proposition_fkey;
ALTER TABLE arrets DROP CONSTRAINT IF EXISTS arrets_numero_arret_fkey;
ALTER TABLE trajets DROP CONSTRAINT IF EXISTS trajets_numero_trajet_fkey;
ALTER TABLE conducteurs DROP CONSTRAINT IF EXISTS conducteurs_numero_etudiant_fkey;
ALTER TABLE conducteurs DROP CONSTRAINT IF EXISTS conducteurs_numero_vehicule_fkey;
ALTER TABLE passagers DROP CONSTRAINT IF EXISTS passagers_numero_etudiant_fkey;

-- Supprimer les tables
DROP TABLE IF EXISTS inscriptions;
DROP TABLE IF EXISTS propositions_arret;
DROP TABLE IF EXISTS propositions_trajet;
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS arrets;
DROP TABLE IF EXISTS trajets;
DROP TABLE IF EXISTS conducteurs;
DROP TABLE IF EXISTS passagers;
DROP TABLE IF EXISTS voitures;
DROP TABLE IF EXISTS Etudiants;

-- Supprimer la base de données
DROP DATABASE IF EXISTS covoiturage_du_campus;
