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

        public function insertSpeaker($username){
            $query="call registrazioneAmministratore(?)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('s', $username); 
            $stmt->execute();
        }

        public function insertPresenter($username){
            $query="call registrazioneAmministratore(?)";
            $stmt=$this->db->prepare($query); 
            $stmt->bind_param('s', $username); 
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
        


    }



    
?>

