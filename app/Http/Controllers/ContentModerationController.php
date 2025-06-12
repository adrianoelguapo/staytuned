<?php

namespace App\Http\Controllers;

use App\Models\ContentModerationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentModerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar los logs de moderación de contenido
     */
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador (esto puede adaptarse según tu sistema de roles)
        if (!Auth::user()->email === 'admin@staytuned.com') { // Adaptar según tu lógica de admin
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $query = ContentModerationLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        // Estadísticas
        $stats = [
            'total_logs' => ContentModerationLog::count(),
            'logs_today' => ContentModerationLog::whereDate('created_at', today())->count(),
            'logs_this_week' => ContentModerationLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'most_common_words' => $this->getMostCommonOffensiveWords(),
            'most_moderated_users' => $this->getMostModeratedUsers()
        ];

        return view('admin.content-moderation.index', compact('logs', 'stats'));
    }

    /**
     * Mostrar detalles de un log específico
     */
    public function show(ContentModerationLog $log)
    {
        // Verificar permisos de administrador
        if (!Auth::user()->email === 'admin@staytuned.com') {
            abort(403);
        }

        $log->load('user');

        return view('admin.content-moderation.show', compact('log'));
    }

    /**
     * Obtener las palabras ofensivas más comunes
     */
    private function getMostCommonOffensiveWords()
    {
        $logs = ContentModerationLog::select('offensive_words')
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $wordCount = [];

        foreach ($logs as $log) {
            $words = $log->offensive_words ?? [];
            foreach ($words as $word) {
                $wordCount[$word] = ($wordCount[$word] ?? 0) + 1;
            }
        }

        arsort($wordCount);

        return array_slice($wordCount, 0, 10, true);
    }

    /**
     * Obtener los usuarios con más contenido moderado
     */
    private function getMostModeratedUsers()
    {
        return ContentModerationLog::select('user_id')
            ->selectRaw('COUNT(*) as moderation_count')
            ->with('user:id,username,email')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('moderation_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Exportar logs a CSV
     */
    public function export(Request $request)
    {
        // Verificar permisos de administrador
        if (!Auth::user()->email === 'admin@staytuned.com') {
            abort(403);
        }

        $query = ContentModerationLog::with('user');

        // Aplicar filtros si existen
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'content_moderation_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            
            // Cabeceras CSV
            fputcsv($file, [
                'ID', 'Fecha', 'Modelo', 'ID Modelo', 'Campo', 'Usuario', 
                'Contenido Original', 'Contenido Moderado', 'Palabras Ofensivas', 'IP'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    class_basename($log->model_type),
                    $log->model_id,
                    $log->field_name,
                    $log->user ? $log->user->username : 'Usuario eliminado',
                    $log->original_content,
                    $log->moderated_content,
                    implode(', ', $log->offensive_words ?? []),
                    $log->ip_address
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
