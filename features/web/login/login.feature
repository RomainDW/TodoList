Feature: login

  @fill_login_form
  Scenario: Fill in the login form
    Given I am on "/login"
    When I fill in the following:
    | email    | test@email.com |
    | password | password       |
    And I press "Se connecter"
    Then I should be on "/"
    And I should see "Bienvenue sur Todo List"

  @fill_login_form_fail
  Scenario: Fill in the login form
    Given I am on "/login"
    When I fill in the following:
      | email    | fakeemail@email.com  |
      | password | password             |
    And I press "Se connecter"
    Then I should be on "/login"
    And I should see "Email introuvable."