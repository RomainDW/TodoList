Feature: show the user edit page

  @show_user_list_success
  Scenario: Show the user list page with role admin
    Given I am logged in as admin
    When I am on "/users"
    Then I should be on "/users"
    And I should see "Liste des utilisateurs"
    And I should see an "table" element

  @show_user_list_fail
  Scenario: Show the user list page with role user
    Given I am logged in as simple user
    When I am on "/users"
    Then the response status code should be 403
    And the response should contain "Access Denied"