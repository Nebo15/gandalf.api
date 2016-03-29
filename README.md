# Gandalf

[![Deployment status from DeployBot](https://nebo15.deploybot.com/badge/56046448049120/64889.svg)](http://deploybot.com)
[![Build Status](https://travis-ci.org/Nebo15/gandalf.api.svg?branch=master)](https://travis-ci.org/Nebo15/gandalf.api)

This is a Back-End project for our Open-Source Decision Engine for Big-Data. You can find front-end here: [Nebo15/gandalf.web](https://github.com/Nebo15/gandalf.web).

API docs is [here](http://nebo15.github.io/qbill.docs/gandalf.html#validation-conditions).

It's build on top of PHP Lumen framework and MongoDB.

# How does it work?

Gandalf allows you to define multiple decision tables and to list all decisions that was made.

You can use it for anti-fraud, risk management, as part any other decision making purposes.

### Features

- Customizable - have the freedom to design a decision rules you need and to manage data structure on the fly in a easy to understand way.
- Split testing - you can group your decision table to run a A/B tests. Later, you can leave only one, that shows best results.
- Analytics and Decision History - you can review all previous decisions made by your tables and to analyze what rules is triggered more often.
- Production-tested - several large NDA-closed PSP's and online lending platforms already use Gandalf.

## Decision Table

It consist of columns that describes API request structure, rows that describe decision-making logic, and cells that represents a single validation rule.

## Columns

Column represent specific request parameter. You can add additional parameters on a fly by adding a column. After saving table with new column all future API request should provide data for it.

However, API consumer can provide more data and you don't need to specify column for each of request fields, in this case we will skip unused ones. Best practise is to feed table with any data you have, so you can use it when you need without changing back-end.

Column can have following settings:

- ```Title``` - human-readable string that describes field in interface.
- ```Field API Key``` - field name from witch data will be taken. For example, if you add a field with key ```name``` than all API consumers should provide JSON with this field: ```{"name": "Some User Name"}```.
- ```Type``` - type of data that will be submitted in this field. ```String```, ```Numeric``` or ```Boolean```.

### Presets

You can modify field value for table rows by adding field preset. For example, you have field called salary and it's too routine to add a "salaries greater than 1000" condition in each row, instead you can create preset that turns ```Numeric``` salary into ```Boolean``` type and simply turn on this validation in each row.

It should look something like this:

- ```Title``` = ```Sufficient Salary```;
- ```Field API Key``` = ```salary```;
- ```Type``` = ```Numeric```;
- ```Preset Condition``` = ```greater than```;
- ```Preset Value``` = ```1000```.

By checking checkbox below ```Low Salary```  column in a row, you will make sure that this row won't pass check untill ```salary``` is greater than ```1000```.

## Rows

Each row represents a rule in an ```OR``` logical operator style.

Row will return value selected in "Decision" column only if all validation rules in it have passed. Rules are checked in a same order as you see them in a table. You can reorder them by drug'n'drop.

## Cells

All cells in a row represent validations in an ```AND``` logical operator style.

Sometimes you have a big table and in some rows you prefer to skip some validations. For this case you can select special validation rule called ```is set```. Logically it means that ```{field_name} is set``` and this condition will always pass validation.

## Validation Conditions

Available rules can differ based on a column type. Generally you should consider all rules logic as follows:
```{request_field_vale} {condition} {condition_value}```. For some conditions you can omit their value.

String fileds support following conditions:

- ```=``` - validation will pass if field value equals specified value.
- ```!=``` - validation will pass if field value does not equal specified value.
- ```in``` - validation will pass if field value eqals to one of listed values. Separate values by comma with space. If searched string have comma you can surround value by single qoute. For example: ```d,e``` in ```a, b, c, 'd,e'``` will return true.
- ```not in``` - validation will pass if field value does not eqal to any of listed values.
- ```contains``` - validation will pass if field value is contains specified value.
- ```is set``` - validation will always pass. (Use it to skip some columns.)

Numeric supports:

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

- ```true``` - will pass if field value is ```true```, ```1```, ```"1"``` or ```'1'```.
- ```false``` - will pass if field value is ```false```, ```0```, ```"0"``` or ```'0'```.
- ```is set``` - validation will always pass. (Use it to skip some columns.)

## Decision Making

The highest row in a table with all validations passed will be returned as a final decision. In API response ```final_decision``` field will be equal to value selected in a "Decision" column for this row. Also we will attach decision title and description, so you can understand what decision rule was triggered first.

If there are no rows with all validations passed, we will return ```final_decision``` that equals a value specified in "Default Decision" dropdown.

# Use Cases

## Decision Table and Rules Engine

There are many cases when you need to make decision based on input data. One good example is a decision to approve or decline lending application. You can setup set of cut-off risk rules to decline applications for a high-risk applicants or to use a decision table to specify what categories of users will receive loan on automatic basis.

## Scoring Table

Instead of rule decision you can set a score point that will be added to a final result. (Also you need to pick another type of decision picking called "Sum of passed decisions").

In a result you will get a total of all scores and you can make further decision based on this value.

# Authorization

For all request you should pass BASIC Authorization header. There are two types of access grants: user and consumer. User can manage tables and access Gandalf GUI; consumer can only submit data and access short decision description by it's id.

```shell
$ curl -uAPI_USER:API_TOKEN http://gandalf-api.nebo15.com/api/v1/admin/tables
```

# Installation Guide

Vagrant up

# API Docs

You can find a full API docs on [this](http://nebo15.github.io/qbill.docs/gandalf.html) page.
