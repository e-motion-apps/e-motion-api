Feature: City controller

    Background:
        Given there is a "Role" in the database:
            | name | admin |
        Given there is a model User in the database:
            | name  | admin             |
            | email | admin@example.com |

#        Given user with "admin@example.com" value in email field has admin role
        Given there is "Spatie\Permission\Middleware\PermissionMiddleware" middleware disabled
        Given there is "Spatie\Permission\Middleware\RoleMiddleware" middleware disabled

        Given user is authenticated in session as admin in name field

    Scenario: test cities can be rendered
        Given there are "4" "City" objects in the database
        Given a user is requesting "/admin/cities"
        When a request is sent
        Then the response status should be OK

    Scenario: City can be created
        Given there is a "Country" in the database:
            | id | 21211 |
        Given a user is requesting "/admin/cities/" using "POST" method
        Given request body contains:
            | key        | value   |
            | name       | Legnica |
            | latitude   | 44.543  |
            | longitude  | -43.122 |
            | country_id | 21211   |
        When a request is sent
        Then the response status should be OK

#        TODO: support creating models in relations vvv
#    Given there is City model with "country_id" equal "id" of Country
#    Given there is City (?TableNode) model with "country_id" equal "id" of Country with name "Poland"
    Scenario: City cannot be created because fields already exist
        Given there is a Country in the database:
            | id | 1 |
        Given there is a model City in the database:
            | name       | Legnica |
            | latitude   | 44.543  |
            | longitude  | -43.122 |
            | country_id | 1       |
        Given a user is requesting "/admin/cities" using "POST" method
        Given request body contains:
            | key        | value   |
            | name       | Legnica |
            | latitude   | 34.543  |
            | longitude  | -13.822 |
            | country_id | 1       |
        When a request is sent
        Then a session flashes errors:
            | key  | message                          | index |
            | name | The name has already been taken. | 0     |


    Scenario: City cannot be created with invalid data
        Given a user is requesting "/admin/cities" using "POST" method
        Given request body contains:
            | key       | value |
            | name      |       |
            | longitude |       |
            | latitude  |       |
        When a request is sent
        Then a session flashes errors:
            | key       | message                          | index |
            | name      | The name field is required.      | 0     |
            | longitude | The longitude field is required. | 0     |
            | latitude  | The latitude field is required.  | 0     |

    Scenario: City cannot be updated with invalid data
        Given there is a model Country in the database:
            | id | 12111 |
        And there is a model City in the database:
            | id         | 121     |
            | name       | Legnica |
            | latitude   | 44.543  |
            | longitude  | -43.122 |
            | country_id | 12111   |
        When a user is requesting "/admin/cities/121" using "PATCH" method
        Given request body contains:
            | key       | value |
            | name      |       |
            | longitude |       |
            | latitude  |       |
        When a request is sent
        Then a session flashes errors:
            | key       | message                          | index |
            | name      | The name field is required.      | 0     |
            | longitude | The longitude field is required. | 0     |
            | latitude  | The latitude field is required.  | 0     |

    Scenario: City can be updated
        Given there is a model Country in the database:
            | id | 12111 |
        Given there is a model City in the database:
            | name       | Legnica |
            | id         | 1       |
            | latitude   | 44.543  |
            | longitude  | -43.122 |
            | country_id | 12111   |
        When a user is requesting "/admin/cities/1" using "PUT" method
        And request body contains:
            | key        | value   |
            | name       | Legnica |
            | latitude   | 31.213  |
            | longitude  | 17.623  |
            | country_id | 12111   |
        When a request is sent
        Then the response status should be OK

    Scenario: City can be deleted
        Given there is a model Country in the database:
            | id | 12111 |
        And there is a model City in the database:
            | id         | 121     |
            | name       | Legnica |
            | latitude   | 44.543  |
            | longitude  | -43.122 |
            | country_id | 12111   |
#     TODO: check if its possible to handle variables vvv -> /admin/cities/$id
        When a user is requesting "/admin/cities/121" using "DELETE" method
        When a request is sent
        Then the response status should be OK

