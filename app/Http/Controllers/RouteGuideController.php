<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Destination;

class RouteGuideController extends Controller
{
    // Show page
    public function index()
    {
        return view('route_guide', [
            'hotels' => Hotel::all(),
            'destinations' => Destination::all(),
        ]);
    }

    // Calculate route + transport
    public function calculate(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);
        $destination = Destination::findOrFail($request->destination_id);

        // ----------------------------
        // Distance calculation
        // ----------------------------
        $distance = $this->haversine(
            $hotel->latitude,
            $hotel->longitude,
            $destination->latitude,
            $destination->longitude
        );

        // ----------------------------
        // Transport data
        // ----------------------------
        $result = $this->calculateTransport($distance);
        $result['distance'] = round($distance, 2);

        // ----------------------------
        // Recommendation logic
        // ----------------------------
        $result['cheapest'] = collect($result)
            ->only(['car', 'bus', 'rickshaw', 'walk', 'cycle'])
            ->sortBy('fare')
            ->keys()
            ->first();

        $result['fastest'] = collect($result)
            ->only(['car', 'bus', 'rickshaw', 'walk', 'cycle'])
            ->sortBy('time')
            ->keys()
            ->first();

        // ----------------------------
        // Return view
        // ----------------------------
        return view('route_guide', [
            'hotels' => Hotel::all(),
            'destinations' => Destination::all(),

            'selected_hotel' => $hotel,
            'selected_destination' => $destination,

            'result' => $result
        ]);
    }

    // ----------------------------
    // Transport engine
    // ----------------------------
    private function calculateTransport($distance)
    {
        return [
            'car' => [
                'time' => round($distance / 40, 2),
                'fare' => round($distance * 251),
            ],
            'bus' => [
                'time' => round($distance / 30, 2),
                'fare' => round($distance * 51),
            ],
            'rickshaw' => [
                'time' => round($distance / 10, 2),
                'fare' => round($distance * 121),
            ],
            'walk' => [
                'time' => round($distance / 5, 2),
                'fare' => 0,
            ],
            'cycle' => [
                'time' => round($distance / 15, 2),
                'fare' => 0,
            ],
        ];
    }

    // ----------------------------
    // Haversine formula
    // ----------------------------
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
             cos(deg2rad($lat1)) *
             cos(deg2rad($lat2)) *
             sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}