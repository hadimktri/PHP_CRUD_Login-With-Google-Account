<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Article.php';

use src\Models\User;

date_default_timezone_set('America/Vancouver');
class UserRepository extends Repository
{
	/**
	 * @param string $id
	 * @return User|false
	 */
	public function getUserById(string $id): User|false
	{
		$sqlStatement = $this->mysqlConnection->prepare("SELECT id, google_id, password_digest, email, name, profile_picture FROM users WHERE id = ?");
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();
		return $resultSet->num_rows >= 1 ? new User($resultSet->fetch_assoc()) : false;
	}

	/**
	 * @param string $email
	 * @return User|false
	 */
	public function getUserByEmail(string $email): User|false
	{
		$sqlStatement = $this->mysqlConnection->prepare('SELECT id, google_id, password_digest, email, name, profile_picture FROM users WHERE email = ?');
		$sqlStatement->bind_param('s', $email);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();
		return $resultSet->num_rows >= 1 ? new User($resultSet->fetch_assoc()) : false;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $bcryptPasswordDigest
	 * @param string $filePath
	 * @return User|false
	 */
	public function saveUser(string $email, string $name, string $bcryptPasswordDigest, string $filePath): User|false
	{
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$sqlStatement = $this->mysqlConnection->prepare("INSERT INTO users (email, name, password_digest, profile_picture ) VALUES (?, ?, ?,?);");
		$sqlStatement->bind_param("ssss", $email, $name, $bcryptPasswordDigest, $filePath);
		$saved = $sqlStatement->execute();

		if ($saved) {
			$id = $this->mysqlConnection->insert_id;
			$sqlStatement = "SELECT * FROM users where id = $id";
			$result = $this->mysqlConnection->query($sqlStatement);
			return new User($result->fetch_assoc());
		}
		return false;
	}

	/**
	 * @param int $id
	 * @param string $name
	 * @param string $email
	 * @return bool
	 */
	public function updateUser(string $id, string $name, string $email): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE users SET name=?, email=? WHERE id=? ;");
		$sqlStatement->bind_param("ssi", $name, $email, $id);
		$sqlStatement->execute();
		return false;
	}

	/**
	 * @param int $id
	 * @param string $filePath
	 * @return bool
	 */
	function updateProfilePic(string $id, string $filePath): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE users SET  profile_picture=?  WHERE id=? ;");
		$sqlStatement->bind_param("si", $filePath, $id);
		$sqlStatement->execute();
		return false;
	}
	/**
	 * @param int $id
	 * @param string $bcryptPasswordDigest
	 * @return bool
	 */
	function updatePassword(string $id, string $bcryptPasswordDigest): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE users SET  password_digest=?  WHERE id=?;");
		$sqlStatement->bind_param("si", $bcryptPasswordDigest, $id);
		$sqlStatement->execute();
		return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteUserById(int $id): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function getUserByGoogleId(string $id): User|false
	{
		$sqlStatement = $this->mysqlConnection->prepare("SELECT id, google_id, password_digest, email, name, profile_picture FROM users WHERE google_id = ?");
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();
		return $resultSet->num_rows >= 1 ? new User($resultSet->fetch_assoc()) : false;
	}

	/**
	 * @param string $googleId
	 * @param string $name
	 * @param string $email
	 * @param string $profilePic
	 * @return User|false
	 */

	public function saveGoogleUser(string $googleId, string $name, string $email, string $profilePic): User|false
	{
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$sqlStatement = $this->mysqlConnection->prepare("INSERT INTO users (google_id, name, email, profile_picture ) VALUES (?, ?, ?,?);");
		$sqlStatement->bind_param("ssss", $googleId, $name, $email, $profilePic);
		$saved = $sqlStatement->execute();

		if ($saved) {
			$id = $this->mysqlConnection->insert_id;
			$sqlStatement = "SELECT * FROM users where id = $id";
			$result = $this->mysqlConnection->query($sqlStatement);
			return new User($result->fetch_assoc());
		}
		return false;
	}

	/**
	 * @param string $id
	 * @param string $profilePic
	 * @return bool
	 */
	function updateGoogleId(string $id, string $googleId, string $profilePic): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE users SET  google_id=?,profile_picture=?  WHERE id=? ;");
		$sqlStatement->bind_param("sss", $googleId, $profilePic, $id);
		$sqlStatement->execute();
		return false;
	}
}
