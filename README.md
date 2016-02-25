# Gandalf
### Fraud shall not pass

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
                    "condition" => "$gte",
                    "value" => 1000
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

### Check

#### POST /tables/{id}/check

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
http://gandalf-api.nebo15.com/api/v1/tables/56c32f02a60ad689060041a9/check
```
```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {   
            "_id": "56c32f02a60ad689060041a9",
            "final_decision": "approve",
            "rules": [
                {
                    "description": "my rule",
                    "decision": null
                },
                {
                    "description": "another rule",
                    "decision": "approve"
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
        "_id": "56c32f02a60ad689060041a9",
        "final_decision": "approve",
        "rules": [
            {
                "description": "my rule",
                "decision": null
            },
            {
                "description": "another rule",
                "decision": "approve"
            }
        ]
    }
}
```