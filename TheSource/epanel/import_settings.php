<?php 
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Replace ePanel settings with sample data values</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;
	
	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP 'http://et_sample_images.com'");
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();
				
				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];
			
			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );
			
			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );
			
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $local_image ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;
	
	$importOptions = 'YTo5Njp7czowOiIiO047czoyMjoidGhlc291cmNlX2NvbG9yX3NjaGVtZSI7czo1OiJCbGFjayI7czoyMDoidGhlc291cmNlX2Jsb2dfc3R5bGUiO047czoyMDoidGhlc291cmNlX2dyYWJfaW1hZ2UiO047czoyMjoidGhlc291cmNlX2NhdG51bV9wb3N0cyI7czoxOiI2IjtzOjI2OiJ0aGVzb3VyY2VfYXJjaGl2ZW51bV9wb3N0cyI7czoxOiI1IjtzOjI1OiJ0aGVzb3VyY2Vfc2VhcmNobnVtX3Bvc3RzIjtzOjE6IjUiO3M6MjI6InRoZXNvdXJjZV90YWdudW1fcG9zdHMiO3M6MToiNSI7czoyMToidGhlc291cmNlX2RhdGVfZm9ybWF0IjtzOjY6Ik0gaiwgWSI7czoyMToidGhlc291cmNlX3VzZV9leGNlcnB0IjtOO3M6MzI6InRoZXNvdXJjZV9yZWNlbnRfZnJvbWNhdF9kaXNwbGF5IjtzOjI6Im9uIjtzOjIyOiJ0aGVzb3VyY2VfaG9tZV9jYXRfb25lIjtzOjQ6IkJsb2ciO3M6MjI6InRoZXNvdXJjZV9ob21lX2NhdF90d28iO3M6ODoiRmVhdHVyZWQiO3M6MjQ6InRoZXNvdXJjZV9ob21lX2NhdF90aHJlZSI7czo5OiJQb3J0Zm9saW8iO3M6MjM6InRoZXNvdXJjZV9ob21lX2NhdF9mb3VyIjtzOjQ6IkJsb2ciO3M6MjQ6InRoZXNvdXJjZV9ob21lcGFnZV9wb3N0cyI7czoxOiI4IjtzOjI0OiJ0aGVzb3VyY2VfZXhsY2F0c19yZWNlbnQiO047czoxODoidGhlc291cmNlX2ZlYXR1cmVkIjtzOjI6Im9uIjtzOjE5OiJ0aGVzb3VyY2VfZHVwbGljYXRlIjtzOjI6Im9uIjtzOjE4OiJ0aGVzb3VyY2VfZmVhdF9jYXQiO3M6ODoiRmVhdHVyZWQiO3M6MjI6InRoZXNvdXJjZV9mZWF0dXJlZF9udW0iO3M6MToiMyI7czoyMToidGhlc291cmNlX3NsaWRlcl9hdXRvIjtOO3M6MTk6InRoZXNvdXJjZV91c2VfcGFnZXMiO047czoyNjoidGhlc291cmNlX3NsaWRlcl9hdXRvc3BlZWQiO3M6NDoiNTAwMCI7czoyMDoidGhlc291cmNlX2ZlYXRfcGFnZXMiO047czoxOToidGhlc291cmNlX21lbnVwYWdlcyI7YToxOntpOjA7czozOiI3MjQiO31zOjI2OiJ0aGVzb3VyY2VfZW5hYmxlX2Ryb3Bkb3ducyI7czoyOiJvbiI7czoxOToidGhlc291cmNlX2hvbWVfbGluayI7czoyOiJvbiI7czoyMDoidGhlc291cmNlX3NvcnRfcGFnZXMiO3M6MTA6InBvc3RfdGl0bGUiO3M6MjA6InRoZXNvdXJjZV9vcmRlcl9wYWdlIjtzOjM6ImFzYyI7czoyNzoidGhlc291cmNlX3RpZXJzX3Nob3duX3BhZ2VzIjtzOjE6IjMiO3M6MTg6InRoZXNvdXJjZV9tZW51Y2F0cyI7YToxOntpOjA7czoxOiIxIjt9czozNzoidGhlc291cmNlX2VuYWJsZV9kcm9wZG93bnNfY2F0ZWdvcmllcyI7czoyOiJvbiI7czoyNjoidGhlc291cmNlX2NhdGVnb3JpZXNfZW1wdHkiO3M6Mjoib24iO3M6MzI6InRoZXNvdXJjZV90aWVyc19zaG93bl9jYXRlZ29yaWVzIjtzOjE6IjMiO3M6MTg6InRoZXNvdXJjZV9zb3J0X2NhdCI7czo0OiJuYW1lIjtzOjE5OiJ0aGVzb3VyY2Vfb3JkZXJfY2F0IjtzOjM6ImFzYyI7czoyNToidGhlc291cmNlX2Rpc2FibGVfdG9wdGllciI7TjtzOjE5OiJ0aGVzb3VyY2VfcG9zdGluZm8yIjthOjQ6e2k6MDtzOjY6ImF1dGhvciI7aToxO3M6NDoiZGF0ZSI7aToyO3M6MTA6ImNhdGVnb3JpZXMiO2k6MztzOjg6ImNvbW1lbnRzIjt9czoyMDoidGhlc291cmNlX3RodW1ibmFpbHMiO3M6Mjoib24iO3M6Mjc6InRoZXNvdXJjZV9zaG93X3Bvc3Rjb21tZW50cyI7czoyOiJvbiI7czozMToidGhlc291cmNlX3RodW1ibmFpbF93aWR0aF9wb3N0cyI7czozOiIxNDAiO3M6MzI6InRoZXNvdXJjZV90aHVtYm5haWxfaGVpZ2h0X3Bvc3RzIjtzOjM6IjE0MCI7czoyNToidGhlc291cmNlX3BhZ2VfdGh1bWJuYWlscyI7TjtzOjI4OiJ0aGVzb3VyY2Vfc2hvd19wYWdlc2NvbW1lbnRzIjtOO3M6MzE6InRoZXNvdXJjZV90aHVtYm5haWxfd2lkdGhfcGFnZXMiO3M6MzoiMTQwIjtzOjMyOiJ0aGVzb3VyY2VfdGh1bWJuYWlsX2hlaWdodF9wYWdlcyI7czozOiIxNDAiO3M6MTk6InRoZXNvdXJjZV9wb3N0aW5mbzEiO2E6NDp7aTowO3M6NjoiYXV0aG9yIjtpOjE7czo0OiJkYXRlIjtpOjI7czoxMDoiY2F0ZWdvcmllcyI7aTozO3M6ODoiY29tbWVudHMiO31zOjI2OiJ0aGVzb3VyY2VfdGh1bWJuYWlsc19pbmRleCI7czoyOiJvbiI7czozMToidGhlc291cmNlX3RodW1ibmFpbF93aWR0aF91c3VhbCI7czozOiIxNDAiO3M6MzI6InRoZXNvdXJjZV90aHVtYm5haWxfaGVpZ2h0X3VzdWFsIjtzOjM6IjE0MCI7czoyMzoidGhlc291cmNlX2N1c3RvbV9jb2xvcnMiO047czoxOToidGhlc291cmNlX2NoaWxkX2NzcyI7TjtzOjIyOiJ0aGVzb3VyY2VfY2hpbGRfY3NzdXJsIjtzOjA6IiI7czoyNDoidGhlc291cmNlX2NvbG9yX21haW5mb250IjtzOjA6IiI7czoyNDoidGhlc291cmNlX2NvbG9yX21haW5saW5rIjtzOjA6IiI7czoyNDoidGhlc291cmNlX2NvbG9yX3BhZ2VsaW5rIjtzOjA6IiI7czozMToidGhlc291cmNlX2NvbG9yX3BhZ2VsaW5rX2FjdGl2ZSI7czowOiIiO3M6MjQ6InRoZXNvdXJjZV9jb2xvcl9oZWFkaW5ncyI7czowOiIiO3M6Mjk6InRoZXNvdXJjZV9jb2xvcl9zaWRlYmFyX2xpbmtzIjtzOjA6IiI7czoyMToidGhlc291cmNlX2Zvb3Rlcl90ZXh0IjtzOjA6IiI7czoyNzoidGhlc291cmNlX2NvbG9yX2Zvb3RlcmxpbmtzIjtzOjA6IiI7czoyNDoidGhlc291cmNlX3Nlb19ob21lX3RpdGxlIjtOO3M6MzA6InRoZXNvdXJjZV9zZW9faG9tZV9kZXNjcmlwdGlvbiI7TjtzOjI3OiJ0aGVzb3VyY2Vfc2VvX2hvbWVfa2V5d29yZHMiO047czoyODoidGhlc291cmNlX3Nlb19ob21lX2Nhbm9uaWNhbCI7TjtzOjI4OiJ0aGVzb3VyY2Vfc2VvX2hvbWVfdGl0bGV0ZXh0IjtzOjA6IiI7czozNDoidGhlc291cmNlX3Nlb19ob21lX2Rlc2NyaXB0aW9udGV4dCI7czowOiIiO3M6MzE6InRoZXNvdXJjZV9zZW9faG9tZV9rZXl3b3Jkc3RleHQiO3M6MDoiIjtzOjIzOiJ0aGVzb3VyY2Vfc2VvX2hvbWVfdHlwZSI7czoyNzoiQmxvZ05hbWUgfCBCbG9nIGRlc2NyaXB0aW9uIjtzOjI3OiJ0aGVzb3VyY2Vfc2VvX2hvbWVfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjI2OiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV90aXRsZSI7TjtzOjMyOiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV9kZXNjcmlwdGlvbiI7TjtzOjI5OiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV9rZXl3b3JkcyI7TjtzOjMwOiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV9jYW5vbmljYWwiO047czozMjoidGhlc291cmNlX3Nlb19zaW5nbGVfZmllbGRfdGl0bGUiO3M6OToic2VvX3RpdGxlIjtzOjM4OiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV9maWVsZF9kZXNjcmlwdGlvbiI7czoxNToic2VvX2Rlc2NyaXB0aW9uIjtzOjM1OiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV9maWVsZF9rZXl3b3JkcyI7czoxMjoic2VvX2tleXdvcmRzIjtzOjI1OiJ0aGVzb3VyY2Vfc2VvX3NpbmdsZV90eXBlIjtzOjIxOiJQb3N0IHRpdGxlIHwgQmxvZ05hbWUiO3M6Mjk6InRoZXNvdXJjZV9zZW9fc2luZ2xlX3NlcGFyYXRlIjtzOjM6IiB8ICI7czoyOToidGhlc291cmNlX3Nlb19pbmRleF9jYW5vbmljYWwiO047czozMToidGhlc291cmNlX3Nlb19pbmRleF9kZXNjcmlwdGlvbiI7TjtzOjI0OiJ0aGVzb3VyY2Vfc2VvX2luZGV4X3R5cGUiO3M6MjQ6IkNhdGVnb3J5IG5hbWUgfCBCbG9nTmFtZSI7czoyODoidGhlc291cmNlX3Nlb19pbmRleF9zZXBhcmF0ZSI7czozOiIgfCAiO3M6MzM6InRoZXNvdXJjZV9pbnRlZ3JhdGVfaGVhZGVyX2VuYWJsZSI7czoyOiJvbiI7czozMToidGhlc291cmNlX2ludGVncmF0ZV9ib2R5X2VuYWJsZSI7czoyOiJvbiI7czozNjoidGhlc291cmNlX2ludGVncmF0ZV9zaW5nbGV0b3BfZW5hYmxlIjtzOjI6Im9uIjtzOjM5OiJ0aGVzb3VyY2VfaW50ZWdyYXRlX3NpbmdsZWJvdHRvbV9lbmFibGUiO3M6Mjoib24iO3M6MjY6InRoZXNvdXJjZV9pbnRlZ3JhdGlvbl9oZWFkIjtzOjA6IiI7czoyNjoidGhlc291cmNlX2ludGVncmF0aW9uX2JvZHkiO3M6MDoiIjtzOjMyOiJ0aGVzb3VyY2VfaW50ZWdyYXRpb25fc2luZ2xlX3RvcCI7czowOiIiO3M6MzU6InRoZXNvdXJjZV9pbnRlZ3JhdGlvbl9zaW5nbGVfYm90dG9tIjtzOjA6IiI7czoyMDoidGhlc291cmNlXzQ2OF9lbmFibGUiO047czoxOToidGhlc291cmNlXzQ2OF9pbWFnZSI7czowOiIiO3M6MTc6InRoZXNvdXJjZV80NjhfdXJsIjtzOjA6IiI7czoyMToidGhlc291cmNlXzQ2OF9hZHNlbnNlIjtzOjA6IiI7fQ==';
	
	/*global $options;
	
	foreach ($options as $value) {
		if( isset( $value['id'] ) ) { 
			update_option( $value['id'], $value['std'] );
		}
	}*/
	
	$importedOptions = unserialize(base64_decode($importOptions));
	
	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
	update_option( $shortname . '_use_pages', 'false' );
} ?>