<?php

namespace App\Http\Controllers;

use TripIt;
use DateInterval;
use DateTimeImmutable;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use OAuthConsumerCredential;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth1\Client\Credentials\CredentialsException;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider() {
        return Socialite::with('tripit')->redirect();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request) {
        try {
            $user = Socialite::with('tripit')->user();

            $request->session()->put('user', $user);

            return redirect('/calculate');
        }
        catch (CredentialsException $e) {
            return redirect('/login');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculate(Request $request) {
        $user = $request->session()->get('user');

        if (!$user) return redirect('/login');

        $creds = new OAuthConsumerCredential(
            config('services.tripit.key'),
            config('services.tripit.secret'),
            $user->token,
            $user->tokenSecret
        );

        $tripit = new TripIt($creds);

        $past = $this->getTrips($tripit, true);
        $future = $this->getTrips($tripit, false);

        // Fix not being an array if only one record.
        $future['Trip'] = empty($future['Trip'][0]) ? [$future['Trip']] : $future['Trip'];

        $trips = collect(array_merge($past['Trip'], $future['Trip']))->sortBy('start_date');

        $now = $request->date ? new DateTimeImmutable($request->date) : new DateTimeImmutable();
        $since = $now->sub(new DateInterval('P180D'));

        $trips = $trips->filter(function ($trip) use ($since, $now) {
            $end = new DateTimeImmutable($trip['end_date']);
            $start = new DateTimeImmutable($trip['start_date']);

            return $end >= $since || $start >= $now;
        })->values();

        $debug = [];

        $in = $trips->reduce(function ($carry, $trip) use ($since, $now, $trips, &$debug) {
            $end = new DateTimeImmutable($trip['end_date']);
            $start = new DateTimeImmutable($trip['start_date']);

            // boundaries
            $start = max($since, $start); // if we were in schengen before 180 days ago, only count from 180 days ago
            $end = min($end, $now); // only count up to today...

            // calculate the days
            $days = $end->diff($start)->days;

            // if in schengen, add the days
            if ($this->inSchengen($trip)) {
                $diff = $days + 1; // inclusive
            }

            // unless it was in the middle of another schengen trip, deduct it
            else if ($this->isInTheMiddleOfAnotherTrip($trip, $trips)) {
                $diff = ($days - 1) * -1; // not inclusive
            }

            // otherwise, the trip doesn't use any days.
            else {
                $diff = 0;
            }

            // add debugging info
            $debug[] = [
                'start' => $start,
                'end' => $end,
                'days' => $days,
                'diff' => $diff,
            ];

            return $carry + $diff;
        }, 0);

        // Render the view
        $log = $this->getTripLog($trips, $debug);
        $remaining = 90 - $in;
        return view('calculate', compact('log', 'remaining', 'in', 'since', 'now'));
    }

    protected function isInTheMiddleOfAnotherTrip($trip, $trips) {
        $leave = new DateTimeImmutable($trip['start_date']);
        $return = new DateTimeImmutable($trip['end_date']);

        foreach ($trips as $trip) {
            $start = new DateTimeImmutable($trip['start_date']);
            $end = new DateTimeImmutable($trip['end_date']);

            if ($leave > $start && $return < $end) return true;
        }

        return false;
    }

    protected function getTripLog($trips, $debug)
    {
        $used = 0;
        $remaining = 90;
        $arr = [];

        foreach ($trips as $i => $trip) {
            $diff = $debug[$i]['diff'];

            $arrive = new DateTimeImmutable($trip['start_date']);
            $leave = new DateTimeImmutable($trip['end_date']);

            $arr[] = [
                'country' => $trip['primary_location'],
                'arrive' => $arrive,
                'arrive_same' => $arrive == $debug[$i]['start'],
                'leave' => $leave,
                'leave_same' => $leave == $debug[$i]['end'],
                'diff' => $debug[$i]['diff'],
                'remaining' => $remaining -= $diff,
            ];
        }

        return $arr;
    }

    protected function getTrips($tripit, $past) {
        $args = [
            'past' => $past ? 'true' : 'false',
            'format' => 'json',
            'page_size' => 100,
        ];
        $trips = $tripit->list_trip($args);

        return $trips;
    }

    protected function inSchengen($trip)
    {
        $cc = $trip['PrimaryLocationAddress']['country'];

        $schengens = [
            'BE',
            'CZ',
            'DK',
            'DE',
            'EE',
            'GR',
            'ES',
            'FR',
            'IT',
            'LV',
            'LT',
            'LU',
            'HU',
            'MT',
            'NL',
            'AT',
            'PL',
            'PT',
            'SI',
            'SK',
            'FI',
            'SE',
            'IS',
            'NO',
            'CH',
            'AT',
        ];

        return in_array($cc, $schengens);
    }
}
