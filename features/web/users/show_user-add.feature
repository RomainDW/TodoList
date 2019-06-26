Feature: show the user creation page

  @show_user_add_success
  Scenario: Show the user creation page with role admin
    Given I am logged in as admin
    When I am on "/users/create"
    Then I should be on "/users/create"
    And I should see "Créer un utilisateur"
    And I should see an "form" element

  @show_user_add_fail
  Scenario: Show the user creation page with role user
    Given I am logged in as simple user
    When I am on "/users/create"
    Then the response status code should be 403
    And the response should contain "Access Denied"