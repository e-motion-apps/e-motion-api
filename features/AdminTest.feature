Feature: Test if admin is working as intended

    Background:

        Given there is a "Role" in the database without factory
            | name | admin |
        Given there is a model User in the database
            | name     | admin             |
            | email    | admin@example.com |
            | password | password          |
        Given User with "admin@example.com" value in "email" field has "admin" role



    Scenario: Test admin can login with valid credentials
        Given a user is requesting "/login" using "POST" method
        Given request body contains "email" equal "admin@example.com"
        Given request body contains "password" equal "password"
        When a request is sent
        Then the response should have status 302

    Scenario: Test if admin can access admin dashboard
        Given user is authenticated in session as admin in name field
        Given a user is requesting "/admin/countries"
        When a request is sent
        Then the response status should be OK

