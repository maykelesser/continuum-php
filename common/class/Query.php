<?php

	/**
	* @author Maykel Esser
	* @copyright (C)2019, Maykel Esser.
	* @version 0.0.1
	* @since 0.0.1
	* @package Continuum 1.0.0.
	*/
	 
	class Query extends Connection
	{
		/**
		* Armazena a query em construção
		* @access private
		* @var String
		*/
		private $sqlQuery;

		/**
		* Formata os campos colocando apostrofo
		* em cada nome, evitando problemas futuros no sql
		* @param String $fields
		* @access protected
		* @return String
		*/
		protected function quotedFields($fields){
			if(!is_array($fields)){
				foreach(explode(',', $fields) as $fields){
				   $field[] = sprintf('`%s`', $fields);
				}
			}elseif(is_array($fields)){
				foreach($fields as $index => $data){
					$field[] = sprintf('`%s`', $index);
				}
			}
			return implode(',', $field);
		}

		/**
		* Recupera o nome tabela da classe 
		* que está chamando o método 
		* @access protected
		* @return String
		*/
		protected function getTable(){
			return sprintf('%s', strtolower(array_pop(explode('\\', get_class($this)))));
		}

		/**
		* Monta uma instrução SELECT
		* @param String $fields
		* @access protected
		* @return Query 
		*/
		public function select($fields = '*'){
			if($fields != '*' && !empty($fields)){
				
				if(is_array($fields)){
					$fields = implode(",", $fields);
				}
				else{
					$fields = $fields;
				}
			}
			else{
				$fields = '*';
			}
			
			$this->sqlQuery = sprintf('SELECT %s', $fields);
			return $this;
		}

		/**
		* Verifica se existe uma cláusula from no sql 
		* @access public
		* @return Boolean
		*/
		public function hasFrom(){
			if(!preg_match('/^(?!UPDATE)(.*)FROM(.*)$/', $this->sqlQuery)){
				return false;
			}else{
				return true;
			}
		}

		/**
		* Verifica se existe uma cláusula has no sql 
		* @access public
		* @return Boolean
		*/
		public function hasSelect(){
			if(!preg_match('/SELECT(.*)|SELECT DISTINCT(.*)/', $this->sqlQuery)){
				return false;
			}else{
				return true;
			}
		}

		/**
		* Adiciona uma claúsula FROM no SQL atual
		* @param String $table
		* @access public
		* @return Query 
		*/
		public function from($table = null){
			$from = !is_null($table) ? sprintf('%s', $table) : $this->getTable();
			$this->sqlQuery = sprintf('%s FROM %s', $this->sqlQuery, $from);
			return $this;
		}

		/**
		* Adiciona uma ordenação nos resultados
		* @param String $order
		* @access public
		* @return Query 
		*/
		public function order($order){
			if(!is_null($order)){
				!$this->hasFrom() ? $this->from() : '';
				$this->sqlQuery = sprintf('%s ORDER BY %s', $this->sqlQuery, $order);
			}
			return $this;
		}

		/**
		* Adiciona um limite aos resultados retornados
		* @param Integer $start
		* @param Integer $end
		* @access public
		* @return Query 
		*/
		public function limit($start, $end){
			if(!is_null($start) || !is_null($end)){
				!$this->hasFrom() ? $this->from() : '';
				$this->sqlQuery = sprintf('%s LIMIT %d,%d', $this->sqlQuery, $start, $end);
			}
			return $this;
		}

		/**
		* Adiciona uma claúsula WHERE no sql
		* @param String $where
		* @access public
		* @return Query 
		*/
		public function where($where){
			if(!is_null($where)){
				$this->sqlQuery = sprintf('%s WHERE %s', $this->sqlQuery, $where);
			}
			return $this;
		}

		/**
		* Adiciona um 'AND' no sql
		* @param String $andWhere
		* @access public
		* @return Query 
		*/
		public function andWhere($andWhere){
			if(!is_null($andWhere)){
				!$this->hasFrom() ? $this->from() : '';
				$this->sqlQuery = sprintf('%s AND %s', $this->sqlQuery, $andWhere);
			}
			return $this;
		}

		/**
		* Adiciona um 'OR' no sql
		* @param String $orWhere
		* @access public
		* @return Query 
		*/
		public function orWhere($orWhere){
			if(!is_null($orWhere)){
				!$this->hasFrom() ? $this->from() : '';
				$this->sqlQuery = sprintf('%s OR %s', $this->sqlQuery, $orWhere);
			}
			return $this;
		}

		/**
		** Adiciona um 'AND' no sql
		* @param String $orWhere
		* @access public
		* @return Query 
		*/
		public function having($having){
			if(!is_null($having)){
				$this->sqlQuery = sprintf('%s HAVING %s', $this->sqlQuery, $having);
			}
			return $this;
		}

		/**
		* Controla o Having
		* @param String $orWhere
		* @access public
		* @return Query 
		*/
		public function andHaving($andHaving){
			if(!is_null($andHaving)){
				$this->sqlQuery = sprintf('%s AND %s', $this->sqlQuery, $andHaving);
			}
			return $this;
		}

		/**
		* Cria uma instrução INSERT
		* @param Array $fields
		* @param String $table
		* @access public
		* @return Query 
		*/
		public function insert(Array $fields, $table = null){
			if(!is_null($fields) || count($fields) != false){
				$table = is_null($table) ? $this->getTable() : '`'.$table.'`';
				$this->sqlQuery = sprintf('INSERT INTO %s (%s)', $table, $this->quotedFields($fields));
			}
			return $this;
		}

		/**
		* Adiciona os valores no SQL, fazendo o cast
		* dos itens no array, string ou integer
		* @param Array  $values
		* @access public
		* @return Query 
		*/
		public function values(Array $values){
			if(!is_null($values) || count($values) != false){
				foreach($values as $index => $data){
					$value[] = "'{$data}'";
				}
				$this->sqlQuery = sprintf('%s VALUES(%s)', $this->sqlQuery, implode(',', $value));
			}
			return $this;
		}

		/**
		* Adiciona uma seleção sem repetição nos resultados
		* nos campos passados no primeiro parâmetro
		* @param String $fields
		* @access public
		* @return Query 
		*/
		public function distinct($fields){
			if(!is_null($fields)){
				$this->sqlQuery = sprintf('SELECT DISTINCT %s', $this->quotedFields($fields));
			}
			return $this;
		}

		/**
		* Cria um group no SQL
		* @param String $group
		* @access public
		* @return Query 
		*/
		public function group($group){
			if(!is_null($group)){
				!$this->hasFrom() ? $this->from() : '';
				$this->sqlQuery = sprintf('%s GROUP BY %s', $this->sqlQuery, $group);
			}
			return $this;
		}

		/**
		* Cria um relacionamento de tabelas
		* Primeiro parâmetro: tipo do join { inner, left, right, natural, cross }
		* Segundo parâmetro: o relacionamento com a claúsula ON
		* Terceiro parâmetro: a tabela a ser relacionada
		* @param String $joinType
		* @param String $join
		* @param String $table
		* @access public
		* @return Query 
		*/
		public function join($joinType = null, $join = null, $table = null){
			$table = is_null($table) ? $this->getTable() : $table;
			$Join = Array('INNER', 'FULL', 'LEFT', 'RIGHT', 'CROSS', 'NATURAL', 'JOIN');
			if(!in_array($joinType, $Join) && !is_null($joinType)){
				throw new InvalidArgumentException('Tipo de join não reconhecido');
			}
			!$this->hasFrom() ? $this->from() : '';
			if(!is_null($join)){
				$this->sqlQuery = sprintf('%s %s JOIN %s ON %s', $this->sqlQuery, $joinType, $table, $join);
			}else{
				$this->sqlQuery = sprintf('%s %s JOIN %s', $this->sqlQuery, $joinType, $table);
			}
			return $this;
		}

		/**
		* Cria uma instrução delete
		* @return Query
		* @access public
		*/
		public function delete(){
			$this->sqlQuery = 'DELETE';
			return $this;
		}

		/**
		* Cria uma instrução update
		* @return Query
		* @access public
		*/
		public function update($table = null, $values){
			$table = is_null($table) ? $this->getTable() : '`'.$table.'`';
			$this->sqlQuery = sprintf('UPDATE %s SET', $table);
			$arrCondition = array();
			if(!is_null($values) || count($values) != false){
				foreach($values as $index => $data){
					$value = "'{$data}'";
					$arrCondition[] = $index." = ".$value;
				}
				$this->sqlQuery = sprintf('%s %s', $this->sqlQuery, implode(", ",$arrCondition));
			}

			return $this;
		}
		 
		/**
		* Recupera o sql como string
		* @return String
		* @access public
		*/
		public function __toString(){
			!$this->hasFrom() && $this->hasSelect() ? $this->from() : '';
			return $this->sqlQuery.";";
		}
	}
