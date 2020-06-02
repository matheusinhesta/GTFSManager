<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CalendarDate;
use App\Models\FareRule;
use App\Models\Stop;
use App\Models\StopTime;
use App\Models\Trip;
use File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GtfsFileController extends Controller {

    public function generateGtfsFiles($agency_id){
        $agency = Agency::findOrFail($agency_id);

        $path = storage_path( 'app/Agency_'. $agency_id);

        $file_name = preg_replace('/ /', '_', $this->clearString($agency->name)) . '_gtfs.zip';
        $file_path = $path . '/' . $file_name;

        // Making Agency directory if not exists
        if(!File::exists($path))
            Storage::makeDirectory($path);

        // --------------- Making gtfs .txt files ---------------

        // --------------- Agency
        $agency_data  = 'agency_id,agency_name,agency_url,agency_timezone,agency_lang,agency_phone' . PHP_EOL;
        $agency_data .= $this->clearString($agency->id . ',' . $agency->name . ',' . $agency->url . ',' . $agency->timezone. ',' . $agency->lang. ',' . $agency->phone);

        Storage::put('Agency_'. $agency_id.'/files/agency.txt', $agency_data);


        // --------------- Calendar Dates
        $calendar_dates      = CalendarDate::where('agency_id', $agency_id)->get();
        $calendar_dates_data = 'service_id,date,exception_type' . PHP_EOL;

        foreach($calendar_dates as $calendar)
            $calendar_dates_data .= $this->clearString($calendar->service_id . ',' . date('Ymd', strtotime($calendar->date)) . ',' . $calendar->enum_key_exception_type+1) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/calendar_dates.txt', $calendar_dates_data);


        // --------------- Routes
        $routes      = $agency->routes;
        $routes_data = 'route_id,route_short_name,route_long_name,route_type' . PHP_EOL;

        foreach($routes as $route)
            $routes_data .= $this->clearString($route->id . ',' . $route->short_name . ',' . $route->long_name . ',' . $route->enum_key_type) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/routes.txt', $routes_data);


        // --------------- Fare Attributes
        $fares      = $agency->fare_attributes;
        $fares_data = 'fare_id,price,currency_type,payment_method,transfers,transfer_duration' . PHP_EOL;

        foreach($fares as $fare)
            $fares_data .= $this->clearString($fare->id . ',' . $fare->price . ',' . $fare->currency_type . ',' . (int)$fare->payment_method . ',' . $fare->enum_key_transfers . ',' . $fare->transfer_duration) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/fare_attributes.txt', $fares_data);


        // --------------- Fare Rules
        $rules      = FareRule::whereIn('fare_id', $fares->pluck('id'))->get();
        $rules_data = 'fare_id,route_id,origin_id, destination_id, contains_id' . PHP_EOL;

        foreach($rules as $rule)
            $rules_data .= $this->clearString($rule->fare_id . ',' . $rule->route_id . ',' . $rule->origin_id . ',' . $rule->destination_id . ',' . $rule->contains_id) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/fare_rules.txt', $rules_data);


        // --------------- Stops
        $stops = Stop::where('agency_id', $agency_id)->get();
        $stops_data = 'stop_id,stop_name,stop_lon,stop_lat,location_type,parent_station' . PHP_EOL;

        foreach($stops as $stop)
            $stops_data .= $this->clearString($stop->id . ',' . $stop->name . ',' . $stop->lon . ',' . $stop->lat . ',' . $stop->enum_key_location_type . ',' . $stop->parent_station) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/stops.txt', $stops_data);


        // --------------- Stops Times
        $stop_times = StopTime::whereIn('stop_id', $stops->pluck('id'))->orderBy('stop_id')->orderBy('stop_sequence')->get();
        $stop_times_data = 'trip_id,arrival_time,departure_time,stop_id,stop_sequence,timepoint' . PHP_EOL;

        foreach($stop_times as $stop_time)
            $stop_times_data .= $this->clearString($stop_time->trip_id . ',' . $stop_time->arrival_time . ',' . $stop_time->departure_time . ',' . $stop_time->stop_id . ',' . $stop_time->stop_sequence. ',' . (int)$stop_time->timepoint) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/stop_times.txt', $stop_times_data);

        // --------------- Trips
        $trips = Trip::whereIn('route_id', $agency->routes->pluck('id'))->orderBy('short_name')->get();
        $trips_data = 'route_id,service_id,trip_id,trip_headsign,direction_id,block_id' . PHP_EOL;

        foreach($trips as $trip)
            $trips_data .= $this->clearString($trip->route_id . ',' . $trip->service_id . ',' . $trip->id . ',' . $trip->headsign . ',' . $trip->enum_key_direction_id. ',' . $trip->block_id) . PHP_EOL;

        Storage::put('Agency_'. $agency_id.'/files/trips.txt', $trips_data);


        // Making .zip file
        $zip = new ZipArchive();

        if ($zip->open($file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $files = Storage::files('Agency_'. $agency_id . '/files');

            foreach ($files as $file)
                $zip->addFile($path . '/files/'. explode('/', $file)[2], explode('/', $file)[2]);

            $zip->close();
        }

        return Storage::download('Agency_'. $agency_id . '/' . $file_name);
    }


    private function clearString($str) {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);

        return $str;
    }

}
