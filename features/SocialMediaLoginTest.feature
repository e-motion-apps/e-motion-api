#Feature: social media login
#
#    Scenario: is redirected
#        Given a user is requesting "/login/github"
#        When a request is sent
#        Then a response status code should be 302
#
#    Scenario: user is logged in
#        Given there is socialte service mocked with
#        Given a user is requesting "http://escooters.blumilk.localhost/login/github/redirect"
##        TODO: support env variables ^^^
#        When a request is sent
#        Then user authenticated in session has "test@example.com" value in email field
#
