<?php
/**
 * Hook
 */
if ( ! function_exists('show_dbdata')){
	define("SHOW_PROFILING_QUERIES", false);
	define("SHOW_PROFILING_SLOWESTS", true);
	define("SHOW_PROFILING_TABLES", true);
	define("SHOW_PROFILING_TABLES_QUERIES", true);
	define("AJAX_PROFILING", false);

	function show_dbdata(){
		if(!USE_PROFILING){
			return;
		}
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest" && !AJAX_PROFILING){
			return;
		}
		$separate_window = true;
		$precision = 6;
		
		global $start_exec_time;
		$result["all_time"] = getmicrotime() - $start_exec_time;

		$CI = &get_instance();
		$queries = $CI->db->queries;
		$query_times = $CI->db->query_times;

		$all_time = 0;
		$queries_index_cache = array();
		$queries_times_cache = array();
		$index = 1;
		$result["queries_all_count"] = 0;
		$result['tables'] = array('table' => array(), 'queries' => array());
		$result["queries"] = array();
		if($queries){
			foreach($queries as $key => $query){
				$query = preg_replace("/\n/i", " ", $query);
				$md5 = md5($query);

				if(isset($queries_index_cache[$md5]) && $tIndex = $queries_index_cache[$md5]){
					$result["queries"][$tIndex]["time"][] = $query_times[$key];
					$result["queries"][$tIndex]["used"]++;
				}else{
					$result["queries"][$index]["query"] = $query;
					$result["queries"][$index]["used"] = 1;
					$result["queries"][$index]["time"][] = $query_times[$key];
					$queries_index_cache[$md5] = $index;
					$index++;
				}
				$table = preg_match("/FROM \((.*)( AS .*)*\)/iU", $query, $matches);
				if(!empty($matches[1])){
					$result['tables']['table'][$matches[1]] = $matches[1];
					$result['tables']['queries'][$matches[1]][] = round($query_times[$key], $precision) . "\t" . $query;
				}

				if(!isset($queries_times_cache[$md5]) || $queries_times_cache[$md5] < $query_times[$key]) {
					$queries_times_cache[$md5] = $query_times[$key];
				}

				$all_time += $query_times[$key];
				$result["queries_all_count"]++;
			}
		}
		foreach($result["queries"] as $key => $query_data){
			$result["queries"][$key]["all_time"] = array_sum($query_data["time"]);
		}
		$result["query_all_time"] = $all_time;
		arsort($queries_times_cache);
		$slowests = array_slice($queries_times_cache, 0, 5);

		ksort($result['tables']['table']);
		ksort($result['tables']['queries']);

		ob_start();
		echo "<b>All queries count:</b> ".$result["queries_all_count"]."<br>";
		echo "<b>Unique queries count:</b> ".count($result["queries"])."<br>";
		echo "<b>Queries time:</b> ".$result["query_all_time"]."<br>";
		echo "<b>All time:</b> ".$result["all_time"]."<br>";
		echo "<b>Tables count:</b> ".count($result['tables']['table'])."<br><br>";

		if(SHOW_PROFILING_SLOWESTS){
			echo "<b>Slowest queries: </b><br>";
			foreach($slowests as $md5 => $time){
				if($time > 0) {
					echo "&nbsp;&nbsp;&nbsp;" . round($time, $precision) . "\t" . $result["queries"][$queries_index_cache[$md5]]["query"]."<br>";
				}
			}
		}

		if(SHOW_PROFILING_TABLES){
			echo "<br><b>Tables (queries):</b><br>";
			foreach($result['tables']['table'] as $key => $tbl){
				echo $tbl.' ('.count($result['tables']['queries'][$key]).")<br>";
			}
		}

		if(SHOW_PROFILING_TABLES){
			echo "<br><b>Table queries:</b><br>";
			foreach($result['tables']['queries'] as $table => $queries){
				echo '<b>', $table, ':</b>', '<br>';
				foreach($queries as $q){
					echo '&nbsp;&nbsp;&nbsp;', $q.'<br>';
				}
			}
		}

		if(SHOW_PROFILING_QUERIES){
			echo "<pre>queries: ".print_r($result["queries"], true)."<br></pre>";
		}
		$page = ob_get_contents();
		ob_end_clean();
		if($separate_window) {
			echo '<script>
				dbdataWindow = window.open("","db_profiling","width=680,height=600,top=0,left=100,resizable=yes,scrollbars=yes,menubar=yes");
				dbdataWindow.document.body.innerHTML = "";
				dbdataWindow.document.write("<html><title>DB profiling console</title><body>");
				dbdataWindow.document.write("<pre>' . str_replace('"', '\'', $page).'</pre>");
				dbdataWindow.document.write("</body></html>");
				self.focus();
				</script>';
		} else {
			echo $page;
		}
	}
}