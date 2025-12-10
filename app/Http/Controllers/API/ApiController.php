<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class ApiController extends Controller
{

    //
    public function get(Request $request){
        $fakeTitles = [
            'Demande de mise à jour d’adresse',
            'Réclamation sur délai de traitement',
            'Demande d’assistance technique',
            'Problème de connexion',
            'Erreur dans le dossier',
            'Réclamation financière',
            'Demande d’éclaircissement',
            'Notification manquante',
            'Erreur d’identité',
            'Dépassement de délai',
        ];

        $fakeDescriptions = [
            'Le promoteur souhaite corriger une information erronée.',
            'Une erreur est survenue lors du traitement du dossier.',
            'Un problème technique a été signalé.',
            'Le promoteur demande une clarification sur son dossier.',
            'Une notification attendue n’a pas été reçue.',
            'Demande d’intervention urgente.',
            'Des documents semblent manquants.',
            'Une mise à jour des informations personnelles est requise.',
        ];
        $fakeStatus = ['En attente', 'Traitée', 'Rejetée', 'En cours'];

        // Nombre aléatoire de doléances entre 1 et 20
        $doleanceCount = rand(1, 20);

        $doleances = [];

        for ($i = 0; $i < $doleanceCount; $i++) {
            $doleances[] = (object)[
                'Titre' => $fakeTitles[array_rand($fakeTitles)],
                'Description' => $fakeDescriptions[array_rand($fakeDescriptions)],
                'Statut' => $fakeStatus[array_rand($fakeStatus)],
            ];
        }

        $promoteur = (object)[
            
            'Nom' => fake()->lastName(),
            'Prenom' => fake()->firstName(),
            'CommuneNaissance' => fake()->city(),
            'DateNaissance' => fake()->date(),
            'Adresse' => fake()->address(),
            'Telephone' => fake()->phoneNumber(),

            'Doleances' => $doleances
        ];


        try {

            if($request->filled('nin')){
                $promoteur->NIN = $request->nin;
                return response()->json([
                    'status_code' => 200,
                    'status'      => 'success',
                    'message'     => 'informations du promoteur (fake)',
                    'data'        => $promoteur,
                ]);
            }
            
            if($request->filled('nom') && $request->filled('prenom') ){
                $promoteur->nom = $request->nom;
                $promoteur->prenom = $request->prenom;
                $promoteur->NIN = Str::upper(Str::random(18));
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



    public function paginate(Request $request)
        {
            $faker = fake();

            // Number per page (default 10)
            $perPage = $request->get('per_page', 10);

            // Total items to generate
            $total = 200; // or any number you want

            // Generate fake promoteurs
            $items = collect(range(1, $total))->map(function () use ($faker) {

                return (object)[
                    'NIN' => $faker->numerify('##########'),
                    'Nom' => $faker->lastName(),
                    'Prenom' => $faker->firstName(),
                    'CommuneNaissance' => $faker->city(),
                    'DateNaissance' => $faker->date('Y-m-d'),
                    'Adresse' => $faker->address(),
                    'Telephone' => $faker->e164PhoneNumber(),

                    // Random doléances (1 to 20)
                    'Doleances' => collect(range(1, rand(1, 20)))->map(function () use ($faker) {
                        $titles = [
                            'Demande de mise à jour d’adresse',
                            'Réclamation sur délai de traitement',
                            'Demande d’assistance technique',
                            'Correction des informations personnelles',
                            'Problème d\'accès au dossier',
                            'Demande de recours',
                        ];

                        $statuses = ['En attente', 'En cours', 'Traitée', 'Rejetée'];

                        return (object)[
                            'Titre' => $faker->randomElement($titles),
                            'Description' => $faker->paragraph(),
                            'Statut' => $faker->randomElement($statuses),
                        ];
                    })->toArray()
                ];
            });

            // Pagination logic
            $page = $request->get('page', 1);
            $pagedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

            $pagination = new LengthAwarePaginator(
                $pagedItems,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json($pagination);
        }

}
