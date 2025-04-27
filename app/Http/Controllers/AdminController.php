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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Notifications\OffreStatusNotification;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $users = User::with('role')->latest()->paginate(10);
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function offres(Request $request)
    {
        // Filtres sur les offres
        $query = Offre::with(['user', 'candidatures']);

        if ($request->has('etat')) {
            $query->where('etat', $request->etat);
        }

        if ($request->has('approved')) {
            $query->where('approved', $request->approved);
        }

        if ($request->has('pending')) {
            $query->whereNull('approved');
        }

        $offres = $query->latest()->paginate(10);

        // Statistiques pour les graphiques

        // 1. Statistiques par type de contrat
        $statsContrat = Offre::select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->orderBy('total', 'desc')
            ->get();

        // 2. Statistiques par lieu (top 5)
        $statsLieux = Offre::select('lieu', DB::raw('count(*) as total'))
            ->groupBy('lieu')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 3. Statistiques des offres par mois (derniers 6 mois)
        $statsMonthly = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $labels[] = $date->format('M Y');

            $count = Offre::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $statsMonthly[] = $count;
        }

        return view('admin.offres', compact(
            'offres',
            'statsContrat',
            'statsLieux',
            'statsMonthly',
            'labels'
        ));
    }

    /**
     * Show pending job offers.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPendingOffers()
    {
        $offres = Offre::where('etat', false)->with('user')->latest()->paginate(10);
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
     * @param Offre $offre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveOffre(Offre $offre)
    {
        // تعيين القيم بشكل صريح
        $offre->etat = 1;
        $offre->approved = 1;
        $offre->save();
        
        // إشعار المجند
        $offre->user->notify(new OffreStatusNotification($offre, true));
        
        return redirect()->back()->with('success', 'L\'offre a été approuvée avec succès.');
    }

    /**
     * Reject a job offer.
     *
     * @param Request $request
     * @param Offre $offre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectOffre(Request $request, Offre $offre)
    {
        // You can add a rejection reason field if needed
        $offre->etat = false;

        // Marquer comme rejeté si la colonne existe
        if (Schema::hasColumn('offres', 'approved')) {
            $offre->approved = false;
        }

        $offre->save();

        // Notify the recruiter that their offer has been rejected
        $offre->user->notify(new OffreStatusNotification($offre, false));

        return redirect()->back()->with('success', 'L\'offre a été rejetée.');
    }

    /**
     * Toggle the status of an offer.
     * 
     * @param Offre $offre
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleOffreStatus(Offre $offre)
    {
        $offre->etat = !$offre->etat;
        
        // إذا تم تفعيل العرض، تأكد من تعيين approved أيضاً
        if ($offre->etat) {
            $offre->approved = 1;
        }
        
        $offre->save();

        $status = $offre->etat ? 'activée' : 'désactivée';
        return redirect()->back()->with('success', "L'offre a été {$status} avec succès.");
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
        $candidatures = Candidature::with(['user', 'offre.user'])->latest()->paginate(10);

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

        // Create the user with role_id directly (since it's a BelongsTo relationship)
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id, // Directly assign role_id
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
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
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Delete a candidature.
     *
     * @param Candidature $candidature
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCandidature(Candidature $candidature)
    {
        $candidature->delete();

        return redirect()->route('admin.candidatures')->with('success', 'La candidature a été supprimée avec succès.');
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

        // Get the role ID for 'candidat'
        $candidatRole = Role::where('name', 'candidat')->first();

        if (!$candidatRole) {
            return redirect()->back()->with('error', 'Le rôle "candidat" n\'existe pas.');
        }

        // Create the candidate user with role_id directly
        $candidate = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $candidatRole->id, // Directly assign role_id
        ]);

        return redirect()->route('admin.candidats')->with('success', 'Le candidat a été créé avec succès.');
    }
}