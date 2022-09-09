USE CONFVIRTUAL;
DROP PROCEDURE IF EXISTS autenticazioneUtente;
DROP PROCEDURE IF EXISTS registrazioneUtente;
DROP PROCEDURE IF EXISTS registrazioneAmministratore;
DROP PROCEDURE IF EXISTS registrazioneSpeaker;
DROP PROCEDURE IF EXISTS registrazionePresenter;
DROP PROCEDURE IF EXISTS inserimentoFoto;
DROP PROCEDURE IF EXISTS inserimentoFileEsterni;
DROP PROCEDURE IF EXISTS modificaFotoSpeaker;
DROP PROCEDURE IF EXISTS modificaCvSpeaker;
DROP PROCEDURE IF EXISTS modificaFotoPresenter;
DROP PROCEDURE IF EXISTS modificaCvPresenter;
DROP PROCEDURE IF EXISTS eliminaFoto;
DROP PROCEDURE IF EXISTS eliminaFileEsterni;
DROP PROCEDURE IF EXISTS modificaUniversitaPresenter;
DROP PROCEDURE IF EXISTS modificaUniversitaSpeaker;
DROP PROCEDURE IF EXISTS inserimentoRisorsa;
DROP PROCEDURE IF EXISTS modificaRisorsa;
DROP PROCEDURE IF EXISTS verificoUsername;
DROP PROCEDURE IF EXISTS registrazioneConferenza;
DROP PROCEDURE IF EXISTS inserimentoMessaggi;

DROP PROCEDURE IF EXISTS inserimentoFavorite;
DROP PROCEDURE IF EXISTS creazioneConferenza;
DROP PROCEDURE IF EXISTS creazioneSessione;
DROP PROCEDURE IF EXISTS creazioneTutorial;
DROP PROCEDURE IF EXISTS creazioneArticoli;
DROP PROCEDURE IF EXISTS presentazioneSpeaker;
DROP PROCEDURE IF EXISTS inserimentoSponsor;
DROP PROCEDURE IF EXISTS sponsorizzazione;
DROP PROCEDURE IF EXISTS inserimentoValutazioneTutorial;
DROP PROCEDURE IF EXISTS inserimentoValutazioneArticolo;

#####	OPERAZIONI CHE RIGUARDANO TUTTI GLI UTENTI #####

#Stored procedure per l'autenticazione di un utente che mi ritorna il tipo di utente
DELIMITER |
CREATE PROCEDURE autenticazioneUtente(IN InputUsername VARCHAR(100), IN InputPassword VARCHAR(100), OUT Tipo VARCHAR(30),  OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numUsername INT;
	DECLARE numAmministratore INT;
	DECLARE numPresenter INT;
	DECLARE numSpeaker INT;
	SET numUsername = (SELECT COUNT(*) FROM UTENTE WHERE (Username = InputUsername AND PasswordUtente = InputPassword));
	IF numUsername = 1 THEN
		SET Esito =  'OK';
		SET numAmministratore = (SELECT COUNT(*) FROM AMMINISTRATORE WHERE (UsernameUtente = InputUsername));
		IF numAmministratore = 1 THEN
			SET Tipo = 'amministratore';
		ELSE
			SET numPresenter = (SELECT COUNT(*) FROM PRESENTER WHERE (UsernameUtente = InputUsername));
			IF numPresenter = 1 THEN
				SET Tipo = 'presenter';
			ELSE
				SET numSpeaker = (SELECT COUNT(*) FROM SPEAKER WHERE (UsernameUtente = InputUsername));
				IF numSpeaker = 1 THEN
					SET Tipo = 'speaker';
				ELSE
					SET Tipo = 'utente';
				END IF;
			END IF;
		END IF;
	ELSE
		SET Esito = 'Credenziali errate';
		SET Tipo = '';
	END IF; 
END;
|
DELIMITER ;

#Stored procedure per la registrazione di un utente alla piattaforma
DELIMITER |
CREATE PROCEDURE registrazioneUtente(IN InputUsername VARCHAR(100), IN InputPassword VARCHAR(50), IN InputNome VARCHAR(100), IN InputCognome VARCHAR(100), IN InputLuogoNascita VARCHAR(50), IN InputDataNascita DATE, OUT Esito VARCHAR(100)) 
BEGIN
	CALL verificoUsername(InputUsername, Esito);
	IF Esito = 'Non Esiste' THEN
		INSERT INTO UTENTE (Username, PasswordUtente, Nome, Cognome, LuogoNascita, DataNascita) VALUES (InputUsername, InputPassword, InputNome, InputCognome, InputLuogoNascita, InputDataNascita);
		SET Esito =  'Utente inserito';
	ELSE
		SET Esito = 'Username già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per la registrazione di un amministratore alla piattaforma
DELIMITER |
CREATE PROCEDURE registrazioneAmministratore(IN InputUsername VARCHAR(100), IN InputPassword VARCHAR(50), IN InputNome VARCHAR(100), IN InputCognome VARCHAR(100), IN InputLuogoNascita VARCHAR(50), IN InputDataNascita DATE, OUT Esito VARCHAR(100)) 
BEGIN
	CALL registrazioneUtente(InputUsername, InputPassword, InputNome, InputCognome, InputLuogoNascita, InputDataNascita, Esito);
	IF (Esito = 'Utente inserito') THEN
		INSERT INTO AMMINISTRATORE (UsernameUtente) VALUES (InputUsername);
	END IF;	
END;
|
DELIMITER ;

#Stored procedure per la registrazione di uno speaker
DELIMITER |
CREATE PROCEDURE registrazioneSpeaker(IN InputUsername VARCHAR(100), IN InputPassword VARCHAR(50), IN InputNome VARCHAR(100), IN InputCognome VARCHAR(100), IN InputLuogoNascita VARCHAR(50), IN InputDataNascita DATE, IN InputCv INT, IN InputFoto INT, IN InputDipartimento VARCHAR(50), OUT Esito VARCHAR(100)) 
BEGIN
	CALL registrazioneUtente(InputUsername, InputPassword, InputNome, InputCognome, InputLuogoNascita, InputDataNascita, Esito);
	IF (Esito = 'Utente inserito') THEN
		INSERT INTO SPEAKER (UsernameUtente, Cv, Foto, Nome, Dipartimento) VALUES (InputUsername, InputCv, InputFoto, InputNome, InputDipartimento);
	END IF;	
END;
|
DELIMITER ;

#Stored procedure per la registrazione di un presenter
DELIMITER |
CREATE PROCEDURE registrazionePresenter(IN InputUsername VARCHAR(100), IN InputPassword VARCHAR(50), IN InputNome VARCHAR(100), IN InputCognome VARCHAR(100), IN InputLuogoNascita VARCHAR(50), IN InputDataNascita DATE, IN InputCv INT, IN InputFoto INT, IN InputDipartimento VARCHAR(50), OUT Esito VARCHAR(100)) 
BEGIN
	CALL registrazioneUtente(InputUsername, InputPassword, InputNome, InputCognome, InputLuogoNascita, InputDataNascita, Esito);
	IF (Esito = 'Utente inserito') THEN
		INSERT INTO PRESENTER (UsernameUtente, Cv, Foto, Nome, Dipartimento) VALUES (InputUsername, InputCv, InputFoto, InputNome, InputDipartimento);
	END IF;	
END;
|
DELIMITER ;

#####	OPERAZIONI CHE RIGUARDANO PRESENTER E SPEAKER	#####

#Stored procedure per l'inserimento di foto e logo 
DELIMITER |
CREATE PROCEDURE inserimentoFoto(IN InputNomeFile VARCHAR(50), IN InputDatiLogo BLOB, OUT Esito VARCHAR(100)) 
BEGIN
	INSERT INTO LOGO (NomeFile, DatiLogo) VALUES (InputNomeFile, InputDatiLogo);
    SET Esito = 'Foto caricata';
END;
|
DELIMITER ;

#Stored procedure per l'inserimento di file esterni
DELIMITER |
CREATE PROCEDURE inserimentoFileEsterni(IN InputNomeFile VARCHAR(50), IN InputDatiFile BLOB, OUT Esito VARCHAR(100)) 
BEGIN
	INSERT INTO FILEESTERNI (NomeFile, DatiFile) VALUES (InputNomeFile, InputDatiFile);
    SET Esito = 'File caricato';
END;
|
DELIMITER ;

#Stored procedure per la modifica di foto dello speaker
DELIMITER |
CREATE PROCEDURE modificaFotoSpeaker(IN InputUsernameUtente VARCHAR(100), IN InputIdFoto INT, OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE SPEAKER  
	SET Foto = InputIdFoto WHERE UsernameUtente = InputUsernameUtente;
    SET Esito = 'Foto modificata';
END;
|
DELIMITER ;

#Stored procedure per la modifica di file esterni per lo speaker
DELIMITER |
CREATE PROCEDURE modificaCvSpeaker(IN InputUsernameUtente VARCHAR(100), IN InputCv VARCHAR(30), OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE SPEAKER  
	SET Cv = InputCv WHERE UsernameUtente = InputUsernameUtente;
    SET Esito = 'CV modificato';
END;
|
DELIMITER ;

#Stored procedure per la modifica di foto di presenter
DELIMITER |
CREATE PROCEDURE modificaFotoPresenter(IN InputUsernameUtente VARCHAR(100), IN InputIdFoto INT, OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE PRESENTER  
	SET Foto = InputIdFoto WHERE UsernameUtente = InputUsernameUtente;
    SET Esito = 'Foto modificata';
END;
|
DELIMITER ;

#Stored procedure per la modifica di file esterni per il presenter
DELIMITER |
CREATE PROCEDURE modificaCvPresenter(IN InputUsernameUtente VARCHAR(100), IN InputCv VARCHAR(30), OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE PRESENTER  
	SET Cv = InputCv WHERE UsernameUtente = InputUsernameUtente;
    SET Esito = 'CV modificato';
END;
|
DELIMITER ;

#Stored procedure per l'eliminazione di foto di presenter e speaker
DELIMITER |
CREATE PROCEDURE eliminaFoto(IN InputIdFoto INT, OUT Esito VARCHAR(100)) 
BEGIN
	DELETE FROM LOGO  
	WHERE idLogo = InputIdFoto;
    SET Esito = 'Foto eliminata';
END;
|
DELIMITER ;

#Stored procedure per l'eliminazione di file esterni
DELIMITER |
CREATE PROCEDURE eliminaFileEsterni(IN InputIdFile INT, OUT Esito VARCHAR(100)) 
BEGIN
	DELETE FROM FILEESTERNI  
	WHERE idFile = InputIdFile;
    SET Esito = 'File eliminata';
END;
|
DELIMITER ;

#Stored procedure per la modifica del nome e del dipartimento dell'affiliazione universitaria del presenter
DELIMITER |
CREATE PROCEDURE modificaUniversitaPresenter(IN InputUsernameUtente VARCHAR(100), IN InputNome VARCHAR(100), IN InputDipartimento VARCHAR(50), OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE PRESENTER   
	SET Nome = InputNome, Dipartimento = InputDipartimento WHERE UsernameUtente = InputUsernameUtente; 
    SET Esito = 'Affiliazione universitaria modificata';
END;
|
DELIMITER ;

#Stored procedure per la modifica del nome e del dipartimento dell'affiliazione universitaria del speaker
DELIMITER |
CREATE PROCEDURE modificaUniversitaSpeaker(IN InputUsernameUtente VARCHAR(100), IN InputNome VARCHAR(100), IN InputDipartimento VARCHAR(50), OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE SPEAKER   
	SET Nome = InputNome, Dipartimento = InputDipartimento WHERE UsernameUtente = InputUsernameUtente; 
    SET Esito = 'Affiliazione universitaria modificata';
END;
|
DELIMITER ;

#Stored procedure per l'inserimento di una nuova risorsa
DELIMITER |
CREATE PROCEDURE inserimentoRisorsa(IN InputLinkWeb VARCHAR(200), IN InputDescrizione VARCHAR(300), IN InputCodiceTutorial VARCHAR(10), IN InputUsernameUtenteSpeaker VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numRisorsa INT;
	SET numRisorsa = (SELECT COUNT(*) FROM RISORSAAGGIUNTIVA WHERE (UsernameUtenteSpeaker = InputUsernameUtenteSpeaker AND CodiceTutorial = InputCodiceTutorial));
	IF numRisorsa = 0 THEN
		INSERT INTO RISORSAAGGIUNTIVA (LinkWeb, Descrizione, CodiceTutorial, UsernameutenteSpeaker) VALUES (InputLinkWeb, InputDescrizione, InputCodiceTutorial, InputUsernameutenteSpeaker);
		SET Esito = 'Risorsa aggiunta';
	ELSE
		SET Esito = 'Risorsa già presente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per la modifica di una risorsa aggiuntiva
DELIMITER |
CREATE PROCEDURE modificaRisorsa(IN InputLinkWeb VARCHAR(200), IN InputDescrizione VARCHAR(300), IN InputCodiceTutorial VARCHAR(10), IN InputUsernameUtenteSpeaker VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	UPDATE RISORSAAGGIUNTIVA   
	SET LinkWeb = InputLinkWeb, Descrizione = InputDescrizione WHERE (UsernameUtenteSpeaker = InputUsernameUtenteSpeaker AND CodiceTutorial = InputCodiceTutorial);
    SET Esito = 'Risorsa  modificata';
END;
|
DELIMITER ;

#####	OPERAZIONI CHE RIGUARDANO TUTTI GLI UTENTI #####

#Stored procedure per verificare la presenza di già un username
DELIMITER |
CREATE PROCEDURE verificoUsername(IN InputUsername VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numUsername INT;
	SET numUsername = (SELECT COUNT(*) FROM UTENTE WHERE (Username = InputUsername));
	IF numUsername = 0 THEN
        SET Esito =  'Non Esiste'; # se è = 0 non ci sono utenti con lo stesso username passato in input
	ELSE
		SET Esito = 'Esiste';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per la registrazione ad una conferenza
DELIMITER |
CREATE PROCEDURE registrazioneConferenza(IN InputAcronimoConferenza VARCHAR(10), IN InputAnnoEdizioneConferenza YEAR, IN InputUsernameUtente VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numCreazione INT;
	SET numCreazione = (SELECT COUNT(*) FROM REGISTRAZIONE WHERE (AnnoEdizioneConferenza = InputAnnoEdizioneConferenza AND AcronimoConferenza = InputAcronimoConferenza AND UsernameUtente = InputUsernameUtente));
		IF numCreazione = 0 THEN
			INSERT INTO REGISTRAZIONE (AcronimoConferenza, AnnoEdizioneConferenza, UsernameUtente) VALUES (InputAcronimoConferenza, InputAnnoEdizioneConferenza, InputUsernameUtente);
			SET Esito = 'Registrazione Effettuata';
		END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento di messaggi nella chat
DELIMITER |
CREATE PROCEDURE inserimentoMessaggi(IN InputTesto VARCHAR(250), IN InputCodiceChat VARCHAR(10), IN InputUsernameUtente VARCHAR (100), OUT Esito VARCHAR(100)) 
BEGIN
	INSERT INTO MESSAGGIO (Testo, DataMessaggio, CodiceChat, UsernameUtente) VALUES (InputTesto, GETDATE(), InputCodiceChat, InputUsernameUtente);
    SET Esito = 'Inserito';
END;
|
DELIMITER ;

#Stored procedure per l'inserimento lista presentazioni favorite
DELIMITER |
CREATE PROCEDURE inserimentoFavorite(IN InputTitoloPresentazione VARCHAR(50), IN InputUsernameUtente VARCHAR(100),  OUT Esito VARCHAR(100)) 
BEGIN
DECLARE numFavorita INT;
	SET numFavorita = (SELECT COUNT(*) FROM PRESENTAZIONEFAVORITA WHERE (TitoloPresentazione = InputTitoloPresentazione AND UsernameUtente = InputUsernameUtente));
		IF numFavorita = 0 THEN
			INSERT INTO PRESENTAZIONEFAVORITA (TitoloPresentazione, UsernameUtente) VALUES (InputTitoloPresentazione, InputUsernameUtente);
			SET Esito = 'Inserita';
		END IF;
END;
|
DELIMITER ;

#####	OPERAZIONI SOLO PER UTENTI AMMINISTRATORE		#####

#Stored procedure per la creazione di una nuova conferenza
DELIMITER |
CREATE PROCEDURE creazioneConferenza(IN InputAcronimo VARCHAR(10), IN InputAnnoEdizione YEAR, IN InputNome VARCHAR(50), IN InputLogo INT, IN InputCampoSvolgimento VARCHAR(50), IN InputTotaleSponsorizzazioni DOUBLE, IN InputUsernameUtente VARCHAR (100), IN InputGiorno INT, IN InputCodice VARCHAR(10), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numConferenza INT;
	SET numConferenza = (SELECT COUNT(*) FROM CONFERENZA WHERE (AnnoEdizione = InputAnnoEdizione AND Acronimo = InputAcronimo));
	IF numConferenza = 0 THEN
		INSERT INTO CONFERENZA (Acronimo, AnnoEdizione, Nome, Logo, CampoSvolgimento, TotaleSponsorizzazioni, UsernameUtenteAmministratore) VALUES (InputAcronimo, InputAnnoEdizione, InputNome, InputLogo, 'ATTIVA', InputTotaleSponsorizzazioni, InputUsernameUtente);
		INSERT INTO PROGRAMMAGIORNALIERO (Codice, Giorno, AcronimoConferenza, AnnoEdizioneConferenza) VALUES (InputCodice, InputGiorno, InputAcronimo, YEAR(GETDATE())); #dalla data di oggi estrae l'anno
        SET Esito = 'Conferenza creata';
	ELSE
		SET Esito = 'Conferenza già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per la creazione di una nuova sessione della conferenza
DELIMITER |
CREATE PROCEDURE creazioneSessione(IN InputCodice VARCHAR(10), IN InputTitolo VARCHAR(50), IN InputNumeroPresentazioni INT, IN InputOraInizio TIME, IN InputOraFine TIME, IN InputLink VARCHAR(200), IN InputCodiceProgrammaGiornaliero VARCHAR (10), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numSessione INT;
	SET numSessione = (SELECT COUNT(*) FROM SESSIONE WHERE (Codice = InputCodice AND Titolo = InputTitolo AND OraInizio = InputOraInizio AND OraFine = InputOraFine));
	IF numSessione = 0 THEN
		INSERT INTO SESSIONE (Codice, Titolo, NumeroPresentazioni, OraInizio, OraFine, Link, CodiceProgrammaGiornaliero) VALUES (InputCodice, InputTitolo, InputNumeroPresentazioni, InputOraInizio, InputOraFine, InputLink, InputCodiceProgrammaGiornaliero);
        SET Esito = 'Sessione creata';
	ELSE
		SET Esito = 'Sessione già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento delle presentazioni di tutorial in una sessione 
DELIMITER |
CREATE PROCEDURE creazioneTutorial(IN InputCodice VARCHAR(10), IN InputOraInizio TIME, IN InputOraFine TIME, IN InputNumSequenza INT, IN InputTitolo VARCHAR(50), IN InputAbstract VARCHAR(500), IN InputCodiceSessione VARCHAR (10), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numTutorial INT;
	SET numTutorial = (SELECT COUNT(*) FROM TUTORIAL WHERE (Codice = InputCodice AND Titolo = InputTitolo AND OraInizio = InputOraInizio AND OraFine = InputOraFine));
	IF numTutorial = 0 THEN
		INSERT INTO TUTORIAL (Codice, OraInizio, OraFine, NumSequenza, Titolo, Abstract, CodiceSessione) VALUES (InputCodice, InputOraInizio, InputOraFine, InputNumSequenza, InputTitolo, InputAbstract, InputCodiceSessione);
        SET Esito = 'Tutorial inserito';
	ELSE
		SET Esito = 'Tutorial già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento delle presentazioni di un articolo in una sessione (comprende l'associazione del presenter alla presentazione)
DELIMITER |
CREATE PROCEDURE creazioneArticoli(IN InputCodice VARCHAR(10), IN InputOraInizio TIME, IN InputOraFine TIME, IN InputNumSequenza INT, IN InputTitolo VARCHAR(50), IN InputNumPagine INT, IN InputFilePresentazione INT, 
    IN InputUsernameUtentePresenter VARCHAR (100), IN CodiceSessione VARCHAR(4), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numArticoli INT;
	SET numArticoli = (SELECT COUNT(*) FROM PRESENTAZIONEDIARTICOLI WHERE (Codice = InputCodice AND Titolo = InputTitolo AND OraInizio = InputOraInizio AND OraFine = InputOraFine));
	IF numArticoli = 0 THEN
		INSERT INTO PRESENTAZIONEDIARTICOLI (Codice, OraInizio, OraFine, NumSequenza, Titolo, NumPagine, FilePresentazione, StatoSvolgimento, UsernameUtentePresenter, CodiceSessione) 
			VALUES (InputCodice, InputOraInizio, InputOraFine, InputNumSequenza, InputTitolo, InputNumPagine, InputFilePresentazione, 'Non coperto', InputUsernameUtentePresenter, InputCodiceSessione);
        SET Esito = 'Articolo inserito';
	ELSE
		SET Esito = 'Articolo già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'associazione dello speaker alla presentazione del suo tutorial in una sessione 
DELIMITER |
CREATE PROCEDURE presentazioneSpeaker(IN InputCodiceTutorial VARCHAR(10), IN InputUsernameUtenteSpeaker VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numTutorial INT;
	SET numTutorial = (SELECT COUNT(*) FROM PRESENTAZIONE WHERE (CodiceTutorial = InputCodiceTutorial AND UsernameUtenteSpeaker = InputUsernameUtenteSpeaker ));
    IF numTutorial = 0 THEN
		INSERT INTO PRESENTAZIONE (CodiceTutorial, UsernameUtenteSpeaker) VALUES (InputCodiceTutorial, InputUsernameUtenteSpeaker);
        SET Esito = 'Speaker associato ';
	ELSE
		SET Esito = 'Speaker già associato';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento di uno sponsor
DELIMITER |
CREATE PROCEDURE inserimentoSponsor(IN InputNome VARCHAR(100), IN InputLogo INT, IN InputImporto INT, OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numSponsor INT;
	SET numSponsor = (SELECT COUNT(*) FROM SPONSOR WHERE (Nome = InputNome AND Importo = InputImporto));
    IF numSponsor = 0 THEN
		INSERT INTO SPONSOR (Nome, Logo, Importo) VALUES (InputNome, InputLogo, InputImporto);
        SET Esito = 'Sponsor inserito ';
	ELSE
		SET Esito = 'Sponsor già esistente';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per la sponsorizzazione (di uno sponsor in una conferenza)
DELIMITER |
CREATE PROCEDURE sponsorizzazione(IN InputAcronimoConferenza VARCHAR(10), IN InputAnnoEdizioneConferenza YEAR, IN InputNomeSponsor VARCHAR(100), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numSponsorizzazione INT;
	SET numSponsorizzazione = (SELECT COUNT(*) FROM DISPOSIZIONE WHERE (NomeSponsor = InputNomeSponsor AND AcronimoConferenza = InputAcronimoConferenza));
    IF numSponsorizzazione = 0 THEN
		INSERT INTO DISPOSIZIONE (AcronimoConferenza, AnnoEdizioneConferenza, NomeSponsor) VALUES (InputAcronimoConferenza, InputAnnoEdizioneConferenza, InputNomeSponsor);
        SET Esito = 'Sponsorizzazione effettuata ';
	ELSE
		SET Esito = 'Sponsorizzazione già effettuata';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento delle valutazioni delle presentazioni di tutorial
DELIMITER |
CREATE PROCEDURE inserimentoValutazioneTutorial(IN InputVoto INT, IN InputUsernameUtenteAmministratore VARCHAR(100), IN InputNote VARCHAR(200), IN InputCodiceTutorial VARCHAR(10), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numValutazione INT;
	SET numValutazione = (SELECT COUNT(*) FROM VALUTAZIONE WHERE (UsernameUtenteAmministratore = InputUsernameUtenteAmministratore AND CodiceTutorial = InputCodiceTutorial));
    IF numValutazione = 0 THEN
		INSERT INTO VALUTAZIONE (Voto, UsernameUtenteAmministratore, Note, CodiceTutorial) VALUES (InputVoto, InputUsernameUtenteAmministratore, InputNote, InputCodiceTutorial);
        SET Esito = 'Valutazione Tutorial effettuata ';
	ELSE
		SET Esito = 'Valutazione Tutorial già effettuata';
	END IF;
END;
|
DELIMITER ;

#Stored procedure per l'inserimento delle valutazioni delle presentazioni di articoli
DELIMITER |
CREATE PROCEDURE inserimentoValutazioneArticolo(IN InputVoto INT, IN InputUsernameUtenteAmministratore VARCHAR(100), IN InputNote VARCHAR(200), IN InputCodicePresentazioneDiArticoli VARCHAR(10), OUT Esito VARCHAR(100)) 
BEGIN
	DECLARE numValutazione INT;
	SET numValutazione = (SELECT COUNT(*) FROM VALUTAZIONE WHERE (UsernameUtenteAmministratore = InputUsernameUtenteAmministratore AND CodicePresentazioneDiArticoli = InputCodicePresentazioneDiArticoli));
    IF numValutazione = 0 THEN
		INSERT INTO VALUTAZIONE (Voto, UsernameUtenteAmministratore, Note, CodicePresentazioneDiArticoli) VALUES (InputVoto, InputUsernameUtenteAmministratore, InputNote, InputCodicePresentazioneDiArticoli);
        SET Esito = 'Valutazione Articolo effettuata ';
	ELSE
		SET Esito = 'Valutazione Articolo già effettuata';
	END IF;
END;
|
DELIMITER ;








