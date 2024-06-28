Feature: Signup test

    Scenario: User can signup with proper credentials
        Given a user is requesting "/register" using POST method
        Given request body contains:
            | key        | value   |
            | name     | user             |
            | email    | user@example.com |
            | password | password         |
        When a request is sent
        Then the response should have status 302
        And user is authenticated in session as user in name field

    Scenario: User cannot be created with invalid password
        Given a user is requesting "/register" using POST method
        Given request body contains:
            | key        | value   |
            | name     | user             |
            | email    | user@example.com |
            | password | 123              |
        When a request is sent
        Then there is no user authenticated in session
