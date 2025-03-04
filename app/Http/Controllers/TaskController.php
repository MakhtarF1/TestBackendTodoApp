<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Afficher toutes les tâches.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Tâches récupérées avec succès',
            'data' => $tasks
        ], Response::HTTP_OK);
    }

    /**
     * Enregistrer une nouvelle tâche.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $task = Task::create([
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tâche créée avec succès',
            'data' => $task
        ], Response::HTTP_CREATED);
    }

    /**
     * Afficher une tâche spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Tâche non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tâche récupérée avec succès',
            'data' => $task
        ], Response::HTTP_OK);
    }

    /**
     * Mettre à jour une tâche.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $task = Task::find($id);
        
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Tâche non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $task->update($request->only(['title', 'is_completed']));

        return response()->json([
            'status' => true,
            'message' => 'Tâche mise à jour avec succès',
            'data' => $task
        ], Response::HTTP_OK);
    }

    /**
     * Supprimer une tâche.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Tâche non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Tâche supprimée avec succès'
        ], Response::HTTP_OK);
    }
}