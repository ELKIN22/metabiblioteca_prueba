<?php

namespace App\Services;

use App\DTOs\OrcidData;
use App\Exceptions\OrcidException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrcidService
{
    private const ORCID_API_URL = 'https://pub.orcid.org/v3.0/';

    /**
     * Obtiene información de un perfil ORCID
     * 
     * @param string $orcid El identificador ORCID (ej: 0000-0003-3580-8766)
     * @return OrcidData
     * @throws OrcidException
     */
    public function getOrcidData(string $orcid): OrcidData
    {
        try {
            $response = Http::acceptJson()
                ->timeout(30)
                ->get(self::ORCID_API_URL . $orcid);

            if (!$response->successful()) {
                $this->handleApiError($response->status(), $orcid);
            }

            $data = $response->json();
            
            return $this->parseOrcidData($data, $orcid);

        } catch (OrcidException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error inesperado al consultar ORCID', [
                'orcid' => $orcid,
                'error' => $e->getMessage()
            ]);
            
            throw new OrcidException(
                'Error de conexión con el servicio ORCID: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Maneja errores de la API
     */
    private function handleApiError(int $statusCode, string $orcid): void
    {
        switch ($statusCode) {
            case 404:
                throw new OrcidException("ORCID no encontrado: {$orcid}", 404);
            case 429:
                throw new OrcidException("Demasiadas solicitudes a la API de ORCID", 429);
            case 500:
            case 502:
            case 503:
                throw new OrcidException("Servicio ORCID no disponible temporalmente", $statusCode);
            default:
                throw new OrcidException("Error del servicio ORCID (HTTP {$statusCode})", $statusCode);
        }
    }

    /**
     * Parsea los datos de la respuesta de ORCID
     */
    private function parseOrcidData(array $data, string $orcid): OrcidData
    {
        // Extraer nombres
        $givenNames = $data['person']['name']['given-names']['value'] ?? null;
        $familyName = $data['person']['name']['family-name']['value'] ?? null;

        // Extraer keywords
        $keywords = [];
        if (isset($data['person']['keywords']['keyword'])) {
            foreach ($data['person']['keywords']['keyword'] as $keyword) {
                if (isset($keyword['content'])) {
                    $keywords[] = $keyword['content'];
                }
            }
        }

        // Extraer email principal
        $primaryEmail = null;
        if (isset($data['person']['emails']['email'])) {
            foreach ($data['person']['emails']['email'] as $email) {
                if (isset($email['primary']) && $email['primary'] === true) {
                    $primaryEmail = $email['email'];
                    break;
                }
            }
        }

        return new OrcidData(
            orcid: $orcid,
            givenNames: $givenNames,
            familyName: $familyName,
            keywords: $keywords,
            primaryEmail: $primaryEmail
        );
    }
}
