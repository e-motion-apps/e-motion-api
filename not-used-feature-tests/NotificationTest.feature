Feature: notifications

    Background:
        Given "Database\Seeders\RoleSeeder" seeder has been run
        Given "Database\Seeders\AdminSeeder" seeder has been run

    Scenario:
        Given notifications are being faked
        Given a user is requesting "/test-notification"
#        Then a response is received
        Then a request is sent
        Then "TestNotification" notification was sent

    Scenario:notifications are being faked to user
        Given notifications are being faked
        Given a user is requesting "/test-notification"
#        Then a response is received
        Then a request is sent
        Then "TestNotification" notification was sent to user with "admin@example.com" value in "email" field

    Scenario: assert not sent

        Given notifications are being faked
        Then not one notification was sent
        Then TestNotification was not sent to User with "admin@example.com" value in "email" field
