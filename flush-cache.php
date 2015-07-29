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
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_tool($post_id, $post, $update)
	{
		$cacheKey = "wordpress-tools";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_master_class($post_id, $post, $update)
	{
		$cacheKey = "wordpress-master-classes";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_hero($post_id, $post, $update)
	{
		$cacheKey = "wordpress-heroes";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_featured_agent($post_id, $post, $update)
	{
		$cacheKey = "wordpress-featured-agents";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_presenter($post_id, $post, $update)
	{
		$cacheKey = "wordpress-presenters";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function save_post_playbook($post_id, $post, $update)
	{
		$cacheKey = "wordpress-playbooks";
		$result = $this->curl_delete($cacheKey);
		//var_dump($result); exit;
	}

	public function curl_delete($cacheKey)
	{
	    $environment = $this->get_environment();

	    $ch = curl_init();
	    $url = $environment . "/v100/Cache/" . $cacheKey;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_VERBOSE, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    $result = curl_exec($ch);
	    curl_close($ch);

	    return $result;
	}

	public function get_environment()
	{
		$serverName = getenv('HTTP_HOST'); //px-wordpress.gr-dev.com
		$environment = str_replace("-wordpress", "", $serverName);
		return $environment;
	}
}

?>