FORMAT: 1A

# Gandalf API

`Gandalf` is a **Open-Source** Decision Engine for Big-Data.
You can find source code on our [GitHub account](https://github.com/Nebo15/gandalf.api/). It’s build on top of PHP Lumen framework and MongoDB.


# Group User

## Users [/api/v1/admin/users]

### List All Users [GET]
          
+ Parameters
    + name: John (string) - Parameter searching users by usernames or emails. If name == jo, in the response users will be with usernames which starts on jo, Jo, JO. Searching by email enabled just when parameter has @
    + size: 10 (number) - Amount of items on page
    + page: 1 (number)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (array[UserList])

### Create User [POST]

+ Parameters
    + email: email@example.com (string, required)
    + password: p@ssword! (string, required) - Length 6-32, necessarily one letter and one number
    + first_name: John (string)
    + last_name: Smith (string)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (User)
        
## Current User [/api/v1/users/current]


### Info about current user [GET]

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (User)

### Update user [PUT]

+ Parameters
    + email: email@example.com (string, required)
    + password: p@ssword! (string, required) - Length 6-32, necessarily one letter and one number
    + first_name: John (string)
    + last_name: Smith (string)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (User)    

## Verify Email [/users/verify/email]

### Verify Email [POST]

+ Parameters
    + token: $2y$10$.lp0OGb.nJSt... (string, required) - Token for verification email

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (User)   

## Reset password [/api/v1/users/password/reset]

### Get reset password url with token [POST]

Send reset password url with token to User.email

+ Parameters
    + email: email@example.com (string, required)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code

### Change user password by token [PUT]

+ Parameters
    + token: $2y$10$.lp0OGb.nJSt... (string, required) - Token for password reset
    + password: newP@s$w0rd (string, required) - New user password
    
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (User)    

## Invite User [/api/v1/invite]

### Create invitation for user to the project [POST]

+ Parameters
    + email: invite@wxample.com (string, required) - Email for invitation
    + role: manager (string, required) Role of user in project
    + scope: read, write (array, required) - Array of user scopes

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (UserInvitation)
        
## Get User Access Token [/api/v1/oauth]

Get user access token, grant_type password scenario
**Every request should be with Access Token**
**-H 'Authorization: token_type access_token_here'**

### Get User Access Token [POST]

+ Parameters
    + `grant_type`: password, refresh_token (enum[string], required) - Grant Type
    + username: John (string) - Username or user email, required if grant_type == password
    + password: p@s$w0rd (string) - User password, required if grant_type == password
    + `refresh_token`: %4y$10$;lp8OGb.nAct... (string) - Refresh token, required if grant_type == refresh_token

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (AccessToken)  


# Group Projects

## Projects [/api/v1/projects]
### List All Projects [GET]

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (array[Project])  
        
### Create Project [POST]

Can create project and in the feature link tables to it.
When you created project, your feature requests should be with Header
**-H 'X-Application: project_id'**

+ Parameters
    + title: New project (string, required)
    + description: Some cool description (string)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)  
        
### Update Project [PUT]

+ Parameters
    + title: New project (string, required)
    + description: Some cool description (string)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)  

### Delete Project [DELETE]

***WARNING!!!*** This method will delete all Tables and Groups which connected to the project
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code


## Get Current Project [/api/v1/projects/current]
### Get info about current project [GET]

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)

## Project Consumers [/api/v1/projects/consumers]

### Create consumer [POST]

Every into existing project user can create consumer with own scopes. 
For login as consumer you should make Basic Auth client_id:client_secret

+ Parameters
    + description: Bot (string, required) - Description of consumer
    + scope: read,check (array, required) - Scopes of consumer

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)

### Update consumer [PUT]

+ Parameters
    + `client_id`: clientId (string, required) - Client_id from consumer info
    + description: Bot (string, required) - Description of consumer
    + scope: read,check (array, required) - Scopes of consumer

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)

### Delete consumer [DELETE]

Remove consumer from the project by client_id

+ Parameters
    + `client_id`: clientId (string, required) - Client_id from consumer info
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)    


## Project Users [/api/v1/projects/users]

### Add User To Project [POST]
Adding existing user to the current project. Share project with other users with different scopes

+ Parameters
    + user_id: 56c31536a60ad644060041af (string, required) - ID of existing User
    + role: manager (string, required) Role of user in project
    + scope: read, write (array, required) - Array of user scopes

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project) 

### Get Info About Current User [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (ProjectUser) 

### Update Project User [PUT]

+ Parameters
    + user_id: 56c31536a60ad644060041af (string, required) - ID of existing User
    + role: manager (string, required) Role of user in project
    + scope: read, write (array, required) - Array of user scopes

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project) 

### Delete User From Project  [DELETE]
+ Parameters
    + user_id: 56c31536a60ad644060041af (string, required) - ID of existing User
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Project)

# Group Table

Resources related to questions in the API.

Warning! If you don't pass _id, new MongoID will be generated, EVEN for existing document! 

## Tables [/api/v1/admin/tables]

### Create Table [POST]

Available rules conditions are: 
* `$is_set` - field key exists in request and its value can be any
* `$is_null` - **(null)** value must be NULL
* `$eq` - **(string, numeric, boolean)** value must be EQUAL to the condition value
* `$ne` - **(string, numeric, boolean)** value must be NOT EQUAL to the condition value
* `$gt` - **(numeric)** value must be GREATER THAN condition value
* `$gte` - **(numeric)** value must be GREATER THAN OR EQUAL to condition value
* `$lt` - **(numeric)** value must be LESS THAN to condition value
* `$lte` - **(numeric)** value must be LESS THAN OR EQUAL to condition value
* `$between` - **(numeric)** value must be in range of condition value. Condition value must be separated by semicolon: *12,3;30* 
* `$in` - **(string, numeric)** value must be one of the following in condition value. Condition value must be separated by comma: *10.8,'hello world'*
* `$nin` - **(string, numeric)** value must NOT be one of the following in condition value. Condition value must be separated by comma: *10.8,'hello world'*

+ Request (application/json)
    + Attributes (Table)

+ Response 201 (application/json)
    + Attributes (object)
        + meta 
            + code: 201 (number) - HTTP response code
        + data (Table)

### List All Tables [GET]

+ Parameters
    + title: Gandalf (string) - Filter by Table.title
    + description: Description (string) - Filter by table description
    + `matching_type`: scoring (string) - Filter by Table.matching_type
    + size: 10 (number) - Amount of items on page
    + page: 1 (number)

+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (array[TableList])

## Table by ID [/api/v1/admin/tables/{id}]        

### Get [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta
            + code: 200 (number) - HTTP response code
        + data (Table)

### Update [PUT]
+ Request (application/json)
    + Attributes (Table)
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (Table)

### Delete [DELETE]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code

## Table Copy [/api/v1/admin/tables/{id}/copy]

Create new Table from existing Table by id

### Copy [POST]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (Table)    


## Table Analytics [/api/v1/admin/tables/{id}/analytics]
Get Table analytics by all made Decisions

### Get [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (TableWithAnalytics)


# Group Decision

## Decisions All List [/admin/decisions]

### Decisions All List [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (array[Decision])


## Decisions By ID [/admin/decisions/{id}]

### Get Decision[GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (Decision)        
        

## Decisions Make [/tables/{id}/decisions]

### Make [POST]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (DecisionConsumer)        


# Group Changelog

## Changelog Tables List [/admin/changelog/tables]

### Changelog Tables List [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (array[Changelog])


## Changelog By Table [/admin/changelog/tables/{id}]

Get Changelog by specific Table

### Changelog By Table [GET]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data (array[Changelog])


## Changelog Tables Diff [/admin/changelog/table/{id}/diff]

View changes between two Tables.

### Changelog Tables Diff [GET]

+ Parameters
    + compare_with: 56c31536a60ad644060041af (string, required) - `Changelog ID. View changes relative from current (if not passed *original* parameter) Table state to passed Table state`
    + original: 46a31536a60ad644060231aa (string) - Changelog ID. Set Table state, that will be compared with *compare_with* Table state


+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data 
            + original 
                (ChangelogTablesDiff)
            + compare_with 
                (arrayChangelogTablesDiff)


## Changelog Table Rollback [/admin/changelog/tables/{table_id}/rollback/{changelog_id}]

Rollback Table to some state that described in passed Changelog

## Changelog Table Rollback [POST]
+ Response 200 (application/json)
    + Attributes (object)
        + meta 
            + code: 200 (number) - HTTP response code
        + data 
            + reverted
                (Table)



## Data Structures

### TableList
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ matching_type: decision,scoring (enum[string], required) - Matching type for Table
+ title: Table title (string) - Table title
+ description: Table title (string) - Table description

### Table (TableList)
+ fields (array[Field])
+ variants (array[Variant])

### Field
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ key: salary (string, required) - API Field key for request
+ title: Field title (string, required) - Field title
+ type: numeric,boolean,string (enum[string], required) - Available field data types
+ preset (object) - Preset object. You can modify field value for table rows by adding field preset
    + condition: $is_set,$is_null,$eq,$ne,$gt,$gte,$lt,$lte,$between,$in,$nin (enum[string], required)
    + value: 1000 (string, required) - Value type can be different depends on condition

### Variant
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ default_decision: Decline (string, required)
+ title: Variant title (string) - Variant title
+ description: Variant description (string) - Variant description
+ default_title: No one Rule matched (string) - When Table processing does not match any rule, this field will be set by default to Decision.title
+ default_description: No one Rule matched (string) - When Table processing does not match any rule, this field will be set by default to Decision.description
+ rules (array[Rule], required)

### Rule
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ than: Approve (string, required)
+ title: Rule title (string, required)
+ description: Rule description (string, required)
+ conditions (array[Condition], required)

### Condition
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ field_key: salary (string, required)
+ condition: $is_set,$is_null,$eq,$ne,$gt,$gte,$lt,$lte,$between,$in,$nin (enum[string], required)
+ value: unemployed | 1000 | true (string, required)

### TableWithAnalytics (Table)
+ variants (array[VariantWithAnalytics])

### VariantWithAnalytics (Variant)
+ rules (array[RuleWithAnalytics], required)

### RuleWithAnalytics (Rule)
+ conditions (array[ConditionWithAnalytics], required)

### ConditionWithAnalytics (Condition)
+ probability: 0.67 (number) - Probability value between 0-1, where 1 is 100% matched result
+ requests: 217 (number) - Requests amount for current condition.

### Decision
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ title: Decision title (string) - Decision title
+ description: Decision description (string) - Decision description
+ final_decision: Approve (string) - Depends of the Table.matching_type could be as highest row in a table with all validations passed or sum of scoring points",
+ request
    + salary: 1000 (string) - All passed parameters from request
+ table
    + _id: 56c31536a60ad644060041af (string) - MongoID
    + matching_type: decision,scoring (enum[string], required) - Matching type for Table
    + title: Table title (string) - Table title
    + description: Table title (string) - Table description
    + applications
    + variant
        + _id: 56c31536a60ad644060041af (string) - MongoID
        + title: Variant title (string) - Variant title
        + description: Variant description (string) - Variant description
+ fields (array[Field])
+ rules (array[DecisionRule])
+ created_at: 2016-02-16 14:15:30 (string)
+ updated_at: 2016-02-16 14:15:30 (string)
        
### DecisionRule (Rule)
+ decision: Approve (string) - Matched Rule decision or Variant.default_decision 
+ conditions (array[Condition], required)

### DecisionCondition (Condition)
+ matched: true (boolean) - Is current condition matched

### DecisionConsumer (Decision)
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ title: Decision title (string) - Decision title
+ description: Decision description (string) - Decision description
+ final_decision: Approve (string) - Depends of the Table.matching_type could be as highest row in a table with all validations passed or sum of scoring points",
+ request
    + salary: 1000 (string) - All passed parameters from request
+ table
    + _id: 56c31536a60ad644060041af (string) - MongoID
    + matching_type: decision,scoring (enum[string], required) - Matching type for Table
    + title: Table title (string) - Table title
    + description: Table title (string) - Table description
    + applications
    + variant
        + _id: 56c31536a60ad644060041af (string) - MongoID
        + title: Variant title (string) - Variant title
        + description: Variant description (string) - Variant description
+ rules
    + title: Rule title (string, required)
    + description: Rule description (string, required)
    + decision: Approve (string) - Matched Rule decision or Variant.default_decision 
+ created_at: 2016-02-16 14:15:30 (string)
+ updated_at: 2016-02-16 14:15:30 (string)


### Changelog
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ author: Admin (string) - Who made changes
+ model
    + _id: 56c31536a60ad644060041af (string) - MongoID
    + table: table (string) - Model table name
+ created_at: 2016-02-16 14:15:30 (string)
+ updated_at: 2016-02-16 14:15:30 (string)

### ChangelogTablesDiff (Changelog)
+ model
    + _id: 56c31536a60ad644060041af (string) - MongoID
    + table: table (string) - Model table name
    + attributes (Table)

### Project
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ title: Gandalf (string) - Project title
+ users (array[ProjectUser])
+ consumers (array[Consumer])

### ProjectUser
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ user_id: 56c31536a60ad644060041af (string) - Unique identifier of a User, MongoID
+ role: manager (string) Role of user in project
+ scope: read, write, ... (array) - Array of user scopes
+ username: Freddy (string)
+ email: email@example.com (string) - User email

### Consumer
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ client_id: fe5fe8d15bb7a6a0daef36d2843c547c6d594b0c (string) - Client ID
+ client_secret: a7c70da3246a436bde66101d65741f9adf570726 (string) - Client secret
+ description: Description of project (string) - Optional description of client
+ scope: read, write, ... (array) - Array of user scopes


### UserList
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ username: Freddy (string)

### User (UserList)
+ description: Some description (string) - Description of the matched rule or Table.default_description
+ email: email@example.com (string) - User email
+ created_at: 2016-02-16 14:15:30 (string)
+ updated_at: 2016-02-16 14:15:30 (string)
+ access_tokens (array[AccessToken])
+ refresh_tokens (array[RefreshToken])
        
### UserInvitation
+ role: manager (string) Role of user in project
+ project
    + _id: 56c31536a60ad644060041af (string) - MongoID
    + title First project (string) - Project title
+ scope: read, write (array) - Array of user scopes
+ email: email@example.com (string) - User email
+ created_at: 2016-02-16 14:15:30 (string)
+ updated_at: 2016-02-16 14:15:30 (string)

### AccessToken
+ access_token: 2fc5a4e7a91771ca312c79e0ad2cc4c49d3b065d (string) - Unique access token for current user
+ expires_in: 360 (number) - Token lifetime, seconds
+ token_type: Bearer (string) - Type of access token
+ refresh_token: 8bcc30bf6ce74ddaf0ec5eff5a51141c7ba65ef9 (string) - Unique refresh token linked to some access token for current user 

### RefreshToken
+ _id: 56c31536a60ad644060041af (string) - MongoID
+ refresh_token: 8bcc30bf6ce74ddaf0ec5eff5a51141c7ba65ef9 (string) - Unique refresh token linked to some access token for current user 
+ client_id: SomeId (string) - Client id for refresh token

### Paging
+ size: 15 (number) - items per page
+ total: 40 (number) - amount of all items
+ current_page: 1 (number)
+ last_page: 3 (number)