<?php

namespace App\Http\Controllers;

use App\Models\DemandeSupport;
use App\Models\Client;
use App\Models\Equipement;
use App\Models\StatutSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demandes = DemandeSupport::with(["client", "equipement.catalogueEquipement", "statutSupport", "utilisateurAssigné"])->orderBy("created_at", "desc")->get(); // Or paginate()
        return view("support.index", compact("demandes"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        $equipements = Equipement::with("catalogueEquipement")->orderBy("numero_serie")->get(); // Consider filtering
        $statuts = StatutSupport::orderBy("nom")->get();
        $techniciens = User::whereHas("role", function ($query) {
            $query->whereIn("nom", ["Technicien", "Administrateur"]); // Or relevant roles
        })->orderBy("name")->get();

        return view("support.create", compact("clients", "equipements", "statuts", "techniciens"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "client_id" => "required|exists:clients,id",
            "equipement_id" => "nullable|exists:equipements,id",
            "statut_id" => "required|exists:statuts_support,id",
            "utilisateur_assigne_id" => "nullable|exists:users,id",
            "objet" => "required|string|max:255",
            "description" => "required|string",
        ]);

        // Add the creator user ID
        $validatedData["utilisateur_createur_id"] = Auth::id();

        DemandeSupport::create($validatedData);

        // Log action
        // Journalisation::create([...]);

        // Notify assigned user? (Future enhancement)

        return redirect()->route("support.index")->with("success", "Demande de support créée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeSupport $demandeSupport)
    {
        // Parameter name matches route model binding
        $demandeSupport->load(["client", "equipement.catalogueEquipement", "statutSupport", "utilisateurAssigné", "utilisateurCreateur"]);
        return view("support.show", compact("demandeSupport"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeSupport $demandeSupport)
    {
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        $equipements = Equipement::with("catalogueEquipement")->orderBy("numero_serie")->get(); // Consider filtering
        $statuts = StatutSupport::orderBy("nom")->get();
        $techniciens = User::whereHas("role", function ($query) {
            $query->whereIn("nom", ["Technicien", "Administrateur"]); // Or relevant roles
        })->orderBy("name")->get();

        return view("support.edit", compact("demandeSupport", "clients", "equipements", "statuts", "techniciens"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DemandeSupport $demandeSupport)
    {
        $validatedData = $request->validate([
            "client_id" => "required|exists:clients,id",
            "equipement_id" => "nullable|exists:equipements,id",
            "statut_id" => "required|exists:statuts_support,id",
            "utilisateur_assigne_id" => "nullable|exists:users,id",
            "objet" => "required|string|max:255",
            "description" => "required|string",
            "resolution" => "nullable|string", // Add resolution field
        ]);

        $demandeSupport->update($validatedData);

        // Log action
        // Journalisation::create([...]);

        // Notify assigned user? (Future enhancement)

        return redirect()->route("support.index")->with("success", "Demande de support mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeSupport $demandeSupport)
    {
        try {
            $demandeSupport->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("support.index")->with("success", "Demande de support supprimée avec succès.");
        } catch (\Exception $e) {
            return redirect()->route("support.index")->with("error", "Impossible de supprimer la demande de support.");
        }
    }
}

