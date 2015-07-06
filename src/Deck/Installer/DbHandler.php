<?php

namespace Deck\Installer;

class DbHandler
{

    protected $connection;
    protected $pathResolver;
    protected $pdo;

    public function __construct($connection, $pathResolver)
    {

        $this->connection = $connection;
        $this->pathResolver = $pathResolver;
    }

    public function setDatabaseConnection()
    {

        $host = $this->connection['host'];
        $name = $this->connection['name'];
        $user = $this->connection['user'];
        $password = $this->connection['password'];

        $dsn = "mysql:host={$host};dbname={$name};charset=utf8";
        $opt = array(
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        $this->pdo = new PDO($dsn, $user, $password, $opt);
    }

    public function mysqlDumpShellOut($table)
    {
        $user = '';
        $password = '';
        $host = '';
        $db = '';
        $table = '';

        $cmd = "mysqldump -u{$user} -p{$password} -h{$host} {$db} {$table}";

        return shell_exec($cmd);
    }

    public function getBackUpFileName($package, $version)
    {
        $filename = $package->dir . '/' . $package->name . '.' . $version . '.' . '.bak.sql';
    }

    public function backupOldTables($package)
    {
        $string = '';

        foreach ($tables as $table) {
            $string .= $this->mysqlDumpShellOut($table);
        }

        $filename = $package->dir . '/' . $package->name . '.' . $package->version . '.' . '.bak.sql';
        file_put_contents($filename, $string);
    }


    public function createTables(Package $package)
    {
        $file = $this->pathResolver->getFullDbTablePath($package);

        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $this->queryDb($sql);
        }
    }

    public function populateTables(Package $package)
    {
        $file = $this->pathResolver->getFullDbValuesPath($package);

        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $this->queryDb($sql);
        }
    }

    protected function dropTables($package)
    {

        foreach ($package->tables as $table) {
            $this->queryDb("DELETE FROM " . $table);
        }
    }

    protected function queryDb($sql)
    {

        return $this->pdo->exec($sql);
    }
}
