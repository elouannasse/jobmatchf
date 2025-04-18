<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;

class HomeController extends Controller
{
    // Cette méthode affiche toutes les offres par défaut (route: /home)
    public function index()
    {
        $offres = Offre::with('category')->latest()->paginate(10);
        return view('dashboard', compact('offres'));
    }

    // Cette méthode effectue une recherche parmi les offres (route: /home/search)
    public function search(Request $request)
    {
        $term = $request->input('search-term');

        $offres = Offre::with('category')
            ->where('title', 'like', "%$term%")
            ->orWhere('description', 'like', "%$term%")
            ->orWhereHas('category', function ($query) use ($term) {
                $query->where('name', 'like', "%$term%");
            })
            ->paginate(10);

        return view('dashboard', compact('offres'));
    }
}
