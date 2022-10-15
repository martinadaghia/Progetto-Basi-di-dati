<?php

    class DatabaseHelper{
        
        private $db;

        public function __construct($servername, $username, $password, $dbname, $port){
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
            if ($this->db->connect_error) {
                die("Connection failed: " . $this->db->connect_error);
            }        
        }
        

        public function getUtente($username){
            $query="SELECT * FROM UTENTE WHERE Username=?";
            // prende la connessione nella variabile db, esegue la funzione -> prepare che serve per far preparare la query
            $stmt=$this->db->prepare($query); 
            // diciamo che c'è un param da passare alle query (nel ?) (con s dico che è una stringa)
            $stmt->bind_param('s', $username); 
            // eseguo la query
            $stmt->execute();
            // prendo ciò che ritorna la query (non sai se c'è un error o meno)
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




        public function caricaFoto($nomeFile, $datiFoto){
            $query="call inserimentoFoto(?,?,@a)";
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




    }



    
?>

