Feature: Test views

    Scenario: Test login page as view
        Given user is looking at "index" view with:
            | key   | value |
            | title | Login |
            | name  | test  |

        Then view should contain test
        Then view should contain Login

    Scenario: experimental
        Given a user is requesting "/test-view"
        Then a request is sent
        Then view response contains:
            | key   | value |
            | title | Login |
            | name  | test  |

