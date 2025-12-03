<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    //
    public function get(Request $request){
        try {
            $promoteur = null;

            if($request->filled('nin')){
                $promoteur = (object)[
                    'NIN' => $request->nin,
                    'Nom' => 'DUPONT',
                    'Prenom' => 'Jean',
                    'CommuneNaissance' => 'Paris',
                    'DateNaissance' => '1980-01-01',
                    'Adresse' => '123 Rue Exemple',
                    'Telephone' => '+33123456789',

                    // Liste des doléances
                    'Doleances' => [
                        (object)[
                            'Titre' => 'Demande de mise à jour d’adresse',
                            'Description' => 'Le promoteur souhaite corriger son adresse dans le système.',
                            'Statut' => 'En attente', // autres valeurs possibles : Traitée, Rejetée
                        ],
                        (object)[
                            'Titre' => 'Réclamation sur délai de traitement',
                            'Description' => 'Le délai de traitement du dossier a dépassé le délai prévu.',
                            'Statut' => 'Traitée',
                        ],
                        (object)[
                            'Titre' => 'Demande d’assistance technique',
                            'Description' => 'Le promoteur signale un problème technique lors de la connexion.',
                            'Statut' => 'En cours',
                        ]
                    ]
                ];


                return response()->json([
                    'status_code' => 200,
                    'status'      => 'success',
                    'message'     => 'informations du promoteur (fake)',
                    'data'        => $promoteur,
                ]);
            }
            
            if($request->filled('nom') && $request->filled('prenom') ){
                $promoteur = (object)[
                    'Nom' => $request->nom,
                    'Prenom' => $request->prenom,
                    'CommuneNaissance' => 'Paris',
                    'DateNaissance' => '1980-01-01',
                    'Adresse' => '123 Rue Exemple',
                    'Telephone' => '+33123456789',

                    // Liste des doléances
                    'Doleances' => [
                        (object)[
                            'Titre' => 'Demande de mise à jour d’adresse',
                            'Description' => 'Le promoteur souhaite corriger son adresse dans le système.',
                            'Statut' => 'En attente', // autres valeurs possibles : Traitée, Rejetée
                        ],
                        (object)[
                            'Titre' => 'Réclamation sur délai de traitement',
                            'Description' => 'Le délai de traitement du dossier a dépassé le délai prévu.',
                            'Statut' => 'Traitée',
                        ],
                        (object)[
                            'Titre' => 'Demande d’assistance technique',
                            'Description' => 'Le promoteur signale un problème technique lors de la connexion.',
                            'Statut' => 'En cours',
                        ]
                    ]
                ];
            }

            
            return response()->json([
                'status_code'  => $promoteur ? 200 : 204,
                'status'         => 'success',
                'message'        => 'informations du promoteur',
                'data'          => $promoteur,
            ]);
        } catch (\Exception $e) {
            
            Log::error('User List Error: ' . $e->getMessage());

            return response()->json([
                'status_code' => 500,
                'status'        => 'error',
                'message'       => $e->getMessage(),
            ], 500);
        }
    }
}
