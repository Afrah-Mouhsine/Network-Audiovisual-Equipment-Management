<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\CatalogueEquipement;
use App\Models\Client;
use App\Models\SiteClient;
use App\Models\Stock;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relationships for efficiency in the view
        $equipements = Equipement::with(["catalogueEquipement.typeEquipement", "catalogueEquipement.referenceConstructeur", "client", "siteClient", "stock"])->orderBy("id")->get(); // Or paginate()
        return view("equipements.index", compact("equipements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catalogues = CatalogueEquipement::with("typeEquipement", "referenceConstructeur")->get();
        $stocks = Stock::orderBy("nom")->get();
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        // Sites can be loaded dynamically based on client selection via JS, or passed if manageable
        $sites = SiteClient::orderBy("nom_site")->get(); // Consider filtering by client if needed

        return view("equipements.create", compact("catalogues", "stocks", "clients", "sites"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "catalogue_equipement_id" => "required|exists:catalogue_equipement,id",
            "numero_serie" => "required|string|max:255|unique:equipements,numero_serie",
            "firmware" => "nullable|string|max:255",
            "date_garantie" => "nullable|date",
            "statut" => "required|in:en stock,assigné,en intervention,en panne,rebut",
            "stock_id" => "nullable|required_if:statut,en stock|exists:stocks,id",
            "client_id" => "nullable|required_if:statut,assigné|exists:clients,id",
            "site_id" => "nullable|required_if:statut,assigné|exists:sites_client,id",
        ]);

        // Ensure location consistency based on status
        if ($validatedData["statut"] === "en stock") {
            $validatedData["client_id"] = null;
            $validatedData["site_id"] = null;
        } else if ($validatedData["statut"] === "assigné") {
            $validatedData["stock_id"] = null;
            // Optionally validate that site_id belongs to client_id
        } else {
            // For other statuses, location might be null or determined differently
            $validatedData["stock_id"] = null;
            $validatedData["client_id"] = null;
            $validatedData["site_id"] = null;
        }

        $equipement = Equipement::create($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("equipements.index")->with("success", "Équipement créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipement $equipement)
    {
        $equipement->load(["catalogueEquipement.typeEquipement", "catalogueEquipement.referenceConstructeur", "client", "siteClient", "stock", "interventions", "mouvementsStock.user", "demandesSupport"]);
        return view("equipements.show", compact("equipement"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipement $equipement)
    {
        $catalogues = CatalogueEquipement::with("typeEquipement", "referenceConstructeur")->get();
        $stocks = Stock::orderBy("nom")->get();
        $clients = Client::where("statut", "actif")->orderBy("nom")->get();
        $sites = SiteClient::orderBy("nom_site")->get(); // Consider filtering by client

        return view("equipements.edit", compact("equipement", "catalogues", "stocks", "clients", "sites"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipement $equipement)
    {
        $validatedData = $request->validate([
            "catalogue_equipement_id" => "required|exists:catalogue_equipement,id",
            "numero_serie" => "required|string|max:255|unique:equipements,numero_serie," . $equipement->id,
            "firmware" => "nullable|string|max:255",
            "date_garantie" => "nullable|date",
            "statut" => "required|in:en stock,assigné,en intervention,en panne,rebut",
            "stock_id" => "nullable|required_if:statut,en stock|exists:stocks,id",
            "client_id" => "nullable|required_if:statut,assigné|exists:clients,id",
            "site_id" => "nullable|required_if:statut,assigné|exists:sites_client,id",
        ]);

        // Ensure location consistency based on status
        if ($validatedData["statut"] === "en stock") {
            $validatedData["client_id"] = null;
            $validatedData["site_id"] = null;
        } else if ($validatedData["statut"] === "assigné") {
            $validatedData["stock_id"] = null;
            // Optionally validate that site_id belongs to client_id
        } else {
            $validatedData["stock_id"] = null;
            $validatedData["client_id"] = null;
            $validatedData["site_id"] = null;
        }

        // Check for location change to create MouvementStock record (simplified example)
        // A more robust implementation would use events or a dedicated service
        $locationChanged = false;
        if ($equipement->stock_id != $validatedData["stock_id"] || $equipement->client_id != $validatedData["client_id"] || $equipement->site_id != $validatedData["site_id"]) {
            $locationChanged = true;
            // Prepare MouvementStock data
        }

        $equipement->update($validatedData);

        if ($locationChanged) {
            // Create MouvementStock record
            // MouvementStock::create([...]);
        }

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("equipements.index")->with("success", "Équipement mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipement $equipement)
    {
        try {
            $equipement->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("equipements.index")->with("success", "Équipement supprimé avec succès.");
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route("equipements.index")->with("error", "Impossible de supprimer l\"équipement car il est lié à d\"autres enregistrements (interventions, etc.).");
        }
    }
}

