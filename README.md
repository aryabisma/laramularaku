<h1 align="center" id="title">Laramu-Laraku</h1>

<p id="description">This is my small project submission at Aegis as part of Tech Lead assignment test.</p>

<h2>🚀 Demo</h2>

[https://laramularaku-production.up.railway.app/api/users](https://laramularaku-production.up.railway.app/api/users)
  
<h2>🧐 Features</h2>

Here're some of the project's best features:

*   Create User API
*   Get Users API
*   Integration with MySQL
*   Randomized User and Order seeder
*   Email notification to admin
*   Email notification to user
*   Pest Control (Positive and Negative)

<h2>🛠️ Installation Steps:</h2>

<p>1. Clone the project</p>

<p>2. go to project root directory</p>

```
cd laramularaku
```

<p>3. Create .env file</p>

```
check provided sample in file .env.example
```

<p>4. Install npm</p>

```
npm install
```

<p>5. Build npm</p>

```
npm run build
```

<p>6. Run database migration</p>

```
php artisan migrate
```

<p>7. Run data seeding</p>

```
php artisan db:seed
```

<p>8. Run application</p>

```
composer run dev
```

<p>9. To test the API use Postman collection in folder /document</p>

```
cd document
```

<p>10. To run unit test using Pest</p>

```
vendor\bin\pest
```

  
  
<h2>💻 Built with</h2>

Technologies used in the project:

*   laravel
*   php
*   mysql