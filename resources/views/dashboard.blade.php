@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Student Portal Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome {{ $userData['name'] ?? 'User' }}!</h4>
                    <p>You have successfully logged in via Student Portal SSO.</p>
                    
                    <div class="mt-4">
                        <h5>Your Student Portal Details:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Name:</strong> {{ $userData['name'] ?? 'N/A' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Email:</strong> {{ $userData['email'] ?? 'N/A' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Matric Number:</strong> {{ $userData['matric_number'] ?? 'N/A' }}
                            </li>
                            @if(isset($userData['additional_data']))
                                <li class="list-group-item">
                                    <strong>Additional Data:</strong>
                                    <pre class="mt-2">{{ json_encode($userData['additional_data'], JSON_PRETTY_PRINT) }}</pre>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="mt-4">
                        <h5>SSO Session Info:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>SSO Token:</strong> {{ session('sso_token') ?? 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 