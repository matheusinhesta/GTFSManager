<?php

namespace App\Jobs;

use App\Models\Stop;
use App\Models\StopTime;
use App\Models\TripDescriptor;
use App\Models\VehiclePosition;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTripDescriptor implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trip_descriptor, $vehicle_position;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TripDescriptor $trip_descriptor, VehiclePosition $vehicle_position) {
        $this->trip_descriptor = $trip_descriptor;
        $this->vehicle_position = $vehicle_position;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $trip             = $this->trip_descriptor->trip;
        $stop_time_update = $this->trip_descriptor->trip_updates()->get()->last()->stop_time_update;

        // Dentro do horario na mesma stop_id
        $stop_time = StopTime::where('stop_id', $stop_time_update->stop_id)->where('trip_id', $trip->id)
                            ->where('arrival_time', '>=', Carbon::make($this->vehicle_position->created_at)->subMinute(1)->format('H:i'))->where('arrival_time', '<=', Carbon::make($this->vehicle_position->created_at)->addMinutes(1)->format('H:i:s'))
                            ->orderBy('stop_sequence')->first();

        // Dentro do horario em outra stop_id
        if(empty($stop_time)){
            $stop_time = StopTime::where('trip_id', $trip->id)
                        ->where('arrival_time', '>=', Carbon::make($this->vehicle_position->created_at)->subMinute(1)->format('H:i'))->where('arrival_time', '<=', Carbon::make($this->vehicle_position->created_at)->addMinutes(1)->format('H:i:s'))
                        ->orderBy('stop_sequence')->first();
        }

        $calc_in_kilometers = 6371;
        $calc_in_miles      = 3959;

        // Parada mais próxima a posição atual do veículo
        $nearby_stop = Stop::select(\DB::raw('*, ('. $calc_in_kilometers .' * acos(
                                            cos( radians('. $this->vehicle_position->lat .') )
                                            * cos( radians( lat ) )
                                            * cos( radians( lon ) - radians('. $this->vehicle_position->lon .') )
                                            + sin( radians('. $this->vehicle_position->lat .') )
                                            * sin( radians( lat ) )
                                        ) ) AS distance'))->where('agency_id', $stop_time->stop->agency_id)
                                    ->having('distance', '<', 25)
                                    ->orderBy('distance')
                                    ->first();

        // dentro do horario e parada
        if($nearby_stop->id == $stop_time->stop_id){

            if($stop_time->stop_sequence != $stop_time_update->stop_sequence)
                $stop_time_update->stop_sequence = $stop_time->stop_sequence;

            if($stop_time->stop_id != $stop_time_update->stop_id){
                $stop_time_update->stop_sequence = $stop_time->stop_sequence;
                $stop_time_update->stop_id       = $stop_time->stop_id;
            }

        } else { // Atrasado ou adiantado
            $stop_time_update->stop_id = $nearby_stop->id; // Posiciona para a parada atual
        }

        $stop_time_update->save();

    }
}
