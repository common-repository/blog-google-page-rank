<?php
/*
Plugin Name: Google Page Rank
Plugin URI: http://www.blogovnik.com/besplatni-alati/google-page-rank/pagerank.php
Description: Google Page Rank is plugin that displays in the footer of Your blog ticket with curent Page Rank For Your Blog.
Version: 1.2
Author: Dejan Major - mangup
Author URI: http://www.blogovnik.com/
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$ver= '1.2';
$gfile = dirname(__FILE__) . '/blog google page rank.php';
if(file_exists($gfile)){
//include_once(dirname(__FILE__) . '/blog google page rank.php');
unlink($gfile);
}
function googlepagerank_setup_menu() {
	if (function_exists('current_user_can')) {
		if (!current_user_can('manage_options')) return;
	} else {
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}
	if (function_exists('add_options_page')) {
		add_options_page(__('Google Page Rank'), __('Google Page Rank'), 1, __FILE__, 'googlepagerank_setup_page');
	}
} 

add_action('admin_menu', 'googlepagerank_setup_menu');

function googlepagerank_setup_page(){
include("class/gEncrypter.php");
	global $wpdb;
	global $ver;
	$options['google_page_rank_url']=$_SERVER["SERVER_NAME"];
	$key = "mnbvcxzlkjhgfdspoiuytrewq9";
	$e = new gEncrypter();
	$enc = $e->gED($_SERVER["SERVER_NAME"], $key,1); 
//	$enc="321";
	$options['google_page_rank_code']= $enc;
	$options['google_page_rank_action']=$options['google_page_rank_action'];
	$op_get = get_option('google_page_rank_option');
	if(!$op_get){
	    // There is no data need update!
	    $options['google_page_rank_theme'] = 2;
	    update_option('google_page_rank_option', $options);
	}
	if (isset($_POST['update'])) {
		$options['google_page_rank_action'] = trim($_POST['google_page_rank_action'],'{}');
		$options['google_page_rank_theme']=$_POST['google_page_rank_theme'];
		update_option('google_page_rank_option', $options);
		echo '<div class="updated"><p>' . __('Options saved. Refresh Your Blog to see changes.') . '</p></div>';
	} else {
		
		$options = get_option('google_page_rank_option');
	}
	

	?>
		<div class="wrap">
		<h2><?php echo __('Blog Google Page Rank Setup Page'); ?></h2>

		<form method="post" action="">
		
		<table class="form-table">

			<tr valign="top">
				<th scope="row"><?php _e('You blog host:') ?></th>
				<td>
				<input name="google_page_rank_url" type="text" id="google_page_rank_url" value="<?php echo $options['google_page_rank_url']; ?>" size="60" disabled /><br />
				

				Host name of server where are blog.</em><br />.</td>
			</tr>
			<tr>
			<th scope="row"><?php _e('Page Rank ID:') ?></th>
				<td>
				<input name="google_page_rank_code" type="text" id="google_page_rank_code" value="<?php echo $options['google_page_rank_code']; ?>" size="60" disabled /><br />
				

				This is ID for retriving PR rank from <a href="http://www.blogovnik.com/" title="Get Free Google Page Rank" target="_blank">http://www.blogovnik.com</a>.<br />
				</em><br /></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Select Theme:') ?></th>
				<td>
				<input name="google_page_rank_theme" id="google_page_rank_theme" value="<?php echo $options['google_page_rank_theme']; ?>" type="radio" checked="checked" ><img src="http://www.blogovnik.com/besplatni-alati/google-page-rank/images/<?php echo $options['google_page_rank_theme']; ?>.jpg"><BR><BR><BR>
				<?php
				$themes= array("1","2","4","5","6","7","8","9","10");
				foreach($themes as $themeid){
					echo '
					<input name="google_page_rank_theme" id="google_page_rank_theme" value="'.$themeid.'" type="radio" ><img src="http://www.blogovnik.com/besplatni-alati/google-page-rank/images/'.$themeid.'.jpg"><BR>
					';
					}
				?>
				<br />
				In future we will add more icons.
				</td>			
			</tr>
			<tr>
				<th scope="row"><?php _e('Enable/Disable:') ?></th>
				<td>
				<select name="google_page_rank_action" id="google_page_rank_action">
				<option value="<?php echo $options['google_page_rank_action']; ?>"><?php echo $options['google_page_rank_action']; ?></option>
				<option value="Disable">Disable</option>
				<option value="Enable">Enable</option>

				</select>
				<br />
				Enable/Disable Google Page Rank.
				</td>
			</tr>
			<tr>
			<th scope="row"><?php _e('Preview google page rank image:') ?></th>
				<td>
			 

<!-- Start GOOGLE PAGE RANK -->
<!-- generated by http://www.blogovnik.com -->
<a href="http://www.blogovnik.com/besplatni-alati/google-page-rank/pagerank.php" title="Free Google Page Rank" target="_blank"><img src='http://www.blogovnik.com/besplatni-alati/google-page-rank/googlepagerank.php?theme=<?php echo $options['google_page_rank_theme']; ?>&id=<?php echo $options['google_page_rank_code']; ?>' alt="Free Google Page Rank" border="0"></a>
<!-- End GOOGLE PAGE RANK-->


				<br />
				

				Preview PAGE RANK image.<br />
				</em><br />.</td>
			</tr>
		
		</table>
	
		<div class="submit">
		<input type="submit" name="update" value="<?php _e('Update') ?>"  style="font-weight:bold;" />
		</div>
		</form> 
 <BR><BR>
Chek out other plugins:<BR>
  <A href="http://wordpress.org/extend/plugins/personal-favicon/" target="_blank" title="Personal Favicon">Personal FavIcon !</a>             		
	</div>
	<?php	
}

function GooglePR() {
$options = get_option('google_page_rank_option');
$code= $options['google_page_rank_code'];
$themeid= $options['google_page_rank_theme'];

echo '<center><p> 

<!-- Start GOOGLE PAGE RANK -->
<!-- generated by http://www.blogovnik.com -->
<a href="http://www.blogovnik.com/besplatni-alati/google-page-rank/pagerank.php
" title="Free Google Page Rank" target="_blank"><img src=\'http://www.blogovnik.com/besplatni-alati/google-page-rank/googlepagerank.php?theme='. $themeid .'&id='. $code .'\' alt="Free Google Page Rank" border="0"></a>
<!-- End GOOGLE PAGE RANK-->

</p></center>';
}

$options = get_option('google_page_rank_option');
switch($options[google_page_rank_action]){
	case 'Enable':
	add_action('wp_footer', 'GooglePR');
	break;
	
	default:
	break;
}

?>