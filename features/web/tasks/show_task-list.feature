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