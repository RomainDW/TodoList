Feature: show homepage

  @show_homepage_logged_as_user
  Scenario: show homepage with user logged in
    Given I am logged in as simple user
    When I am on the homepage
    Then I should see "Bienvenue sur Todo List"
    And I should see "Créer une nouvelle tâche"
    And I should see "Consulter la liste des tâches"
    And I should not see "Créer un utilisateur"
    And I should not see "Voir la liste des utilisateurs"

  @show_homepage_logged_as_admin
  Scenario: show homepage with user logged in
    Given I am logged in as admin
    When I am on the homepage
    Then I should see "Bienvenue sur Todo List"
    And I should see "Créer une nouvelle tâche"
    And I should see "Consulter la liste des tâches"
    And I should see "Créer un utilisateur"
    And I should see "Voir la liste des utilisateurs"

  @show_homepage_not_logged
  Scenario: show homepage with user not logged in
    When I am on the homepage
    Then I should be on "/login"