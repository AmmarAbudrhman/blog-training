
# Blog Training API

A comprehensive Blog API built with Laravel 11.x, featuring authentication, post management, and social interactions.

## Features

- **Authentication**: Secure API authentication using Laravel Sanctum.
- **OTP Verification**: Email-based OTP verification for account security.
- **Profile Management**: Update user profile details and avatar.
- **Posts**: Full CRUD operations for blog posts with image upload support.
- **Categories**: Organize posts into categories.
- **Tags**: Tagging system for posts with duplicate prevention.
- **Comments**: Users can comment on posts.
- **Likes**: Users can like and unlike posts.
- **Architecture**: Implements the Single Action Controller pattern for clean and maintainable code.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Database (MySQL, SQLite, etc.)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd blog-training
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your database settings in the `.env` file.

4. **Run Migrations and Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Link Storage**
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

## Running Tests

The project includes a comprehensive test suite covering all features.

```bash
php artisan test
```

## API Endpoints

### Auth
- `POST /api/auth/login` - Login
- `POST /api/auth/register` - Register a new user
- `GET /api/auth/profile` - Get current user profile
- `PUT /api/auth/profile` - Update profile

### OTP
- `POST /api/otp/send` - Send OTP
- `POST /api/otp/verify` - Verify OTP

### Posts
- `GET /api/posts` - List posts
- `POST /api/posts` - Create post
- `GET /api/posts/{id}` - Show post
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post

### Interactions
- `POST /api/posts/{id}/comments` - Add comment
- `POST /api/posts/{id}/like` - Like post
- `DELETE /api/posts/{id}/like` - Unlike post

### Categories & Tags
- Standard CRUD endpoints available.

## Instructor 
This project was completed during the **Potify** internship under the instruction of [@omerbaflah](https://github.com/omerbaflah).

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
