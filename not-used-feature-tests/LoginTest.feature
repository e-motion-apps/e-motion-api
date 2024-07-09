Feature: Login test

    Background:
        Given there is a model User in the database:
            | name     | user             |
            | email    | user@example.com |
            | password | password         |

    Scenario: User can login with valid credentials
        Given a user is requesting "/login" using "POST" method
        Given request body contains:
            | key      | value            |
            | email    | user@example.com |
            | password | password         |
        When a request is sent
        Then the response should have status 302
        And user authenticated in session has user value in name field

    Scenario: Authenticated user can log uot
        Given user is authenticated in session as user in name field
        Given a user is requesting "/logout" using "POST" method
        When a request is sent
        Then the response should have status 302
        And no user is authenticated in session

    Scenario: User cannot login with invalid password
        Given a user is requesting "/login" using POST method
        Given request body contains:
            | key      | value            |
            | email    | user@example.com |
            | password | invalidPassword  |
        When a request is sent
        Then no user is authenticated in session
        And a session flashes errors:
            | key        | message                       | index |
            | loginError | Invalid password or username. | 0     |

        Given request form params contains:
            | key  | value   |
            | City | Legnica |
