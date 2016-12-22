@javascript
Feature: Artist can be added to Collection
  As a user
  I want to add an Artist to Collection
  So I know the page is working

  Background:
    Given there are Artists with the following details:
      | uid     | name       | country |
      | artist1 | ArtistName | DE      |

  Scenario: Adding Artist to Collection
    Given I am on the homepage
    And I fill in "Artist" with "artistName"
    When I press "Add"
    And I wait for the ajax response
    Then I should see "ArtistName"
    And I should see "No matching albums found."
