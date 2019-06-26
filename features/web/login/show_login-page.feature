Feature: show login page

  @show_login_page_logged
  Scenario: show the login page with user already logged in
    Given I am logged in as simple user
    When I am on "/login"
    Then I should be on "/"
    Then I should see "Attention ! Vous êtes déjà connecté"

  @show_login_page_not_logged
  Scenario: show the login page with user not logged in
    When I am on "/login"
    Then I should see an "form" element