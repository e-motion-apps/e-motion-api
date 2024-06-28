Feature: console

    Scenario: console
        Given I run artisan command "list"
        Then console output should contain "blt"
        Then console exit code is 0

    Scenario: blt
        Given I run artisan command "blt:init"
        Then console output should contain "configured"

    Scenario: with options:
        Given I run artisan command "make:notification" with:
            | argument | NoParamsNotification |
            | option   | force                |
            | option   | force                |

        Given I run artisan command "make:notification" with:
            | argument | NoParamsNotification |
            | option   | force                |

        Then console output contains "created successfully"

