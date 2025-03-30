# Hirehub

Hirehub is a comprehensive platform designed to connect employers and job seekers, streamlining the hiring process through an intuitive and feature-rich interface.

## Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- jQuery

### Backend
- PHP
- MySQL/MariaDB

### Development Tools
- XAMPP (Apache, MySQL, PHP)
- Git & GitHub
- Visual Studio Code

## Features

- User authentication for employers and job seekers
- Job posting and management
- Candidate profile creation
- Application tracking
- Search functionality with filters
- Real-time notifications

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/hirehub.git
   ```

2. Place the project files in your XAMPP htdocs directory:
   ```bash
   C:\xampp\htdocs\binaryhackathon
   ```

3. Start XAMPP and ensure Apache and MySQL services are running

4. Import the database schema:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a database named "hirehub"
   - Import the database from the `database/hirehub.sql` file

5. Configure database connection:
   - Open `config/db_config.php`
   - Update database credentials if necessary

6. Access the application at:
   ```
   http://localhost/binaryhackathon
   ```

## Usage

1. Register as an employer or job seeker
2. Complete your profile 
3. Start posting jobs or applying for positions

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see below for details:

