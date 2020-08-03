@extends('layouts.default')

@section('title')
Select Beverage
@stop
@section('content')
<main role="main" class="inner cover">
    <h1 class="cover-heading"></h1>
    <p class="lead selection-header">Select Your Beverage and tell us how many you had.</p>
    <form id="bvg-submit-data" method="POST" action="/api/v1/beverages/check">
        @csrf
        <div id="beverages-options">
            <div class="error d-none" id="server-error"></div>
        </div>
        <div id="beverages-options-results">
            <div class="success"></div>
            <div class="re-attempt d-none">
                <br><a href="" class="btn btn-primary">Check One more time</a></div>
        </div>
        <div style="text-align:center;">
            <button id="submitbutton" type="submit" class="btn btn-primary d-none">Submit</button>
        </div>
    </form>
</main>
@stop