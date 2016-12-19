Feature: Manage Artists data via the RESTFul API

  In order to offer the User resource via an hypermedia API
  As a client software developer
  I need to be able to retrieve Albums by Artist's name

  Background:
    Given there are Artists with the following details:
      | uid     | name       | country |
      | artist1 | ArtistName | DE      |
#    And there are Albums with the following details:
#      | uid    | name   | artist  |
#      | album1 | album1 | artist1 |
    And I set header "Content-Type" with value "application/json"
    And I set header "X-Accept-Version" with value "v1"

  Scenario: User can GET Album Collection by artists name
    When I send a GET request to ":baseUrl/api/artists/artistname"
    Then the response code should be 200
    And the response should contain json:
    """
    [
      {
        "name": "ArtistName",
        "country": "DE",
        "beginDate": null,
        "endDate": null,
        "genres": [],
        "albums": []
      }
    ]
    """
