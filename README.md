## Website Angle Take Home Test - Yathursan 
This project was developed using Laravel ^12.0, Filament ^4.0  and Livewire

## Setup iinstruction
1. Ensure your PHP version is ^8.2.
2. This project uses SQLite, so no external database setup is required.
3. Install frontend packages: `npm install`
4. Run the local server: `php artisan serve`
5. Access the backend login panel:
    URL: `http://localhost:8000/admin/login`
    Email: `gyathursan@yahoo.com`
    Password: ` Exam123!!! `

 ## Simple Explation
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
