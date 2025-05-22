# Mock Portal - SSO Testing Environment

This is a mock portal application designed to test SSO integration with the Student Portal system.

## Setup Instructions

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

3. Copy `.env.example` to `.env` and configure your environment:
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

5. Configure Student Portal SSO settings in `.env`:
```
STUDENT_PORTAL_URL=http://localhost:8000
STUDENT_PORTAL_API_KEY=your_api_key_here
```

6. Run migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve --port=8001
```

## Testing SSO Integration

The mock portal provides two methods for testing SSO:

1. **Manual SSO Login**: Access the SSO login form at `/login/sso` and enter a matric number
2. **Direct SSO**: Simulate direct SSO login from Student Portal using `/login/sso/direct?token=test_token&timestamp=current_time`

## Security Notes

This is a testing environment. In production:
- Use HTTPS
- Implement proper token validation
- Add rate limiting
- Add proper session management
- Implement CSRF protection
