<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait EnumManipulation {

    public static function getEnumValues($table, $column) {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();

        foreach(explode(',', $matches[1]) as $value){
            $v = trim( $value, "'" );
            array_push($enum, $v);
        }

        return $enum;
    }

}
