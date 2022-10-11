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




    }



    
?>

