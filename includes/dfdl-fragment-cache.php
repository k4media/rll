<?php

/**
 * K4 Fragment Cache.
 * 
 * Class for caching high-load fragments.
 * 
 * @key 
 * @ttl
 * @function
 * 
 * @return 
 */ 

final class K4 {

	private int $ttl;
	private string $cache_file_dir;

	public function __construct () {
		$this->init();
	}

    public function init() {

		/**
	 	 * Cache TTL
	 	 */
		$this->ttl = 7 * DAY_IN_SECONDS;

		/**
		 * Cache directory
		 */
		$this->cache_file_dir = get_template_directory() . '/cache/';

		/**
	 	 * Clear cache on 'post_updated'
	 	 */
		add_action( 'save_post', array($this, 'empty_cache'), 10, 3 );

    }

	private function get_cache_dir() {
		return $this->cache_file_dir;
	}

	private function get_ttl() {
		return $this->ttl;
	}

	/**
	 * Fragment Cache
	 */
	public function fragment_cache(string $key, $function) {

		/**
		 * Cache file path
		 */
		$cache_file_path = $this->get_cache_dir() . $key;

		/**
		 * Default to no cache
		 */ 
		$use_cache = false;

		/**
		 * Start timer
		 */ 
		$time_start = microtime(true);

		/**
		 * Unique string used to split timestamp from html
		 * and that will not collide with common html
		 */
		$splitter = "*#|#*";

		/**
		 * Check cache for existing file
		 */
		if ( file_exists( $cache_file_path ) ) {
							
			$raw_cache 		= file_get_contents( $cache_file_path );	
			$page_cache 	= explode($splitter, $raw_cache );
			$elapsed_time 	= time() - intval($page_cache[0]) ;
			
			/**
			 * Is cache file valid?
			 */
			if ( intval($elapsed_time) < intval( $this->get_ttl() ) ) {
				$use_cache = true;
			}
			
		}
		
		if ( $use_cache == true ) {
					
			/* stop timer */
			$time_end = microtime(true);
			
			$cache_created = date ("F d Y H:i:s.", filemtime($cache_file_path));
			$elapsed_time = $this->elapsed_date($cache_created);
			
			echo "\n\n<!--" . $key . " generated about " . $elapsed_time  . " -->\n\n";		
			echo $page_cache[1] ;	
			
		} else {
				
			ob_start();
			call_user_func($function);
			
			$now = time(); 
			$output = ob_get_clean();
			$file = $now . $splitter . $output ; 
			$handle = fopen( $cache_file_path, 'w') ;
			
			fwrite( $handle, $file );	
			fclose($handle);
			
			/**
			 * Stop timer
			 */
			$time_end = microtime(true);
			
			echo "<!-- " . $key . " fragment generated in " . round($time_end - $time_start, 5) . " seconds -->\n\n";	
			echo $output;
		
		}

	}

	/**
	* SCreate cache key
	*/
	public function cache_key( string $key ): string {
		return get_post_type() . "-" . get_queried_object_id() . "-" . $key . "-" . md5(get_permalink());
	}

	public function empty_cache( string $post_id, $post_before, $post_after): void {
		$files = glob( $this->cache_file_dir . '/*' );
		foreach( $files as $file ){
			if( is_file($file) ) {
				if ( "dfdl_awards" === get_post_type() && str_contains($file, "dfdl-award") ) {
					unlink($file);
				}
			}
		}
	}

	private function elapsed_date(string $a): string {
		//get current timestampt
		$b = strtotime("now"); 
		//get timestamp when tweet created
		$c = strtotime($a);
		//get difference
		$d = $b - $c;
		//calculate different time values
		$minute = 60;
		$hour = $minute * 60;
		$day = $hour * 24;
		$week = $day * 7;
		if(is_numeric($d) && $d > 0) {
			//if less then 3 seconds
			if($d < 3) return "right now";
			//if less then minute
			if($d < $minute) return floor($d) . " seconds ago";
			//if less then 2 minutes
			if($d < $minute * 2) return "about 1 minute ago";
			//if less then hour
			if($d < $hour) return floor($d / $minute) . " minutes ago";
			//if less then 2 hours
			if($d < $hour * 2) return "about 1 hour ago";
			//if less then day
			if($d < $day) return floor($d / $hour) . " hours ago";
			//if more then day, but less then 2 days
			if($d > $day && $d < $day * 2) return "1 day ago";
			//if less then year
			if($d < $day * 365) return floor($d / $day) . " days ago";
			//else return more than a year
			return "over a year ago";
		}
	}

	

}

$K4 = new K4;

?>