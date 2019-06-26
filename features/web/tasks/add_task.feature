Feature: creation of a task

  Background:
    Given I am logged in as simple user
    And I am on "/tasks/create"

  @add_task_success
  Scenario: creation success
    When I fill in the following:
      | task_title    | Test title   |
      | task_content  | Test content |
    And I press "Ajouter"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche a été bien été ajoutée."
    And I should see "Test title"
    And I should see "Test content"

  @add_task_fail
  Scenario: creation fail
    When I fill in the following:
      | task_title    | |
      | task_content  | |
    And I press "Ajouter"
    Then I should be on "/tasks/create"
    And I should see "Vous devez saisir un titre."
    And I should see "Vous devez saisir du contenu."