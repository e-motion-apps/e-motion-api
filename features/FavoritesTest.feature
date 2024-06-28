Feature: Favorites

    Background: user is admin
        Given user is in session as admin
        Given there is a model City in the database
            | name | TestCity |
            | id   | 123232   |
#        TODO: add class recognition vvv
        Given "Database\Seeders\ProviderSeeder" seeder has been ran

    Scenario: City can be added to favourites

        Given a user is requesting "/api/favorites" using POST method
        Given request body contains city_id equal 123232
        When a request is sent
        Then the response status should be OK
        Then the model Favorites exists in the database
        Then the model Favorites belongs to User
        Then the model Favorites belongs to City

    Scenario: Favorites change notification is sent at change
        Given notifications are being faked

        Given a user is requesting "/api/favorites" using POST method
        Given request body contains city_id equal 123232
        When a request is sent

        Given the model Provider exists in the database
            | name | TestProvider |

        Given a user is requesting "/update-city-providers/123232" using PATCH method
        Given request body contains:
            | key           | value    |
            | providerNames | [Baqme,Beam] |
        When a request is sent
        Given NoParamsNotification notification is sent to user with "userWithRole@example.com" value in "email" field
        Then NoParamsNotification notification was sent

