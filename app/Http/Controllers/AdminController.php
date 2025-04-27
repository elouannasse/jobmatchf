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
    /**
     * @var AdminOffreService
     */
    private $adminOffreService;
    
    /**
     * @var UserService
     */
    private $userService;
    
    /**
     * @var CandidatureService
     */
    private $candidatureService;

    /**
     * Create a new controller instance.
     *
     * @param AdminOffreService $adminOffreService
     * @param UserService $userService
     * @param CandidatureService $candidatureService
     * @return void
     */
    public function __construct(
        AdminOffreService $adminOffreService,
        UserService $userService,
        CandidatureService $candidatureService
    ) {
        $this->adminOffreService = $adminOffreService;
        $this->userService = $userService;
        $this->candidatureService = $candidatureService;
        $this->middleware('auth');
        // $this->middleware('role:admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get statistics
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

        // Get notifications
        $notifications = auth()->user()->unreadNotifications;

        // Get pending offers
        $pendingOffers = Offre::where('etat', false)->latest()->take(5)->get();

        // Get latest offers
        $latestOffers = Offre::with('user')->latest()->take(5)->get();

        // Get monthly registration data for chart
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

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        return $this->index();
    }

    /**
     * Show all users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function users()
    {
        $users = $this->userService->getAllUsers(10);
        return view('admin.users', compact('users'));
    }

    /**
     * Show all recruiters.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function recruteurs()
    {
        $recruteurs = User::whereHas('role', function ($q) {
            $q->where('name', 'recruteur');
        })->with('typeEntreprise')->latest()->paginate(10);

        return view('admin.recruteurs', compact('recruteurs'));
    }

    /**
     * Show all candidates.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function candidats()
    {
        $candidats = User::whereHas('role', function ($q) {
            $q->where('name', 'candidat');
        })->latest()->paginate(10);

        return view('admin.candidats', compact('candidats'));
    }

    /**
     * Show all job offers with statistics.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function offres(Request $request)
    {
        // Get the filters from the request
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
        
        // Get paginated job offers with filters
        $offres = $this->adminOffreService->getAllOffers($filters, 10);
        
        // Get statistics for charts
        $stats = $this->adminOffreService->getOfferStatistics();
        
        return view('admin.offres', [
            'offres' => $offres,
            'statsContrat' => $stats['statsContrat'],
            'statsLieux' => $stats['statsLieux'],
            'statsMonthly' => $stats['statsMonthly'],
            'labels' => $stats['labels']
        ]);
    }

    /**
     * Show pending job offers.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPendingOffers()
    {
        $offres = $this->adminOffreService->getPendingOffers(10);
        return view('admin.offres-pending', compact('offres'));
    }

    /**
     * Show job offer details.
     *
     * @param Offre $offre
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showOffer(Offre $offre)
    {
        $offre->load('user', 'candidatures.user');
        return view('admin.offre-details', compact('offre'));
    }

    /**
     * Approve a job offer.
     *
     * @param int $offreId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveOffre(int $offreId)
    {
        $result = $this->adminOffreService->approveOffer($offreId);
        
        if ($result) {
            return redirect()->back()->with('success', 'L\'offre a été approuvée avec succès.');
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors de l\'approbation de l\'offre.');
    }

    /**
     * Reject a job offer.
     *
     * @param Request $request
     * @param int $offreId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectOffre(Request $request, int $offreId)
    {
        $result = $this->adminOffreService->rejectOffer($offreId);
        
        if ($result) {
            return redirect()->back()->with('success', 'L\'offre a été rejetée.');
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors du rejet de l\'offre.');
    }

    /**
     * Toggle the status of an offer.
     * 
     * @param int $offreId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleOffreStatus(int $offreId)
    {
        $result = $this->adminOffreService->toggleOfferStatus($offreId);
        // Get the updated offre to check its status
        $updatedOffre = Offre::find($offreId);
        
        if ($result) {
            $status = $updatedOffre && $updatedOffre->etat ? 'activée' : 'désactivée';
            return redirect()->back()->with('success', "L'offre a été {$status} avec succès.");
        }
        
        return redirect()->back()->with('error', 'Un problème est survenu lors de la modification du statut de l\'offre.');
    }

    /**
     * Change user status (active/inactive).
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "L'utilisateur a été {$status} avec succès.");
    }

    /**
     * Show admin reports and statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statistics()
    {
        // User registrations per month
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

        // Offers per month
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

        // Applications per month
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

        // Popular job types
        $popularJobTypes = Offre::select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->orderBy('total', 'desc')
            ->get();

        // Popular locations
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

    /**
     * Show and manage candidatures.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function candidatures()
    {
        $candidatures = $this->candidatureService->getAllCandidatures(10);

        // Ajouter le comptage des candidatures par statut
        $enAttente = Candidature::where('statut', 'en_attente')->count();
        $acceptees = Candidature::where('statut', 'acceptee')->count();
        $refusees = Candidature::where('statut', 'refusee')->count();
        $totalCandidatures = Candidature::count();

        // Top des offres avec le plus de candidatures
        $topOffres = Offre::withCount('candidatures')
            ->orderBy('candidatures_count', 'desc')
            ->take(5)
            ->get();

        // Récupérer les titres et le nombre de candidatures pour chaque offre
        $topOffresTitres = $topOffres->pluck('titre')->toArray();
        $topOffresCount = $topOffres->pluck('candidatures_count')->toArray();
        $topOffresTotaux = $totalCandidatures; // Total de toutes les candidatures

        // Statistiques de candidatures par mois
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

        // Ajout des statistiques mensuelles
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

    /**
     * Delete a user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(User $user)
    {
        // Delete the user
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }

    /**
     * Create a new user form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createUser()
    {
        $roles =Role::all();
        return view('admin.create-user', compact('roles'));
    }

    /**
     * Store a new user in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Edit a user form.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update a user.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'role_id' => $request->role_id, // Directly assign role_id
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

    /**
     * Delete a user.
     *
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Delete a candidature.
     *
     * @param int $candidatureId
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Show the form to create a new candidate.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createCandidate()
    {
        return view('admin.create-candidate');
    }

    /**
     * Store a new candidate in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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