<?php
global $wpdb;
if(isset($_POST['stl_node_date'])):
	$_POST = stripslashes_deep($_POST);
	$node_content = $_POST['stl_node_content'];
	$node_date = strtotime($_POST['stl_node_date']);
	$node_status = $_POST['stl_node_status'];
	if($wpdb->insert($wpdb->prefix.'stl_node', array( 'node_content' => $node_content, 'node_date' => $node_date, 'node_status' => $node_status)))
	{
		echo '<div class="updated"><p><strong>新的时间轴事件已经被添加。</strong></p></div>';
	}
	else
	{
		echo '<div class="error"><p><strong>添加失败</strong></p></div>';
	}
endif;
?>
<div style="width:80%;margin:50px;">
添加时间轴事件：
<br >
<br >
<form name="stl_add_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<label for="stl_node_date">时间日期：</label>
<input class="Wdate" id="stl_node_date" name="stl_node_date" type="text" value="<?php if(isset($_POST['stl_node_date'])){echo $_POST['stl_node_date'];} ?>" /> 格式：YYYY-MM-DD
<br >
<br >
<label for="stl_node_status">是否显示：</label>
<select id="stl_node_status" name="stl_node_status">
	<option value="1" <?php if(isset($_POST['stl_node_status'])&&$_POST['stl_node_status']==1){echo 'selected';} ?>>是</option>
	<option value="0" <?php if(isset($_POST['stl_node_status'])&&$_POST['stl_node_status']==0){echo 'selected';} ?>>否</option>
</select>
<br >
<br >
<?php wp_editor( $_POST['stl_node_content'], 'stl_node_content' );?>
<br />
<br >
<input type="submit" name="Submit" value="添加事件" />
</form>
</div>