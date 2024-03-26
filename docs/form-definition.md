## Tabs / Columns/ Sections

To define a tab, add it to `tabs.json` and then add the corresponding file to the `tabs` folder.

A tab file is broken into columns > sections > fields.

Columns have sections, and sections have a title + fields.

For example, for a 2 column page:

```
[
    {
        // column 1
        "width": 50,
        "sections: [
            {
                "title": "<title>",
                "fields": [
                    ...
                ]
            }
        ]        
    },
    {
        // column 2
        "width": 50,
        "sections: ... 
    }
]
```

Columns, sections and fields can be given an optional `width` parameter to organise the page. Elements should appear
beside each other unless a subsequent element's width takes the total above 100 - then it should appear on a new line.

If needed this can be used in conjunction with the `space` field type.

## Fields

The field types are

- text
- number
- textarea
- dropdown
- checkbox
- radio
- form
- fieldset
- space

### text/number/textarea:

```
{
    "name": "<name>",
    "field": "<field>",
    "type": "<type>"
} 
```

`name` is the name that appears on the form, `field` is the field in the database, `type` is the type above.

### dropdown/radio/lookup:

``` 
{
    "name": "<name>",
    "type": "<type>",
    "field": "<field>",
    "values": { "<value1>": <Name 1>, "<value2>": <Name 2>, ... },
}
```

### checkbox:
``` 
{
    "name": "<name>",
    "type": "checkbox",
    "fields": [
        {
            "name": "<name>",
            "field": "<field>"
        },
        ...
    ]
}
```

For checkboxes with mutually exclusive values.

#### Codes

The `values` property may be replaced with a `code` property that specifies which codeTable the values for a field
should be retrieved from.

```json
{
  "type": "lookup",
  "code": "AGE"
}
```

### form (i.e. table):

```
{
    "type": "form",
    "defaultRows": <defaultRows>,
    "cells": [
        {
            "name": "<name>",
            "field": "<field>"
        },
        ...
    ]
}
```

For array elements that should appear on the frontend as a table/grid.

`defaultRows` is the initial amount of rows to display - it's not been specified whether it should work like this i.e.
with a variable number of rows, or whether the row number will be fixed (probably the former).

The `cells` are just text input fields.

### fieldset

``` 
{
    "type": "fieldset",
    "fieldsetLegend": "<fieldsetLegend>",
    "fields": ...
}
```

If you need to display multiple fields together with just one label, use a fieldset with a `fieldsetLegend` and hidden
names for the fields.

If you need to logically group multiple fields together, leave `fieldsetLegend` blank and don't have empty field names.

### space

```
{
    "type": "space",
    "width: "<width>
}
```

For visual/structural purposes only.

### nameHidden

To hide the name on the form, set this to true rather than leaving the field name blank (for accessibility).

### Conditionals

Each field can have a conditional, e.g. for a value-in comparison:

``` 
"conditional": {
  "field": "<field>",
  "compare": "equals",
  "value": [ "<value1>", "<value2>", ... ]
}
```

If the condition is not met, the field should be greyed out.

`compare` can be `equals` or `includes` and `value` can be a single value or array.

### Defaults

A field may have default value. This can be defined in two ways:

#### Dynamic default

A field can have a dynamic default value when the `default` property is defined.

```json
{
  "type": "lookup",
  "code": "AGE",
  "default": "code"
}
```

The value of the default property must be one of the following:
- `code`: takes the default code from the code table specified in the `code` key.
- `currentDate`: the date at the moment the enquiry is started
- `currentTime`: the time at the moment the enquiry is started

#### Static default

A field may be given a static default value using the `defaultValue` property.

```json
{
  "type": "text",
  "defaultValue": "lorem ipsum"
}
```
