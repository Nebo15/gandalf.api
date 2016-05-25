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
- Split testing (aka. Champion Winner) - each table can have infinite number of different variants, you can allocate traffic between them and leave only best one later.
- Decision History - all decisions are saved with all metadata: request parameters, decision table at request moment of time and decision itself.
- Decision Analytics - you can review all decisions made by your tables and to analyze what rules is triggered more often.
- Revisions History - rollback any changes that was made by you or your collaborators.
- Tooling. Debug your decision tables directly from Gandalf GUI.
- SASS-based Role Model and oAuth 2.0. You can create projects and share access among users to them.
- Production-tested - several large NDA-closed PSP's and online lending platforms already use Gandalf.

# Use Cases

## Decision Table and Rules Engine

There are many cases when you need to make decision based on input data. One good example is a decision to approve or decline lending application. You can setup set of cut-off risk rules to decline applications for a high-risk applicants or to use a decision table to specify what categories of users will receive loan on automatic basis.

## Scoring Table

Instead of rule decision you can set a score point that will be added to a final result. (Also you need to pick another type of decision picking called "Sum of passed decisions").

In a result you will get a total of all scores and you can make further decision based on this value.

# Support

This project is continuously supported on behalf of our customers and as part of our own [SASS-service](https://gandalf.nebo15.com/). We recommend to use it, but feel free to create your own installation of Gandalf.

Feel free to submit tickets in our GitHub repos, we will respond shortly. And if you want some custom conditions, like 99.9% SLA - [contact us](mailto:support@nebo15.com).

# How does it work?

You simply create a decision or scoring table, define some rules and query this table via API.

It makes Gandalf suitable for anti-fraud, loan scoring, transaction scoring, risk management, and as part any other decision making processes.

## Table

Decision table is the main entity in Gandalf. It consist of columns that describes API request structure, rows that describe decision-making logic, and cells that represents a single validation rule.

### Decision Table

Decision table will apply all rules one by one, until one rule is passed and return value in Decision column for this rule.

### Scoring Table

Scoring table will sum all values in Scoring column and return their total.

## Columns

Column represent specific request parameter. You can add additional parameters on a fly by adding a column. After saving table with new column all future API request should provide data for it. (Or at least ```null```, if you want to sometimes omit some request parts.)

<aside class="notice">
All request parameters are required!
</aside>

However, API consumer can provide more data and you don't need to specify column for each of request fields, in this case we will skip unused ones. Best practice is to feed table with any data you have, so you can use it when you need without changing back-end.

Column can have following settings:

- ```Title``` - human-readable string that describes field in interface.
- ```Field API Key``` - field name from witch data will be taken. For example, if you add a field with key ```name``` than all API consumers should provide JSON with this field: ```{"name": "Some User Name"}```.
- ```Type``` - type of data that will be submitted in this field. ```String```, ```Numeric``` or ```Boolean```.

### Presets

You can modify field value for table rows by adding field preset. For example, you have field called salary and it's too routine to add a "salaries greater than 1000" condition in each row, instead you can create preset that turns ```Numeric``` salary into ```Boolean``` type and simply turn on this validation in each rule.

It should look something like this:

- ```Title``` = ```Sufficient Salary```;
- ```Field API Key``` = ```salary```;
- ```Type``` = ```Numeric```;
- ```Preset Condition``` = ```greater than```;
- ```Preset Value``` = ```1000```.

By checking checkbox below ```Low Salary```  column in a row, you will make sure that this row won't pass check until ```salary``` is greater than ```1000```.

## Rows

Row represents a single rule in decision table. All rows are checked in order it was defined. (You can reorder it by drag'n'drop.) Basically, you can think about relation between rows as ```OR``` logical operators (or as ```else if```'s).

Row will return value selected in "Decision" column only if all validation rules in it have passed. Rules are checked in a same order as you see them in a table. You can reorder them by drug'n'drop.

If no one of defined rows passed all conditions, then we will return ```final_decision``` that equals a value specified in "Default Decision" dropdown.

> IF rule1 then return rule1.decision
ELSEIF rule2 then return rule2.decision
ELSEIF rule3 then return rule3.decision
ELSEIF ruleN then return ruleN.decision
ELSE default return default.decision

## Cells

All cells in a row represent validations in an ```AND``` logical operator style (or you can thing about them as a bunch of conditions joined with ```&&``` inside ```if``` statement).

Sometimes you have a big table and in some rows you prefer to skip some validations. For this case you can select special validation rule called ```is set```. Logically it means that ```{field_name} is set``` and this condition will always pass validation.

> IF rule1(cellCondition1 && cellCondition2 && cellConditionN) then return rule1.decision
ELSEIF ruleN(cellCondition1 && cellCondition2 && cellConditionN) then return ruleN.decision
ELSE default return default.decision

## Validation Conditions

Available rules can differ based on a column type. Generally you should consider all rules logic as follows:
```{request_field_vale} {condition} {condition_value}```. For some conditions you can omit their value.

String fields support following conditions:

- ```=``` - validation will pass if field value equals specified value.
- ```!=``` - validation will pass if field value does not equal specified value.
- ```in``` - validation will pass if field value eqals to one of listed values. Separate values by comma with space. If searched string have comma you can surround value by single qoute. For example: ```d,e``` in ```a, b, c, 'd,e'``` will return true.
- ```not in``` - validation will pass if field value does not eqal to any of listed values.
- ```contains``` - validation will pass if field value is contains specified value.
- ```is set``` - validation will always pass. (Use it to skip some columns.)
- ```is null``` - validation will pass if field value equals ```null```. (Use it check if some column is skipped.)

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
- ```is null``` - validation will pass if field value equals ```null```. (Use it check if some column is skipped.)

Boolean supports:

- ```true``` - will pass if field value is ```true```, ```1```, ```"1"``` or ```'1'```.
- ```false``` - will pass if field value is ```false```, ```0```, ```"0"``` or ```'0'```.
- ```is set``` - validation will always pass. (Use it to skip some columns.)
- ```is null``` - validation will pass if field value equals ```null```. (Use it check if some column is skipped.)

# Installation Guide

You can use [Vagrant](https://www.vagrantup.com/) to intialize a development environment. Simply install it, ```cd``` into your project directory and run:

```
vagrant up
```

Also you can notice that we use [Puppet](https://puppetlabs.com/puppet/puppet-open-source) as our configuration tool. Scripts located in [/puppet](https://github.com/Nebo15/gandalf.api/tree/master/puppet) directory of this repo. You can deploy code to your server and simply run:

```
set -o nounset -o errexit -o pipefail -o errtrace
trap 'error "${BASH_SOURCE}" "${LINENO}"' ERR
echo 127.0.0.1 gandalf.yourdomain.com | sudo tee -a /etc/hosts
sudo /bin/bash puppet/initial/init.sh -u "www-data"
```

And your server will be ready for production use!

# Full Docs

You can find a full GUI and API docs on [this](http://nebo15.github.io/qbill.docs/gandalf.html) page.
