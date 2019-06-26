Feature: creation of a task

  Background:
    Given I am logged in as simple user
    And I am on "/tasks/1/edit"

  @edit_task_success
  Scenario: creation success
    When I fill in the following:
      | task_title    | Test title   |
      | task_content  | Test content |
    And I press "Modifier"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche a bien été modifiée. "
    And I should see "Test title"
    And I should see "Test content"

  @edit_task_fail
  Scenario: creation fail
    When I fill in the following:
      | task_title    | |
      | task_content  | |
    And I press "Modifier"
    Then I should be on "/tasks/1/edit"
    And I should see "Vous devez saisir un titre."
    And I should see "Vous devez saisir du contenu."