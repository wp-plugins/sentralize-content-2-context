<?php

add_action('admin_menu', 'sentralize_admin_menu');

function sentralize_admin_menu() {

  add_options_page('Sentralize Settings', 'Sentralize', 'manage_options', 'sentralize', 'sentralize_admin_options');

}

function sentralize_admin_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  $cache_options = array('5 min' => 5*60, '10 min' => 10*60, '15 min' => 15*60, '30 min' => 30*60);

  $cache_ttl = get_option('sentralize_cache_ttl');

  if(isset($_POST['sentralize_cache_ttl']))
  {
	foreach($cache_options as $opt)
	{
		if($_POST['sentralize_cache_ttl'] == $opt)
		{
			update_option('sentralize_cache_ttl', $opt);
			$cache_ttl = $opt;
		}
	}
  }
  ?>

  <div class="wrap">
  <div id="icon-options-general" class="icon32"><br /></div>
  <h2>Sentralize Plugin Settings</h2>
  <form name="sentralize_admin_form" method="post" action="">
  <label for="sentralize_cache_ttl">Cache Timeout: 
  <select id="sentralize_cache_ttl" name="sentralize_cache_ttl" ">

<?php
  foreach($cache_options as $opt)
  {
	echo '<option value="'.$opt.'"'.( $opt == $cache_ttl ? "selected='selected'" : '' ).'>'.$opt.' sec</option>';
  }
?>
  </select>
  </label>

  <p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save" /></p>

  </form> 
  </div>

<?php

}

?>
