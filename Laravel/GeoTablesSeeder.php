<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Geo\Country;
use App\Models\Geo\Region;

class GeoTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path() . '/seeders/GeoJSON/';

        $path_countries = $path . 'countries.json';
        $path_regions = $path . 'regions.json';

        if(file_exists($path_countries)) {
            $countries = json_decode(
                file_get_contents(
                    $path_countries
                ),
                true
            );
        }

        if(file_exists($path_regions)) {
            $regions = json_decode(
                file_get_contents(
                    $path_regions
                ),
                true
            );
        }

        if (!empty($countries)) {
            foreach ($countries as $country) {
                if (!Country::where('id', $country['id'])->exists()) {
                    $new_country = new Country;
                    $new_country->id = $country['id'];
                    $new_country->title = $country['title'];
                    $new_country->save();
                }
            }
        }

        if (!empty($regions)) {
            foreach ($regions as $region) {
                if (!Region::where('id', $region['id'])->exists()) {
                    $new_region = new Region;
                    $new_region->id = $region['id'];
                    $new_region->country_id = $region['country_id'];
                    $new_region->title = $region['title'];
                    $new_region->save();
                }
            }
        }
    }
}
