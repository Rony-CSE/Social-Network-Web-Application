<?php 
class Database{
	private static function connect(){
        $dbhost = '127.0.0.1';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'db_social_network';
        // Create DSN
        $dsn = "mysql:host=$dbhost;dbname=$dbname";
        try{
            $pdo = new PDO($dsn, $dbuser, $dbpass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e){
            die('Failed to connect to database'.$e->getMessage());
        }
    }

    public static function query($query, $params = array()){
	    $statement=self::connect()->prepare($query);
	    $statement->execute($params);

	    if (explode(' ', $query)[0] == 'SELECT'){
            $data=$statement->fetchAll();
            return $data;
        }
    }
}
 ?>
