<?php

    class DatabaseHelper{
        
        private $db;

        public function __construct($servername, $username, $password, $dbname, $port){
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
            if ($this->db->connect_error) {
                die("Connection failed: " . $this->db->connect_error);
            }        
        }


        public function getUtente($username, $password){
            $query="call autenticazioneUtente(?, ?, @tipo, @ok)";
            // prende la connessione nella variabile db, esegue la funzione -> prepare che serve per far preparare la query
            $stmt=$this->db->prepare($query); 
            // diciamo che c'è un param da passare alle query (nel ?) (con s dico che è una stringa)
            $stmt->bind_param('ss', $username, $password); 
            // eseguo la query
            $stmt->execute();
            // prendo ciò che ritorna la query (non sai se c'è un error o meno)

            $query="SELECT @ok, @tipo";
            $stmt=$this->db->prepare($query);  
            $stmt->execute();
            $result=$stmt->get_result();
            // fetch_all prende tutte le righe dal risultato della query
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        

        public function insertUser($username, $password, $nome, $cognome, $data, $luogo){
            $query="call registrazioneUtente(?,?,?,?,?,?,@a)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ssssss', $username, $password, $nome, $cognome, $data, $luogo); 
            $stmt->execute();
        }

        public function insertAmministratore($username){
            $query="call registrazioneAmministratore(?)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('s', $username); 
            $stmt->execute();
        }

        public function insertSpeaker($username, $nome, $cv, $foto, $dipartimento){
            $query="call registrazioneSpeaker(?,?,?,?,?,@a)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sssss', $username, $nome, $cv, $foto, $dipartimento); 
            $stmt->execute();
        }

        public function insertPresenter($username, $nome, $cv, $foto, $dipartimento){
            $query="call registrazionePresenter(?,?,?,?,?,@a)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sssss', $username, $nome, $cv, $foto, $dipartimento); 
            $stmt->execute();
        }


        public function checkTipoUser($username, $password){
            $query="call autenticazioneUtente(?, ?, @tipo, @a)"; // chiama procedure per controllare che tipo di utente è loggato
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sssss', $username, $nome, $cv, $foto, $dipartimento); 
            $stmt->execute();

            


            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }


        public function caricaFoto($nomeFile, $datiFoto){
            $query="call inserimentoFoto(?,?,@a)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $nomeFile, $datiFoto);
            $stmt->execute();
        }

        public function caricaFileEsterni($nomeFile, $datiFoto){
            $query="call inserimentoFileEsterni(?,?,@a)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $nomeFile, $datiFoto);
            $stmt->execute();
        }

        

        public function getUltimaFoto(){
            $query="SELECT idLogo FROM LOGO ORDER BY idLogo desc limit 1"; // ritorna l'ultimo inserito
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }


        public function getUltimoFile(){
            $query="SELECT idFile FROM fileesterni ORDER BY idFile desc limit 1"; // ritorna l'ultimo inserito
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }



        // HOMEPAGE
        // totale registrate 
        public function getTotaleConferenze(){
            $query="SELECT count(Acronimo) as totale FROM CONFERENZA"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // totale attive 
        public function getTotaleConfAttive(){
            $query="SELECT count(Acronimo) as totale FROM CONFERENZA WHERE CampoSvolgimento='ATTIVA'"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // totale utenti registrati 
        public function getTotaleUtenti(){
            $query="SELECT count(Username) as totale FROM UTENTE "; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // classifica presenter 
        public function getClassificaPresenter(){
            $query="SELECT U.Username, AVG(V.Voto) as Voto, 'Presenter' as tipo FROM UTENTE as U JOIN PRESENTER as P ON P.UsernameUtente=U.Username 
                    JOIN PRESENTAZIONEDIARTICOLI as PA ON PA.UsernameUtentePresenter=P.UsernameUtente 
                    JOIN VALUTAZIONE as V ON V.CodicePresentazioneDiArticoli=PA.Codice 
                    GROUP BY U.Username 
                    ORDER BY Voto desc"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // classifica speaker 
        public function getClassificaSpeaker(){
            $query="SELECT U.Username, AVG(V.Voto) as Voto, 'Speaker' as tipo FROM UTENTE as U JOIN SPEAKER as S ON S.UsernameUtente=U.Username 
                    JOIN PRESENTAZIONE as PR ON PR.UsernameUtenteSpeaker=S.UsernameUtente JOIN TUTORIAL as T ON T.Codice=PR.CodiceTutorial 
                    JOIN VALUTAZIONE as V ON V.CodiceTutorial=T.Codice 
                    GROUP BY U.Username 
                    ORDER BY Voto desc"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }


        // conferenze disponibili
        public function getConferenzeDisponibili(){
            $query="SELECT Acronimo, Nome, AnnoEdizione FROM CONFERENZA WHERE CampoSvolgimento='ATTIVA'"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // verifica registrazione ad una conferenza
        public function getVerificaRegistrazioneConf(){
            $query="SELECT C2.Acronimo, C2.Nome, C2.AnnoEdizione 
			        FROM CONFERENZA as C2 
                    LEFT JOIN (SELECT R.AcronimoConferenza, R.AnnoEdizioneConferenza FROM registrazione as R WHERE R.UsernameUtente='". $_SESSION["username"] ."') as T 
                    ON ((T.AcronimoConferenza=C2.Acronimo) AND (T.AnnoEdizioneConferenza=C2.AnnoEdizione)) 
                    WHERE T.AcronimoConferenza IS NULL AND T.AnnoEdizioneConferenza IS NULL 
                    AND C2.CampoSvolgimento='attiva' GROUP BY Acronimo"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // effettua registrazione ad una conferenza
        public function getEffettuaRegistrazioneConf($acrConferenza, $annoEdizioneConferenza, $username){
            $query="call registrazioneConferenza(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sis', $acrConferenza, $annoEdizioneConferenza, $username);
            $stmt->execute();
        }

        // get numero di sponsorrizazioni per home
        public function getSponsorizzazioniHome(){
            $query="SELECT * FROM numerosponsorizzazioni"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }








        // sessioni 
        public function getSessioni(){
            $query="SELECT Codice, Titolo, Link FROM SESSIONE"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // articoli
        public function getArticoli($codiceSessione){
            $query="SELECT PA.Codice, PA.OraInizio, PA.OraFine, PA.Titolo, UsernameUtentePresenter 
                    FROM PRESENTAZIONEDIARTICOLI as PA JOIN SESSIONE as S ON PA.CodiceSessione=S.Codice WHERE PA.CodiceSessione=?"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('i', $codiceSessione);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // tutorial
        public function getTutorial($codiceSessione){
            $query="SELECT T.Codice, T.OraInizio, T.OraFine, T.Titolo FROM TUTORIAL as T 
                    JOIN SESSIONE as S ON T.CodiceSessione=S.Codice WHERE T.CodiceSessione=?"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('i', $codiceSessione);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }





        // prendo messaggi in una chat di sessione 
        public function getMessaggi($codiceSessione){
            $query="SELECT m.UsernameUtente, m.Testo, m.DataMessaggio FROM messaggio as m 
                    JOIN chat as c ON m.CodiceChat=c.Codice JOIN sessione as s ON c.CodiceSessione=s.Codice WHERE s.Codice=?"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('i', $codiceSessione);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }




        // ritorna codice chat
        public function getCodiceChat($codiceSessione){
            $query="SELECT c.Codice FROM chat as c JOIN sessione as s ON c.CodiceSessione=s.Codice WHERE s.Codice=?"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('i', $codiceSessione);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }


        // inserimento messaggio
        public function inserisciMessaggio($testo, $codiceChat, $username){
            $query="call inserimentoMessaggi(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sss', $testo, $codiceChat, $username);
            $stmt->execute();
        }


        // inserimento conferenza
        public function inserisciConferenza($acronimo, $annoEdizione, $nome, $idLogo, $totSponsor, $username, $giornoProgramma){
            $query="call creazioneConferenza(?,?,?,?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sisiiss', $acronimo, $annoEdizione, $nome, $idLogo, $totSponsor, $username, $giornoProgramma);
            $stmt->execute();
        }

        // ritorna tutti i programmi giornalieri
        public function getProgrammiGiornalieri(){
            $query="SELECT p.Codice, c.Nome, c.Acronimo, c.AnnoEdizione, p.DataConferenza 
            FROM programmagiornaliero AS p 
            JOIN conferenza AS c 
            ON c.Acronimo=p.AcronimoConferenza and c.AnnoEdizione=p.AnnoEdizioneConferenza"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // inserimento sessione
        public function inserisciSessione($titoloSessione, $numPresentazioni, $oraInizioSessione, $oraFineSessione, $linkSessione, $programmiSessione){
            $query="call creazioneSessione(?,?,?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sissss', $titoloSessione, $numPresentazioni, $oraInizioSessione, $oraFineSessione, $linkSessione, $programmiSessione);
            $stmt->execute();
        }

        // inserimento sponsor
        public function inserisciSponsor($titoloSponsor, $idLogo, $importoSponsor){
            $query="call inserimentoSponsor(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sii', $titoloSponsor, $idLogo, $importoSponsor);
            $stmt->execute();
        }

        // inserimento sponsor
        public function getNomeSponsor(){
            $query="SELECT Nome FROM sponsor"; 
            $stmt=$this->db->prepare($query); 
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // inserimento sponsorizzazione
        public function inserisciSponsorizzazione($titolo, $anno, $nomeSponsor){
            $query="call sponsorizzazione(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sis', $titolo, $anno, $nomeSponsor);
            $stmt->execute();
        }

        // modifica foto presenter
        public function modificaFotoPresenter($username, $datiFoto){
            $query="call modificaFotoPresenter(?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $username, $datiFoto);
            $stmt->execute();
        }

        // modifica cv presenter
        public function modificaCVPresenter($username, $cv){
            $query="call modificaCVPresenter(?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $username, $cv);
            $stmt->execute();
        }

        // modifica Universita presenter
        public function modificaUniPresenter($username, $nome, $dipa){
            $query="call modificaUniversitaPresenter(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sss', $username, $nome, $dipa);
            $stmt->execute();
        }





        // modifica foto speaker
        public function modificaFotoSpeaker($username, $datiFoto){
            $query="call modificaFotoSpeaker(?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $username, $datiFoto);
            $stmt->execute();
        }

        // modifica cv speaker
        public function modificaCVspeaker($username, $cv){
            $query="call modificaCVSpeaker(?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $username, $cv);
            $stmt->execute();
        }

        // modifica Universita speaker
        public function modificaUniSpeaker($username, $nome, $dipa){
            $query="call modificaUniversitaSpeaker(?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('sss', $username, $nome, $dipa);
            $stmt->execute();
        }

        // get speaker codici
        public function getSpeakerCodici($username){
            $query="SELECT p.CodiceTutorial 
            FROM `presentazione` AS p 
            JOIN risorsaaggiuntiva AS r 
            ON r.CodiceTutorial=p.CodiceTutorial 
            WHERE p.UsernameUtenteSpeaker=?"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }
        
        // inserisci risorsa speaker
        public function inserimentoRisorsaSpeaker($linkWeb, $desc, $codeTutorial, $username){
            $query="call inserimentoRisorsa(?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ssss', $linkWeb, $desc, $codeTutorial, $username);
            $stmt->execute();
        }

        // modifica risorsa speaker
        public function modificaRisorsaSpeaker($linkWeb, $desc, $codeTutorial, $username){
            $query="call modificaRisorsa(?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ssss', $linkWeb, $desc, $codeTutorial, $username);
            $stmt->execute();
        }




        // associa tutorial con speaker
        public function associaPresentazioneSpeaker($codiceTutorial, $username){
            $query="call presentazioneSpeaker(?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $codiceTutorial, $username);
            $stmt->execute();
        }

        // associa presenter con artiocli
        public function associaPresentazionePresenter($codiceArticoli, $username){
        $query="UPDATE presentazionediarticoli
            SET UsernameUtentePresenter = ?
            WHERE Codice=?"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ss', $username, $codiceArticoli);
            $stmt->execute();
        }
        
        
        // get speaker
        public function getSpeaker(){
            $query="SELECT UsernameUtente FROM speaker"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get presenters
        public function getPresenters(){
            $query="SELECT UsernameUtente FROM presenter"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get tutorials senza speaker
        public function getTutorialsWSpeakers(){
            $query="SELECT t.Codice 
            FROM tutorial AS t 
            LEFT JOIN presentazione AS p 
            ON p.CodiceTutorial=t.Codice 
            WHERE p.CodiceTutorial IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }
        
        // get articoli senza speaker
        public function getArticolisWPresenter(){
            $query="SELECT p.Codice 
            FROM presentazionediarticoli AS p 
            WHERE p.UsernameUtentePresenter IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }



        // inserisci tutorial
        public function inserisciTutorial($oraInizo, $oraFine, $numSeq, $titolo, $abstract, $codSessione){
            $query="call creazioneTutorial(?,?,?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('ssissi', $oraInizo, $oraFine, $numSeq, $titolo, $abstract, $codSessione);
            $stmt->execute();
        }

        // inserisci articoli
        public function inserisciArticoli($oraInizo, $oraFine, $numSeq, $titolo, $numPagine, $idPresent, $codSessione){
            $query="call creazioneArticoli(?,?,?,?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('ssisiii', $oraInizo, $oraFine, $numSeq, $titolo, $numPagine, $idPresent, $codSessione);
            $stmt->execute();
        }


        // get tutti gli articoli esistenti
        public function getArticoliWVote($username){
        $query="SELECT p.Codice 
                FROM presentazionediarticoli AS p 
                LEFT JOIN (SELECT v.CodicePresentazioneDiArticoli 
                            FROM valutazione AS v 
                            WHERE v.UsernameUtenteAmministratore=?) AS t 
                ON t.CodicePresentazioneDiArticoli=p.Codice 
                WHERE t.CodicePresentazioneDiArticoli IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get tutti gli articoli esistenti
        public function getTutorialWVote($username){
        $query="SELECT p.Codice 
                FROM tutorial AS p 
                LEFT JOIN (SELECT v.CodiceTutorial 
                            FROM valutazione AS v 
                            WHERE v.UsernameUtenteAmministratore=?) AS t 
                ON t.CodiceTutorial=p.Codice 
                WHERE t.CodiceTutorial IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }


        // inserisci valutazione Articolo
        public function inserisciValutazioneArticoli($voto, $username, $note, $codice){
            $query="call inserimentoValutazioneArticolo(?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('isss', $voto, $username, $note, $codice);
            $stmt->execute();
        }


        // inserisci valutazione Tutorial
        public function inserisciValutazioneTutorial($voto, $username, $note, $codice){
            $query="call inserimentoValutazioneTutorial(?,?,?,?,@a)"; 
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('isss', $voto, $username, $note, $codice);
            $stmt->execute();
        }

        // get valutazione di articolo
        public function getValutazioniArticolo($codice){
            $query="call visualizzaValutazioniArticoli(?)"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('i', $codice);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get valutazione di tutorials
        public function getValutazioniTutorial($codice){
            $query="call visualizzaValutazioniTutorial(?)"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $codice);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get articoli
        public function getTuttiArticoli(){
            $query="SELECT Codice FROM presentazionediarticoli"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get tutorial
        public function getTuttiTutorial(){
            $query="SELECT Codice FROM tutorial"; 
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get not favourite Articoli
        public function getNotArticoliFavoriti($username){
            $query="SELECT p.Titolo 
                    FROM presentazionediarticoli AS p 
                    LEFT JOIN (Select TitoloPresentazione 
                                FROM presentazionefavorita 
                                WHERE UsernameUtente=?) AS t ON t.TitoloPresentazione=p.Titolo 
                    WHERE t.TitoloPresentazione IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get not favourite Tutorial
        public function getNotTutorialFavoriti($username){
            $query="SELECT p.Titolo 
                    FROM tutorial AS p 
                    LEFT JOIN (Select TitoloPresentazione 
                                FROM presentazionefavorita 
                                WHERE UsernameUtente=?) AS t ON t.TitoloPresentazione=p.Titolo 
                    WHERE t.TitoloPresentazione IS NULL"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get favourite Artiocli
        public function getArticoliTutorialFavoriti($username){
            $query="Select TitoloPresentazione FROM presentazionefavorita WHERE UsernameUtente=?"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        // get inserisci Favourite Presentazioni
        public function inserisciPresentazioneFavorita($username, $titolo){
            $query="INSERT INTO presentazionefavorita (TitoloPresentazione, UsernameUtente)
            VALUES (?, ?);"; 
            $stmt=$this->db->prepare($query);
            $stmt->bind_param('ss', $titolo, $username);
            $stmt->execute();
        }



    }
?>

