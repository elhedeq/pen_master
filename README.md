# Pen Master

A simple PHP blog platform with user registration, login, profile management, admin dashboard, and post creation.  
Supports rich text editing with SunEditor.

---

## Features

- User registration and login
- User profile with avatar upload
- Admin dashboard (for admin users)
- Create, edit, and delete posts (with WYSIWYG editor)
- Search posts and users


---

## Requirements

- PHP 7.4 or newer (PDO extension enabled)
- MySQL/MariaDB
- Apache/Nginx (recommended)
- Composer (optional, for installing HTMLPurifier or other libraries)

---

## Installation

1. **Clone or copy the repository:**
    ```sh
    git clone https://github.com/yourusername/pen_master.git
    cd pen_master
    ```

2. **Set up the database:**
    - Create a MySQL database (e.g., `blog`).
    - Import the provided `db.sql`:
      ```sh
      mysql -u youruser -p blog < db.sql
      ```

3. **Configure the project:**
    - Edit `config.php` with your database credentials:
      ```php
      <?php
      return [
          'db' => [
              'host' => 'localhost',
              'user' => 'your_db_user',
              'pass' => 'your_db_pass',
              'name' => 'blog'
          ],
          'root' => __DIR__
      ];
      ```

4. **Set permissions for uploads:**
    ```sh
    mkdir -p uploads
    chmod 755 uploads
    ```

5. **(Optional) Install HTMLPurifier for HTML sanitization:**
    ```sh
    composer require ezyang/htmlpurifier
    ```

---

## Usage

- Visit `http://localhost/<root>/` in your browser (where root is your website root directory).
- After importing db.sql, update the admin password in the database using:
- ```sql
  UPDATE users SET password = '<hashed_password>' WHERE email = 'admin@example.com';
  ```
- You can generate a password hash using PHP:
- ```php
  echo password_hash('yourpassword', PASSWORD_BCRYPT);
  ```
- Register a new user and start posting!
- Admin users can access the admin dashboard.

---

## Security Notes

- All database queries use prepared statements (PDO) to prevent SQL injection.
- User input is validated and escaped to prevent XSS.
- Post content is rendered as HTML; **sanitize with HTMLPurifier** if you allow user HTML.
- Uploaded avatars are restricted to JPEG files and safe filenames.

---

## Customization

- To allow more file types or change validation rules, edit the relevant PHP files.


---

## License

MIT License

---

## Credits

- [SunEditor](https://github.com/JiHong88/SunEditor)
- [Bootstrap](https://getbootstrap.com/)
- [HTMLPurifier](https://github.com/ezyang/htmlpurifier) (optional)

---

**For questions or contributions, open an issue or pull request!**