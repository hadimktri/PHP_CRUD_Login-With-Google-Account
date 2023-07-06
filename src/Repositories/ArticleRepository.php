<?php

namespace src\Repositories;

require_once 'Repository.php';
require_once __DIR__ . '/../Models/Article.php';

use src\Models\Article;
use src\Models\User;

date_default_timezone_set('America/Vancouver');
class ArticleRepository extends Repository
{
	/**
	 * @return Article[]
	 */
	public function getAllArticles(): array
	{
		$sqlStatement = $this->mysqlConnection->prepare("SELECT * FROM articles ORDER BY created_at DESC;");
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();

		$articles = [];
		while ($row = $resultSet->fetch_assoc()) {
			$articles[] = new Article($row);
		}
		return $articles;
	}

	/**
	 * @param int $id
	 * @return Article|false 
	 */
	public function getArticle(int $id): Article|false
	{
		$sqlStatement = $this->mysqlConnection->prepare('SELECT id, title, url, created_at, updated_at, author_id FROM articles WHERE id = ?');
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();
		return $resultSet === false ? false : new Article($resultSet->fetch_assoc());
	}

	/**
	 * @param string $title
	 * @param string $url
	 * @param int $authorId
	 * @return Article|false 
	 */
	public function saveArticle(string $title, string $url, int $authorId): Article|false
	{
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$createdAt = date('Y-m-d H:i:s');
		$sqlStatement = $this->mysqlConnection->prepare("INSERT INTO articles (id, author_id, title, url, created_at, updated_at) VALUES(NULL, ?, ?, ?, ?, NULL);");
		$sqlStatement->bind_param('isss', $authorId, $title, $url, $createdAt);
		$saved = $sqlStatement->execute();
		$articleId = $this->mysqlConnection->insert_id;
		if ($saved) {
			return $this->getArticle($articleId);
		} else {
			return false;
		}
	}

	/**
	 * @param int $id
	 * @param string $title
	 * @param string $url
	 * @return bool
	 */
	public function updateArticle(int $id, string $title, string $url): bool
	{
		if ($this->mysqlConnection->connect_error) {
			die("Connection failed: " . $this->mysqlConnection->connect_error);
		}
		$updated_at = date('Y-m-d H:i:s');
		$sqlStatement = $this->mysqlConnection->prepare("UPDATE articles SET title=?, url=?,updated_at=? WHERE id=?;");
		$sqlStatement->bind_param("sssi", $title, $url, $updated_at, $id);
		$sqlStatement->execute();
		return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteArticle(int $id): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare('DELETE FROM articles WHERE id = ?');
		$sqlStatement->bind_param('i', $id);
		$sqlStatement->execute();
		return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteArticleByAuthor(int $authorId): bool
	{
		$sqlStatement = $this->mysqlConnection->prepare('DELETE FROM articles WHERE author_id = ?');
		$sqlStatement->bind_param('i', $authorId);
		$sqlStatement->execute();
		return false;
	}

	/**
	 *
	 * @param int $articleId
	 * @return User|false
	 */
	public function getArticleAuthor(int $articleId): User|false
	{
		$sqlStatement = $this->mysqlConnection->prepare(
			"SELECT users.id, users.name, users.email, users.password_digest, users.profile_picture FROM users INNER JOIN articles a ON users.id = a.author_id WHERE a.id = ?;"
		);
		$sqlStatement->bind_param('i', $articleId);
		$success = $sqlStatement->execute();
		if ($success) {
			$resultSet = $sqlStatement->get_result();
			if ($resultSet->num_rows === 1) {
				return new User($resultSet->fetch_assoc());
			}
		}
		return false;
	}

	/**
	 * @param int $userId
	 * @return User|false
	 */
	public function getAllEditArticles($userId): array
	{
		$sqlStatement = $this->mysqlConnection->prepare(
			"SELECT articles.id, articles.title, articles.url, articles.author_id FROM articles INNER JOIN users ON articles.author_id = users.id WHERE articles.author_id = ? ORDER BY created_at DESC;"
		);
		$sqlStatement->bind_param('i', $userId);
		$sqlStatement->execute();
		$resultSet = $sqlStatement->get_result();

		$articles = [];
		while ($row = $resultSet->fetch_assoc()) {
			$articles[] = new Article($row);
		}
		return $articles;
	}
}
