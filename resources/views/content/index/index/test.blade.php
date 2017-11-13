@extends('layouts.scan')

@section('content')


    @if ( $organization )

        <div class="wrapper">


            <img src="{{ url('uploads/organization_logos/' . $organization->logo) }}" style="height: 40px; margin-top: -15px;">
            <h2 class="mb-5 display-inline-block ml-4">
                {{ $organization->long_name }}
            </h2>


            <iframe src="{{ url('test?referrer=' . (isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '') . '&organization_id=' . $organization->id . '&support_email=' . urlencode($organization->support_email) . '&scan_page_content=' . urlencode($organization->scan_page_content)) }}" frameborder="0" width="100%" height="2400px"></iframe>

        </div>

    @else

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i> Invalid Organization Request
        </div>

    @endif

@endsection