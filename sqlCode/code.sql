CREATE DATABASE DriveLoc;

USE DriveLoc;

-- Table Role
CREATE TABLE role (
    id_role int NOT null AUTO_INCREMENT PRIMARY KEY,
    name_user ENUM('Admin', 'Client') NOT NULL
);

-- Table UserSite
CREATE TABLE usersite (
    id_user int NOT null AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(250) NOT NULL UNIQUE,
    email VARCHAR(250) NOT NULL UNIQUE,
    telephone varchar(50) NOT null UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_role int NOT null,
    FOREIGN KEY (id_role) REFERENCES role(id_role)
);

CREATE TABLE categorie (
    id_categorie int NOT null AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(250) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE vehicule (
    id_vehicule INT NOT null AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(250) NOT NULL,
    marque VARCHAR(250) NOT NULL,
    prix_par_jour float NOT NULL,
    disponibilite BOOLEAN DEFAULT TRUE NOT null,
    id_categorie INT,
    imageUrl VARCHAR(255) not null,
    description TEXT NOT null,
    km float NOT null,
    consom ENUM('Gasoil', 'Essence', 'Hybride', 'Électrique') NOT NULL;
    annee int NOT null,
    place int NOT null,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE SET NULL
);

CREATE TABLE reservations (
    id_reservation INT NOT null AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT null,
    id_vehicule INT NOT null,
    date_rama DATE NOT NULL,
    heure_rama time not null,
    lieu_rama VARCHAR(250) NOT null,
    date_depo DATE NOT NULL,
    heure_depo TIME NOT NULL,
    lieu_depo VARCHAR(250) NOT NULL;
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('en attente', 'approuvée', 'refusée') DEFAULT 'en attente',
    FOREIGN KEY (id_user) REFERENCES usersite(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id_vehicule) ON DELETE CASCADE
);

CREATE TABLE avis (
    id_avis INT NOT null AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT null,
    id_vehicule INT NOT null,
    commentaire TEXT NOT null,
    note float CHECK (note BETWEEN 1 AND 5) NOT null,
    soft_delete BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_user) REFERENCES usersite(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id_vehicule) ON DELETE CASCADE
);





INSERT INTO role (name_user) VALUES 
('Admin'),
('Client');

INSERT INTO categorie (nom, description) VALUES 
('Économique', 'Petites voitures adaptées pour les trajets urbains'),
('SUV', 'Véhicules spacieux adaptés pour les longs trajets et le hors-piste'),
('Luxe', 'Véhicules haut de gamme pour une expérience de conduite premium'),
('Sport', 'Voitures puissantes et performantes conçues pour la vitesse et l''agilité');

INSERT INTO vehicule (modele, marque, prix_par_jour, disponibilite, id_categorie, imageUrl, description, km, consom, annee, place) VALUES 
('Clio 5', 'Renault', 250, TRUE, 1, 'https://lemag.gueudet.fr/wp-content/uploads/gueudet/2024/05/renault-clio-e-tech-esprit-alpine-e1716793370431.jpg', 'Petite voiture économique idéale pour la ville.', 30, 'Essence', 2022, 5),
('Duster', 'Dacia', 400, TRUE, 2, 'https://autohub.ma/wp-content/uploads/2024/06/dacia-duster-maroc.webp', 'SUV robuste parfait pour les routes du Maroc.', 45, 'Gasoil', 2021, 5),
('Classe E', 'Mercedes', 1200, TRUE, 3, 'https://www.challenge.ma/wp-content/uploads/2023/08/Class-E-ouv-600-x-329.jpg', 'Véhicule de luxe pour les occasions spéciales.', 15, 'Hybride', 2023, 5),
('Tesla Model 3', 'Tesla', 1500, TRUE, 3, 'https://www.shop4tesla.com/cdn/shop/articles/lohnt-sich-ein-gebrauchtes-tesla-model-3-vor-und-nachteile-833053.jpg?v=1733570691', 'Voiture électrique haut de gamme pour un confort optimal.', 10, 'Électrique', 2023, 5),
('Golf 7 GTI', 'Volkswagen', 800, TRUE, 4, 'https://www.la-passion-de-l-auto.com/upload/golf-7-gti-occasion-1663582685-49931.jpg', 'Voiture sportive avec une excellente maniabilité.', 20, 'Essence', 2021, 5),
('Range Rover Evoque', 'Land Rover', 1000, TRUE, 2, 'https://media.cdn-jaguarlandrover.com/api/v2/images/102859/w/640.jpg', 'SUV premium adapté aux routes urbaines et off-road.', 25, 'Gasoil', 2022, 5),
('Yaris', 'Toyota', 220, TRUE, 1, 'https://imagecdnblogsa.carbay.com/wp-content/uploads/2024/09/12123439/SleekLooks-1-500x313.jpg', 'Voiture compacte économique pour la ville.', 28, 'Essence', 2020, 5),
('Fortuner', 'Toyota', 700, TRUE, 2, 'https://cdni.autocarindia.com/Utils/ImageResizer.ashx?n=http://img.haymarketsac.in/autocarpro/709f4883-7b5d-48a5-8aee-b556afd7e4f4_Fortuner-Leader-white.jpg&w=735&h=490&q=80', 'SUV robuste idéal pour les longs trajets.', 35, 'Gasoil', 2022, 7),
('Civic Type R', 'Honda', 900, TRUE, 4, 'https://www.autotecnica.org/wp-content/uploads/2022/07/2023-honda-civic-type-r-rear-view-1024x576.jpg', 'Voiture sportive haute performance.', 12, 'Essence', 2023, 4),
('Kwid', 'Renault', 180, TRUE, 1, 'https://imgd.aeplcdn.com/664x374/n/cw/ec/141125/kwid-exterior-right-front-three-quarter-3.jpeg?isig=0&q=80', 'Petite voiture urbaine économique et pratique.', 15, 'Essence', 2022, 5);



-- Vue SQL
CREATE VIEW ListeVehicules AS
SELECT 
    v.id_vehicule,
    v.modele,
    v.marque,
    v.prix_par_jour,
    v.disponibilite,
    v.imageUrl,
    v.description,
    v.km,
    v.consom,
    v.annee,
    v.place,
    c.nom AS categorie,
    c.description AS categorie_description,
    COALESCE(AVG(a.note), 0) AS note_moyenne,
    COUNT(a.id_avis) AS nombre_avis
FROM 
    vehicule v
LEFT JOIN 
    categorie c ON v.id_categorie = c.id_categorie
LEFT JOIN 
    avis a ON v.id_vehicule = a.id_vehicule AND a.soft_delete = FALSE
GROUP BY 
    v.id_vehicule, c.nom, c.description;


-- Procédure Stockée
DELIMITER $$

CREATE PROCEDURE AjouterReservation(
    IN p_id_user INT,
    IN p_id_vehicule INT,
    IN p_date_rama DATE,
    IN p_heure_rama TIME,
    IN p_lieu_rama VARCHAR(250),
    IN p_date_depo DATE,
    IN p_heure_depo TIME,
    IN p_lieu_depo VARCHAR(250)
)
BEGIN
    -- Vérifier si le véhicule est disponible
    IF EXISTS (
        SELECT 1 
        FROM reservations 
        WHERE id_vehicule = p_id_vehicule
          AND status = 'approuvée'
          AND (
                (p_date_rama BETWEEN date_rama AND date_depo) OR
                (p_date_depo BETWEEN date_rama AND date_depo) OR
                (p_date_rama <= date_rama AND p_date_depo >= date_depo)
              )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le véhicule n''est pas disponible pour les dates sélectionnées.';
    ELSE
        -- Insérer la réservation
        INSERT INTO reservations (
            id_user,
            id_vehicule,
            date_rama,
            heure_rama,
            lieu_rama,
            date_depo,
            heure_depo,
            lieu_depo
        ) VALUES (
            p_id_user,
            p_id_vehicule,
            p_date_rama,
            p_heure_rama,
            p_lieu_rama,
            p_date_depo,
            p_heure_depo,
            p_lieu_depo
        );

        -- Mettre à jour la disponibilité du véhicule si nécessaire
        UPDATE vehicule
        SET disponibilite = FALSE
        WHERE id_vehicule = p_id_vehicule;
    END IF;
END$$

DELIMITER ;



-- Les nouvelles tables pour la version 2 de Drive & Loc

-- Table themes
CREATE TABLE themes (
    id_theme INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    imgUrl varchar(250) NOT null;
);

-- Table articles
CREATE TABLE articles (
    id_article INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'publie', 'rejete') DEFAULT 'en_attente',
    image_url VARCHAR(255),
    video_url VARCHAR(255),
    id_user INT NOT NULL,
    id_theme INT,
    FOREIGN KEY (id_user) REFERENCES usersite(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_theme) REFERENCES themes(id_theme) ON DELETE SET NULL
);

-- Table tags
CREATE TABLE tags (
    id_tag INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- Table articles_tags
CREATE TABLE articles_tags (
    id_article_tag INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_article INT NOT NULL,
    id_tag INT NOT NULL,
    UNIQUE KEY article_tag_unique (id_article, id_tag),
    FOREIGN KEY (id_article) REFERENCES articles(id_article) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag) ON DELETE CASCADE
);

-- Table commentaires
CREATE TABLE commentaires (
    id_commentaire INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_article INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_article) REFERENCES articles(id_article) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES usersite(id_user) ON DELETE CASCADE
);

-- Table favoris
CREATE TABLE favoris (
    id_favori INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_article INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_article_unique (id_user, id_article),
    FOREIGN KEY (id_user) REFERENCES usersite(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_article) REFERENCES articles(id_article) ON DELETE CASCADE
);


INSERT INTO themes (nom, description, imgUrl)
VALUES
('Conseils de Conduite', 'Découvrez des astuces et recommandations pour une conduite plus sûre et agréable au quotidien.', 'https://images.unsplash.com/photo-1562618817-4c48e063c39a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMTc3M3wwfDF8c2VhcmNofDU4fHxkcml2aW5nJTIwYWR2aWNlfGVufDB8fHx8MTY2NzU3MzU2Nw&ixlib=rb-4.0.3&q=80&w=2000'),
('Entretien Automobile', 'Apprenez à entretenir votre véhicule pour prolonger sa durée de vie et éviter les pannes coûteuses.', 'https://www.altersecurite.org/wp-content/uploads/2019/11/entretien-v%C3%A9hicule.jpg'),
('Sécurité Routière', 'Découvrez les meilleures pratiques et réglementations pour une conduite sécurisée.', 'https://lp-guynemer-dunkerque.fr/wp-content/uploads/securite-routiere-2019-750x500.jpg'),
('Tendances du Marché', 'Analysez les tendances actuelles du marché automobile et leurs évolutions futures.', 'https://demarchesadministratives.fr/images/actualites/5532/fin-moteur-thermique.jpg');

-- Theme 1
INSERT INTO articles (titre, contenu, statut, image_url, id_user, id_theme)
VALUES
('Comment réduire sa consommation de carburant', 'Découvrez 10 astuces simples pour économiser du carburant et réduire vos dépenses lors de vos trajets quotidiens.', 'publie', 'https://i.lepelerin.com/1400x787/smart/2024/02/05/hausse-du-prix-du-carburant-comment-reduire-sa-con-.jpeg', 2, 1),
('Les erreurs courantes à éviter en conduite', 'Une liste des erreurs fréquentes que commettent les conducteurs et comment les éviter pour améliorer votre sécurité.', 'publie', 'https://www.welixe.fr/upload/86924-Les-piy-ges-du-Code-de-la-route-Comment-y-viter-les-erreurs-courantes.webp', 3, 1),
('Adopter une conduite éco-responsable', 'Comprenez les principes de l’éco-conduite et comment réduire votre empreinte écologique sur la route.', 'en_attente', 'https://www.salonautomonaco.com/wp-content/uploads/2022/03/ecoresponsable-voiture-soyez.jpg', 4, 1),
('Comment rester concentré au volant', 'Des conseils pratiques pour maintenir votre attention et éviter les distractions pendant vos trajets.', 'publie', 'https://www.permis-lausanne.ch/contenu/uploads/2023/08/ameliorer-sa-concentration-au-volant.jpg', 5, 1);

-- Theme 2
INSERT INTO articles (titre, contenu, statut, image_url, id_user, id_theme)
VALUES
('Les étapes pour vérifier la pression des pneus', 'Une vérification régulière des pneus est essentielle pour votre sécurité et vos économies.', 'publie', 'https://ouipneus.ma/blog/wp-content/uploads/2021/11/verifier-la-pression-des-pneus-1024x683.jpg', 4, 2),
('Quand changer les plaquettes de frein ?', 'Découvrez les signes d’usure des plaquettes de frein et l’importance de les remplacer à temps.', 'publie', 'https://img-4.linternaute.com/49VWOgqNCmFFv6dAAYn23xO3P30=/1500x/smart/0e81851a3743458d99c3dfbbf8472e34/ccmcms-linternaute/10654128.jpg', 2, 2),
('Les bases de l’entretien moteur', 'Apprenez les vérifications régulières à effectuer pour garder votre moteur en bon état.', 'en_attente', 'https://www.yamaha-motor.eu/content/dam/yme/fr/ymfr/services/2022_YAM_ACC-APP_WORKWEAR_BUSTER_002.jpg', 5, 2),
('Comment prolonger la durée de vie de votre batterie', 'Des conseils pour maintenir votre batterie en bon état et éviter les pannes.', 'publie', 'https://www.automoto-gp.com/wp-content/uploads/2024/10/1729759285_astuces-pour-prolonger-la-duree-de-votre-batterie-automobile.jpg', 3, 2);

-- Theme 3
INSERT INTO articles (titre, contenu, statut, image_url, id_user, id_theme)
VALUES
('L’importance du port de la ceinture de sécurité', 'Découvrez pourquoi la ceinture de sécurité reste l’un des dispositifs les plus efficaces pour sauver des vies.', 'publie', 'https://i0.wp.com/www.drive-innov.com/wp-content/uploads/2023/04/main-femme-attachant-ceinture-securite-dans-voiture-image-recadree-femme-assise-dans-voiture-mettant-sa-ceinture-securite-concept-conduite-sure.jpg?ssl=1', 5, 3),
('Les dangers de l’utilisation du téléphone au volant', 'Pourquoi il est essentiel de rester concentré et de ne pas utiliser votre téléphone en conduisant.', 'publie', 'https://www.fondationdelaroute.fr/wp-content/uploads/2021/03/telephone_volant_interdit.jpg', 4, 3),
('Comment adapter sa vitesse aux conditions météorologiques', 'Apprenez à ajuster votre conduite en cas de pluie, de brouillard ou de neige.', 'en_attente', 'https://www.assuronline.com/wp-content/uploads/2021/06/18013902_l-scaled.jpg', 3, 3),
('Les règles essentielles pour conduire de nuit', 'Découvrez des conseils pour assurer votre sécurité et celle des autres sur la route la nuit.', 'publie', 'https://media.roole.fr/_next/image?url=https%3A%2F%2Fassets.prod.roole.fr%2Fdata%2Fassets%2Froute_de_nuit_a1b340cc99.jpg&w=3840&q=75', 2, 3);

-- Theme 4
INSERT INTO articles (titre, contenu, statut, image_url, id_user, id_theme)
VALUES
('L’essor des véhicules électriques en 2025', 'Découvrez pourquoi les voitures électriques dominent le marché et les avantages qu’elles offrent.', 'publie', 'https://cdn-s-www.leprogres.fr/images/1D58F605-87DD-4A44-84F2-28CD9E30184D/NW_raw/connaitre-le-prix-du-plein-avant-de-se-brancher-une-mission-impossible-1638368607.jpg', 3, 4),
('Les tendances en matière de SUV', 'Pourquoi les SUV restent un choix populaire parmi les acheteurs de voitures modernes.', 'en_attente', 'https://cdn.motor1.com/images/mgl/qkkWMG/s1/brabus-900-auf-basis-mercedes-maybach-gls-600-4matic.jpg', 5, 4),
('L’évolution des technologies automobiles', 'Explorez les innovations technologiques qui redéfinissent l’expérience de conduite.', 'publie', 'https://i0.wp.com/blog.mbadmb.com/wp-content/uploads/2024/11/IMG_2790-edited.webp?resize=1080%2C608&ssl=1', 2, 4),
('Les voitures autonomes : état des lieux en 2025', 'Un aperçu des avancées dans le domaine des voitures autonomes et des défis restants.', 'publie', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOU9088kAlyVIv9QtqrYWoFcVfjtjRyivgEQ&s', 4, 4);

-- Tags
INSERT INTO tags (nom)
VALUES
('Eco-conduite'),
('Entretien'),
('Sécurité'),
('Technologie'),
('Électrique'),
('Tendances');

-- Liens pour le thème "Conseils de Conduite" (id_theme = 1)
INSERT INTO articles_tags (id_article, id_tag)
VALUES
(1, 1), -- Réduire consommation de carburant (Eco-conduite)
(1, 3), -- Réduire consommation de carburant (Sécurité)
(2, 3), -- Erreurs courantes (Sécurité)
(2, 2), -- Erreurs courantes (Entretien)
(3, 1), -- Conduite éco-responsable (Eco-conduite)
(3, 5), -- Conduite éco-responsable (Électrique)
(4, 3), -- Concentration au volant (Sécurité)
(4, 6); -- Concentration au volant (Tendances)


-- Liens pour le thème "Entretien Automobile" (id_theme = 2)
INSERT INTO articles_tags (id_article, id_tag)
VALUES
(5, 2), -- Pression des pneus (Entretien)
(5, 3), -- Pression des pneus (Sécurité)
(6, 2), -- Plaquettes de frein (Entretien)
(6, 3), -- Plaquettes de frein (Sécurité)
(7, 2), -- Entretien moteur (Entretien)
(7, 4), -- Entretien moteur (Technologie)
(8, 2), -- Batterie (Entretien)
(8, 5); -- Batterie (Électrique)


-- Liens pour le thème "Sécurité Routière" (id_theme = 3)
INSERT INTO articles_tags (id_article, id_tag)
VALUES
(9, 3), -- Ceinture de sécurité (Sécurité)
(9, 6), -- Ceinture de sécurité (Tendances)
(10, 3), -- Téléphone au volant (Sécurité)
(10, 4), -- Téléphone au volant (Technologie)
(11, 3), -- Vitesse et météo (Sécurité)
(11, 1), -- Vitesse et météo (Eco-conduite)
(12, 3), -- Conduite de nuit (Sécurité)
(12, 6); -- Conduite de nuit (Tendances)


-- Liens pour le thème "Tendances du Marché" (id_theme = 4)
INSERT INTO articles_tags (id_article, id_tag)
VALUES
(13, 5), -- Véhicules électriques (Électrique)
(13, 6), -- Véhicules électriques (Tendances)
(14, 6), -- SUV (Tendances)
(14, 4), -- SUV (Technologie)
(15, 4), -- Technologies (Technologie)
(15, 6), -- Technologies (Tendances)
(16, 6), -- Voitures autonomes (Tendances)
(16, 4); -- Voitures autonomes (Technologie)