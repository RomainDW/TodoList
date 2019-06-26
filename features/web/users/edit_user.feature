Feature: edit user

  Background:
    Given I am logged in as admin

  @edit_user_fill_form_success
  Scenario: Fill in the form
    When I am on "/users/1/edit"
    And I fill in the following:
      | user_username         | Romain            |
      | user_password_first   | password          |
      | user_password_second  | password          |
      | user_email            | myemail@email.com |
    And I additionally select "Admin" from "user_roles"
    And I press "Modifier"
    Then I should be on "/users"
    And I should see "Superbe ! L'utilisateur a bien été modifié"
    And I should see "Romain"
    And I should see "myemail@email.com"

  @edit_user_fill_form_fail
  Scenario: Fill in the form with existing email
    When I am on "/users/1/edit"
    And I fill in the following:
      | user_username         | Romain            |
      | user_password_first   | password          |
      | user_password_second  | password          |
      | user_email            | user@email.com    |
    And I additionally select "Utilisateur" from "user_roles"
    And I press "Modifier"
    Then I should be on "/users/1/edit"
    And I should see "Oops ! Cette adresse email est déjà utilisée."