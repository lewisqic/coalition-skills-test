@extends('layouts.index')

@section('content')

<h1 class="my-5 text-center">Let's get you signed up!</h1>

<div class="row">
    <div class="col-sm-6">

        <form action="{{ url('sign-up') }}" method="post" class="validate labels-right" id="sign_up_form">
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <input type="hidden" name="subdomain-validation" value="robo">
            <input type="hidden" name="init_ts" value="{{ time() }}">
            {!! Html::hiddenInput(['method' => 'post']) !!}


            <div class="form-group row mb-0 subdomain-input">
                <label class="col-form-label col-sm-4">Domain Name</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon">https://</span>
                        <input type="text" name="subdomain" class="form-control" placeholder="domainname" value="{{ old('subdomain') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="24" autofocus>
                        <span class="input-group-addon">.teller.com</span>
                    </div>
                    <small class="subdomain-validation-error text-danger"></small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-8 ml-auto">
                    <small class="text-primary">
                        Enter the name (or business name) that you want to be used for your personalized Teller<sup>&reg;</sup> domain name. <a href="#" tabindex="-1" data-toggle="tooltip" data-placement="bottom" data-container="body" data-title="more text here that explains stuff."><i class="fa fa-info-circle"></i></a>
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-4">First Name</label>
                <div class="col-sm-8">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" data-fv-notempty="true">
                </div>
            </div>

            <div class="form-group row display-none">
                <label class="col-form-label col-sm-4">Middle Name</label>
                <div class="col-sm-8">
                    <input type="text" name="middle_name" class="form-control" placeholder="Middle Name" value="" data-fv-notempty="true">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-4">Last Name</label>
                <div class="col-sm-8">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" data-fv-notempty="true">
                </div>
            </div>

            <div class="form-group row display-none">
                <label class="col-form-label col-sm-4">Address</label>
                <div class="col-sm-8">
                    <input type="text" name="address" class="form-control" placeholder="Address" value="adddresss" data-fv-notempty="true">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-4">Email</label>
                <div class="col-sm-8">
                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-stringlength-max="64" data-fv-emailaddress="true">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-4">Password</label>
                <div class="col-sm-8">
                    <input type="password" name="password" class="form-control" placeholder="Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-8 ml-auto">
                    <button type="submit" class="btn btn-lg btn-success btn-block" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Complete Sign Up</button>
                </div>
            </div>

        </form>


    </div>
    <div class="col-sm-5 ml-auto">

        <div class="card">
            <div class="card-header">
                <h3 class="text-primary mt-0 mb-3">
                    Plan: <strong>{{ $plan->name }}</strong><br>
                    <small class="text-muted">{{ Format::currency($plan->price_month) }}</strong>/mo <em>or</em> {{ Format::currency($plan->price_year) }}</strong>/yr</small>
                </h3>
                <h4>All plans include a <span class="text-success">14-Day FREE Trial</span></h4>
                <h5>Risk free, 100% satisfaction guaranteed - No credit card is required up front; if you don't like it, you don't pay a dime!</h5>
            </div>
        </div>



        <h3 class="text-success mt-4">You're Almost There!</h3>
        <h5 class="mt-4">
            Begin accepting one time and/or recurring online payments within the next <em>5 minutes!</em>
        </h5>

        <ul class="mt-4 pl-0" style="list-style: none;">
            <li><i class="fa fa-check text-success"></i> No credit card required <small class="text-muted">(during free trial)</small></li>
            <li><i class="fa fa-check text-success"></i> No previous merchant account needed</li>
            <li><i class="fa fa-check text-success"></i> No credit check or financial verification</li>
            <li><i class="fa fa-check text-success"></i> No setup fees, no hidden costs, no surprises</li>
            <li><i class="fa fa-check text-success"></i> Cancel any time, for any reason, no questions asked!</li>
        </ul>

    </div>
</div>



@endsection

@push('scripts')
<script src="{{ url('assets/js/modules/sign-up.js') }}"></script>
@endpush