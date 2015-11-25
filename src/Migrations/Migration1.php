<?php

namespace TotalFlex\Migrations;

class Migration1 extends MigrationBase {
	const VERSION = 1;

	public function execute($db) {
		/**
		 * Total Flex Core
		 */
		$table = $this->getTablename('table');
		$db->getPdo()->query("
			CREATE TABLE $table (
				`id_table`			INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`table_name`		TEXT NOT NULL,
				`order`				INTEGER,
				`caption`			TEXT NOT NULL,
				`icon`				TEXT,
				`primary_key`		TEXT,
				`queryselectscript`	TEXT,
				`beforesavescript`	TEXT,
				`aftersavescript`	TEXT
			);
		");

		$field = $this->getTablename('field');
		$db->getPdo()->query("
			CREATE TABLE $field (
				`id_field`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`caption`		TEXT NOT NULL,
				`id_table`		INTEGER NOT NULL,
				`id_field_type`	INTEGER NOT NULL,
				`field_context`	INTEGER NOT NULL
			);
		");

		$field_type = $this->getTablename('field_type');
		$db->getPdo()->query("
			CREATE TABLE $field_type (
				`id_field_type`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`name`			TEXT
			);
		");

		/**
		 * Field Context
		 */

		$field_context = $this->getTablename('field_context');
		$db->getPdo()->query("
			CREATE TABLE $field_context (
				`id_field_context`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`name`				TEXT
			);
		");		

		/**
		 * Field Formatters
		 */
		$field_formatter = $this->getTablename('field_formatter');
		$db->getPdo()->query("
			CREATE TABLE $field_formatter (
				`id_field_context`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`name`				TEXT
			);
		");

		$field_formatter_field = $this->getTablename('field_formatter_field');
		$db->getPdo()->query("
			CREATE TABLE $field_formatter_field (
				`id_field`				INTEGER NOT NULL,
				`id_field_formatter`	INTEGER NOT NULL,
				PRIMARY KEY(id_field, id_field_formatter)
			);
		");		

		/**
		 * Field Search
		 */
		$field_search = $this->getTablename('field_search');
		$db->getPdo()->query("
			CREATE TABLE $field_search (
				`id_field_search`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`name`				TEXT
			);
		");

		$field_search_field = $this->getTablename('field_search_field');
		$db->getPdo()->query("
			CREATE TABLE $field_search_field (
				`id_field`				INTEGER NOT NULL,
				`id_field_search`	INTEGER NOT NULL,
				PRIMARY KEY(id_field, id_field_search)
			);
		");

		/**
		 * Field Rules
		 */

		$field_rule = $this->getTablename('field_rule');
		$db->getPdo()->query("
			CREATE TABLE $field_rule (
				`id_field_rule`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				`name`			TEXT
			);
		");

		$field_rule_field = $this->getTablename('field_rule_field');
		$db->getPdo()->query("
			CREATE TABLE $field_rule_field (
				`id_field_rule`	INTEGER NOT NULL,
				`id_field`		INTEGER NOT NULL,
				`param`			TEXT,
				`fl_not`		INTEGER DEFAULT 0,
				PRIMARY KEY(id_field_rule, id_field)
			);
		");


	}
}