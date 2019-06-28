Feature: show the task list

  @show_task_list_success
  Scenario: Show the task list page when user is logged
    Given I am logged in as simple user
    When I am on "/tasks"
    Then I should be on "/tasks"
    And I should see "Tâche n°1"

  @show_task_list_fail
  Scenario: Show the task list page when user is not logged
    When I am on "/tasks"
    Then I should be on "/login"

  @show_task_list_not_found
  Scenario: Show the task list page when user is not logged
    Given I am logged in as simple user
    When I am on "/tasks?page=999"
    Then the response status code should be 404
    And the response should contain "La page n'existe pas"