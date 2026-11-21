<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Client;
use App\Models\SiteClient;
use App\Models\PrioriteIntervention;
use App\Models\StatutIntervention;
use App\Models\User;
use App\Models\Equipement;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::with(["client", "siteClient", "statutIntervention", "prioriteIntervention", "techniciens"])->orderBy("date_planification", "desc")->get(); // Or paginate()
        return view("interventions.index", compact("interventions"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        $sites = SiteClient::orderBy("nom_site")->get(); // Consider filtering by client
        $priorites = PrioriteIntervention::all();
        $statuts = StatutIntervention::all();
        $techniciens = User::whereHas("role", function ($query) {
            $query->where("nom", "Technicien");
        })->orderBy("name")->get();
        $equipements = Equipement::with("catalogueEquipement")->orderBy("numero_serie")->get(); // Consider filtering by client/site

        return view("interventions.create", compact("clients", "sites", "priorites", "statuts", "techniciens", "equipements"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "client_id" => "required|exists:clients,id",
            "site_id" => "required|exists:sites_client,id",
            "priorite_id" => "required|exists:priorites_intervention,id",
            "statut_id" => "required|exists:statuts_intervention,id",
            "objet" => "required|string|max:255",
            "description" => "nullable|string",
            "date_planification" => "nullable|date",
            "techniciens" => "nullable|array",
            "techniciens.*" => "exists:users,id",
            "equipements" => "nullable|array",
            "equipements.*" => "exists:equipements,id",
        ]);

        // Optionally validate that site_id belongs to client_id

        $intervention = Intervention::create($validatedData);

        // Attach technicians and equipements
        if ($request->has("techniciens")) {
            $intervention->techniciens()->sync($request->input("techniciens"));
        }
        if ($request->has("equipements")) {
            $intervention->equipements()->sync($request->input("equipements"));
        }

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("interventions.index")->with("success", "Intervention planifiée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Intervention $intervention)
    {
        $intervention->load(["client", "siteClient", "statutIntervention", "prioriteIntervention", "techniciens", "equipements.catalogueEquipement", "compteRendu.piecesJointes"]);
        return view("interventions.show", compact("intervention"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention)
    {
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        $sites = SiteClient::orderBy("nom_site")->get(); // Consider filtering by client
        $priorites = PrioriteIntervention::all();
        $statuts = StatutIntervention::all();
        $techniciens = User::whereHas("role", function ($query) {
            $query->where("nom", "Technicien");
        })->orderBy("name")->get();
        $equipements = Equipement::with("catalogueEquipement")->orderBy("numero_serie")->get(); // Consider filtering by client/site

        $intervention->load(["techniciens", "equipements"]); // Load current relations

        return view("interventions.edit", compact("intervention", "clients", "sites", "priorites", "statuts", "techniciens", "equipements"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intervention $intervention)
    {
        $validatedData = $request->validate([
            "client_id" => "required|exists:clients,id",
            "site_id" => "required|exists:sites_client,id",
            "priorite_id" => "required|exists:priorites_intervention,id",
            "statut_id" => "required|exists:statuts_intervention,id",
            "objet" => "required|string|max:255",
            "description" => "nullable|string",
            "date_planification" => "nullable|date",
            "date_cloture" => "nullable|date",
            "compte_rendu_interne" => "nullable|string",
            "temps_passe" => "nullable|integer|min:0",
            "techniciens" => "nullable|array",
            "techniciens.*" => "exists:users,id",
            "equipements" => "nullable|array",
            "equipements.*" => "exists:equipements,id",
        ]);

        // Optionally validate that site_id belongs to client_id

        $intervention->update($validatedData);

        // Sync technicians and equipements
        $intervention->techniciens()->sync($request->input("techniciens", []));
        $intervention->equipements()->sync($request->input("equipements", []));

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("interventions.index")->with("success", "Intervention mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention)
    {
        try {
            // Detach relationships first if needed, or rely on cascade (if set up)
            $intervention->techniciens()->detach();
            $intervention->equipements()->detach();
            // Delete related CompteRendu? Depends on requirements.

            $intervention->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("interventions.index")->with("success", "Intervention supprimée avec succès.");
        } catch (\Exception $e) {
            return redirect()->route("interventions.index")->with("error", "Impossible de supprimer l\"intervention.");
        }
    }
}

