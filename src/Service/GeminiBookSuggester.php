<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\LivreRepository;

class GeminiBookSuggester
{
    private $client;
    private $apiKey;
    private $livreRepository;

    public function __construct(
        HttpClientInterface $client, 
        LivreRepository $livreRepository,
        string $openRouterApiKey
    ) {
        $this->client = $client;
        $this->livreRepository = $livreRepository;
        $this->apiKey = $openRouterApiKey;
    }

    public function getBookSuggestions(string $query): array
    {
        // First get all books from database
        $allBooks = $this->livreRepository->findAll();
        $bookTitles = array_map(fn($book) => $book->getTitre(), $allBooks);
        
        // Then query the AI
        $response = $this->client->request('POST', 'https://openrouter.ai/api/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'mistralai/mistral-7b-instruct',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are a book suggestion assistant. Here are available books: ' . 
                                    implode(', ', $bookTitles) . 
                                    '. When user searches, respond with JSON array of exact matching book titles only.'
                    ],
                    ['role' => 'user', 'content' => $query],
                ],
                'temperature' => 0.7,
                'response_format' => ['type' => 'json_object']
            ]
        ]);

        $data = $response->toArray(false);
        
        if (!isset($data['choices'][0]['message']['content'])) {
            return [];
        }

        // Parse the JSON response
        $suggestions = json_decode($data['choices'][0]['message']['content'], true);
        
        // Find matching books in database
        $results = [];
        foreach ($allBooks as $book) {
            if (in_array($book->getTitre(), $suggestions)) {
                $results[] = $book;
            }
        }

        return $results;
    }
}