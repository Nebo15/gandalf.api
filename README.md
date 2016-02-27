# Gandalf

[![Deployment status from DeployBot](https://nebo15.deploybot.com/badge/56046448049120/64889.svg)](http://deploybot.com)
[![Build Status](https://travis-ci.com/Nebo15/gandalf.api.svg?token=xgZbSs9Y2bjswEUEXkUb&branch=master)](https://travis-ci.com/Nebo15/gandalf.api)

This is a Back-End project for our Open-Source Decision Engine for Big-Data. You can find front-end here: [Nebo15/gandalf.web](https://github.com/Nebo15/gandalf.web).

It's build on top of PHP Lumen framework and MongoDB.

## How does it work?

Gandalf allows you to define multiple decigion tables and to list all decigions that was made.

You can use it for anti-fraud, risk management, as part any other decigion making purposes.

### Decision Table

It consist of columns that describes API request structure, rows that describe decision-making logic, and cells that represents a single validation rule.

#### Columns

Column represent specific request parameter. You can add additional parameters on a fly by adding a column. After saving table with new column all future API request should provide data for it. 

However, API consumer can provide more data and you don't need to specify column for each of request fields, in this case we will skip unused ones. Best practise is to feed table with any data you have, so you can use it when you need without changing back-end.

Column can have following settings:

- ```Title``` - human-readable string that describes field in interface.
- ```Field API Key``` - field name from witch data will be taken. For example, if you add a field with key ```name``` than all API consumers should provide JSON with this field: ```{"name": "Some User Name"}```.
- ```Type``` - type of data that will be submitted in this field. ```String```, ```Number``` or ```Boolean```.

##### Presets

You can modify field value for table rows by adding field preset. For example, you have field called salary and it's too routine to add a "salaries greater than 1000" condition in each row, instead you can create preset that turns ```Numeric``` salary into ```Boolean``` type and simply turn on this validation in each row.

It should look something like this:

- ```Title``` = ```Sufficient Salary```;
- ```Field API Key``` = ```salary```;
- ```Type``` = ```Number```;
- ```Preset Condition``` = ```greater than```;
- ```Preset Value``` = ```1000```.

By checking checkbox below ```Low Salary```  column in a row, you will make sure that this row won't pass check untill ```salary``` is greater than ```1000```.

#### Rows

Each row represents a rule in a ```OR``` logical operator style. 

Row will return value selected in "Decision" column only if all validation rules in it have passed. Rules are checked in a same order as you see them in a table. You can reorder them by drug'n'drop.

#### Cells

All cells in a row represent validations in a ```AND``` logical operator style. 

Sometimes you have a big table and in some rows you prefer to skip some validations. For this case you can select special validation rule called ```is set```. Logically it means that ```{field_name} is set``` and this condition will always pass validation. 

#### Validation Conditions

Available rules can differ based on a column type. Generally you should consider all rules logic as follows:
```{request_field_vale} {condition} {condition_value}```. For some conditions you can omit their value.

String fileds support following conditions:
- ```=``` - validation will pass if field value equals specified value.
- ```!=``` - validation will pass if field value does not equal specified value.
- ```in``` - validation will pass if field value eqals to one of listed values. Separate values by comma with space. If searched string have comma you can surround value by single qoute. For example: ```d,e``` in ```a, b, c, 'd,e'``` will return true.
- ```not in``` - validation will pass if field value does not eqal to any of listed values.
- ```contains``` - validation will pass if field value is contains specified value. 
- ```is set``` - validation will always pass. (Use it to skip some columns.)

Number supports:
- ```=``` - validation will pass if field value equals specified value.
- ```>``` - validation will pass if field value is greater than specified value.
- ```>=``` - validation will pass if field value is greater or equal to a specified value.
- ```<``` - validation will pass if field value is less than specified value.
- ```<=``` - validation will pass if field value is less or equal to a specified value.
- ```!=``` - validation will pass if field value does not equal specified value.
- ```in``` - validation will pass if field value eqals to one of listed values. Separate values by comma with space. If searched string have comma you can surround value by single qoute. For example: ```d,e``` in ```a, b, c, 'd,e'``` will return true.
- ```not in``` - validation will pass if field value does not eqal to any of listed values.
- ```is set``` - validation will always pass. (Use it to skip some columns.)

Boolean supports:
- ```true``` - will pass if field value is ```true```, ```"true"```, ```'true'```, ```1```, ```"1"``` or ```'1'```.
- ```false``` - will pass if field value is ```false```, ```"false"```, ```'false'```, ```0```, ```"0"``` or ```'0'```.
- ```is set``` - validation will always pass. (Use it to skip some columns.)

#### Decision Making

The highest row in a table with all validations passed will be returned as a final decision. In API response ```final_decision``` field will be equal to value selected in a "Decision" column for this row. Also we will attach decision title and description, so you can understand what decision rule was triggered first.

If there are no rows with all validations passed, we will return ```final_decision``` that equals a value specified in "Default Decision" dropdown.

#### Sample CURL Request

You can request current decision table by calling this command from your CLI (works on Linux and Mac):

## Features

- Customizable - have the freedom to design a decision rules you need and to manage data structure on the fly in a easy to understand way.
- Split testing - you can group your decision table to run a A/B tests. Later, you can leave only one, that shows best results.
- Analytics and Decision History - you can review all previous decisions made by your tables and to analyze what rules is triggered more often.
- Production-tested - several large NDA-closed PSP's and online lending platforms already use Gandalf.

## Story behind Gandlaf

We - are production oriented team, and we work for a fintech company. Almost all project that we created need a decision engine that reduces business risks. And all solutions we can find is ether old and ugly, or very expensive. So we decided to create free open-source alternative, that will be scalable, reliable and flexible enough to cover 95% of cases.

To make is suitable for Big Data we decided to build it on top of use very reliable open-source database MongoDB, that have good sharding capabilities and easy to maintain.

Also we believe that vendor-lock is a bad thing, so we published all source code on a MIT license, so you are free to change it as you wish.

Done by [Nebo #15](https://github.com/Nebo15).

## Step by Step Guide

TODO.

# API Docs

## Auth

For all request you should pass BASIC Authorization header

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/admin/tables
```

## Decision table

### List

#### GET /admin/tables

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/admin/tables
```

```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {   
            "_id": "56c31536a60ad644060041af",
            "title": "My decision table",
            "description": "Some cool table",
            "default_decision": "approve"
        },
        {   
            "_id": "12c31536a67ad644060041ba",
            "title": "Yet another decision table",
            "description": "Very cool table",
            "default_decision": "decline"
        }
    ]
}
```

### Create decision table

#### POST /admin/tables

Create decision table.

Params:
 * `table` - full decision table with title, description, default_decision, fields, rules, conditions.
  
It is possible to create a **preset** for some request *param*, that will be prepared for conditions.
For example, if you create a **preset** for field *Borrowers Salary* with condition: `$gte` and value `1000`,
for rules conditions you will receive result of **preset** checking - `true` or `false`

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" -d'{"table": DECISION_TABLE }' 
http://gandalf-api.nebo15.com/api/v1/admin/tables/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
        "_id": "56c31536a60ad644060041af",
        "default_decision": "approve",
        "fields": [
            {
                "key": "salary",
                "title": "Borrowers Salary",
                "source": "request",
                "type": "string",
                "preset": {
                    "condition": "$gte",
                    "value": 1000
                }
            },
            {
                "key": "contact_person_phone_verification",
                "title": "Contact person phone verification",
                "source": "request",
                "type": "bool"
            }
        ],
        "rules": [
            {
                "than": "approve",
                "title": "My title",
                "description": "my",
                "conditions": [
                    {
                        "field_key": "salary",
                        "condition": "$eq",
                        "value": "true"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            },
            {
                "than": "decline",
                "title": "My title",
                "description": "new",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Life"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            }
        ]
    }
}
```

### Clone decision table

#### POST /admin/tables/{id}/clone

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" -X POST http://gandalf-api.nebo15.com/api/v1/admin/tables/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
        "_id": "44c31536a60ad644060021aa",
        "default_decision": "approve",
        "fields": [
            {
                "key": "borrowers_phone_name",
                "title": "Borrowers Phone Name",
                "source": "request",
                "type": "string"
            },
            {
                "key": "contact_person_phone_verification",
                "title": "Contact person phone verification",
                "source": "request",
                "type": "bool"
            }
        ],
        "rules": [
            {
                "than": "approve",
                "title": "My title",
                "description": "my",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Vodaphone"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            },
            {
                "than": "decline",
                "title": "My title",
                "description": "new",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Life"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            }
        ]
    }
}
```

### Get decision table

#### GET /admin/tables/{id}

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/admin/tables/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
        "_id": "56c31536a60ad644060041af",
        "title": "My decision table",
        "description": "Some cool table",
        "default_decision": "approve",
        "fields": [
            {
                "key": "borrowers_phone_name",
                "title": "Borrowers Phone Name",
                "source": "request",
                "type": "string"
            },
            {
                "key": "contact_person_phone_verification",
                "title": "Contact person phone verification",
                "source": "request",
                "type": "bool"
            }
        ],
        "rules": [
            {
                "than": "approve",
                "title": "My title",
                "description": "my",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Vodaphone"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            },
            {
                "than": "decline",
                "title": "My title",
                "description": "new",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Life"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            }
        ]
    }
}
```

### Update decision table

#### PUT /admin/tables/{id}

Update decision table.
Params:

 * `table` - full decision table with title, description, default_decision, fields, rules, conditions. 

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" -X PUT -d'{"table": DECISION_TABLE }' 
http://gandalf-api.nebo15.com/api/v1/admin/tables/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
        "_id": "56c31536a60ad644060041af",
        "default_decision": "approve",
        "fields": [
            {
                "key": "borrowers_phone_name",
                "title": "Borrowers Phone Name",
                "source": "request",
                "type": "string"
            },
            {
                "key": "contact_person_phone_verification",
                "title": "Contact person phone verification",
                "source": "request",
                "type": "bool"
            }
        ],
        "rules": [
            {
                "decision": "approve",
                "description": "my",
                "title": "My title",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Vodaphone"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            },
            {
                "decision": "decline",
                "title": "My title",
                "description": "new",
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Life"
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true"
                    }
                ]
            }
        ]
    }
}
```

### Delete decision table

#### Delete /admin/tables/{id}

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" -X DELETE http://gandalf-api.nebo15.com/api/v1/admin/tables/56c31536a60ad644060041af
```

```json
{
    "meta": {
        "code": 200
    }
}
```

## Decisions

### Decisions history for admin 

#### GET admin/decisions

Params:

* `table_id` - decisions by table id, default null
* `size` - **integer**, items per page, default 20
* `page` - **integer**, page

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/admin/decisions
```

```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {
            "_id": "56c32f02a60ad689060041a9",
            "table_id": "12c31536a60ad644060054az",
            "default_decision": "approve",
            "final_decision": "approve",
            "fields": [
                {
                    "key": "borrowers_phone_name",
                    "title": "Borrowers Phone Name",
                    "source": "request",
                    "type": "string"
                },
                {
                    "key": "contact_person_phone_verification",
                    "title": "Contact person phone verification",
                    "source": "request",
                    "type": "bool"
                }
            ],
            "rules": [
                {
                    "than": "approve",
                    "title": "My title",
                    "description": "my",
                    "decision": null,
                    "conditions": [
                        {
                            "field_key": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Vodaphone",
                            "matched": true
                        },
                        {
                            "field_key": "contact_person_phone_verification",
                            "condition": "$eq",
                            "value": "true",
                            "matched": false
                        }
                    ]
                },
                {
                    "than": "decline",
                    "title": "My title",
                    "description": "new",
                    "decision": null,
                    "conditions": [
                        {
                            "field_key": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Life",
                            "matched": false
                        },
                        {
                            "field_key": "contact_person_phone_verification",
                            "condition": "$eq",
                            "value": "true",
                            "matched": false
                        }
                    ]
                }
            ],
            "request": {
                "borrowers_phone_name": "Vodaphone",
                "contact_person_phone_verification": 123
            },
            "updated_at": "2016-02-16 14:15:30",
            "created_at": "2016-02-16 14:15:30",
        }
    ],
    "paginate": {
        "size": 20,
        "total": 1,
        "current_page": 1,
        "last_page": 1
    }
}
```

### Decision history item by id for admin 

#### GET admin/decisions/{id}

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/admin/decisions/56c32f02a60ad689060041a9
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {
        "_id": "56c32f02a60ad689060041a9",
        "table_id": "12c31536a60ad644060054az",
        "default_decision": "approve",
        "final_decision": "approve",
        "fields": [
            {
                "key": "borrowers_phone_name",
                "title": "Borrowers Phone Name",
                "source": "request",
                "type": "string"
            },
            {
                "key": "contact_person_phone_verification",
                "title": "Contact person phone verification",
                "source": "request",
                "type": "bool"
            }
        ],
        "rules": [
            {
                "than": "approve",
                "title": "My title",
                "description": "my",
                "decision": null,
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Vodaphone",
                        "matched": true
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true",
                        "matched": false
                    }
                ]
            },
            {
                "than": "decline",
                "description": "new",
                "decision": null,
                "conditions": [
                    {
                        "field_key": "borrowers_phone_name",
                        "condition": "$eq",
                        "value": "Life",
                        "matched": false
                    },
                    {
                        "field_key": "contact_person_phone_verification",
                        "condition": "$eq",
                        "value": "true",
                        "matched": false
                    }
                ]
            }
        ],
        "request": {
            "borrowers_phone_name": "Vodaphone",
            "contact_person_phone_verification": 123
        },
        "updated_at": "2016-02-16 14:15:30",
        "created_at": "2016-02-16 14:15:30",
    }
}
```

## Consumer API

### Create decisions from decision table

#### POST /tables/{id}/decisions

Check params from request with decision table.
POST params it's an associative array (dictionary) whose keys are the values of `fields.key` from decision table.

For decision table, that you can see in example above, you should pass two params:
 * `borrowers_phone_name`
 * `contact_person_phone_verification`

All fields are required!

Additional params:
 * `webhook` - optional, webhook url for table decision


```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" -d'{"borrowers_phone_name": "test", "contact_person_phone_verification": "Life"}' 
http://gandalf-api.nebo15.com/api/v1/tables/56c32f02a60ad689060041a9/decisions
```
```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {   
            "_id": "56c32f02a60ad689060041a9",
            "table": {
                "_id": "56c32f02a60ad689060041a9",
                "title": "Test title",
                "description": "Test description"
            },
            "title": "First matched rule title",
            "description": "First matched rule description",
            "final_decision": "Approve",
            "request": {
                "borrowers_phone_verification": "Positive",
                "contact_person_phone_verification": "Positive",
                "internal_credit_history": "Positive",
                "employment": true,
                "property": true
            },
            "rules": [
                {
                    "title": "First matched rule title",
                    "description": "First matched rule description",
                    "decision": null
                },
                {
                    "title": "another rule title",
                    "description": "another rule description",
                    "decision": "Approve"
                }
            ]
        }
    ]
}
```

### Get decision history by id

#### GET /decisions/{id}

```shell
$ curl -H"Authorization: Basic YXV0aDphdXRo" http://gandalf-api.nebo15.com/api/v1/tables/56c32f02a60ad689060041a9/decisions
```

```json
{
    "meta": {
        "code": 200
    },
    "data": {   
        "_id": "56cf1827a60ad63e1000564e",
        "table": {
            "_id": "56c32f02a60ad689060041a9",
            "title": "Test title",
            "description": "Test description"
        },
        "title": "First matched rule title",
        "description": "First matched rule description",
        "final_decision": "Approve",
        "request": {
            "borrowers_phone_verification": "Positive",
            "contact_person_phone_verification": "Positive",
            "internal_credit_history": "Positive",
            "employment": true,
            "property": true
        },
        "rules": [
            {
                "title": "First matched rule title",
                "description": "First matched rule description",
                "decision": null
            },
            {
                "title": "another rule title",
                "description": "another rule description",
                "decision": "Approve"
            }
        ]
    }
}
```
