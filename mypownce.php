<?php
/*
Plugin Name: MyPOWNCE
Plugin URI: http://a86p.wordpress.com/projects/mypownce
Description: Send post to Pownce from wp admin panel
Author: Antonio Perrone <fUsHji>
Author URI: http://a86p.wordpress.com
*/

include ("pownceapi.class.php");
$app_key = '3mvhg564nls311q04iw2jqnq8a3x1a51'; //Do Not Edit
define('APP_KEY', $app_key);


add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {

    add_menu_page('MyPOWNCE', 'MyPOWNCE', 8, __FILE__, 'mt_toplevel_page');

    add_submenu_page(__FILE__, 'Message', 'Message', 8, 'sub-page', 'mt_sublevel_page');

    add_submenu_page(__FILE__, 'Link', 'Link', 8, 'sub-page2', 'mt_sublevel_page2');
	
	add_submenu_page(__FILE__, 'Event', 'Event', 8, 'sub-page3', 'mt_sublevel_page3');
}

function pownce_message($post_data,$to) 
{
  
  		$pownceAPI = new pownceAPI();
		$pownceAPI->setPownceAddress('http://api.pownce.com/2.0/'); //URL for Pownce
		$pownceAPI->setPownceDatasource('send/message.xml');
		$pownceAPI->setPownceUserPass(base64_encode(get_option('p_user').':'.get_option('p_pass')));
		$pownceAPI->setPownceHeaders('Authorization: Basic ' . $pownceAPI->getPownceUserPass());

		
		$pownceAPI->setPownceType('POST');
		$post_options = 'app_key=' . APP_KEY . '&note_to=' . $to . '&note_body=' . $post_data;
		$get_options  = 'app_key=' . APP_KEY;
		$pownceAPI->setPownceOptions($post_options);
		$pownceAPI->initCurl();
		$pownceAPI->execCurl();
}
	
function pownce_link($link=NULL,$post_data,$to) 
{
   		$pownceAPI = new pownceAPI();
		$pownceAPI->setPownceAddress('http://api.pownce.com/2.0/'); //URL for Pownce
		$pownceAPI->setPownceDatasource('send/link.xml');
		$pownceAPI->setPownceUserPass(base64_encode(get_option('p_user').':'.get_option('p_pass')));
		$pownceAPI->setPownceHeaders('Authorization: Basic ' . $pownceAPI->getPownceUserPass());

		
		$pownceAPI->setPownceType('POST');

		$post_options = 'app_key=' . APP_KEY . '&note_to=' . $to . '&url=' . $link . '&note_body=' . $post_data;
		$get_options  = 'app_key=' . APP_KEY;
		$pownceAPI->setPownceOptions($post_options);
		$pownceAPI->initCurl();
		$pownceAPI->execCurl();
}

function pownce_event($hour,$minutes,$month,$day,$year,$name,$location,$post_data,$to) 
{
   		$pownceAPI = new pownceAPI();
		$pownceAPI->setPownceAddress('http://api.pownce.com/2.0/'); //URL for Pownce
		$pownceAPI->setPownceDatasource('send/event.xml');
		$pownceAPI->setPownceUserPass(base64_encode(get_option('p_user').':'.get_option('p_pass')));
		$pownceAPI->setPownceHeaders('Authorization: Basic ' . $pownceAPI->getPownceUserPass());

		$pownceAPI->setPownceType('POST');

		$post_options = 'app_key=' . APP_KEY . '&note_to=' . $to . '&event_name=' . $name . '&event_location=' . $location . '&event_date=' . $year . '-' . $month .'-'.$day.' '.$hour.':'.$minutes.'&note_body='.$post_data;
		$get_options  = 'app_key=' . APP_KEY;
		$pownceAPI->setPownceOptions($post_options);
		$pownceAPI->initCurl();
		$pownceAPI->execCurl();
}

function mt_toplevel_page() {
    $post_flag = "wp_post";
   
    	$opt_val['p_user'] = get_option( 'p_user');
		$opt_val['p_pass'] = get_option( 'p_pass');
	
    	
    	if( $_POST[ $post_flag ] == 'Y' ) {
		
        	$opt_val['p_user'] = $_POST['p_user'];
        	$opt_val['p_pass'] = $_POST['p_pass'];

        	update_option('p_user', $opt_val['p_user']);
	    	update_option('p_pass', $opt_val['p_pass']);

           	echo '<div id="message" class="updated fade"><p><strong>Pownce settings saved</strong></p></div>';
   		 }

   
		echo '<div class="wrap">';
    	echo "<h2>" . __( 'MyPownce settings', 'mt_trans_domain' ) . "</h2>";
    
		echo '<form name="form1" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';   
			echo '<input type="hidden" name="'.$post_flag.'" value="Y">';
			echo '<p>'._e("Pownce account:", 'mt_trans_domain'); 
			echo '<input type="text" name="p_user" value="'.$opt_val['p_user'].'"size="20">';
			echo '<br>';
			
			echo '<p>'._e("password:", 'mt_trans_domain'); 
			echo '<input type="password" name="p_pass" value="'.$opt_val['p_pass'].'"size="20">';
			echo '</p><hr />';

			echo '<p class="submit">';
			echo '<input type="submit" name="Submit" value="Update Options"/>';
			echo '</p>';
		echo '</form>';
		echo '</div>';
}

function mt_sublevel_page() {
    $post_flag = "wp_post";
	
		if( $_POST[ $post_flag ] == 'Y' ) {
        	pownce_message($_POST['post'],$_POST['to']);
            echo '<div id="message" class="updated fade"><p><strong>Message Posted</strong></p></div>';
    	}
		
		echo '<div class="wrap">';

      	echo "<h2>" . __( 'MyPownce post', 'mt_trans_domain' ) . "</h2>";
    
		echo '<form name="form1" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';   
			echo '<input type="hidden" name="'.$post_flag.'" value="Y">';
			echo '<p>'._e("Pownce current status:", 'mt_trans_domain'); 
			echo '<textarea name="post" id="textarea" cols="45" rows="5"></textarea></p>';
			echo 'Send to: <select name="to" id="to">';
				echo '<option value="public">'."Public".'</option>';
				echo '<option value="all">'."All".'</option>';
			echo '</select>';	
			echo '<br>';

			echo '<p class="submit">';
			echo '<input type="submit" name="Submit" value="Send Post"/>';
			echo '</p>';
		echo '</form>';
		echo '</div>';
}

function mt_sublevel_page2() {
    $post_flag = "wp_post";
	
		if( $_POST[ $post_flag ] == 'Y' ) {
        	pownce_link($_POST['link'],$_POST['post'],$_POST['to']);
            echo '<div id="message" class="updated fade"><p><strong>Message Posted</strong></p></div>';
    	}
		
		echo '<div class="wrap">';

      	echo "<h2>" . __( 'MyPownce link', 'mt_trans_domain' ) . "</h2>";
    
		echo '<form name="form2" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';   
			echo '<input type="hidden" name="'.$post_flag.'" value="Y">';
			echo '<p>'._e("Pownce current status:", 'mt_trans_domain');
			echo '<input type="text" name="link" value="http://" size="50"></p><p>';
			echo '<textarea name="post" id="textarea" cols="45" rows="5"></textarea></p>';
			echo 'Send to: <select name="to" id="to">';
				echo '<option value="public">'."Public".'</option>';
				echo '<option value="all">'."All".'</option>';
			echo '</select>';
			echo '<br>';

			echo '<p class="submit">';
			echo '<input type="submit" name="Submit" value="Send Post"/>';
			echo '</p>';
		echo '</form>';
		echo '</div>';
}

function mt_sublevel_page3() {
    $post_flag = "wp_post";
	
		if( $_POST[ $post_flag ] == 'Y' ) {
        	pownce_event($_POST['hour'],$_POST['minutes'],$_POST['month'],$_POST['day'],$_POST['year'],$_POST['name'],$_POST['location'],$_POST['post'],$_POST['to']);
            echo '<div id="message" class="updated fade"><p><strong>Message Posted</strong></p></div>';
    	}
		
		echo '<div class="wrap">';

      	echo "<h2>" . __( 'MyPownce link', 'mt_trans_domain' ) . "</h2>";
    
		echo '<form name="form2" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';   
			echo '<input type="hidden" name="'.$post_flag.'" value="Y">';
			echo '<p>'._e("Pownce current status:", 'mt_trans_domain');
			echo '<input type="text" name="name" value="what\'s happening" size="50"></p><p>';
			echo '<input type="text" name="location" value="where?" size="50"></p><p>';
			
			echo '<select name="hour" id="hour">';
				echo '<option value="00">'."00".'</option>';
				echo '<option value="01">'."01".'</option>';
				echo '<option value="02">'."02".'</option>';
				echo '<option value="03">'."03".'</option>';
				echo '<option value="04">'."04".'</option>';
				echo '<option value="05">'."05".'</option>';
				echo '<option value="06">'."06".'</option>';
				echo '<option value="07">'."07".'</option>';
				echo '<option value="08">'."08".'</option>';
				echo '<option value="09">'."09".'</option>';
				echo '<option value="10">'."10".'</option>';
				echo '<option value="11">'."11".'</option>';
				echo '<option value="12">'."12".'</option>';
				echo '<option value="13">'."13".'</option>';
				echo '<option value="14">'."14".'</option>';
				echo '<option value="15">'."15".'</option>';
				echo '<option value="16">'."16".'</option>';
				echo '<option value="17">'."17".'</option>';
				echo '<option value="18">'."18".'</option>';
				echo '<option value="19">'."19".'</option>';
				echo '<option value="20">'."20".'</option>';
				echo '<option value="21">'."21".'</option>';
				echo '<option value="22">'."22".'</option>';
				echo '<option value="23">'."23".'</option>';
			echo '</select> : ';
			echo '<select name="minutes" id="minutes">';
				echo '<option value="00">'."00".'</option>';
				echo '<option value="01">'."01".'</option>';
				echo '<option value="02">'."02".'</option>';
				echo '<option value="03">'."03".'</option>';
				echo '<option value="04">'."04".'</option>';
				echo '<option value="05">'."05".'</option>';
				echo '<option value="06">'."06".'</option>';
				echo '<option value="07">'."07".'</option>';
				echo '<option value="08">'."08".'</option>';
				echo '<option value="09">'."09".'</option>';
				echo '<option value="10">'."10".'</option>';
				echo '<option value="11">'."11".'</option>';
				echo '<option value="12">'."12".'</option>';
				echo '<option value="13">'."13".'</option>';
				echo '<option value="14">'."14".'</option>';
				echo '<option value="15">'."15".'</option>';
				echo '<option value="16">'."16".'</option>';
				echo '<option value="17">'."17".'</option>';
				echo '<option value="18">'."18".'</option>';
				echo '<option value="19">'."19".'</option>';
				echo '<option value="20">'."20".'</option>';
				echo '<option value="21">'."21".'</option>';
				echo '<option value="22">'."22".'</option>';
				echo '<option value="23">'."23".'</option>';
				echo '<option value="24">'."24".'</option>';
				echo '<option value="25">'."25".'</option>';
				echo '<option value="26">'."26".'</option>';
				echo '<option value="27">'."27".'</option>';
				echo '<option value="28">'."28".'</option>';
				echo '<option value="29">'."29".'</option>';
				echo '<option value="30">'."30".'</option>';
				echo '<option value="31">'."31".'</option>';
				echo '<option value="32">'."32".'</option>';
				echo '<option value="33">'."33".'</option>';
				echo '<option value="34">'."34".'</option>';
				echo '<option value="35">'."35".'</option>';
				echo '<option value="36">'."36".'</option>';
				echo '<option value="37">'."37".'</option>';
				echo '<option value="38">'."38".'</option>';
				echo '<option value="39">'."39".'</option>';
				echo '<option value="40">'."40".'</option>';
				echo '<option value="41">'."41".'</option>';
				echo '<option value="42">'."42".'</option>';
				echo '<option value="43">'."43".'</option>';
				echo '<option value="44">'."44".'</option>';
				echo '<option value="45">'."45".'</option>';
				echo '<option value="46">'."46".'</option>';
				echo '<option value="47">'."47".'</option>';
				echo '<option value="48">'."48".'</option>';
				echo '<option value="49">'."49".'</option>';
				echo '<option value="50">'."50".'</option>';
				echo '<option value="51">'."51".'</option>';
				echo '<option value="52">'."52".'</option>';
				echo '<option value="53">'."53".'</option>';
				echo '<option value="54">'."54".'</option>';
				echo '<option value="55">'."55".'</option>';
				echo '<option value="56">'."56".'</option>';
				echo '<option value="57">'."57".'</option>';
				echo '<option value="58">'."58".'</option>';
				echo '<option value="59">'."59".'</option>';
				echo '<option value="60">'."60".'</option>';
			echo '</select> ';
			
			
			echo '<select name="month" id="month">';
				echo '<option value="01">'."01".'</option>';
				echo '<option value="02">'."02".'</option>';
				echo '<option value="03">'."03".'</option>';
				echo '<option value="04">'."04".'</option>';
				echo '<option value="05">'."05".'</option>';
				echo '<option value="06">'."06".'</option>';
				echo '<option value="07">'."07".'</option>';
				echo '<option value="08">'."08".'</option>';
				echo '<option value="09">'."09".'</option>';
				echo '<option value="10">'."10".'</option>';
				echo '<option value="11">'."11".'</option>';
				echo '<option value="12">'."12".'</option>';		
			echo '</select>';
			
			echo '<select name="day" id="day">';
				echo '<option value="01">'."01".'</option>';
				echo '<option value="02">'."02".'</option>';
				echo '<option value="03">'."03".'</option>';
				echo '<option value="04">'."04".'</option>';
				echo '<option value="05">'."05".'</option>';
				echo '<option value="06">'."06".'</option>';
				echo '<option value="07">'."07".'</option>';
				echo '<option value="08">'."08".'</option>';
				echo '<option value="09">'."09".'</option>';
				echo '<option value="10">'."10".'</option>';
				echo '<option value="11">'."11".'</option>';
				echo '<option value="12">'."12".'</option>';
				echo '<option value="13">'."13".'</option>';
				echo '<option value="14">'."14".'</option>';
				echo '<option value="15">'."15".'</option>';
				echo '<option value="16">'."16".'</option>';
				echo '<option value="17">'."17".'</option>';
				echo '<option value="18">'."18".'</option>';
				echo '<option value="19">'."19".'</option>';
				echo '<option value="20">'."20".'</option>';
				echo '<option value="21">'."21".'</option>';
				echo '<option value="22">'."22".'</option>';
				echo '<option value="23">'."23".'</option>';
				echo '<option value="24">'."24".'</option>';
				echo '<option value="25">'."25".'</option>';
				echo '<option value="26">'."26".'</option>';
				echo '<option value="27">'."27".'</option>';
				echo '<option value="28">'."28".'</option>';
				echo '<option value="29">'."29".'</option>';
				echo '<option value="30">'."30".'</option>';
				echo '<option value="31">'."31".'</option>';		
			echo '</select>';
			
			echo '<select name="year" id="year">';
			for ($i=2008;$i<2021;$i++) 
				echo '<option value='.$i.'>'."$i".'</option>';
			echo '</select></p><p>';
			
			echo '<textarea name="post" id="textarea" cols="45" rows="5"></textarea></p>';
			echo 'Send to: <select name="to" id="to">';
				echo '<option value="public">'."Public".'</option>';
				echo '<option value="all">'."All".'</option>';
			echo '</select>';
			echo '<br>';

			echo '<p class="submit">';
			echo '<input type="submit" name="Submit" value="Send Post"/>';
			echo '</p>';
		echo '</form>';
		echo '</div>';
}

?>