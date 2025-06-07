<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        try {
            $comment = Comment::create([
                'text' => $request->text,
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'likes' => 0,
            ]);

            // Cargar la relación del usuario para la respuesta
            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Comentario agregado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creando comentario: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'text' => $request->text
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al agregar el comentario. Por favor, intenta más tarde.'
            ], 500);
        }
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Verificar que el usuario sea el propietario del comentario
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'error' => 'No tienes permisos para editar este comentario'
            ], 403);
        }

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        try {
            $comment->update([
                'text' => $request->text,
            ]);

            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Comentario actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error actualizando comentario: ' . $e->getMessage(), [
                'comment_id' => $comment->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar el comentario'
            ], 500);
        }
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Verificar que el usuario sea el propietario del comentario
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'error' => 'No tienes permisos para eliminar este comentario'
            ], 403);
        }

        try {
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comentario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error eliminando comentario: ' . $e->getMessage(), [
                'comment_id' => $comment->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar el comentario'
            ], 500);
        }
    }
}
