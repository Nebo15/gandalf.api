# Gandalf
### Frodo shall not pass

## Decision table

### Get decision table

#### GET /decisions

```shell
$ curl http://gandalf.api/api/v1/decisions
```

```json
{
    "meta": {
    "code": 200
},
    "data": [
        {   
            "_id": "56c31536a60ad644060041af",
            "default_decision": "approve",
            "fields": [
                {
                    "alias": "borrowers_phone_name",
                    "title": "Borrowers Phone Name",
                    "source": "request",
                    "type": "string"
                },
                {
                    "alias": "contact_person_phone_verification",
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
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Vodaphone"
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
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
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Life"
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
                            "condition": "$eq",
                            "value": "true"
                        }
                    ]
                }
            ]
        }
    ]
}
```


#### PUT /decisions

Update decision table.
Params:

 * `decision` - full decision table with fields, rules, conditions. 


```shell
$ curl -d'{"decision": DECISION_TABLE }' http://gandalf.api/api/v1/decisions
```

```json
{
    "meta": {
    "code": 200
},
    "data": [
        {   
            "_id": "56c31536a60ad644060041af",
            "default_decision": "approve",
            "fields": [
                {
                    "alias": "borrowers_phone_name",
                    "title": "Borrowers Phone Name",
                    "source": "request",
                    "type": "string"
                },
                {
                    "alias": "contact_person_phone_verification",
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
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Vodaphone"
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
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
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Life"
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
                            "condition": "$eq",
                            "value": "true"
                        }
                    ]
                }
            ]
        }
    ]
}
```

## Scoring

### Check

#### POST /scoring/check

Check params from request with decision table.
POST params it's an associative array (dictionary) whose keys are the values of `fields.alias` from decision table.

For decision table, that you can see in example above, you should pass two params:
 * `borrowers_phone_name`
 * `contact_person_phone_verification`

All fields are required!


```shell
$ curl -d'{"borrowers_phone_name": "test", "contact_person_phone_verification": "Life"}' 
http://gandalf.api/api/v1/scoring/check
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
                    "result": null
                },
                {
                    "description": "another rule",
                    "result": "approve"
                }
            ]
        }
    ]
}
```

### Get scoring history

#### GET /scoring/history

Params:

* `size` - **integer**, items per page, default 20
* `page` - **integer**, page

```shell
$ curl http://gandalf.api/api/v1/scoring/history
```

```json
{
    "meta": {
        "code": 200
    },
    "data": [
        {
            "_id": "56c32f02a60ad689060041a9",
            "default_decision": "approve",
            "final_decision": "approve",
            "fields": [
                {
                    "alias": "borrowers_phone_name",
                    "title": "Borrowers Phone Name",
                    "source": "request",
                    "type": "string"
                },
                {
                    "alias": "contact_person_phone_verification",
                    "title": "Contact person phone verification",
                    "source": "request",
                    "type": "bool"
                }
            ],
            "rules": [
                {
                    "decision": "approve",
                    "description": "my",
                    "result": null,
                    "conditions": [
                        {
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Vodaphone",
                            "matched": true
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
                            "condition": "$eq",
                            "value": "true",
                            "matched": false
                        }
                    ]
                },
                {
                    "decision": "decline",
                    "description": "new",
                    "result": null,
                    "conditions": [
                        {
                            "field_alias": "borrowers_phone_name",
                            "condition": "$eq",
                            "value": "Life",
                            "matched": false
                        },
                        {
                            "field_alias": "contact_person_phone_verification",
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