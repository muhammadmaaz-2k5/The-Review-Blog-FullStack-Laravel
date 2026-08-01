<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
    }

    /**
     * Get details for a Movie.
     */
    public function getMovieDetails(int $tmdbId): ?array
    {
        return $this->request("/movie/{$tmdbId}");
    }

    /**
     * Get details for a TV Show.
     */
    public function getTvDetails(int $tmdbId): ?array
    {
        return $this->request("/tv/{$tmdbId}");
    }

    /**
     * Get details for a TV Episode.
     */
    public function getEpisodeDetails(int $tvId, int $season, int $episode): ?array
    {
        return $this->request("/tv/{$tvId}/season/{$season}/episode/{$episode}");
    }

    /**
     * Get details for a TV Season.
     */
    public function getTvSeasonDetails(int $tvId, int $season): ?array
    {
        return $this->request("/tv/{$tvId}/season/{$season}");
    }

    /**
     * Search for Movie/TV.
     */
    public function search(string $query, string $type = 'multi'): array
    {
        return $this->request("/search/{$type}", ['query' => $query])['results'] ?? [];
    }

    /**
     * Make a request to TMDB API.
     */
    protected function request(string $endpoint, array $params = []): ?array
    {
        $params['api_key'] = $this->apiKey;

        try {
            $response = Http::withoutVerifying()->get($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("TMDB API Error: " . $response->body() . " | Status: " . $response->status() . " | URL: " . $this->baseUrl . $endpoint);
        } catch (\Exception $e) {
            Log::error("TMDB Request Exception: " . $e->getMessage());
        }

        return null;
    }
}
