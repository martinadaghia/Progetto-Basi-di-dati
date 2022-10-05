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
    }



    
?>

