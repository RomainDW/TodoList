Feature: delete a task

  @task_task_success
  Scenario: delete success
    Given I am logged in as admin
    When I am on "/tasks"
    And I press "Supprimer"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche a bien été supprimée."

  @delete_task_fail
  Scenario: delete fail
    Given I am logged in with email "user@email.com"
    When I am on "/tasks?page=1"
    Then I should not see "Supprimer"

#  @delete_task_fail
#  Scenario: delete fail
#    Given I am logged in with email "user@email.com"
#    When I am on "/tasks?page=1"
#    And I press "Supprimer"
#    Then the response status code should be 403
#    And the response should contain "Cette tâche ne vous appartient pas."

  @toggle_task_fail_request
  Scenario: toggle fail because the http request method is get
    Given I am logged in as simple user
    When I am on "/tasks/1/delete"
    Then the response status code should be 405
    And the response should contain "Method not allowed"