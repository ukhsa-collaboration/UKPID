# User Roles and Permissions

We have 2 sets of data at the moment - one is 3 records, containing all the fields, and the other is many thousands, but
is missing several fields.

## Data Import

1) Split the large spreadsheet into smaller files \
`php artisan enquiry:split-csv <path_to_csv>`

Can also specify:

`--enquiriesPerFile (default 1000)` \
`--maxFileCount (default 2000)` \
`--outputDir (default output)`

2) Import the split files \
`php artisan enquiry:import <inputDir>`

Can also specify:

`--files (default 50)` i.e. max number of files \
`--reprocess-errors` process files in `{inputDir}/error` directory

This will import files in `inputDir`, up to `files` to prevent exceeding memory. \
All successfully processed files will be moved to `{inputDir}/archive`, any failed will be moved to `{inputDir}/error`

So, just keep running the command until all files are processed. Then fix any issues for files in the error directory
and run with `--reprocess-errors.

## Populating large spreadsheet

In `app/Migration/scripts` there are 2 scripts, which are used to populate the large spreadsheet which is missing
columns.

These probably won't need to be run again, but in case they are:

`add-headers.php` to create a file filled with headers and fill each row with corresponding empty values
`fill-key.php` to fill the primary key with data

The paths to the files for these need to be hardcoded.
