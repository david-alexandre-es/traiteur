<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LivraisonService
{
    private string $apiKey;
    private HttpClientInterface $httpClient;

    private float $bordeauxLat = 44.8378;
    private float $bordeauxLng = -0.5792;

    public function __construct(HttpClientInterface $httpClient, string $orsApiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $orsApiKey;
    }

    public function calculerDistanceKm(string $adresse, string $ville): float
    {
        // Géocoder l'adresse
        $response = $this->httpClient->request('GET', 'https://api.openrouteservice.org/geocode/search', [
            'headers' => ['Authorization' => $this->apiKey],
            'query' => [
                'text' => $adresse . ' ' . $ville . ' France',
                'size' => 1,
            ]
        ]);

        $data = $response->toArray();

        if (empty($data['features'])) {
            return 0;
        }

        $coords = $data['features'][0]['geometry']['coordinates'];
        $lng = $coords[0];
        $lat = $coords[1];

        // Calculer la distance routière
        $response = $this->httpClient->request('GET', 'https://api.openrouteservice.org/v2/directions/driving-car', [
            'headers' => ['Authorization' => $this->apiKey],
            'query' => [
                'start' => $this->bordeauxLng . ',' . $this->bordeauxLat,
                'end' => $lng . ',' . $lat,
            ]
        ]);

        $data = $response->toArray();

        if (empty($data['features'])) {
            return 0;
        }

        // La distance est dans summary->distance en mètres
        $distanceMetres = $data['features'][0]['properties']['summary']['distance'];
        return $distanceMetres / 1000;
    }

    public function calculerFraisLivraison(string $adresse, string $ville): float
    {
        $villeNormalisee = strtolower(trim($ville));

        if ($villeNormalisee === 'bordeaux') {
            return 5.0;
        }

        try {
            $distanceKm = $this->calculerDistanceKm($adresse, $ville);

            if ($distanceKm <= 0) {
                return 5.0;
            }

            return round(5.0 + ($distanceKm * 0.59), 2);
        } catch (\Exception $e) {
            return 5.0;
        }
    }
}