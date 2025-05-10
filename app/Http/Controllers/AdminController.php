<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminOffreService;
use App\Services\UserService;
use App\Services\CandidatureService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Notifications\OffreStatusNotification;

class AdminController extends Controller
{
    
    private $adminOffreService;
    
    
    private $userService;
    
    
    private $candidatureService;

    
    public function __construct(
        AdminOffreService $adminOffreService,
        UserService $userService,
        CandidatureService $candidatureService
    ) {
        $this->adminOffreService = $adminOffreService;
        $this->userService = $userService;
        $this->candidatureService = $candidatureService;
        $this->middleware('auth');
    }

    
    public function index()
    {
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_recruteurs' => User::whereHas('role', function ($q) {
                $q->where('name', 'recruteur');
            })->count(),
            'total_candidats' => User::whereHas('role', function ($q) {
                $q->where('name', 'candidat');
            })->count(),
            'total_offres' => Offre::count(),
        ];

        $notifications = auth()->user()->unreadNotifications;

        $pendingOffers = Offre::where('etat', false)->latest()->take(5)->get();

        $latestOffers = Offre::with('user')->latest()->take(5)->get();

        $year = Carbon::now()->year;
        $monthlyRegistrations = [];
        $monthLabels = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyRegistrations[] = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $monthLabels[] = Carbon::create()->month($month)->format('F');
        }

        return view('admin.dashboard', compact(
            'stats',
            'notifications',
            'pendingOffers',
            'latestOffers',
            'monthlyRegistrations',
            'monthLabels'
        ));
    }

    
    public function adminDashboard()
    {
        return $this->index();
    }

   
    public function users()
    {
        $users = $this->userService->getAllUsers(10);
        return view('admin.users', compact('users'));
    }

   
    public function recruteurs()
    {
        $recruteurs = User::whereHas('role', function ($q) {
            $q->where('name', 'recruteur');
        })->with('typeEntreprise')->latest()->paginate(10);

        return view('admin.recruteurs', compact('recruteurs'));
    }

   
    public function candidats()
    {
        $candidats = User::whereHas('role', function ($q) {
            $q->where('name', 'candidat');
        })->latest()->paginate(10);

        return view('admin.candidats', compact('candidats'));
    }

   
    public function offres(Request $request)
    {
        $filters = [];
        
        if ($request->has('etat')) {
            $filters['etat'] = $request->etat;
        }
        
        if ($request->has('approved')) {
            $filters['approved'] = $request->approved;
        }
        
        if ($request->has('pending')) {
            $filters['pending'] = true;
        }
        
        $offres = $this->adminOffreService->getAllOffers($filters, 10);
        
        $stats = $this->adminOffreService->getOfferStatistics();
        
        return view('admin.offres', [
            'offres' => $offres,
            'statsContrat' => $stats['statsContrat'],
            'statsLieux' => $stats['statsLieux'],
            'statsMonthly' => $stats['statsMonthly'],
            'labels' => $stats['labels']
        ]);
    }

   
    public function showPendingOffers()
    {
        $offres = $this->adminOffreService->getPendingOffers(10);
        return view('admin.offres-pending', compact('offres'));
    }

   
    public function showOffer(Offre $offre)
    {
        $offre->load('user', 'candidatures.user');
        return view('admin.offre-details', compact('offre'));
    }

   
    public function approveOffre(int $offreId)
    {
        $result = $this->adminOffreService->approveOffer($offreId);
        
        if ($result) {
            return redirect()->back()->with('success', 'L\'offre a été approuvée avec succès.');
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors de l\'approbation de l\'offre.');
    }

    
    public function rejectOffre(Request $request, int $offreId)
    {
        $result = $this->adminOffreService->rejectOffer($offreId);
        
        if ($result) {
            return redirect()->back()->with('success', 'L\'offre a été rejetée.');
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors du rejet de l\'offre.');
    }

    
    public function toggleOffreStatus(int $offreId)
    {
        $result = $this->adminOffreService->toggleOfferStatus($offreId);
        $updatedOffre = Offre::find($offreId);
        
        if ($result) {
            $status = $updatedOffre && $updatedOffre->etat ? 'activée' : 'désactivée';
            return redirect()->back()->with('success', "L'offre a été {$status} avec succès.");
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors de la modification du statut de l\'offre.');
    }

   
    public function changeUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "L'utilisateur a été {$status} avec succès.");
    }

   
    public function statistics()
    {
        $userRegistrations = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $offerStatistics = Offre::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $applicationStatistics = Candidature::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $popularJobTypes = Offre::select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->orderBy('total', 'desc')
            ->get();

        $popularLocations = Offre::select('lieu', DB::raw('count(*) as total'))
            ->groupBy('lieu')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports', compact(
            'userRegistrations',
            'offerStatistics',
            'applicationStatistics',
            'popularJobTypes',
            'popularLocations'
        ));
    }

   
    public function candidatures()
    {
        $candidatures = $this->candidatureService->getAllCandidatures(10);

        $enAttente = Candidature::where('statut', 'en_attente')->count();
        $acceptees = Candidature::where('statut', 'acceptee')->count();
        $refusees = Candidature::where('statut', 'refusee')->count();
        $totalCandidatures = Candidature::count();

        $topOffres = Offre::withCount('candidatures')
            ->orderBy('candidatures_count', 'desc')
            ->take(5)
            ->get();

        $topOffresTitres = $topOffres->pluck('titre')->toArray();
        $topOffresCount = $topOffres->pluck('candidatures_count')->toArray();
        $topOffresTotaux = $totalCandidatures; 

        $candidaturesByMonth = Candidature::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->take(6)
            ->get();

        $monthLabels = [];
        $candidatureMonthlyData = [];

        foreach ($candidaturesByMonth as $entry) {
            $date = Carbon::createFromDate($entry->year, $entry->month, 1);
            $monthLabels[] = $date->format('M Y');
            $candidatureMonthlyData[] = $entry->total;
        }

        $monthlyStats = $candidatureMonthlyData;

        return view('admin.candidatures', compact(
            'candidatures',
            'enAttente',
            'acceptees',
            'refusees',
            'topOffresTitres',
            'topOffresCount',
            'topOffresTotaux',
            'monthLabels',
            'candidatureMonthlyData',
            'totalCandidatures',
            'monthlyStats'
        ));
    }

   
    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }

   
    public function createUser()
    {
        $roles =Role::all();
        return view('admin.create-user', compact('roles'));
    }

   
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = $this->userService->createUser($request->all());

        if ($user) {
            return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
        }

        return redirect()->route('admin.users.create')
            ->with('error', 'Erreur lors de la création de l\'utilisateur')
            ->withInput();
    }

   
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

   
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id, 
        ];

        if ($request->filled('prenom')) {
            $userData['prenom'] = $request->prenom;
        }

        $user->update($userData);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur modifié avec succès');
    }

   
    public function destroyUser(int $userId)
    {
        $result = $this->userService->deleteUser($userId);
        
        if ($result) {
            return redirect()->route('admin.users')
                ->with('success', 'Utilisateur supprimé avec succès');
        }
        
        return redirect()->route('admin.users')
            ->with('error', 'Erreur lors de la suppression de l\'utilisateur');
    }

   
    public function deleteCandidature(int $candidatureId)
    {
        $result = $this->candidatureService->deleteCandidature($candidatureId);

        if ($result) {
            return redirect()->route('admin.candidatures')
                ->with('success', 'La candidature a été supprimée avec succès.');
        }
        
        return redirect()->route('admin.candidatures')
            ->with('error', 'Une erreur est survenue lors de la suppression de la candidature.');
    }

   
    public function createCandidate()
    {
        return view('admin.create-candidate');
    }

   
    public function storeCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $candidate = $this->userService->createCandidate($request->all());

        if ($candidate) {
            return redirect()->route('admin.candidats')->with('success', 'Le candidat a été créé avec succès.');
        }

        return redirect()->back()
            ->with('error', 'Erreur lors de la création du candidat. Vérifiez que le rôle "candidat" existe.')
            ->withInput();
    }
}