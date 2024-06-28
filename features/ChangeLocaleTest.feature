Feature: Language change

    Scenario: User can change language to supported language
        Given a user is requesting "/api/language/en" using POST method
        When a request is sent
        Then the response status should be OK
        And the response should contain JSON:
            | message | Language has been changed. |

        Given a user is requesting "/api/language/pl" using POST method
        When a request is sent
        Then the response status should be OK
        And the response should contain JSON:
            | message | Język został zmieniony. |

    Scenario: User can change language to supported language
        Given locale is set to en
        Given a user is requesting "/api/language/unsupported" using POST method
        When a request is sent
        Then the response status should be unprocessable
        And the response should contain JSON:
            | message | Error changing the language. |

        Given locale is set to pl
        Given a user is requesting "/api/language/unsupported" using POST method
        When a request is sent
        Then the response status should be unprocessable
        And the response should contain JSON:
            | message | Błąd podczas zmiany języka. |



