# Mock Portal - SSO Integration with SAgilePMT

This is a mock portal application that demonstrates Single Sign-On (SSO) integration with the SAgilePMT system. It provides user registration, authentication, and seamless access to SAgilePMT through SSO.

## Setup Instructions for Mock Portal

### Basic Setup

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

3. Copy `.env.example` to `.env` and generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mock_portal
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Configure SAgilePMT SSO settings in `.env`:
```
SAGILEPMT_URL=http://localhost:8000
SAGILEPMT_KEY=your-secret-key
SAGILEPMT_CLIENT_ID=mock-portal-client
```

6. Run migrations to set up the database:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve --port=8001
```

### Configuring SAgilePMT to Accept SSO from Mock Portal

For the SSO integration to work, you need to configure the SAgilePMT system:

1. Add these environment variables to SAgilePMT's `.env` file:
```
MOCK_PORTAL_URL=http://localhost:8001
MOCK_PORTAL_KEY=your-secret-key
```
   - Make sure to use the same secret key in both systems

2. Ensure SAgilePMT has the necessary SSO routes and controllers:
   - The route `/login/sso/direct` should handle SSO redirects from mock portal
   - An API endpoint `/api/verify-sso-token` should verify SSO tokens

## Using the SSO Feature

### For End Users

1. Register for an account on the mock portal
2. Log in to the mock portal
3. Click "Access SAgilePMT" in the navigation or on the dashboard
4. You will be automatically authenticated on the SAgilePMT system

### For Developers (Testing SSO)

The mock portal implements two SSO approaches:

1. **User-Initiated SSO**: 
   - Triggered when authenticated users click "Access SAgilePMT"
   - System generates a secure token and redirects to SAgilePMT
   - SAgilePMT verifies token, creates/logs in user, and redirects back

2. **API-Based Token Verification**:
   - SAgilePMT can verify tokens via `/api/verify-sso-token` endpoint
   - Request structure:
     ```json
     {
       "token": "your-sso-token",
       "client_id": "mock-portal-client",
       "api_key": "your-secret-key"
     }
     ```

## Implementing SSO in Other Systems

To add SSO from your own system to SAgilePMT:

1. Configure environment variables for SAgilePMT:
   ```
   SAGILEPMT_URL=http://localhost:8000
   SAGILEPMT_KEY=your-shared-secret-key
   SAGILEPMT_CLIENT_ID=your-system-client-id
   ```

2. Create an SSO endpoint that:
   - Generates a timestamp and secure token: `hash('sha256', $timestamp . $secretKey . $clientId)`
   - Encodes user data: `base64_encode(json_encode($userData))`
   - Redirects to SAgilePMT with parameters:
     ```
     $ssoUrl = "{$sagilePmtUrl}/login/sso/direct";
     $queryParams = http_build_query([
         'token' => $token,
         'timestamp' => $timestamp,
         'client_id' => $clientId,
         'state' => $state,
         'redirect_url' => $callbackUrl,
         'user_data' => $encodedUserData
     ]);
     
     return redirect("{$ssoUrl}?{$queryParams}");
     ```

3. Create a callback endpoint that:
   - Verifies the state parameter for CSRF protection
   - Validates the returned token
   - Stores the user's session information

4. Add the appropriate route in SAgilePMT's `.env`:
   ```
   YOUR_SYSTEM_URL=http://your-system-url
   YOUR_SYSTEM_KEY=your-shared-secret-key
   ```

## Troubleshooting

- **Token validation fails**: Ensure the same secret key is used in both systems
- **Redirect loop**: Check that the callback URL is correctly set
- **User not created in SAgilePMT**: Verify user data format matches SAgilePMT expectations
- **CSRF errors**: Make sure state parameter is properly generated and verified

## Security Recommendations for Production

In a production environment, implement these additional security measures:

- Use HTTPS for all communications
- Add rate limiting to prevent brute force attacks
- Store tokens securely and with short expiration times
- Implement IP whitelisting for API access
- Add comprehensive logging for security auditing
- Implement proper session management and timeouts
- Use stronger hashing algorithms for tokens
