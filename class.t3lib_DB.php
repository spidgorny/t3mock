<?php

class t3lib_DB {

	/**
	 * @var Config
	 */
	var $config;

	/**
	 * @var MySQL4RP
	 */
	var $db;

	var $debugOutput = false;

	/**
	 * @var boolean
	 */
	public $debug_lastBuiltQuery;

	function __construct() {
		$this->config = Config::getInstance();
		$this->db = $this->config->getDB();
	}

	function sql_query($query) {
		return $this->db->perform($query);
	}

	function SELECTquery($what, $table, $where, $heh = NULL, $order = '', $limit = NULL) {
		$sql = "SELECT $what FROM $table";
		if ($where) {
			$sql .= " WHERE $where";
		}
		if ($order) {
			$sql .= " ORDER BY $order";
		}
		if ($limit) {
			$sql .= " LIMIT $limit";
		}
		$this->debug_lastBuiltQuery = $sql;
		return $sql;
	}

	function exec_SELECTquery($what, $table, $where, $heh = NULL, $order = '') {
		$sql = $this->SELECTquery($what, $table, $where, $heh, $order);
		return $this->sql_query($sql);
	}

	function sql_fetch_assoc($res) {
		return $this->db->fetchAssoc($res);
	}

	function UPDATEquery($table, $where, array $update) {
		$set = $this->quoteArrayForWhere($update);
		$set = implode(', ', $set);
		$sql = "UPDATE $table SET $set WHERE $where";
		$this->debug_lastBuiltQuery = $sql;
		return $sql;
	}

	function exec_UPDATEquery($table, $where, array $update) {
		$sql = $this->UPDATEquery($table, $where, $update);
		return $this->sql_query($sql);
	}

	function quoteArray($update) {
		$set = [];
		foreach ($update as $key => $val) {
			$set[] = "'".$this->escape($val)."'";
		}
		return $set;
	}

	function quoteArrayForWhere($update) {
		$set = [];
		foreach ($update as $key => $val) {
			$set[] = "$key = '".$this->escape($val)."'";
		}
		return $set;
	}

	function escape($val) {
		return $this->db->escape($val);
	}

	function INSERTquery($table, array $insert) {
		$keys = array_keys($insert);
		$keys = implode(', ', $keys);
		$set = $this->quoteArray($insert);
		$values = array_values($set);
		$values = implode(', ', $values);
		$sql = "INSERT INTO $table ($keys) VALUES ($values)";
		$this->debug_lastBuiltQuery = $sql;
		return $sql;
	}

	function exec_INSERTquery($table, array $insert) {
		$sql = $this->exec_INSERTquery($table, $insert);
		return $this->sql_query($sql);
	}

	function sql_insert_id($res = NULL) {
		return $this->db->lastInsertID($res);
	}

	/**
	 * Retrieves data from MySQL result object in to array.
	 *
	 * @param resource $res
	 * @param string $column	Only this column will be the value of the arrays
	 * @param string $key		Data will be associated with this column
	 * @return array
	 */
	function fetchAll($res, $column = NULL, $key = NULL) {
		TaylorProfiler::start(__CLASS__."::".__FUNCTION__);
		$rows = array();
		if (is_string($res)) {
			$res = $this->sql_query($res);
		}
		while ($row = $this->sql_fetch_assoc($res)) {
			//d($this->db->lastQuery, $res, $row);
			//print($res.' '.sizeof($rows).'/'.$this->db->sql_num_rows($res).' '.memory_get_usage().'/'.ini_get('memory_limit').'<br>');
			if ($column) {
				if ($key) {
					$rows[$row[$key]] = $row[$column];
				} else {
					$rows[] = $row[$column];
				}
			} else {
				if ($key) {
					$rows[$row[$key]] = $row;
				} else {
					$rows[] = $row;
				}
			}
		}
		TaylorProfiler::stop(__CLASS__."::".__FUNCTION__);
		return $rows;
	}

	function IDalize($array, $column, $key = 'uid') {
		TaylorProfiler::start(__METHOD__);
		$rows = array();
		foreach ($array as $row) {
			$rows[$row[$key]] = $row[$column];
		}
		TaylorProfiler::stop(__METHOD__);
		return $rows;
	}

	function sql_affected_rows($res = NULL) {
		return $this->db->affectedRows($res);
	}

}
