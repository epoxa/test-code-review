<?php

namespace Api\Controller; // Wrong namespace. Should be App instead of Api

use App\Model;
use App\Storage\DataStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController 
{
    /**
     * @var DataStorage
     */
    private $storage;

    public function __construct(DataStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Request $request
     *
     * @Route("/project/{id}", name="project", method="GET")
     */
    public function projectAction(Request $request) // Declare return type
    {
        // Use JsonResponse in all cases
        try {
            $project = $this->storage->getProjectById($request->get('id'));

            return new Response($project->toJson());
        } catch (Model\NotFoundException $e) {
            return new Response('Not found', 404);
        } catch (\Throwable $e) {
            return new Response('Something went wrong', 500);
        }
    }

    /**
     * @param Request $request
     *
     * @Route("/project/{id}/tasks", name="project-tasks", method="GET")
     */
    public function projectTaskPagerAction(Request $request) // Declare return type
    {
        $tasks = $this->storage->getTasksByProjectId(
            $request->get('id'),
            $request->get('limit'),
            $request->get('offset')
        );

        return new Response(json_encode($tasks)); // Use JsonResponse or at least add ext-json to composer.json.
    }

    /**
     * @param Request $request
     *
     * @Route("/project/{id}/tasks", name="project-create-task", method="PUT") // Not PUT but POST (see README.md)
     */
    public function projectCreateTaskAction(Request $request) // Add JsonResponse as return type
    {
	// All the body was indented with tabs (not spaces). PSR-2 violation
		$project = $this->storage->getProjectById($request->get('id'));
		if (!$project) { // It's impossible. Catch NotFoundException instead
			return new JsonResponse(['error' => 'Not found']); // Consider declare a ProjectNotFoundJsonResponse after all
		}

        // Catch a lot of Throwables after this
		return new JsonResponse( // Add ->jsonSerialize() after argument
			$this->storage->createTask($_REQUEST, $project->getId()) // No!!! Use $request not $_REQUEST
		); // Add status 201 as second parameter
    }
}
