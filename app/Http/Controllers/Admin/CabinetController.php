<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Types\Etat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CabinetController extends Controller
{
    /**
     * Afficher la liste des cabinets actifs
     */
    public function index(): View
    {
        // Récupérer uniquement les cabinets actifs
        $cabinets = Cabinet::where('etat', Etat::ACTIF)
                          ->orderBy('nom')
                          ->get();

        return view('admin.cabinets.index', compact('cabinets'));
    }

    /**
     * Enregistrer un nouveau cabinet (AJAX)
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:cabinets,nom',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
        ], [
            'nom.required' => 'Le nom du cabinet est obligatoire.',
            'nom.unique' => 'Ce nom de cabinet existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
        ]);

        // Créer le cabinet
        $cabinet = Cabinet::create([
            'nom' => $validated['nom'],
            'adresse' => $validated['adresse'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'email' => $validated['email'] ?? null,
            'etat' => Etat::ACTIF,
        ]);

        return response()->json([
            'message' => 'Cabinet créé avec succès !',
            'cabinet' => $cabinet
        ]);
    }

    /**
     * Mettre à jour un cabinet (AJAX)
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $cabinet = Cabinet::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:cabinets,nom,' . $id,
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
        ], [
            'nom.required' => 'Le nom du cabinet est obligatoire.',
            'nom.unique' => 'Ce nom de cabinet existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
        ]);

        // Mettre à jour le cabinet
        $cabinet->update($validated);

        return response()->json([
            'message' => 'Cabinet modifié avec succès !',
            'cabinet' => $cabinet
        ]);
    }

    /**
     * Supprimer (désactiver) un cabinet (AJAX)
     */
    public function destroy(string $id): JsonResponse
    {
        $cabinet = Cabinet::findOrFail($id);

        // Vérifier si le cabinet a des clients actifs
        $clientsActifs = $cabinet->clients()->where('etat', Etat::ACTIF)->count();

        if ($clientsActifs > 0) {
            return response()->json([
                'message' => "Impossible de supprimer ce cabinet. Il a encore {$clientsActifs} client(s) actif(s)."
            ], 422);
        }

        // Désactiver le cabinet (pas de suppression définitive)
        $cabinet->update(['etat' => Etat::SUPPRIME]);

        return response()->json([
            'message' => 'Cabinet supprimé avec succès !'
        ]);
    }
}