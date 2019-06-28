Feature: show the task edit page

  @show_task_edit_success
  Scenario: Show the task edit page when user is logged
    Given I am logged in as simple user
    When I am on "/tasks/1/edit"
    Then I should be on "/tasks/1/edit"
    And I should see an "form" element

  @show_task_edit_fail
  Scenario: Show the task edit page when user is not logged
    When I am on "/tasks/1/edit"
    Then I should be on "/login"

  @show_task_edit_404
  Scenario:
    Given I am logged in as simple user
    When I am on "/tasks/99999999/edit"
    Then I should see "Oops ! La tâche n'existe pas. "