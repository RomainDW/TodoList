Feature: show the task creation page

  @show_task_create_success
  Scenario: Show the task creation page when user is logged
    Given I am logged in as simple user
    When I am on "/tasks/create"
    Then I should be on "/tasks/create"
    And I should see an "form" element

  @show_task_create_fail
  Scenario: Show the task creation page when user is not logged
    When I am on "/tasks/create"
    Then I should be on "/login"