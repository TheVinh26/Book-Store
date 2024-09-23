<?php 
    class Connection{

        function CheckConnect(){
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=bookdb","root","");
                return $pdo;
            } catch (PDOException $ex) {
                echo "Failed to connect to MySQL: " . $ex->getMessage();
                die();
            }
        }
    }
?>