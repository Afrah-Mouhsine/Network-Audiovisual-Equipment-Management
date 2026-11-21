<?php

namespace App\Http\Controllers;

use App\Models\CatalogueEquipement;
use App\Models\TypeEquipement;
use App\Models\ReferenceConstructeur;
use Illuminate\Http\Request;

class CatalogueEquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $catalogues = CatalogueEquipement::with(["typeEquipement", "referenceConstructeur"])->orderBy("modele")->get(); // Or paginate()
        return view("catalogue.index", compact("catalogues"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TypeEquipement::orderBy("nom")->get();
        $constructeurs = ReferenceConstructeur::orderBy("nom")->get();
        return view("catalogue.create", compact("types", "constructeurs"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "type_equipement_id" => "required|exists:types_equipement,id",
            "reference_constructeur_id" => "required|exists:references_constructeur,id",
            "modele" => "required|string|max:255",
            "description" => "nullable|string",
            "seuil_alerte" => "nullable|integer|min:0",
        ]);

        CatalogueEquipement::create($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("catalogue.index")->with("success", "Entrée catalogue créée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(CatalogueEquipement $catalogueEquipement)
    {
        // Parameter name is catalogueEquipement due to route parameter mapping
        $catalogueEquipement->load(["typeEquipement", "referenceConstructeur", "equipements"]);
        return view("catalogue.show", compact("catalogueEquipement"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CatalogueEquipement $catalogueEquipement)
    {
        $types = TypeEquipement::orderBy("nom")->get();
        $constructeurs = ReferenceConstructeur::orderBy("nom")->get();
        return view("catalogue.edit", compact("catalogueEquipement", "types", "constructeurs"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatalogueEquipement $catalogueEquipement)
    {
        $validatedData = $request->validate([
            "type_equipement_id" => "required|exists:types_equipement,id",
            "reference_constructeur_id" => "required|exists:references_constructeur,id",
            "modele" => "required|string|max:255",
            "description" => "nullable|string",
            "seuil_alerte" => "nullable|integer|min:0",
        ]);

        $catalogueEquipement->update($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("catalogue.index")->with("success", "Entrée catalogue mise à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogueEquipement $catalogueEquipement)
    {
        // Check if any equipement instance uses this catalogue entry
        if ($catalogueEquipement->equipements()->count() > 0) {
            return redirect()->route("catalogue.index")->with("error", "Impossible de supprimer cette entrée catalogue car elle est utilisée par des équipements existants.");
        }

        try {
            $catalogueEquipement->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("catalogue.index")->with("success", "Entrée catalogue supprimée avec succès.");
        } catch (\Exception $e) {
            return redirect()->route("catalogue.index")->with("error", "Impossible de supprimer l\"entrée catalogue.");
        }
    }
}

