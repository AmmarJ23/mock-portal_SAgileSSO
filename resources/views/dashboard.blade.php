@extends('layouts.app')

@section('content')
<style>
    .dashboard-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: calc(100vh - 150px);
        padding: 40px 0;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        font-weight: 600;
        font-size: 1.2rem;
    }
    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        padding: 15px;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .list-group-item strong {
        color: #667eea;
    }
    pre {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .alert {
        border: none;
        border-radius: 8px;
    }
</style>

<div class="dashboard-section">
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

                        <h4 class="mb-4">Welcome {{ $userData['name'] ?? 'User' }}!</h4>
                        <p class="text-muted mb-4">You have successfully logged in via Student Portal SSO.</p>
                        
                        <div class="mt-4">
                            <h5 class="mb-3">Your Student Portal Details:</h5>
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
                                        <pre>{{ json_encode($userData['additional_data'], JSON_PRETTY_PRINT) }}</pre>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">SSO Session Info:</h5>
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
</div>
@endsection 