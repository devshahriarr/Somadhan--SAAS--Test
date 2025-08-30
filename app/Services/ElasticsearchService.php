<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        try {
            $this->client = ClientBuilder::create()
                ->setHosts([config('elasticsearch.host')])
                ->setBasicAuthentication(config('elasticsearch.username'), config('elasticsearch.password'))
                ->setSSLVerification(config('elasticsearch.ssl_verification'))
                ->build();
            Log::info('Elasticsearch client initialized successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to initialize Elasticsearch client: '.$e->getMessage());
            throw $e;
        }
    }

    public function createIndex($index)
    {
        try {
            Log::info("Checking if index {$index} exists.");
            $existsResponse = $this->client->indices()->exists(['index' => $index]);
            $exists = $existsResponse->getStatusCode() !== 404;
            Log::info("Index {$index} exists: ".($exists ? 'true' : 'false'));
            if ($exists) {
                Log::info("Deleting existing index {$index}.");
                $this->client->indices()->delete([
                    'index' => $index,
                    'client' => ['timeout' => 60],
                ]);
            } else {
                Log::info("Index {$index} does not exist, no need to delete.");
            }
            $payload = [
                'index' => $index,
                'body' => [
                    'mappings' => [
                        'properties' => [
                            'name' => ['type' => 'text'],
                            'description' => ['type' => 'text'],
                            'category_id' => ['type' => 'integer'],
                            'unit' => ['type' => 'text'],
                            'status' => ['type' => 'text'],
                            'variations' => [
                                'type' => 'nested',
                                'properties' => [
                                    'variation_id' => ['type' => 'integer'],
                                    'variation_name' => ['type' => 'text'],
                                    'product_id' => ['type' => 'integer'],
                                    'barcode' => ['type' => 'keyword'],
                                    'cost_price' => ['type' => 'float'],
                                    'b2b_price' => ['type' => 'float'],
                                    'b2c_price' => ['type' => 'float'],
                                    'size_id' => ['type' => 'integer'],
                                    'color_id' => ['type' => 'integer'],
                                    'size' => ['type' => 'text'],
                                    'color' => ['type' => 'text'],
                                    'stock_quantity' => ['type' => 'integer'],
                                    'status' => ['type' => 'text'],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            Log::info("Creating index {$index} with payload: ".json_encode($payload, JSON_PRETTY_PRINT));
            $response = $this->client->indices()->create($payload);
            Log::info("Index {$index} created successfully: ".json_encode($response->asArray()));

            return $response;
        } catch (\Exception $e) {
            Log::error("Failed to create index {$index}: ".$e->getMessage()."\nStack trace: ".$e->getTraceAsString());
            throw $e;
        }
    }

    public function index($index, $id, $data)
    {
        try {
            Log::info("Indexing document ID {$id} in index {$index}.");
            $response = $this->client->index([
                'index' => $index,
                'id' => $id,
                'body' => $data,
                'refresh' => true,
            ]);
            Log::info("Indexed document ID {$id} successfully: ".json_encode($response));

            return $response;
        } catch (\Exception $e) {
            Log::error("Failed to index document ID {$id} in {$index}: ".$e->getMessage());
            throw $e;
        }
    }

    public function search($index, $query)
    {
        try {
            if (empty($query)) {
                Log::info("Empty query provided for index {$index}, returning empty results.");

                return ['hits' => ['total' => ['value' => 0], 'hits' => []]];
            }

            Log::info("Searching index {$index} with query: {$query}");

            return $this->client->search([
                'index' => $index,
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [
                                    'multi_match' => [
                                        'query' => $query,
                                        'fields' => ['name^3', 'description^2', 'unit', 'status'],
                                        'fuzziness' => 'AUTO',
                                        'prefix_length' => 2,
                                    ],
                                ],
                                [
                                    'nested' => [
                                        'path' => 'variations',
                                        'query' => [
                                            'multi_match' => [
                                                'query' => $query,
                                                'fields' => ['variations.variation_name^2', 'variations.barcode', 'variations.status'],
                                                'fuzziness' => 'AUTO',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'minimum_should_match' => 1,
                        ],
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Search failed in index {$index}: ".$e->getMessage());
            throw $e;
        }
    }
}
