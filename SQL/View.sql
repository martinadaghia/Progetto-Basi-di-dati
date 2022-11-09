USE CONFVIRTUAL;
DROP VIEW IF EXISTS ConferenzeDisponibili;
DROP VIEW IF EXISTS VisualizzaTutorial;
DROP VIEW IF EXISTS PresentazioneArticoli;
DROP VIEW IF EXISTS MessaggiChat;
DROP VIEW IF EXISTS Favorite;
DROP VIEW IF EXISTS ConferenzeRegistrate;
DROP VIEW IF EXISTS ConferenzeAttive;
DROP VIEW IF EXISTS UtentiRegistrati;
DROP VIEW IF EXISTS ClassificaPresenter;
DROP VIEW IF EXISTS ClassificaSpeaker;
DROP VIEW IF EXISTS NumeroSponsorizzazioni;

DROP TRIGGER IF EXISTS aggiornamentoStatoPresentazioneArticolo;
DROP TRIGGER IF EXISTS numeroPresentazioniAggiornato;
DROP TRIGGER IF EXISTS numeroTutorialAggiornato;

DROP PROCEDURE IF EXISTS numeroPresentazioni;

DROP EVENT IF EXISTS modificaStatoSvolgimentoConferenza;

DROP PROCEDURE IF EXISTS visualizzaMessaggi;
DROP PROCEDURE IF EXISTS visualizzaValutazioniArticoli;
DROP PROCEDURE IF EXISTS visualizzaValutazioniTutorial;

/* View per visualizzare le conferenze disponibili */
CREATE VIEW ConferenzeDisponibili(Acronimo, AnnoEdizione, Nome, Logo) AS
	SELECT Acronimo, AnnoEdizione, Nome, Logo FROM CONFERENZA WHERE (CampoSvolgimento = 'ATTIVA');
	
/* View per visualizzare le sessioni dei tutorial */
CREATE VIEW VisualizzaTutorial AS
	SELECT TUTORIAL.* FROM TUTORIAL JOIN SESSIONE ON TUTORIAL.CodiceSessione = SESSIONE.Codice;

/* View per visualizzare le sessioni della presentazione di articoli */
CREATE VIEW PresentazioneArticoli AS
	SELECT PRESENTAZIONEDIARTICOLI.* FROM PRESENTAZIONEDIARTICOLI JOIN SESSIONE ON PRESENTAZIONEDIARTICOLI.CodiceSessione = SESSIONE.Codice;
    
/* View per la visualizzazione dei messaggi */
CREATE VIEW MessaggiChat AS
	SELECT Testo, DataMessaggio, UsernameUtente FROM MESSAGGIO JOIN CHAT ON MESSAGGIO.CodiceChat = CHAT.Codice JOIN SESSIONE ON CHAT.CodiceSessione = SESSIONE.Codice;

/* View per la visualizzazione delle presentazioni favorite */
CREATE VIEW Favorite AS
	SELECT * FROM PRESENTAZIONEFAVORITA JOIN UTENTE ON PRESENTAZIONEFAVORITA.UsernameUtente = UTENTE.Username;

/*	STATISTICHE	*/

/* View per visualizzare il numero totale di conferenze registrate */
CREATE VIEW ConferenzeRegistrate(numeroConferenze) AS
	SELECT COUNT(*) FROM CONFERENZA GROUP BY AnnoEdizione;
    
/* View per visualizzare il numero totale di conferenze attive */
CREATE VIEW ConferenzeAttive AS
	SELECT COUNT(*) FROM CONFERENZA WHERE CampoSvolgimento = 'ATTIVA';
    
/* View per visualizzare il numero totale di utenti registrati */
CREATE VIEW UtentiRegistrati(numeroUtenti) AS
	SELECT COUNT(*) FROM UTENTE;
    
/* View per visualizzare la classifica dei presenter e degli speaker sulla base del voto medio */
CREATE VIEW ClassificaPresenter(presenter, media) AS
	SELECT AVG(Voto) AS media, UsernameUtente
    FROM VALUTAZIONE, PRESENTAZIONEDIARTICOLI, PRESENTER
    WHERE PRESENTAZIONEDIARTICOLI.UsernameUtentePresenter = PRESENTER.UsernameUtente 
		AND VALUTAZIONE.CodicePresentazioneDiArticoli = PRESENTAZIONEDIARTICOLI.Codice 
        AND VALUTAZIONE.CodicePresentazioneDiArticoli != NULL
    GROUP BY UsernameUtente 
    ORDER BY UsernameUtente;
    
CREATE VIEW ClassificaSpeaker(speaker, media) AS
	SELECT AVG(Voto) AS media, UsernameUtente
    FROM VALUTAZIONE, SPEAKER, PRESENTAZIONE, TUTORIAL
    WHERE PRESENTAZIONE.UsernameUtenteSpeaker = SPEAKER.UsernameUtente 
        AND PRESENTAZIONE.CodiceTutorial = TUTORIAL.Codice AND VALUTAZIONE.CodiceTutorial = TUTORIAL.Codice 
        AND	VALUTAZIONE.CodiceTutorial != NULL
    GROUP BY UsernameUtente 
    ORDER BY UsernameUtente;

/* View che conta il numero di sponsorizzazioni */
CREATE VIEW NumeroSponsorizzazioni AS
	SELECT NomeSponsor, COUNT(*) AS numeroSponsorizzazioni, l.NomeFile
	FROM DISPOSIZIONE AS d 
	JOIN sponsor AS s ON s.Nome=d.NomeSponsor
    JOIN logo AS l ON l.idLogo=s.Logo
	GROUP BY AcronimoConferenza;

/* 	VINCOLI SUL IMPLEMENTAZIONE	*/

/* Trigger per cambiare lo stato di una presentazione di articolo, una volta creato l articolo con il presenter che è in stato Non coperto cambia subito stato */
DELIMITER |
CREATE TRIGGER aggiornamentoStatoPresentazioneArticolo 
AFTER UPDATE ON PRESENTAZIONEDIARTICOLI  
FOR EACH ROW
BEGIN
	UPDATE PRESENTAZIONEDIARTICOLI SET StatoSvolgimento = 'Coperto' WHERE StatoSvolgimento = 'Non coperto';
END;
|
DELIMITER ;

/* Trigger per implementare l’operazione di aggiornamento del campo numero_presentazioni ogni qualvolta si aggiunge una nuova presentazione ad una sessione della conferenza */
DELIMITER |
CREATE TRIGGER numeroPresentazioniAggiornato 
AFTER INSERT ON PRESENTAZIONEDIARTICOLI  
FOR EACH ROW
BEGIN
	CALL numeroPresentazioni(NEW.CodiceSessione);
END;
|
DELIMITER ;

DELIMITER |
CREATE TRIGGER numeroTutorialAggiornato 
AFTER INSERT ON TUTORIAL  
FOR EACH ROW
BEGIN
	CALL numeroPresentazioni(NEW.CodiceSessione);
END;
|
DELIMITER ;

DELIMITER |
CREATE PROCEDURE numeroPresentazioni(IN InputCodice VARCHAR(10))
BEGIN
	DECLARE numero INT;
	SET numero = (SELECT SESSIONE.NumeroPresentazioni FROM SESSIONE WHERE (SESSIONE.Codice = InputCodice));
	SET numero = (numero + 1);
	UPDATE SESSIONE SET NumeroPresentazioni = numero WHERE (SESSIONE.Codice = InputCodice);
END;
|
DELIMITER ;

/* Event per modificare il campo svolgimento di una conferenza, è Completata non appena la data corrente eccede di un giorno l’ultima data di svolgimento di una conferenza. */
DELIMITER |
CREATE EVENT modificaStatoSvolgimentoConferenza
ON SCHEDULE EVERY 1 DAY
STARTS '2022-08-18 00:01:00'
DO
BEGIN
	DECLARE AnnoEdizioneConf YEAR;
	DECLARE AcronimoConf VARCHAR(10);	
    
	DECLARE isCompletata BOOLEAN;
	DECLARE stopCondition INT DEFAULT 0; /* mi serve per bloccare il while */
    
	DECLARE conferenze CURSOR FOR SELECT AnnoEdizione, Acronimo FROM CONFERENZA WHERE (CampoSvolgimento = 'ATTIVA'); /* array con in ogni casella l anno e l acronimo di una conferenza */
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET stopCondition = 1; /* quando finisce di leggere l array */

	SET stopCondition = 0;
	OPEN conferenze;
	WHILE(stopCondition = 0) DO
		FETCH conferenze INTO AnnoEdizioneConf, AcronimoConf;
		SET isCompletata = (SELECT IF(CURRENT_DATE() > MAX(PROGRAMMAGIORNALIERO.DataConferenza), TRUE, FALSE) 
			FROM CONFERENZA JOIN PROGRAMMAGIORNALIERO ON CONFERENZA.Acronimo = PROGRAMMAGIORNALIERO.AcronimoConferenza AND CONFERENZA.AnnoEdizione = PROGRAMMAGIORNALIERO.AnnoEdizioneConferenza 
			WHERE (CONFERENZA.AnnoEdizione = AnnoEdizioneConf AND CONFERENZA.Acronimo  = AcronimoConf));
		IF isCompletata = TRUE THEN /* Conferenza finita */
			UPDATE CONFERENZA SET CampoSvolgimento = 'COMPLETATA' WHERE (AnnoEdizione = AnnoEdizioneConf AND Acronimo = AcronimoConf);
		END IF;
	END WHILE;
	CLOSE conferenze;
END;
|
DELIMITER ;

/* Stored procedure per la visualizzazione di messaggi nella chat */
DELIMITER |
CREATE PROCEDURE visualizzaMessaggi (IN CodiceSes VARCHAR(10)) 
BEGIN
	SELECT Testo, DataMessaggio, UsernameUtente 
		FROM MESSAGGIO JOIN CHAT ON MESSAGGIO.CodiceChat = CHAT.Codice JOIN SESSIONE ON CHAT.CodiceSessione = SESSIONE.Codice
        WHERE CHAT.CodiceSessione = CodiceSes;
END;
|
DELIMITER ;

/* Stored procedure per la visualizzazione le valutazioni sulle presentazioni di articoli */
DELIMITER |
CREATE PROCEDURE visualizzaValutazioniArticoli (IN CodicePresentazioneArticoli VARCHAR(10)) 
BEGIN
	SELECT Titolo, OraInizio, OraFine, Voto, Note
		FROM PRESENTAZIONEDIARTICOLI JOIN VALUTAZIONE ON PRESENTAZIONEDIARTICOLI.Codice = VALUTAZIONE.CodicePresentazioneDiArticoli 
        WHERE PRESENTAZIONEDIARTICOLI.Codice = CodicePresentazioneArticoli
        GROUP BY PRESENTAZIONEDIARTICOLI.Codice;
END;
|
DELIMITER ;

/* Stored procedure per la visualizzazione le valutazioni sulle presentazioni di tutorial */
DELIMITER |
CREATE PROCEDURE visualizzaValutazioniTutorial (IN CodicePresentazioneTutorial VARCHAR(10)) 
BEGIN
	SELECT Titolo, OraInizio, OraFine, Voto, Note
		FROM TUTORIAL JOIN VALUTAZIONE ON TUTORIAL.Codice = VALUTAZIONE.CodiceTutorial
        WHERE TUTORIAL.Codice = CodicePresentazioneTutorial
        GROUP BY TUTORIAL.Codice;
END;
|
DELIMITER ;
            