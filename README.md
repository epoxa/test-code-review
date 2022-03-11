# Code review

The overall impression is the code must die :) sorry

I literally can not detect all the flaws but the main are:
* Total ignoring input data validation
* Not using PDO parameters. Allowing SQL injections
* Not declaring arguments and return types for methods (and possible exceptions in PHPDoc blocks)
* Not always handling obvious exceptions
* Not using psalm or any other code style fixer/linter

# REST API for managing project and tasks

The API to manage project ans tasks in Database

Notices: In this test code review task we assume, that there is already some authentication middleware layer. 
So all routes protected by some token-based authorization, etc


## GET /project/{id}

Retrieve project entity by ID

+ Parameters

    + id: 1201 (number)

+ Response 200 (application/json)

    ```
      {
        "id": 1,
        "title": "Booking.com",
        "created_at": "2021-04-23T10:00:00+01"
      }  
    ```

+ Response 404


## GET /project/{id}/tasks{?limit,offset}

Retrieve project tasks

+ Parameters

    + id: 1201 (number)
    + limit: 10 (Optional, default: 10)
    + offset: 0 (Optional, default: 0)

+ Response 200 (application/json)

   ```
      [
        {
          "id": 1,
          "title": "Create UI mockups"
        },
        {
          "id": 2,
          "title": "Implement login form"
        }
      ]  
   ```

## POST /project/{id}/tasks

Create task for project

+ Parameters

    + id: 1201 (number): ID of the project

+ Request

    ```
      {
        "title": "Implement API for hotels search"
      }  
    ```

+ Response 201 (application/json) //  json assumes response body. What should it contain? Only this task with new id?
+ Response 404  
