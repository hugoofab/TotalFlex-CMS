# Migrations

Migrations are tools to allow Total Flex to install or update itself and it's data with no human intervention. They are the first to execute in the Total Flex initialization flow.
Each Migration X, relies that the Migration `X-1` was already executed. So, if developing a Migration, you can just look at the last Migration.
Be sure your migration will work in SQLite, it's the TotalFlex configuration database engine.

## Stardards

- Migrations are stored in a directory called `src/Migrations`. There are all files in the format: `MigrationX.php`, where `X` is the Migration version. 

- Every Migration class **must** specify `VERSION` constant, in this example, the value is `X`.

- Every Migration class **must** be in the namespace `TotalFlex\Migrations`.

- By default, Migrations extends `MigrationBase` class, which provide common functions and context to execution.

## Execution

- In the migration execution process, the first function that will be called is `__constructor`. It receives the database connection instance (`\FluentPDO`) and the table preffix. All the migrations **must** edit **only** the tables with the preffix received in order.

- Second, the `run` function will be invoked. It's defined in `MigrationBase` class, which starts a transaction, calls the `execute` function and, if no exceptions are thrown, commit the transaction and return true; otherwhise rollback it and return false. 

- Last, the real implementation of the Migration code is in the `execute` function. It'll receive the database connection and can perform schema and data migration from last version.

- According with the `run` method return, the next Migration will be run or not.

## Example

`src/Migrations/Migration2.php`

```
<?php

namespace TotalFlex\Migrations;

class Migration2 extends MigrationBase {
	const VERSION = 2;

	public function execute($db) {
		// Drops the table `sample` because its not necessary anymore
		$sample = $this->getTablename('sample');
		$db->getPdo()->query("DROP TABLE $sample;");

		// Create `newtable`
		$newtable = $this->getTablename('newtable');
		$db->getPdo()->query("CREATE TABLE $table (
			`id_newtable` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			`column_1` TEXT NOT NULL,
			`column_2` TEXT NOT NULL
		);")

		// Migrate data from `oldtable` to `newtable` using FluentPDO
		$oldtable = $this->getTablename('oldtable');
		$oldTableData = $db->from($oldtable)->select('*');
		
		foreach ($oldTableData as $oldTableRow) {
			$values = [
				'id_newtable' => $oldTableRow['id_oldtable'], 
				'column_1' => $oldTableRow['column_with_bad_name'],
				'column_2' => strrev($oldTableRow['column_with_bad_data'])
			];

			$query = $fpdo->insertInto($newtable)->values($values)->execute();
		}
	}
}
?>
```