<?php


namespace App\Console\Commands;


use Edujugon\Laradoo\Exceptions\OdooException;
use Edujugon\Laradoo\Odoo;

class OdooConnectionModel
{
    /* @var string $user */
    private $user;
    /* @var string $password */
    private $password;
    /* @var string $database */
    private $database;
    /* @var string $host */
    private $host;
    /* @var Odoo $connection */
    private $connection;

    /**
     * Model constructor.
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $host
     */
    public function __construct(string $user, string $password, string $database, string $host)
    {
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database): void
    {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return Odoo
     * @throws OdooException
     */
    public function getConnection(): Odoo
    {
        if (!isset($this->connection)) {
            $this->connection = (new Odoo())
                ->username($this->user)
                ->password($this->password)
                ->db($this->database)
                ->host($this->host)
                ->connect();

        }
        return $this->connection;
    }
}
