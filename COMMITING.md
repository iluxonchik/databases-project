# Code Guide

## Queries

As noted in [Project's pdf](./Project_Part_2.pdf) the queires should work even if the 
new **columns** or **entries** are added (columns **will not** be removed). Be sure that the queries you submit follow those
rules. This has the following consequences (besides others):

* no `Natural Join`s, use `,` instead (in case new columns are added)
* no `SELECT *`s (in case new columns are added)
* explicity specify column names in `INSERT INTO` statements. This means that instead of

	```sql
	INSERT INTO table_name
	VALUES (value1, value2, value3) 
	```

	use

	```sql
	INSERT INTO table_name (column1, colum2, column3)
	VALUES (value1, value2, value3)
	```

Please note that there are not the only rules to be followed, but rather the main ones.

# Style

* Use 4 **spaces** for tabs

# Workflow

* No pushing directly to `master`
* Include issue (if applicable) **at the begging** of commit message: `(#ISSUE_NUMBER)`
For example, a commit message related to adding a licence, which
also closes the issue would be something like:
```	
(#1) Add LICENCE
Added a licence. Closes #1.
```
* If you are working on something, create an issue (if it does not exist yet) and assign
it to yourself (to make sure that there is no duplicate work)


Feel free to alter this file. If you want to change something, please create an issue,
so that all of the members are aware of the change.