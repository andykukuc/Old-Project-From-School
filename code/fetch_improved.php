<?php
/*
 * Written By: Andy 'shad0wMaster' Kukuc
 * Improved Version with Modern PHP Best Practices
 */

declare(strict_types=1);

// Load environment variables
require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__).'/');
$dotenv->load();

class BeaconDataFetcher {
    private const WEEK_IN_MS = 604800000;
    private const MPH_CONVERSION = 1.609344;
    
    private array $beaconIdentifiers = [
        'BLUE' => '9b863659fd9527ba6b18e62262a4ae11',
        'GREEN' => 'd784a2665ab1f35079bcec89588d5d1d'
    ];

    private string $username;
    private string $password;
    private int $currentTime;
    private int $convertedTime;

    public function __construct(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
        $this->currentTime = time() * 1000;
        $this->convertedTime = $this->currentTime - self::WEEK_IN_MS;
    }

    private function getApiUrl(string $identifier): string {
        return "https://cloud.estimote.com/v3/lte/device_events?identifier={$identifier}&since={$this->convertedTime}";
    }

    private function createContext(): array {
        return [
            'http' => [
                'method' => "GET",
                'header' => "Authorization: Basic " . base64_encode("{$this->username}:{$this->password}")
            ]
        ];
    }

    public function fetchBeaconData(): array {
        $context = stream_context_create($this->createContext());
        
        $beaconData = [];
        foreach ($this->beaconIdentifiers as $color => $id) {
            $url = $this->getApiUrl($id);
            $response = file_get_contents($url, false, $context);
            if ($response === false) {
                throw new RuntimeException("Failed to fetch data for $color beacon");
            }
            $beaconData[$color] = json_decode($response, true);
        }
        
        return $beaconData;
    }

    public function getBeaconIdentification(array $data): string {
        foreach ($data['data'] as $event) {
            $id = $event['device_identifier'];
            if ($id === $this->beaconIdentifiers['BLUE']) {
                return "Truck 1 Blue";
            }
            if ($id === $this->beaconIdentifiers['GREEN']) {
                return "Truck 2 Green";
            }
        }
        return "Unknown Beacon";
    }

    public function getLatestTemperature(array $data): float {
        if (empty($data['data'])) {
            throw new RuntimeException("No temperature data available");
        }
        
        $celsius = round($data['data'][0]['payload']['temperature'], 1);
        return round(($celsius * 9/5) + 32, 1); // Convert to Fahrenheit
    }

    public function getLatestGpsData(array $data): array {
        if (empty($data['data'])) {
            throw new RuntimeException("No GPS data available");
        }

        $latest = $data['data'][0]['payload'];
        return [
            'speed' => round($latest['speed'] / self::MPH_CONVERSION, 1), // Convert to MPH
            'latitude' => round($latest['lat'], 5),
            'longitude' => round($latest['long'], 5)
        ];
    }

    public function getDataSize(array $data): int {
        return count($data['data'] ?? []);
    }
}

// Usage
try {
    $fetcher = new BeaconDataFetcher($_ENV['username'], $_ENV['password']);
    $beaconData = $fetcher->fetchBeaconData();

    // Process Blue Beacon
    $blueBeaconId = $fetcher->getBeaconIdentification($beaconData['BLUE']);
    $blueTemp = $fetcher->getLatestTemperature($beaconData['BLUE']);
    $blueGps = $fetcher->getLatestGpsData($beaconData['BLUE']);

    // Process Green Beacon
    $greenBeaconId = $fetcher->getBeaconIdentification($beaconData['GREEN']);
    $greenTemp = $fetcher->getLatestTemperature($beaconData['GREEN']);
    $greenGps = $fetcher->getLatestGpsData($beaconData['GREEN']);

    // Output data (format as needed for your frontend)
    $output = [
        'blue' => [
            'id' => $blueBeaconId,
            'temperature' => $blueTemp . ' ℉',
            'speed' => $blueGps['speed'] . ' mph',
            'location' => [
                'lat' => $blueGps['latitude'],
                'long' => $blueGps['longitude']
            ]
        ],
        'green' => [
            'id' => $greenBeaconId,
            'temperature' => $greenTemp . ' ℉',
            'speed' => $greenGps['speed'] . ' mph',
            'location' => [
                'lat' => $greenGps['latitude'],
                'long' => $greenGps['longitude']
            ]
        ]
    ];

    // You can either return JSON or use the data in your HTML
    // header('Content-Type: application/json');
    // echo json_encode($output);
    
} catch (Exception $e) {
    // Log error and return appropriate response
    error_log($e->getMessage());
    http_response_code(500);
    echo "An error occurred while fetching beacon data";
}
