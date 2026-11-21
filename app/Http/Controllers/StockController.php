<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::orderBy("nom")->get(); // Or paginate()
        return view("stocks.index", compact("stocks"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("stocks.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "nom" => "required|string|max:255|unique:stocks,nom",
            "adresse" => "nullable|string|max:255",
        ]);

        Stock::create($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("stocks.index")->with("success", "Stock créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(["equipements.catalogueEquipement"]); // Load equipements in this stock
        return view("stocks.show", compact("stock"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        return view("stocks.edit", compact("stock"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validatedData = $request->validate([
            "nom" => "required|string|max:255|unique:stocks,nom," . $stock->id,
            "adresse" => "nullable|string|max:255",
        ]);

        $stock->update($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("stocks.index")->with("success", "Stock mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        // Check if stock contains any equipements before deleting
        if ($stock->equipements()->count() > 0) {
            return redirect()->route("stocks.index")->with("error", "Impossible de supprimer le stock car il contient des équipements.");
        }

        try {
            $stock->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("stocks.index")->with("success", "Stock supprimé avec succès.");
        } catch (\Exception $e) {
            return redirect()->route("stocks.index")->with("error", "Impossible de supprimer le stock.");
        }
    }
}

