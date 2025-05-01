<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CandidatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    /**
     * @var CandidatureService
     */
    protected $candidatureService;

    /**
     * CandidatureController constructor.
     *
     * @param CandidatureService $candidatureService
     */
    public function __construct(CandidatureService $candidatureService)
    {
        $this->candidatureService = $candidatureService;
        $this->middleware('auth');
    }

    /**
     * Get candidature details.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $candidature = $this->candidatureService->getCandidatureDetails($id);
        
        // Check if candidature exists
        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature not found'
            ], 404);
        }
        
        // Check if user is authorized to view this candidature
        // Admin can view all, recruiters can view candidatures for their offers,
        // candidates can view their own candidatures
        $isAuthorized = Auth::user()->isAdmin() || 
                       (Auth::user()->isRecruteur() && $candidature->offre->user_id === Auth::id()) ||
                       (Auth::user()->isCandidat() && $candidature->user_id === Auth::id());
        
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this candidature'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'candidature' => $candidature
        ]);
    }
}
