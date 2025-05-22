@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">Welcome to Mock Portal</h1>
            <p class="lead">This portal demonstrates SSO integration with the Student Portal system.</p>
            
            <div class="mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Student Portal SSO</h5>
                        <p class="card-text">Click below to log in using your Student Portal credentials.</p>
                        <a href="{{ route('sso.login.form') }}" class="btn btn-primary">
                            Login with Student Portal
                        </a>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger mt-4">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
