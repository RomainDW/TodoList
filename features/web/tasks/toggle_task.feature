Feature: toggle a task

  @toggle_task_false_success
  Scenario: toggle success
    Given I am logged in as simple user
    When I am on "/tasks"
    And I press "Marquer non terminée"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche Tâche n°1 a bien été marquée comme non terminée."

  @toggle_task_true_success
  Scenario: toggle success
    Given I am logged in as simple user
    When I am on "/tasks"
    And I press "Marquer comme faite"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche Tâche n°4 a bien été marquée comme faite."

  @toggle_task_fail_request
  Scenario: toggle fail because the http request method is get
    Given I am logged in as simple user
    When I am on "/tasks/1/toggle"
    Then the response status code should be 405
    And the response should contain "Method not allowed"