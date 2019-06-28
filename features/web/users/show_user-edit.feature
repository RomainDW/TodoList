Feature: show the user edit page

  @show_user_edit_success
  Scenario: Show the user edit page with role admin
    Given I am logged in as admin
    When I am on "/users/1/edit"
    Then I should be on "/users/1/edit"
    And I should see "Modifier Admin"
    And I should see an "form" element

  @show_user_edit_fail
  Scenario: Show the user edit page with role user
    Given I am logged in as simple user
    When I am on "/users/1/edit"
    Then the response status code should be 403
    And the response should contain "Access Denied"

  @show_user_edit_404
  Scenario:
    Given I am logged in as admin
    When I am on "/users/99999999/edit"
    Then I should see "Oops ! L'utilisateur n'existe pas."