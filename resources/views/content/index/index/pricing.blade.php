@extends('layouts.index')

@section('content')

<h1>Pricing</h1>

<div class="row">
    <div class="col-sm-4">
        <h3>Basic</h3>
        <h4>
            {{ Format::currency(9.95) }}/month<br>
            <small>(or {{ Format::currency(99.95) }}/year)</small>
        </h4>
        <a href="{{ url('sign-up/1') }}" class="btn btn-success">SIGN UP <i class="fa fa-angle-right"></i></a>
    </div>
    <div class="col-sm-4">
        <h3>Standard</h3>
        <h4>
            {{ Format::currency(19.95) }}/month<br>
            <small>(or {{ Format::currency(199.95) }}/year)</small>
        </h4>
        <a href="{{ url('sign-up/2') }}" class="btn btn-success">SIGN UP <i class="fa fa-angle-right"></i></a>
    </div>
    <div class="col-sm-4">
        <h3>Pro</h3>
        <h4>
            {{ Format::currency(29.95) }}/month<br>
            <small>(or {{ Format::currency(299.95) }}/year)</small>
        </h4>
        <a href="{{ url('sign-up/3') }}" class="btn btn-success">SIGN UP <i class="fa fa-angle-right"></i></a>
    </div>
</div>

@endsection