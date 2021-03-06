﻿# Databases Course Project Part 2

## Requirements:

* **Python 3** (not tested with **Phython 2**) - generate the connection variables file (more info below).
	Optionally, this step can be done manually.


## Running PHP

### Setup

To setup the connection variables(database host, database host, database username and password), navigate
to the `srcipts/` directory and execute:

```
$ python connectvars.py -s <databasehost> -d <databasename> -u <databaseuser> -p <databasepassword>
```

or simply:

```
$ python connectvars.py <databasehost> <databasename> <databaseuser> <databasepassword>
```

The scipt will automatically generate the `connectvars.php` file (or **override**, if one already exists), 
which defines the required connection variables used app-wide.

Please, note it's important that the script is run from the specified directory. If you wish to change that,
simply edit the `PHP_PATH` and `CONNECTVARS_PATH` in `connectvars.py`.
