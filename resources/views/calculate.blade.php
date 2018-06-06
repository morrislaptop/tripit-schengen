@extends('layouts.app')

@section('content')
    <div class="subtitle m-b-md">
        You have {{ $remaining }} days remaining as of {{ $now->format('d M Y') }}
    </div>

    <form class="m-b-md">
        <input type="date" name="date" value="{{ $now->format('Y-m-d') }}" />
        <input type="submit" />
    </form>

    <table cellpadding="8" cellspacing="0" class="m-b-md">
        <tr>
            <th align="left">Country</th>
            <th>Arrive</th>
            <th>Leave</th>
            <th>Diff</th>
            <th>Remaining</th>
        </tr>
        <tr>
            <td align="left"><em>From {{ $since->format('d M Y') }}</em></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><em>90</em></td>
        </tr>
        @foreach ($log as $trip)
            <tr>
                <td align="left">{{ $trip['country'] }}</td>
                <td style="font-style: {{ $trip['arrive_same'] ? 'normal' : 'italic' }};">{{ $trip['arrive']->format('d M Y') }}</td>
                <td style="font-style: {{ $trip['leave_same'] ? 'normal' : 'italic' }};">{{ $trip['leave']->format('d M Y') }}</td>
                <td>{{ $trip['diff'] }}</td>
                <td>{{ $trip['remaining'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection
