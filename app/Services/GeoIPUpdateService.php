<?php


namespace App\Services;


use App\Models\GeoIpBlock;
use App\Models\GeoIpLocation;
use Cache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GeoIPUpdateService
{
    private string $edition = 'GeoLite2-Country-CSV';

    private string $suffix = 'zip';

    private string $baseUrl = 'https://download.maxmind.com/app/geoip_download';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'query' => [
                'edition_id' => $this->edition,
                'license_key' => config('services.maxmind.key'),
                'suffix' => $this->suffix
            ],
        ]);
    }

    public function shouldUpdate(): bool
    {
        try {
            $latestVersion = $this->getLatestVersion();
        } catch (GuzzleException $e) {
            \Log::error($e);
            die();
        }

        $currentVersion = Cache::get('current_geoip_database_version');

        if($currentVersion < $latestVersion) {
            Cache::set('current_geoip_database_version', $latestVersion);
            return true;
        }

        return false;
    }

    /**
     * Downloads the latest versions of the GeoIP databases to the disk
     */
    public function updateFiles()
    {
        Storage::put('maxmind.zip', $this->client->get('')->getBody());

        $zip = new ZipArchive;
        $open = $zip->open(Storage::path('maxmind.zip'));
        if ($open) {
            $zip->extractTo(Storage::path(''));
            $zip->close();
        } else {
            \Log::error("Failed opening zip, error: {$open}");
            die();
        }
    }

    /**
     * Returns the last date that the database was updated
     *
     * @return Carbon
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLatestVersion(): int
    {
        $request = $this->client->head('');

        $lastModified = $request->getHeaderLine('last-modified');

        return Carbon::create($lastModified)->format('Ymd');
    }

    public function importIpBlocks(): void
    {
        $latestVersion = Cache::get('current_geoip_database_version');

        $paths = [
            $this->edition . '_' . $latestVersion . '/GeoLite2-Country-Blocks-IPv4.csv',
            $this->edition . '_' . $latestVersion . '/GeoLite2-Country-Blocks-IPv6.csv',
        ];

        foreach ($paths as $path) {
            foreach (parse_csv($path) as $block) {
                GeoIpBlock::create([
                    'cidr' => $block['network'],
                    'geoname_id' => ($block['geoname_id'] === "") ? null : $block['geoname_id'],
                ]);
            }
        }
    }

    public function importGeoLocations(): void
    {
        $latestVersion = Cache::get('current_geoip_database_version');

        $path = $this->edition . '_' . $latestVersion . '/GeoLite2-Country-Locations-en.csv';

        foreach (parse_csv($path) as $location) {
            GeoIpLocation::create([
                'geoname_id' => $location['geoname_id'],
                'continent_name' => $location['continent_name'],
                'country_name' => $location['country_name'],
            ]);
        }
    }

    public function removeFiles(): void
    {
        $latestVersion = Cache::get('current_geoip_database_version');

        $dirname = Storage::path($this->edition . '_' . $latestVersion);
        array_map('unlink', glob("$dirname/*.*"));
        @rmdir($dirname);
        @unlink(Storage::path('maxmind.zip'));
    }
}
