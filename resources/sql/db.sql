CREATE TABLE banque (
  idbanque   INT NOT NULL  AUTO_INCREMENT,
  nombanque  VARCHAR(50),
  codebanque VARCHAR(10),
  CONSTRAINT pk_banque PRIMARY KEY (idbanque)
);

CREATE TABLE paiement (
  idpaiement  INT         NOT NULL  AUTO_INCREMENT,
  nompaiement VARCHAR(15) NOT NULL,
  CONSTRAINT pk_paiement PRIMARY KEY (idpaiement)
);

CREATE TABLE rang (
  idrang  INT         NOT NULL  AUTO_INCREMENT,
  nomrang VARCHAR(50) NOT NULL,
  CONSTRAINT pk_rang PRIMARY KEY (idrang)
);

CREATE TABLE utilisateur (
  idutilisateur     INT          NOT NULL  AUTO_INCREMENT,
  nomutilisateur    VARCHAR(64)  NOT NULL,
  prenomutilisateur VARCHAR(64),
  adresse           VARCHAR(255) NOT NULL,
  codepostal        NUMERIC(5)   NOT NULL,
  ville             VARCHAR(64)  NOT NULL,
  telephone         NUMERIC(10),
  baisse            BOOLEAN      NOT NULL,
  login             VARCHAR(64)  NOT NULL UNIQUE,
  motdepasse        VARCHAR(255) NOT NULL,
  email             VARCHAR(255) NOT NULL UNIQUE,
  rang              INT          NOT NULL,
  CONSTRAINT pk_utilisateur PRIMARY KEY (idutilisateur),
  CONSTRAINT fk_utilisateur FOREIGN KEY (rang) REFERENCES rang (idrang)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE association (
  idassociation    INT          NOT NULL  AUTO_INCREMENT,
  nomassociation   VARCHAR(255) NOT NULL,
  sigleassociation VARCHAR(25),
  villeassociation VARCHAR(64)  NOT NULL,
  idadministrateur INT          NOT NULL,
  CONSTRAINT pk_association PRIMARY KEY (idassociation),
  CONSTRAINT fk_association FOREIGN KEY (idadministrateur) REFERENCES utilisateur (idutilisateur)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE foire (
  idfoire         INT          AUTO_INCREMENT,
  nomfoire        VARCHAR(255) NOT NULL,
  idassociation   INT          NOT NULL,
  idadmin         INT          NOT NULL,
  datedebutfoire  DATE,
  datefinfoire    DATE,
  datedebutsaisie DATE,
  datefinsaisie   DATE,
  prixbaisse      DECIMAL(5,2),
  maxobj          NUMERIC(3),
  maxobjassoc     NUMERIC(3),
  CONSTRAINT pk_foire PRIMARY KEY (idfoire),
  CONSTRAINT fk_foire FOREIGN KEY (idassociation) REFERENCES association (idassociation)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_foire_admin FOREIGN KEY (idadmin) REFERENCES utilisateur (idutilisateur)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE objet (
  idobjet       INT            NOT NULL          AUTO_INCREMENT UNIQUE,
  numitem       INT,
  idfoire       INT,
  idutilisateur INT,
  description   VARCHAR(255)   NOT NULL,
  baisse        BOOLEAN        NOT NULL          DEFAULT 0,
  prix          DECIMAL(5, 2) NOT NULL,
  vendu         BOOLEAN        NOT NULL          DEFAULT 0,
  taille        VARCHAR(32),
  nbitem        INT            NOT NULL,
  verrou        BOOLEAN        NOT NULL          DEFAULT 0,
  CONSTRAINT pk_objet PRIMARY KEY (numitem, idfoire, idutilisateur),
  CONSTRAINT fk_objet FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT fk_objFoire FOREIGN KEY (idfoire) REFERENCES foire (idfoire)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE transaction (
  idtransaction   INT  NOT NULL  AUTO_INCREMENT,
  idfoire         INT  NOT NULL,
  idutilisateur   INT,
  montant         INT  NOT NULL,
  idpaiement      INT,
  idbanque        INT,
  datetransaction DATE NOT NULL,
  CONSTRAINT pk_transaction PRIMARY KEY (idtransaction),
  CONSTRAINT fk_transaction_foire FOREIGN KEY (idfoire) REFERENCES foire (idfoire)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT fk_transaction_utilisateur FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_transaction_type_paiement FOREIGN KEY (idpaiement) REFERENCES paiement (idpaiement)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT fk_transaction_banque FOREIGN KEY (idbanque) REFERENCES banque (idbanque)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE vente (
  idvente       INT           NOT NULL  AUTO_INCREMENT,
  idtransaction INT           NOT NULL,
  idvendeur     INT           NOT NULL,
  idobjet       INT           NOT NULL,
  prixvente     DECIMAL(5, 2) NOT NULL,
  CONSTRAINT pk_vente PRIMARY KEY (idvente),
  CONSTRAINT fk_vente_transaction FOREIGN KEY (idtransaction) REFERENCES transaction (idtransaction)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT fk_vente_vendeur FOREIGN KEY (idvendeur) REFERENCES objet (idutilisateur)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT fk_vente_objet FOREIGN KEY (idobjet) REFERENCES objet (idobjet)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE participant (
  idfoire       INT,
  idutilisateur INT,
  valide        BOOLEAN,
  CONSTRAINT pk_participants PRIMARY KEY (idfoire, idutilisateur),
  CONSTRAINT fk_part_user FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_part_foire FOREIGN KEY (idfoire) REFERENCES foire (idfoire)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO banque (nombanque, codebanque) VALUES ('BNP', 'BNP');
INSERT INTO banque (nombanque, codebanque) VALUES ('Banque populaire', 'BP');
INSERT INTO banque (nombanque, codebanque) VALUES ('BRED', 'BRED');
INSERT INTO banque (nombanque, codebanque) VALUES ('Crédit Agricole', 'CA');
INSERT INTO banque (nombanque, codebanque) VALUES ('CCF', 'CCF');
INSERT INTO banque (nombanque, codebanque) VALUES ('Caisse des Dépots', 'CD');
INSERT INTO banque (nombanque, codebanque) VALUES ('Caisse Epargne', 'CE');
INSERT INTO banque (nombanque, codebanque) VALUES ('Caixa Geral de Depositos', 'CGD');
INSERT INTO banque (nombanque, codebanque) VALUES ('CIC', 'CIC');
INSERT INTO banque (nombanque, codebanque) VALUES ('Crédit Industriel de l\'Ouest', 'CIO');
INSERT INTO banque (nombanque, codebanque) VALUES ('Crédit Lyonnais', 'CL');
INSERT INTO banque (nombanque, codebanque) VALUES ('Crédit Mutuel', 'CM');
INSERT INTO banque (nombanque, codebanque) VALUES ('Crédit du Nord', 'CN');
INSERT INTO banque (nombanque, codebanque) VALUES ('Divers', 'D');
INSERT INTO banque (nombanque, codebanque) VALUES ('HSBC', 'HSBC');
INSERT INTO banque (nombanque, codebanque) VALUES ('La Poste', 'LP');
INSERT INTO banque (nombanque, codebanque) VALUES ('Paribas', 'P');
INSERT INTO banque (nombanque, codebanque) VALUES ('Groupe Banque Populaire', 'SBE');
INSERT INTO banque (nombanque, codebanque) VALUES ('Société Générale', 'SG');
INSERT INTO banque (nombanque, codebanque) VALUES ('Trésors Public', 'TP');
INSERT INTO banque (nombanque, codebanque) VALUES ('Union des Banques de Paris', 'UBP');


INSERT INTO paiement (nompaiement) VALUES ('Liquide');
INSERT INTO paiement (nompaiement) VALUES ('Chèque');


INSERT INTO rang (nomrang) VALUES ('Vendeur');
INSERT INTO rang (nomrang) VALUES ('Opérateur');
INSERT INTO rang (nomrang) VALUES ('Administrateur de foire');
INSERT INTO rang (nomrang) VALUES ('Super Administrateur');


INSERT INTO utilisateur (nomutilisateur, prenomutilisateur, adresse, codepostal, ville, telephone, baisse, login, motdepasse, email, rang)
VALUES
  ('SUPER', 'ADMIN', '127.0.0.1', '404', 'Internet', '8', TRUE, 'superadmin', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0',
            'superadmin@localhost', 4);
INSERT INTO utilisateur (nomutilisateur, adresse, codepostal, ville, baisse, login, motdepasse, email, rang)
VALUES ('AFAE', '16 Boulevard AbelCornaton', 91290, 'Arpajon', FALSE, 'afae', 'b272dbd564ebd3898c2ecd69fcd94b67',
        'afae@free.fr', 3);


INSERT INTO association (nomassociation, sigleassociation, villeassociation, idadministrateur)
VALUES ('Association des Familles d\'Arpajon et de ses Environs', 'AFAE', 'Arpajon', (SELECT idutilisateur
                                                                                      FROM utilisateur
                                                                                      WHERE nomutilisateur = 'AFAE'))