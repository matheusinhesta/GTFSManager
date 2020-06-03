<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTripDescriptor;
use App\Models\Agency;
use App\Models\TripDescriptor;
use App\Models\VehiclePosition;
use Illuminate\Console\Command;

class FakeRealtimePosition extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:realtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simuling a fake position vehicle in a trip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        echo "Starting...\n";

        $agency = Agency::find(1);

        $agency->users()->where('type_id', 2)->first();

        $trip_descriptor = TripDescriptor::findOrFail(1);
        $trip_descriptor->update(['trip_status' => 'started']);

        // 1 -- TERMINAL
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785451',
            'lon'                => '-46.566383'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);

        // 2
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785182',
            'lon'                => '-46.568239'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);

        // 3
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.784982',
            'lon'                => '-46.569462'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        echo "Chegando na Urca\n";

        sleep(30);

        // 4
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785590',
            'lon'                => '-46.571511'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);


        // 5 -- URCA
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785788',
            'lon'                => '-46.572749'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        echo "Urca\n";

        sleep(30);

        // 6
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785761',
            'lon'                => '-46.573970'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);


        // 7
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785636',
            'lon'                => '-46.575858'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);

        // 8
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785457',
            'lon'                => '-46.578465'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        echo "Chegando na Pitágoras\n";

        sleep(30);

        // 9
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785295',
            'lon'                => '-46.580789'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);

        // 10 -- Pitágoras
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785190',
            'lon'                => '-46.582972'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        echo "Pitágoras\n";

        sleep(30);


        // 11
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785133',
            'lon'                => '-46.583770'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);

        // 12
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785083',
            'lon'                => '-46.584457'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);


        // 13
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.785003',
            'lon'                => '-46.585476'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        echo "Chegando no terminal do Parque\n";

        sleep(30);


        // 14
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.784913',
            'lon'                => '-46.586383'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);

        sleep(30);


        // 15 -- Terminal Parque
        $vehicle_position = VehiclePosition::create([
            'trip_descriptor_id' => $trip_descriptor->id,
            'vehicle_id'         => $trip_descriptor->trip_updates->first()->vehicle_id,
            'lat'                => '-21.784888',
            'lon'                => '-46.587649'
        ]);

        UpdateTripDescriptor::dispatch($trip_descriptor, $vehicle_position);
        echo "Terminal do Parque\n";

        echo "Finish!";
    }
}
