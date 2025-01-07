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