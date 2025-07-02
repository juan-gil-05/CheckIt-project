<?

namespace  App\Db;

use Exception;
use PDO;

class Mysql
{
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_port;
    private $db_host;
    private $pdo = null;
    private static $_instance = null;

    public function __construct()
    {
        // Config file with environment variables
        $config = require BASE_PATH . "/config.php";

        $this->db_name = $config['MYSQL_DATABASE'];
        $this->db_user = $config['MYSQL_USER'];
        $this->db_password = $config['MYSQL_PASSWORD'];
        $this->db_port = $config['MYSQL_PORT'];
        $this->db_host = $config['MYSQL_HOST'];
    }

    public static function getInstance(): self
    {
        try {
            if (is_null(self::$_instance)) {
                self::$_instance = new Mysql();
            }
            return self::$_instance;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getPdo(): PDO
    {
        try {

            $host = $this->db_host;
            $db_name = $this->db_name;
            $db_port = $this->db_port;

            if (is_null($this->pdo)) {
                $dsn = "mysql:host=$host;port=$db_port;dbname=$db_name;charset=utf8";
                return $this->pdo = new PDO($dsn, $this->db_user, $this->db_password);
            }
            return $this->pdo;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
