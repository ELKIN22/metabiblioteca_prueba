<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResearcherRequest;
use App\Http\Requests\UpdateResearcherRequest;
use App\Http\Resources\ResearcherCollection;
use App\Http\Resources\ResearcherResource;
use App\Models\Researcher;
use App\Models\ResearcherKeyword;
use App\Services\OrcidService;
use App\Exceptions\OrcidException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResearcherController extends Controller
{
    public function __construct(
        private readonly OrcidService $orcidService
    ) {}

    /**
     * POST /api/orcid/create
     * Crear un investigador desde ORCID
     */
    public function store(StoreResearcherRequest $request): JsonResponse
    {
        try {
            $orcid = $request->validated()['orcid'];

            // Verificar si ya existe
            if (Researcher::findByOrcid($orcid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este ORCID ya existe en la base de datos.'
                ], 409);
            }

            // Obtener datos de ORCID
            $orcidData = $this->orcidService->getOrcidData($orcid);

            DB::transaction(function () use ($orcidData) {
                $researcher = Researcher::create([
                    'orcid' => $orcidData->orcid,
                    'given_names' => $orcidData->givenNames,
                    'family_name' => $orcidData->familyName,
                    'primary_email' => $orcidData->primaryEmail,
                ]);

               
                foreach ($orcidData->keywords as $keyword) {
                    ResearcherKeyword::create([
                        'researcher_id' => $researcher->id,
                        'keyword' => $keyword,
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente.'
            ], 201);

        } catch (OrcidException $e) {
            if ($e->getCode() === 404) {
                return response()->json([
                    'success' => false,
                    'message' => 'ORCID no encontrado.'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar ORCID: ' . $e->getMessage()
            ], $e->getCode() ?: 500);

        } catch (\Exception $e) {
            Log::error('Error al crear investigador', [
                'orcid' => $request->input('orcid'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * PUT /api/orcid/update
     * Actualizar información del investigador
     */
    public function update(UpdateResearcherRequest $request): JsonResponse
    {
        try {
            $orcid = $request->input('orcid');
            
            if (!$orcid) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ORCID es obligatorio para actualizar.'
                ], 400);
            }

            $researcher = Researcher::findByOrcid($orcid);
            
            if (!$researcher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investigador no encontrado.'
                ], 404);
            }

            $validated = $request->validated();

            DB::transaction(function () use ($researcher, $validated) {
                // Actualizar datos del investigador (excepto ORCID)
                $researcher->update([
                    'given_names' => $validated['given_names'] ?? $researcher->given_names,
                    'family_name' => $validated['family_name'] ?? $researcher->family_name,
                    'primary_email' => $validated['primary_email'] ?? $researcher->primary_email,
                ]);

                // Actualizar keywords si se proporcionan
                if (isset($validated['keywords'])) {
                    // Eliminar keywords existentes
                    $researcher->keywords()->delete();
                    
                    // Crear nuevas keywords
                    foreach ($validated['keywords'] as $keyword) {
                        ResearcherKeyword::create([
                            'researcher_id' => $researcher->id,
                            'keyword' => $keyword,
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Investigador actualizado exitosamente.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar investigador', [
                'orcid' => $request->input('orcid'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * DELETE /api/orcid/delete/{orcid}
     * Eliminar investigador por ORCID
     */
    public function destroy(string $orcid): JsonResponse
    {
        try {
            if (empty($orcid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ORCID es obligatorio.'
                ], 400);
            }

            $researcher = Researcher::findByOrcid($orcid);
            
            if (!$researcher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investigador no encontrado.'
                ], 404);
            }

            // El cascade en la migración eliminará automáticamente las keywords
            $researcher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Investigador eliminado exitosamente.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar investigador', [
                'orcid' => $orcid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * GET /api/orcid/list
     * Listar investigadores con paginación
     */
    public function index(): JsonResponse
    {
        try {
            $researchers = Researcher::with('keywords')
                ->orderBy('created_at', 'desc')
                ->paginate(2); // Paginación de 2 en 2

            return response()->json([
                'success' => true,
                'data' => new ResearcherCollection($researchers)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al listar investigadores', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * GET /api/orcid/{orcid}
     * Ver detalle de un investigador
     */
    public function show(string $orcid): JsonResponse
    {
        try {
            if (empty($orcid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ORCID es obligatorio.'
                ], 400);
            }

            $researcher = Researcher::with('keywords')->where('orcid', $orcid)->first();
            
            if (!$researcher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investigador no encontrado.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new ResearcherResource($researcher)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalle del investigador', [
                'orcid' => $orcid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }
}
