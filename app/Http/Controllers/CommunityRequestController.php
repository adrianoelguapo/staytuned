<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityRequestController extends Controller
{
    /**
     * Solicitar unirse a una comunidad privada
     */
    public function store(Request $request, Community $community)
    {
        $user = Auth::user();

        // Verificar que la comunidad es privada
        if (!$community->is_private) {
            return redirect()->back()->with('error', 'Esta comunidad es pública, puedes unirte directamente.');
        }

        // Verificar que el usuario puede hacer la solicitud
        if (!$community->canUserJoin($user)) {
            return redirect()->back()->with('error', 'No puedes solicitar unirte a esta comunidad.');
        }

        $request->validate([
            'message' => 'nullable|string|max:500'
        ]);

        // Buscar si ya existe una solicitud previa
        $existingRequest = CommunityRequest::where('user_id', $user->id)
            ->where('community_id', $community->id)
            ->first();

        if ($existingRequest) {
            // Si la solicitud existe y está pendiente, no hacer nada
            if ($existingRequest->status === 'pending') {
                return redirect()->back()->with('info', 'Ya tienes una solicitud pendiente para esta comunidad.');
            }
            
            // Si fue rechazada o aprobada, crear nueva solicitud como "reenvío"
            if ($existingRequest->status === 'rejected' || $existingRequest->status === 'approved') {
                $existingRequest->update([
                    'message' => $request->message,
                    'status' => 'pending',
                    'admin_message' => null,
                    'responded_at' => null,
                    'updated_at' => now()
                ]);
                
                return redirect()->back()->with('success', 'Solicitud reenviada correctamente. El administrador de la comunidad revisará tu nueva solicitud.');
            }
        }

        // Crear nueva solicitud si no existe ninguna previa
        CommunityRequest::create([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Solicitud enviada correctamente. El administrador de la comunidad revisará tu solicitud.');
    }

    /**
     * Aprobar una solicitud de membresía
     */
    public function approve(CommunityRequest $request)
    {
        $community = $request->community;
        $user = Auth::user();

        // Verificar que el usuario actual es el dueño de la comunidad
        if (!$community->isOwner($user)) {
            return redirect()->back()->with('error', 'No tienes permisos para aprobar solicitudes en esta comunidad.');
        }

        // Verificar que la solicitud está pendiente
        if (!$request->isPending()) {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        // Aprobar la solicitud
        $request->update([
            'status' => 'approved',
            'responded_at' => now()
        ]);

        // Agregar al usuario como miembro de la comunidad
        $community->addMember($request->user);

        return redirect()->back()->with('success', 'Solicitud aprobada. El usuario ha sido agregado a la comunidad.');
    }

    /**
     * Rechazar una solicitud de membresía
     */
    public function reject(CommunityRequest $request, Request $httpRequest)
    {
        $community = $request->community;
        $user = Auth::user();

        // Verificar que el usuario actual es el dueño de la comunidad
        if (!$community->isOwner($user)) {
            return redirect()->back()->with('error', 'No tienes permisos para rechazar solicitudes en esta comunidad.');
        }

        // Verificar que la solicitud está pendiente
        if (!$request->isPending()) {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        // Rechazar la solicitud sin mensaje del admin
        $request->update([
            'status' => 'rejected',
            'admin_message' => null,
            'responded_at' => now()
        ]);

        return redirect()->back();
    }

    /**
     * Ver las solicitudes pendientes de una comunidad (solo para dueños)
     */
    public function index(Community $community)
    {
        $user = Auth::user();

        // Verificar que el usuario actual es el dueño de la comunidad
        if (!$community->isOwner($user)) {
            return redirect()->route('communities.show', $community)
                           ->with('error', 'No tienes permisos para ver las solicitudes de esta comunidad.');
        }

        $pendingRequests = $community->pendingRequests()
                                   ->with('user')
                                   ->latest()
                                   ->get();

        return view('communities.requests', compact('community', 'pendingRequests'));
    }
}
