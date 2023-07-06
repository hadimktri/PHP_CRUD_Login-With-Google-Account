<?php

namespace src\Repositories;

require_once __DIR__ . '/../../vendor/autoload.php';

use mysqli;
use Dotenv\Dotenv as Dotenv;
// use src\Models\Model;

/**
 * An example of a base class to reduce database connectivity configuration for each repository subclass.
 */
class Repository
{

	protected mysqli $mysqlConnection;

	private string $hostname;
	private string $username;
	private string $databaseName;
	private string $databasePassword;

	public function __construct()
	{
		$dotenv = Dotenv::createImmutable(__DIR__ . '/../../vendor');
		$dotenv->load();

		$this->hostname = $_ENV['HOSTNAME'];
		$this->username = $_ENV['DB_USER'];
		$this->databaseName = $_ENV['DB_NAME'];
		$this->databasePassword = $_ENV['DB_pass'];
		$this->mysqlConnection = new mysqli($this->hostname, $this->username, $this->databasePassword, $this->databaseName);
		if ($this->mysqlConnection->connect_error) {
			die('Connection failed: ' . $this->mysqlConnection->connect_error);
		}
	}
	public function getMySqlConnection():object
	{
		return $this->mysqlConnection;
	}
}
