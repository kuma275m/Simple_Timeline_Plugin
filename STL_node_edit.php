<?php
global $wpdb;
if(isset($_POST['stl_node_id'])):
	$_POST = stripslashes_deep($_POST);
	$id = $_POST['stl_node_id'];
	$node_content = $_POST['stl_node_content'];
	$node_date = strtotime($_POST['stl_node_date']);
	$node_status = $_POST['stl_node_status'];
	if($wpdb->update($wpdb->prefix.'stl_node', array( 'node_content' => $node_content, 'node_date' => $node_date, 'node_status' => $node_status ), array( 'id' => $id ) ))
	{
		echo '<div class="updated"><p><strong>时间轴事件已经被修改。</strong></p></div>';
	}
	else
	{
		echo '<div class="error"><p><strong>修改失败</strong></p></div>';
	}
endif;
if(isset($_GET['node_id'])):
	$nodes = $wpdb->get_results("SELECT id, node_content, node_date, node_status FROM ".$wpdb->prefix."stl_node WHERE id = ".$_GET['node_id']." limit 1 ");
endif;
if(!isset($_GET['node_id'])):
	wp_safe_redirect(home_url('/wp-admin/admin.php?page=STL_admin'));
endif;
?>
<div style="width:80%;margin:50px;">
修改时间轴事件：(<a href="admin.php?page=STL_admin_add">添加时间轴</a>)
<br >
<br >
<form name="stl_add_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<label for="stl_node_date">时间日期：</label>
<input class="Wdate" id="stl_node_date" name="stl_node_date" type="text" value="<?php echo date('Y-m-d',$nodes[0]->node_date); ?>" /> 格式：YYYY-MM-DD
<input type="hidden" id="stl_node_id" name="stl_node_id" value="<?php echo $nodes[0]->id;?>" />
<br >
<br >
<label for="stl_node_status">是否显示：</label>
<select id="stl_node_status" name="stl_node_status">
	<option value="1" <?php if($nodes[0]->node_status==1){echo 'selected';} ?>>是</option>
	<option value="0" <?php if($nodes[0]->node_status==0){echo 'selected';} ?>>否</option>
</select>
<br >
<br >
<?php wp_editor( $nodes[0]->node_content, 'stl_node_content' );?>
<br />
<br >
<input type="submit" name="Submit" value="修改事件" />
</form>
</div>