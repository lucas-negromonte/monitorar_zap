<?php

namespace Source\Core;

/**
 * Connect Core
 * @package Source\Core
 */
class Connect
{
    /** @var array */
    private static $options = array(
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,

    );

    /** @var \PDO */
    private static $instance;

    /** @var \PDO */
    private static $instanceUtf8;

    /** @var \PDO */
    private static $instanceSystem;


    /**
     * @return \PDO
     */
    public static function getInstance($MYSQL_ATTR_INIT_COMMAND = "SET NAMES latin1")
    {
        // latin1 reconhece acentos de textos vindo do banco de dados
        self::$options[\PDO::MYSQL_ATTR_INIT_COMMAND] = $MYSQL_ATTR_INIT_COMMAND;

        if ((empty(self::$instance)) || $MYSQL_ATTR_INIT_COMMAND != "SET NAMES latin1") {
            try {
                self::$instance = new \PDO(
                    "mysql:host=" . CONF_DB_HOST . ";dbname=" . CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASS,
                    self::$options
                );
            } catch (\PDOException $e) {
                die("Erro ao conectar");
            }
        }
        return self::$instance;
    }


    /**
     * Usado para se conectar com outra conexão no banco tisoftware_system
     *
     * @return void
     */
    public static function getInstanceSystem()
    {
        if (empty(self::$instanceSystem)) {
            try {
                self::$instanceSystem = new \PDO(
                    "mysql:host=" . CONF_DB_HOST_SYSTEM . ";dbname=" . CONF_DB_NAME_SYSTEM,
                    CONF_DB_USER_SYSTEM,
                    CONF_DB_PASS_SYSTEM,
                    self::$options
                );
            } catch (\PDOException $e) {
                die("Erro ao conectar");
            }
        }
        return self::$instanceSystem;
    }
    /**
     * Connect constructor
     */
    final private function __construct()
    {
    }

    /**
     * Connect clone
     */
    final private function __clone()
    {
    }
}
