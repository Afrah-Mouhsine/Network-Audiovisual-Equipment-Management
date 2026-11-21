<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch clients, potentially with pagination
        $clients = Client::orderBy("nom")->get(); // Or use paginate()
        return view("clients.index", compact("clients"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("clients.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "nom" => "required|string|max:255",
            "secteur" => "nullable|string|max:255",
            "statut" => "required|in:actif,archivé",
        ]);

        $client = Client::create($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("clients.index")->with("success", "Client créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Eager load related data if needed
        // $client->load(["contactsClient", "sitesClient", "interventions", "equipements"]);
        return view("clients.show", compact("client"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view("clients.edit", compact("client"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            "nom" => "required|string|max:255",
            "secteur" => "nullable|string|max:255",
            "statut" => "required|in:actif,archivé",
        ]);

        $client->update($validatedData);

        // Log action
        // Journalisation::create([...]);

        return redirect()->route("clients.index")->with("success", "Client mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            // Log action
            // Journalisation::create([...]);
            return redirect()->route("clients.index")->with("success", "Client supprimé avec succès.");
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle potential foreign key constraint violation if needed
            return redirect()->route("clients.index")->with("error", "Impossible de supprimer le client car il est lié à d\'autres enregistrements.");
        }
    }
}

