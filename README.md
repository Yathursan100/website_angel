## Website Angle Take Home Test - Yathursan 
This project was developed using Laravel ^12.0, Filament ^4.0  and Livewire

## Setup Instructions
1. Ensure your PHP version is ^8.2.
2. This project uses SQLite, so no external database setup is required.
3. Clone the project from Github to your local environment
`git clone https://github.com/Yathursan100/website_angel`
`cd website_angel`
4. Install packages: 
`composer install`
`npm install`
`npm run build`
`php artisan key:generate`
`php artisan migrate`
5. Run the local server: `php artisan serve`
6. Access the backend login panel:
    Create Filament user: `php artisan make:filament-user` then provide `username`, `email`, `password`
    you can login here (`http://localhost:8000/admin/login`) with your email and password. 
7. In the backend panel, 
  - select user from the side bar and "Import Users Api Data"
  - select post from the side bar and "Import Posts Api Data" 

 ## Simple Explanation
 - Filament panel installed and a default user created.
 - User model created with migration update_user_table including necessary attributes.
 - Post model created and migrated.
 - Filament resources used to create Posts and Users resources; forms and tables updated as per requirements.
 - API function implemented inside headerActions.
 - Relationships:

    - A User has many Posts, and a Post belongs to a User.

    - The number of posts per user is displayed using Laravel's post_count virtual attribute.

    - To display the correct User (BelongsTo), the userId from the API is mapped to the User's external_id and saved in the database.

  - Additional columns slug, publish, and draft created for future use in viewing posts.

Thank you
Yathu
