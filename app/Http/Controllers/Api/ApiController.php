<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function holidayAndAir(Request $request)
    {
        $json = Cache::remember('holday_and_air', 60, function () {
            $json  = [
                'holiday' => null,
                'air'     => null
            ];

            $client = new \GuzzleHttp\Client();

            \GuzzleHttp\Promise\settle([
                $client->getAsync('https://calendarific.com/api/v2/holidays', [
                    'query' => [
                        'api_key' => config('app.calendarific_api_key'),
                        'country' => 'hr',
                        'year' => date('Y'),
                        'type' => 'national,religious'
                    ]
                    ])->then(function ($res) use (&$json) {
                        foreach (json_decode($res->getBody())->response->holidays as $holiday) {
                            $date = $holiday->date->iso;

                            if ($date > date('Y-m-d')) {
                                $json['holiday'] = [
                                    'name' => $holiday->name,
                                    'date' => date("j M, Y", strtotime($date))
                                ];

                                break;
                            }
                        }

                        return false;
                    }
                ),
                $client->getAsync('https://api.openaq.org/v1/latest', [
                    'query' => [
                        'city' => 'Primorsko-goranska'
                    ]
                    ])->then(function ($res) use (&$json) {
                        $json['air'] = json_decode($res->getBody())->results[0]->measurements;

                        return false;
                    }
                ),
            ])->wait();

            return $json;
        });

        return response()->json($json, 200);
    }
}