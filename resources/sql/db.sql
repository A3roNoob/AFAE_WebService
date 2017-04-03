-- Creation des tables --
CREATE TABLE banque (
	idbanque             int  NOT NULL  AUTO_INCREMENT,
	nombanque            varchar(50)    ,
	codebanque           varchar(10)    ,
	CONSTRAINT pk_banque PRIMARY KEY ( idbanque )
);

CREATE TABLE paiement (
	idpaiement            int  NOT NULL  AUTO_INCREMENT,
	nompaiement          varchar(15)  NOT NULL  ,
	CONSTRAINT pk_paiement PRIMARY KEY ( idpaiement )
);

CREATE TABLE rang (
	idrang               int  NOT NULL  AUTO_INCREMENT,
	nomrang              varchar(50)  NOT NULL  ,
	CONSTRAINT pk_rang PRIMARY KEY ( idrang )
);

CREATE TABLE utilisateur (
	idutilisateur        int  NOT NULL  AUTO_INCREMENT,
	nomutilisateur           varchar(64)  NOT NULL  ,
	prenomutilisateur        varchar(64),
	adresse              varchar(255)  NOT NULL  ,
	codepostal           numeric(5)  NOT NULL  ,
	ville                varchar(64)    NOT NULL,
	telephone            numeric(10)    ,
	baisse               BOOLEAN  NOT NULL  ,
	changelock           BOOLEAN    ,
	login                varchar(64)    NOT NULL UNIQUE,
	motdepasse           varchar(255)    NOT NULL,
	email                varchar(255)    NOT NULL UNIQUE,
	rang                 int  NOT NULL  ,
	CONSTRAINT pk_utilisateur PRIMARY KEY ( idutilisateur ),
	CONSTRAINT fk_utilisateur FOREIGN KEY ( rang ) REFERENCES rang( idrang ) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE association (
	idassociation        int  NOT NULL  AUTO_INCREMENT,
	nomassociation       varchar(255)  NOT NULL  ,
	sigleassociation     varchar(25)    ,
	villeassociation     varchar(64)  NOT NULL  ,
	idadministrateur       int  NOT NULL  ,
  datedebut
	CONSTRAINT pk_association PRIMARY KEY ( idassociation ),
	CONSTRAINT fk_association FOREIGN KEY ( idadministrateur ) REFERENCES utilisateur( idutilisateur ) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE foire (
	idfoire              int  NOT NULL  AUTO_INCREMENT,
	nomfoire             varchar(255)  NOT NULL  ,
	idassociation        int  NOT NULL  ,
	CONSTRAINT pk_foire PRIMARY KEY ( idfoire ),
	CONSTRAINT fk_foire FOREIGN KEY ( idassociation ) REFERENCES association( idassociation ) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE objet (
	idobjet              int  NOT NULL  AUTO_INCREMENT UNIQUE,
	numitem              int,
  idfoire              int NOT NULL,
	idutilisateur        int,
	description          varchar(255)  NOT NULL  ,
	baisse               BOOLEAN  NOT NULL  ,
	prix                 decimal(5,2)  NOT NULL  ,
	vendu                BOOLEAN  NOT NULL  ,
	taille               varchar(32)    ,
  nbitem               int NOT NULL,
	CONSTRAINT pk_objet PRIMARY KEY (numitem, idutilisateur ),
	CONSTRAINT fk_objet FOREIGN KEY ( idutilisateur ) REFERENCES utilisateur( idutilisateur ) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT fk_objFoire FOREIGN KEY (idfoire) REFERENCES foire(idfoire) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE transaction (
	idtransaction        int  NOT NULL  AUTO_INCREMENT,
	idfoire              int  NOT NULL  ,
	idutilisateur        int    ,
	montant              int  NOT NULL  ,
	idpaiement           int    ,
	idbanque             int    ,
	datetransaction      date  NOT NULL  ,
	CONSTRAINT pk_transaction PRIMARY KEY ( idtransaction ),
	CONSTRAINT fk_transaction_foire FOREIGN KEY ( idfoire ) REFERENCES foire( idfoire ) ON DELETE NO ACTION ON UPDATE CASCADE,
	CONSTRAINT fk_transaction_utilisateur FOREIGN KEY ( idutilisateur ) REFERENCES utilisateur( idutilisateur ) ON DELETE NO ACTION ON UPDATE NO ACTION,
	CONSTRAINT fk_transaction_type_paiement FOREIGN KEY ( idpaiement ) REFERENCES paiement( idpaiement ) ON DELETE NO ACTION ON UPDATE CASCADE,
	CONSTRAINT fk_transaction_banque FOREIGN KEY ( idbanque ) REFERENCES banque( idbanque ) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE vente (
	idvente              int  NOT NULL  AUTO_INCREMENT,
	idtransaction        int  NOT NULL  ,
	idvendeur            int  NOT NULL  ,
	idobjet              int  NOT NULL  ,
	prixvente            decimal(5,2) NOT NULL  ,
	CONSTRAINT pk_vente PRIMARY KEY ( idvente ),
	CONSTRAINT fk_vente_transaction FOREIGN KEY ( idtransaction ) REFERENCES transaction( idtransaction ) ON DELETE NO ACTION ON UPDATE CASCADE,
	CONSTRAINT fk_vente_vendeur FOREIGN KEY ( idvendeur ) REFERENCES objet( idutilisateur ) ON DELETE NO ACTION ON UPDATE CASCADE,
	CONSTRAINT fk_vente_objet FOREIGN KEY ( idobjet ) REFERENCES objet( idobjet ) ON DELETE NO ACTION ON UPDATE CASCADE
);

-- Insertion des données par défaut --
-- Insertion des banques --
INSERT INTO banque(nombanque, codebanque) VALUES ('BNP', 'BNP');
INSERT INTO banque(nombanque, codebanque) VALUES ('Banque populaire', 'BP');
INSERT INTO banque(nombanque, codebanque) VALUES ('BRED', 'BRED');
INSERT INTO banque(nombanque, codebanque) VALUES ('Cr�dit Agricole', 'CA');
INSERT INTO banque(nombanque, codebanque) VALUES ('CCF', 'CCF');
INSERT INTO banque(nombanque, codebanque) VALUES ('Caisse des D�pots', 'CD');
INSERT INTO banque(nombanque, codebanque) VALUES ('Caisse Epargne', 'CE');
INSERT INTO banque(nombanque, codebanque) VALUES ('Caixa Geral de Depositos', 'CGD');
INSERT INTO banque(nombanque, codebanque) VALUES ('CIC', 'CIC');
INSERT INTO banque(nombanque, codebanque) VALUES ('Crédit Industriel de l\'Ouest', 'CIO');
INSERT INTO banque(nombanque, codebanque) VALUES ('Crédit Lyonnais', 'CL');
INSERT INTO banque(nombanque, codebanque) VALUES ('Crédit Mutuel', 'CM');
INSERT INTO banque(nombanque, codebanque) VALUES ('Crédit du Nord', 'CN');
INSERT INTO banque(nombanque, codebanque) VALUES ('Divers', 'D');
INSERT INTO banque(nombanque, codebanque) VALUES ('HSBC', 'HSBC');
INSERT INTO banque(nombanque, codebanque) VALUES ('La Poste', 'LP');
INSERT INTO banque(nombanque, codebanque) VALUES ('Paribas', 'P');
INSERT INTO banque(nombanque, codebanque) VALUES ('Groupe Banque Populaire', 'SBE');
INSERT INTO banque(nombanque, codebanque) VALUES ('Société Générale', 'SG');
INSERT INTO banque(nombanque, codebanque) VALUES ('Trésors Public', 'TP');
INSERT INTO banque(nombanque, codebanque) VALUES ('Union des Banques de Paris', 'UBP');

-- Insertion des m�thodes de paiement --
INSERT INTO paiement(nompaiement) VALUES ('Liquide');
INSERT INTO paiement(nompaiement) VALUES ('Chèque');

-- Insertion des rangs --
INSERT INTO rang(nomrang) VALUES ('Utilisateur');
INSERT INTO rang(nomrang) VALUES ('Vendeur');
INSERT INTO rang(nomrang) VALUES ('Opérateur');
INSERT INTO rang(nomrang) VALUES ('Administrateur de foire');
INSERT INTO rang(nomrang) VALUES ('Super Administrateur');

-- Insertion des utilisateurs par d�faut --
INSERT INTO utilisateur(nomutilisateur, prenomutilisateur, adresse, codepostal, ville, telephone, baisse, changelock, login, motdepasse, email, rang)
VALUES('SUPER', 'ADMIN', '127.0.0.1', '404', 'Internet', '8', TRUE, FALSE, 'superadmin', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0', 'superadmin@localhost', 5);
INSERT INTO utilisateur(nomutilisateur, adresse, codepostal, ville, baisse, changelock, login, motdepasse, email, rang)
VALUES('AFAE', '16 Boulevard AbelCornaton', 91290, 'Arpajon', FALSE, FALSE, 'afae', 'b272dbd564ebd3898c2ecd69fcd94b67', 'afae@free.fr', 2);

-- Insertion de l'association AFAE --
INSERT INTO association(nomassociation, sigleassociation, villeassociation, idadministrateur)
VALUES ('Association des Familles d\'Arpajon et de ses Environs', 'AFAE', 'Arpajon', (SELECT idutilisateur FROM utilisateur WHERE nomutilisateur='AFAE'))