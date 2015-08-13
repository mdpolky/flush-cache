<?php 
/*
Plugin Name: Flush Cache Plugin
Description: This is a custom GR plugin that will clear the cache when an item is saved
Version:     1.0
Author:      Matt Polky
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


global $flushCache_plugin;
$flushCache_plugin = new flushCache_plugin();
add_action('save_post_video', array( $flushCache_plugin, 'save_post_video' ) );
add_action('save_post_tool', array( $flushCache_plugin, 'save_post_tool' ) );
add_action('save_post_master-class', array( $flushCache_plugin, 'save_post_master_class' ) );
add_action('save_post_hero', array( $flushCache_plugin, 'save_post_hero' ) );
add_action('save_post_featured-agent', array( $flushCache_plugin, 'save_post_featured_agent' ) );
add_action('save_post_presenter', array( $flushCache_plugin, 'save_post_presenter' ) );
add_action('save_post_playbook', array( $flushCache_plugin, 'save_post_playbook' ) );

class flushCache_plugin {
	
	public function save_post_video($post_id, $post, $update)
	{
		$cacheKey = "wordpress-videos";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_tool($post_id, $post, $update)
	{
		$cacheKey = "wordpress-tools";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_master_class($post_id, $post, $update)
	{
		$cacheKey = "wordpress-master-classes";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_hero($post_id, $post, $update)
	{
		$cacheKey = "wordpress-heroes";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_featured_agent($post_id, $post, $update)
	{
		$cacheKey = "wordpress-featured-agents";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_presenter($post_id, $post, $update)
	{
		$cacheKey = "wordpress-presenters";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function save_post_playbook($post_id, $post, $update)
	{
		$cacheKey = "wordpress-playbooks";
		$this->delete_by_cacheKey($cacheKey);
	}

	public function get_endpoints()
	{
		$environment = $this->get_environment();
		if ($environment === 'dev')
			return Array('http://px.gr-dev.com:80', 'http://px.gr-dev.com:80');
		else if ($environment === 'prod') {
			return Array('http://10.1.40.139:8012',
						 'http://10.1.40.138:8012');
		}
		else
			return Array();
	}

	public function delete_by_cacheKey($cacheKey)
	{
	    $endpoints = $this->get_endpoints();
	    for ($i=0; $i < count($endpoints); $i++) {
		    $this->fire_and_forget_request($endpoints[$i], $cacheKey);
	    }
	}

	public function fire_and_forget_request($url, $cacheKey)
	{
		$parseUrl = parse_url($url);
		$host = $parseUrl['host'];
		$port = $parseUrl['port'];
		
	    $fp = fsockopen($host, $port, $errno, $errstr, 30);
		if (!$fp) {
		    echo "$errstr ($errno)<br />\n";
		} else {
		    $request = "Delete /v100/Cache/".$cacheKey." HTTP/1.1\r\n";
		    $request.= "Host: ".$host."\r\n";
		    $request.= "Connection: Close\r\n\r\n";
		    fwrite($fp, $request);
	            sleep(1);
		    fclose($fp);
		}
	}

	public function get_environment()
	{
		$serverName = getenv('HTTP_HOST'); //px-wordpress.gr-dev.com

		if (strpos($serverName, 'gr-dev') >= 0)
			return 'dev';
		else if (strpos($serverName, 'guaranteedrate') >= 0)
			return 'prod';

		return '';
	}
}

?>