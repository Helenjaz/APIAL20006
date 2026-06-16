<?php
class Db {
    // Configuración local (luego cambiarás esto con los datos de Filess.io)
    private $dbHost = '9ir14c.h.filess.io';
    private $dbUser = 'control_salud_seriousboy';
    private $dbPass = 'de5287c64064022060ac3f89fba339e96d8560b5';
    private $dbName = 'control_salud_seriousboy';
    private $dbPort = '61032';

    public function connect() {
        // Agregamos port=$this->dbPort a la cadena de conexión:
        $mysqlConnect = "mysql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName;charset=utf8";
        $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
