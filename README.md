<h1 align="center">PhoneBook API</h1>

This application is developed using Symfony framework to store the contacts in Phone book.

<p>PHP version used: 8.1.6</p>
<p>Symfony version used: 6.1.2</p>

## Application Setup

1. Pull the code from the repository
2. Run composer install in root directory of the repo

Run following command to run the application in your local

    symfony server start

Once the application is setup in your local we can browse folowing endpoints to store, retrive, update and delete contacts from phone book.

POST: http://127.0.0.1:8000/api/contact <br>
GET: http://127.0.0.1:8000/api/contact/{name} <br>
PUT: http://127.0.0.1:8000/api/contact/{id} <br>
DELETE: http://127.0.0.1:8000/api/contact/{id}

## Swagger documentation URL for the API

https://app.swaggerhub.com/apis/RAHIMANDCET/PhoneBook-API/0.1

## Test cases

For all the end points PHP unit test cases are written.

You can run the following command to run the test cases.

    php bin/phpunit
    
## Code coverage report

Run following command to see code coverage report

php bin/phpunit --coverage-html <directory>
