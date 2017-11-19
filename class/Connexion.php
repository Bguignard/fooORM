<?php

class Connexion
{

    /**
     * @var PDO
     */
    private static $pdo; //PDO object encapsulated
    /**
     * @var Connexion
     */
    private static $maConnexion = NULL; //Singleton flag
    /**
     * @var string
     */
    private static $req = ''; //Query to execute
    /**
     * @var PDOStatement
     */
    private static $jeu = NULL; //Set of data returned by query()

    //DB connexion informations
    /**
     * @var string
     */
    private $address = ''; //host address + db name
    /**
     * @var string
     */
    private $userName = ''; //user name
    /**
     * @var string
     */
    private $password = ''; //user password


    private function __construct($dbname, $username, $password, $address = 'localhost', $port = '3306')
    {
        $this->address = 'mysql:host=' . $address . ';port=' . $port . ';dbname=' . $dbname . '';
        $this->userName = $username;
        $this->password = $password;

        try {
            //connecting
            Connexion::$pdo = new PDO($this->address, $this->userName, $this->password);

            //UTF8 encoding
            Connexion::$req = "SET NAMES utf8";
            $this->xeq();
            Connexion::$req = "SET CHARACTER SET utf8";
            $this->xeq();

            //setting universal time of the server
            Connexion::$req = "SET time_zone='+0:00'";
            $this->xeq();
        }
        catch (Exception $e) {
            echo "Souci avec Mysql";
            if(Conf::$debug === true){
                echo $e->getMessage();
            }
            die();
        }
    }

    //destructor
    public function _destruct()
    {
        Connexion::$pdo = null;
    }

    //Return PDO object using constructor - checking Singleton flag
    public static function getPdo($dbname, $username, $password, $address = 'localhost', $port = '3306')
    {
        if (Connexion::$maConnexion == NULL) {
            Connexion::$maConnexion = new Connexion($dbname, $username, $password, $address, $port);
        }
        return Connexion::$maConnexion;
    }

    //Execute a INSERT, DELETE, UPDATE db request, returns the number of rows affected
    public function xeq()
    {
//        Connexion::safeChars();
        $nb = Connexion::$pdo->exec(Connexion::$req);
        if ($nb === false) {
            echo '<p>Erreur : requête incorrecte</p>';
            exit;
        }
        Connexion::$req = '';
        return $nb;
    }

    //Execute a SELECT db request, returns PDO object
    public function query()
    {
        Connexion::$jeu = Connexion::$pdo->query(Connexion::$req);
        if (Connexion::$jeu === false) {
            echo "<p>Erreur : requête incorrecte</p>";
            exit;
        }
        Connexion::$req = '';
        return $this;
    }

    //Combinated with query, returns an array of rows returned by the SELECT request ex : (array(Class))fooArray = (Connexion)$connexion->query()->tab(Class);
    public function tableTab()
    {
        return Connexion::$jeu->fetchAll(PDO::FETCH_COLUMN);
    }
    //Combinated with query, returns an array of rows returned by the SELECT request ex : (array(Class))fooArray = (Connexion)$connexion->query()->tab(Class);
    public function tab($class = 'stdClass')
    {
        Connexion::$jeu->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        return Connexion::$jeu->fetchAll();
    }

    //Combinated with query, returns the first row returned by the SELECT request ex : (Class)class = (Connexion)$connexion->query()->first(Class);
    public function first($class = 'stdClass')
    {
        Connexion::$jeu->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        return Connexion::$jeu->fetch();
    }

    //Returns true if SELECT query returns something, false otherwise : ex : (Bool)test = (Connexion)$connexion->query()->ins();
    public function ins($instance = null)
    {
        Connexion::$jeu->setFetchMode(PDO::FETCH_INTO, $instance);
        return (bool)(Connexion::$jeu->fetch());
    }

    //Returns the "protected" string to avoid SQL injections
    public function safeChars()
    {
        $se = ['\''];
        $re = ['\'\''];
        Connexion::$req = str_replace($se, $re, Connexion::$req);
    }

    /**
     * @return Connexion
     */
    public static function getMaConnexion(): Connexion
    {
        return self::$maConnexion;
    }

    /**
     * @param Connexion $maConnexion
     */
    public static function setMaConnexion(Connexion $maConnexion)
    {
        self::$maConnexion = $maConnexion;
    }

    /**
     * @return string
     */
    public static function getReq(): string
    {
        return self::$req;
    }

    /**
     * @param string $req
     */
    public static function setReq(string $req)
    {
        self::$req = $req;
    }

    /**
     * @return PDOStatement
     */
    public static function getJeu(): PDOStatement
    {
        return self::$jeu;
    }

    /**
     * @param PDOStatement $jeu
     */
    public static function setJeu(PDOStatement $jeu)
    {
        self::$jeu = $jeu;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }



}
?>
