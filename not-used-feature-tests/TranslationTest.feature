Feature: Translation

    Scenario: Translations
        Given localization is set to pl
        When I ask for translations of "Prices"
        Then I should see the following phrases:
            | locale | phrase   |
            | pl     | Ceny     |
            | fr     | Baguette |
            | de     | Bier     |
