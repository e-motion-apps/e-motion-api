Feature: Importer Controller

    Scenario: Import info array is returned
        Given user is in session as admin
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
