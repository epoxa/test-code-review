<?php

namespace App\Storage;

// I personally prefer to import all used names from all namespaces including global (like \PDO)
// Add ext-pdo to composer.json

use App\Model;

class DataStorage
{
    /**
     * @var \PDO 
     */
    public $pdo; // Oh, no! Not public please

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user'); // Hardcoded connection string sucks
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId) // Use type (int) declaration here instead of PHPDoc block
    {
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId); // And then no typecasting need here
        // But actually projectId have to be passed as integer param to PDO

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row); // Declare return type in function signature ^
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id // I personally avoid duplicating declared arguments types with PHPDoc
     * @param int $limit
     * @param int $offset
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset) // $limit and $offset should be declared as nullable ?int individually. It's not Pascal
        // Also add default $limit=10 and $offset=0 values (and I prefer to set default values in the controller)
    {
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?"); // Positional parameters are less safe than named
        $stmt->execute([$limit, $offset]);

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) { // Will fetch both named and positional fields variants. Use FETCH_ASSOC
            $tasks[] = new Model\Task($row); // So data for model creation will be invalid (exhausted)
        }
        // Will return unwanted columns like *created_at* and *status*
        return $tasks; // Declare function return type as array
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    public function createTask(array $data, $projectId) // int $projectId
    // But better change signature (and corresponding logic) to this:
    // public function createTask(string $taskTitle, Project $project): Task
    {
        $data['project_id'] = $projectId;

        $fields = implode(',', array_keys($data)); // No data validation. The fields must be listed explicitly
        $values = implode(',', array_map(function ($v) { // $v - isn't really semantic name. Even $val is better
            return is_string($v) ? '"' . $v . '"' : $v; // At least add str_replace('"','""',$v) if you hate using PDO parameters so much
        }, $data));

        // Next will produce NOT NULL violation for *status* column
        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)"); // Hello SQL-injection!
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn(); // No, no, no! use PDO::lastInsertId()

        return new Model\Task($data);
    }
}
