DROP DATABASE IF EXISTS CONFVIRTUAL;
CREATE DATABASE IF NOT EXISTS CONFVIRTUAL;
USE CONFVIRTUAL;

set global event_scheduler=ON;
set autocommit=0;

/* Costruisce la tabella LOGO che serve anche per le foto di speaker e presenter oltre al logo degli sponsor*/
CREATE TABLE LOGO (
	idLogo INT auto_increment PRIMARY KEY,
    NomeFile VARCHAR(50),
    DatiLogo BLOB
) ENGINE=INNODB;

/* Costruisce la tabella FILEESTERNI */
CREATE TABLE FILEESTERNI (
	idFile INT auto_increment PRIMARY KEY,
    NomeFile VARCHAR(50),
    DatiFile BLOB
) ENGINE=INNODB;

/* Costruisce la tabella SPONSOR */
CREATE TABLE SPONSOR (
	Nome VARCHAR (100) PRIMARY KEY,
    Logo INT,
    Importo INT,
    FOREIGN KEY (Logo) REFERENCES LOGO(idLogo)
) ENGINE=INNODB;

/* Costruisce la tabella UTENTE */
CREATE TABLE UTENTE (
	Username VARCHAR(100) PRIMARY KEY,
    PasswordUtente VARCHAR(50) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    DataNascita DATE NOT NULL,
    LuogoNascita VARCHAR (50) NOT NULL
) ENGINE=INNODB;

/* Costruisce la tabella PRESENTER */
CREATE TABLE PRESENTER (
	UsernameUtente VARCHAR(100) PRIMARY KEY,
    Cv VARCHAR(30) NOT NULL,
    Foto INT,
    Nome VARCHAR(100) NOT NULL,
    Dipartimento VARCHAR (50) NOT NULL,
    FOREIGN KEY (Foto) REFERENCES LOGO(idLogo),
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)
) ENGINE=INNODB;

/* Costruisce la tabella SPEAKER */
CREATE TABLE SPEAKER (
	UsernameUtente VARCHAR(100) PRIMARY KEY,
    Cv VARCHAR(30) NOT NULL,
    Foto INT,
    Nome VARCHAR(100) NOT NULL,
    Dipartimento VARCHAR (50) NOT NULL,
    FOREIGN KEY (Foto) REFERENCES LOGO(idLogo),
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)
) ENGINE=INNODB;

/* Costruisce la tabella AMMINISTRATORE */
CREATE TABLE AMMINISTRATORE (
	UsernameUtente VARCHAR (100) PRIMARY KEY,
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)
) ENGINE=INNODB;

/* Costruisce la tabella CONFERENZA */
CREATE TABLE CONFERENZA (
	Acronimo VARCHAR(10) NOT NULL,
    AnnoEdizione YEAR NOT NULL,
    Nome VARCHAR(50) NOT NULL,
    Logo INT,
    CampoSvolgimento ENUM('ATTIVA','COMPLETATA') NOT NULL,
    TotaleSponsorizzazioni DOUBLE,
    UsernameUtenteAmministratore VARCHAR (100) NOT NULL,
    FOREIGN KEY (UsernameUtenteAmministratore) REFERENCES AMMINISTRATORE(UsernameUtente),
    FOREIGN KEY (Logo) REFERENCES LOGO(idLogo),
    PRIMARY KEY (Acronimo, AnnoEdizione)
) ENGINE=INNODB;

/* Costruisce la tabella DISPOSIZIONE */
CREATE TABLE DISPOSIZIONE (
    AcronimoConferenza VARCHAR (10),
    AnnoEdizioneConferenza YEAR,
    NomeSponsor VARCHAR (100),
    PRIMARY KEY (AcronimoConferenza, AnnoEdizioneConferenza, NomeSponsor),
    FOREIGN KEY (AcronimoConferenza,AnnoEdizioneConferenza) REFERENCES CONFERENZA (Acronimo, AnnoEdizione),
    FOREIGN KEY (NomeSponsor) REFERENCES SPONSOR(Nome)    
) ENGINE=INNODB;

/* Costruisce la tabella ASSOCIAZIONE */
CREATE TABLE ASSOCIAZIONE (
    AcronimoConferenza VARCHAR (10),
    AnnoEdizioneConferenza YEAR,
    UsernameUtenteAmministratore VARCHAR (100),
    PRIMARY KEY (AcronimoConferenza, AnnoEdizioneConferenza, UsernameUtenteAmministratore),
    FOREIGN KEY (AcronimoConferenza, AnnoEdizioneConferenza) REFERENCES CONFERENZA (Acronimo, AnnoEdizione),
    FOREIGN KEY (UsernameUtenteAmministratore) REFERENCES AMMINISTRATORE(UsernameUtente)
) ENGINE=INNODB;

/* Costruisce la tabella REGISTRAZIONE */
CREATE TABLE REGISTRAZIONE (
    AcronimoConferenza VARCHAR (10),
    AnnoEdizioneConferenza YEAR,
    UsernameUtente VARCHAR (100),
    PRIMARY KEY (AcronimoConferenza, AnnoEdizioneConferenza, UsernameUtente),
    FOREIGN KEY (AcronimoConferenza, AnnoEdizioneConferenza) REFERENCES CONFERENZA (Acronimo, AnnoEdizione),
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)
) ENGINE=INNODB;

/* Costruisce la tabella PROGRAMMAGIORNALIERO */
CREATE TABLE PROGRAMMAGIORNALIERO (
	Codice INT NOT NULL auto_increment PRIMARY KEY,
    DataConferenza DATE,
    AcronimoConferenza VARCHAR (10),
    AnnoEdizioneConferenza YEAR,
	FOREIGN KEY (AcronimoConferenza, AnnoEdizioneConferenza) REFERENCES CONFERENZA (Acronimo, AnnoEdizione)
) ENGINE=INNODB;

/* Costruisce la tabella SESSIONE */
CREATE TABLE SESSIONE (
	Codice INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Titolo VARCHAR (100) NOT NULL,
    NumeroPresentazioni INT NOT NULL DEFAULT 0,
    OraInizio time(0) NOT NULL,
    OraFine time(0) NOT NULL,
    Link VARCHAR (200) NOT NULL,
    CodiceProgrammaGiornaliero INT,
    FOREIGN KEY (CodiceProgrammaGiornaliero) REFERENCES PROGRAMMAGIORNALIERO(Codice)
) ENGINE=INNODB;

/* Costruisce la tabella CHAT */
CREATE TABLE CHAT (
	Codice INT auto_increment PRIMARY KEY,
    CodiceSessione INT,
    FOREIGN KEY (CodiceSessione) REFERENCES SESSIONE (Codice)
) ENGINE=INNODB;

/* Costruisce la tabella MESSAGGIO */
CREATE TABLE MESSAGGIO (
    Testo VARCHAR (250),
    DataMessaggio DATE,
    CodiceChat INT,
    UsernameUtente VARCHAR (100),
    PRIMARY KEY (Testo, DataMessaggio),
	FOREIGN KEY (CodiceChat) REFERENCES CHAT(Codice),
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)
) ENGINE=INNODB;

/* Costruisce la tabella TUTORIAL */
CREATE TABLE TUTORIAL (
	Codice INT auto_increment PRIMARY KEY,
    OraInizio time(0) NOT NULL,
    OraFine time(0) NOT NULL,
    NumSequenza INT NOT NULL,
    Titolo VARCHAR (50),
    Abstract VARCHAR(500),
    CodiceSessione INT,
    FOREIGN KEY (CodiceSessione) REFERENCES SESSIONE(Codice) 
) ENGINE=INNODB;

/* Costruisce la tabella PRESENTAZIONEDIARTICOLI */
CREATE TABLE PRESENTAZIONEDIARTICOLI (
	Codice INT auto_increment PRIMARY KEY,
    OraInizio time(0) NOT NULL,
    OraFine time(0) NOT NULL,
    NumSequenza INT NOT NULL,
    Titolo VARCHAR (50),
    NumPagine INT,
    FilePresentazione INT,
    StatoSvolgimento VARCHAR (200),
    UsernameUtentePresenter VARCHAR (100),
    CodiceSessione INT NOT NULL,
    FOREIGN KEY (UsernameUtentePresenter) REFERENCES PRESENTER(UsernameUtente),
    FOREIGN KEY (CodiceSessione) REFERENCES SESSIONE(Codice),
    FOREIGN KEY (FilePresentazione) REFERENCES FILEESTERNI(idFile)
) ENGINE=INNODB;

/* Costruisce la tabella PAROLACHIAVE */
CREATE TABLE PAROLACHIAVE (
	Descrizione VARCHAR(20) NOT NULL,
    CONSTRAINT PrimaryKeyParolaChiave PRIMARY KEY (Descrizione, CodicePresentazioneDiArticoli),
    CodicePresentazioneDiArticoli INT,
    FOREIGN KEY (CodicePresentazioneDiArticoli) REFERENCES PRESENTAZIONEDIARTICOLI(Codice)
) ENGINE=INNODB;

/* Costruisce la tabella AUTORE */
CREATE TABLE AUTORE (
	Nome VARCHAR(100),
    Cognome VARCHAR(100),
    CONSTRAINT PrimaryKeyAutore PRIMARY KEY (Nome, Cognome, CodicePresentazioneDiArticoli),
    CodicePresentazioneDiArticoli INT,
    FOREIGN KEY (CodicePresentazioneDiArticoli) REFERENCES PRESENTAZIONEDIARTICOLI(Codice)
) ENGINE=INNODB;

/* Costruisce la tabella PRESENTAZIONE */
CREATE TABLE PRESENTAZIONE (
	CodiceTutorial INT,
    UsernameUtenteSpeaker VARCHAR(100),
    PRIMARY KEY (CodiceTutorial, UsernameUtenteSpeaker),
	FOREIGN KEY (CodiceTutorial) REFERENCES TUTORIAL (Codice),
    FOREIGN KEY (UsernameUtenteSpeaker) REFERENCES SPEAKER(UsernameUtente)    
) ENGINE=INNODB;

/* Costruisce la tabella PRESENTAZIONEFAVORITA */
CREATE TABLE PRESENTAZIONEFAVORITA (
	TitoloPresentazione VARCHAR(50),
    UsernameUtente VARCHAR(100),
    PRIMARY KEY (TitoloPresentazione, UsernameUtente),
    FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username)    
) ENGINE=INNODB;

/* Costruisce la tabella RISORSAAGGIUNTIVA */
CREATE TABLE RISORSAAGGIUNTIVA (
	LinkWeb VARCHAR(200) PRIMARY KEY,
    Descrizione VARCHAR(300),
    CodiceTutorial INT,
    UsernameUtenteSpeaker VARCHAR(100),
	FOREIGN KEY (CodiceTutorial) REFERENCES TUTORIAL (Codice),
    FOREIGN KEY (UsernameUtenteSpeaker) REFERENCES SPEAKER(UsernameUtente)    
) ENGINE=INNODB;

/* Costruisce la tabella VALUTAZIONE */
CREATE TABLE VALUTAZIONE (
	Voto INT PRIMARY KEY CHECK (Voto >= 0 AND Voto<=10),
    UsernameUtenteAmministratore VARCHAR(100) NOT NULL,
    Note VARCHAR (50),
    CodiceTutorial INT NULL,
    CodicePresentazioneDiArticoli INT NULL,
    FOREIGN KEY (UsernameUtenteAmministratore) REFERENCES AMMINISTRATORE(UsernameUtente),
    FOREIGN KEY (CodiceTutorial) REFERENCES TUTORIAL(Codice),
    FOREIGN KEY (CodicePresentazioneDiArticoli) REFERENCES PRESENTAZIONEDIARTICOLI(Codice)
) ENGINE=INNODB;
