# Blog Application

A simple PHP-based blog application that allows users to create, read, update, and delete blog posts. The application includes user authentication, role-based access control, and an admin dashboard for managing users and posts.

---

## Features

### User Features
- **Authentication**: Register, login, and logout functionality.
- **Post Management**: Create, edit, delete, and view blog posts.
- **Search**: Search for posts by keywords.
- **Pagination**: Paginate posts for better navigation.

### Admin Features
- **User Management**: View, update roles, and delete users.
- **Post Management**: View, edit, and delete posts created by any user.

### Security
- Passwords are hashed using `password_hash()`.
- CSRF protection for forms.
- Role-based access control to restrict unauthorized actions.

---

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL or MariaDB
- Composer (optional for dependency management)
- A web server (e.g., Apache or Nginx)

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/blog-application.git
   cd blog-application
   ```
2. Create the database:
   ```sql
   CREATE DATABASE blog;
   USE blog;
   -- Add your table creation queries here
   ```
3. Connect to the database:
   ```php
   $host = 'localhost';
   $dbname = 'blog';
   $username = 'root';
   $password = '';

   $connect = mysqli_connect($host, $dbname, $username, $password);
   ```

## Project Structure

Blog Application/
├── assets/                 # Static assets (CSS, JS, images)
├── config/
│   ├── database.php        # Database connection
│   ├── Includes/           # Core includes (auth, header, footer, etc.)
│   └── sql/                # SQL scripts for database setup
├── posts/                  # Post-related pages (create, edit, delete, etc.)
├── auth/                   # Authentication pages (login, register, logout)
├── admin/                  # Admin dashboard and management pages
└── index.php               # Main entry point

# Blog Application

A simple PHP-based blog application that allows users to create, read, update, and delete blog posts. The application includes user authentication, role-based access control, and an admin dashboard for managing users and posts.

---

## Features

### User Features
- **Authentication**: Register, login, and logout functionality.
- **Post Management**: Create, edit, delete, and view blog posts.
- **Search**: Search for posts by keywords.
- **Pagination**: Paginate posts for better navigation.

### Admin Features
- **User Management**: View, update roles, and delete users.
- **Post Management**: View, edit, and delete posts created by any user.

### Security
- Passwords are hashed using `password_hash()`.
- CSRF protection for forms.
- Role-based access control to restrict unauthorized actions.

---


### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/blog-application.git
   cd blog-application
   ```

2. Set up the database:
   - Create a database named `blog`.
   - Import the SQL schema from `config/sql/schema.sql`:
     ```sql
     CREATE DATABASE blog;
     USE blog;
     -- Add your table creation queries here
     ```

3. Configure the database connection:
   - Open `config/database.php` and update the database credentials:
     ```php
     $host = 'localhost';
     $dbname = 'blog';
     $username = 'root';
     $password = '';
     ```

4. Start the server:
   - If using PHP's built-in server:
     ```bash
     php -S localhost:8000
     ```
   - Access the application at `http://localhost:8000`.

---

## File Structure

```
Blog Application/
├── assets/                 # Static assets (CSS, JS, images)
├── config/
│   ├── database.php        # Database connection
│   ├── Includes/           # Core includes (auth, header, footer, etc.)
│   └── sql/                # SQL scripts for database setup
├── posts/                  # Post-related pages (create, edit, delete, etc.)
├── auth/                   # Authentication pages (login, register, logout)
├── admin/                  # Admin dashboard and management pages
└── index.php               # Main entry point
```

---

## Usage

### User Authentication
1. Register a new account at `/auth/register.php`.
2. Login at `/auth/login.php`.
3. Create, edit, or delete posts from the dashboard.

### Admin Dashboard
1. Login as an admin user.
2. Access the admin dashboard at index.php.
3. Manage users and posts.

---

## Troubleshooting

### Common Issues
1. **Database Connection Failed**:
   - Ensure the database server is running.
   - Verify the credentials in database.php.

2. **Error Messages**:
   - Enable error reporting in index.php:
     ```php
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
     ```

3. **CSS/JS Not Loading**:
   - Check the paths in the `<link>` and `<script>` tags in `header.php` and `footer.php`.

---

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
