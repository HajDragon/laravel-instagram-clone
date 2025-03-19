<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Laravel Instagram Clone 📸
A fully-featured Instagram clone built with Laravel, featuring user profiles, posts, comments, and a follow system. This project demonstrates modern web development practices using Laravel, Tailwind CSS, and Alpine.js.

✨ Features
User Authentication - Register, login, and password reset
User Profiles - Customizable bio, profile picture, and stats
Post System - Create, view, and interact with image posts
Social Features - Follow/unfollow users, comment on posts
Responsive Design - Mobile-first approach with Tailwind CSS
Real-time Updates - Dynamic content loading without page refreshes

🛠️ Technologies Used
Laravel 10 - PHP web framework
Tailwind CSS - Utility-first CSS framework
Alpine.js - Lightweight JavaScript framework
MySQL - Database
Laravel Breeze - Authentication starter kit

📋 Requirements
PHP 8.1+
Composer
Node.js & NPM
MySQL or SQLite

🚀 Installation
1.Clone the repository:
```
git clone https://github.com/yourusername/laravel-instagram-clone.git
cd laravel-instagram-clone
```

2.Install PHP dependencies:
```
composer install
```
```
Install and compile frontend dependencies:
```
3.Install and compile frontend dependencies:
```
npm install
npm run dev
```
4.Create a copy of the .env file:
```
cp .env.example .env
```
5.Generate application key:
```
php artisan key:generate
```

6.Configure the database in .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=instagram_clone
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7.Run migrations and seed the database:
```
php artisan migrate --seed
```

8.Create storage link:
```
php artisan storage:link
```

9.Start the development server:
```
php artisan serve
```

💻 Usage
After installation, you can access the application at http://localhost:8000.

Demo Accounts
The database seeder creates several test accounts:

Email: test@example.com / Password: password
Plus 100+ random users with generated content
Creating Posts
Login to your account
Click on the "+" button in the navigation bar
Upload an image, add a caption, and optionally set a location
Click "Share" to publish your post
Following Users
Navigate to a user's profile
Click the "Follow" button to follow or unfollow
📸 Screenshots
Home Feed	Profile	Post Detail
<img alt="Home Feed" src="https://placeholder.com/400x800">
<img alt="Profile" src="https://placeholder.com/400x800">
<img alt="Post Detail" src="https://placeholder.com/400x800">
🧪 Development

Run the application tests:

```
php artisan test
```

Seeding Fresh Data
Reset the database with fresh seed data:
```
php artisan migrate:fresh --seed
```
🤝 Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

Fork the project
Create your feature branch (git checkout -b feature/amazing-feature)
Commit your changes (git commit -m 'Add some amazing feature')
Push to the branch (git push origin feature/amazing-feature)
Open a Pull Request
📝 License
This project is licensed under the MIT License - see the LICENSE file for details.

🙏 Acknowledgements
Laravel - The web framework used
Tailwind CSS - CSS framework
Picsum Photos - For demo images
Heroicons - SVG icons
Built with ❤️ by Your Name

