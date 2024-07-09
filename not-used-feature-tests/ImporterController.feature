Feature: Importer Controller

    Scenario: Import info array is returned
#        Given user is in session as admin
        Given there is a User in the database:
            | name     | admin                    |
            | email    | userWithRole@example.com |
            | password | password                 |
        Given there is "Spatie\Permission\Middleware\PermissionMiddleware" middleware disabled
        Given there is "Spatie\Permission\Middleware\RoleMiddleware" middleware disabled
        Given there is "App\Http\Middleware\Authenticate" middleware disabled

        Given a user is requesting "/api/admin/importers"
        When a request is sent
        Then the response should be JSON

#    TODO: support arrays in json response
#        Then the response should contain JSON:
#            | importInfo | [] |
#            | codes      | [] |
#            | providers  | [] |

    Scenario: Unauthorized user cannot access Import Info
        Given a user is requesting "/api/admin/importers"
        When a request is sent
        Then the response status should be not found
