Feature: console

    Scenario: console
        Given a command list is ran
        Then console output should contain "db"
        Then console exit code is 0

    Scenario: blt
        Given a command "blt:init" is ran in console
        Then console output should contain "configured"

    Scenario: with options:
        Given a command "make:notification" is ran with:
            | argument | NoParamsNotification |
            | option   | force                |
            | option   | force                |

        Given a command "make:notification" is ran with:
            | argument | NoParamsNotification |
            | option   | force                |

        Then console output contains "created successfully"

