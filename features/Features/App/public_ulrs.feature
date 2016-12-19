Feature: Public URLs

  Scenario: User is able to visit home page
    When I am on "/"
    Then the response status code should be 200
