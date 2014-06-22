<?php
global $wpdb;
$nodes = $wpdb->get_results("SELECT id, node_date, node_status, node_content FROM ".$wpdb->prefix."stl_node ORDER BY id ASC");
$i = 1;
?>
<div style="width:80%;margin:50px;">
时间轴管理 [stl-show-timeline] <a href="admin.php?page=STL_admin_add">添加时间轴</a>
<br />
<br />
<div id="stl_table">
<table class="wp-list-table widefat fixed pages">
<thead>
	<th scope="col" id="id" class="manage-column column-cb check-column" style="text-align:center;"><span>ID</span></th>
	<th scope="col" id="date" class="manage-column column-title sortable desc" style="text-align:center;"><span>日期</span></th>
	<th scope="col" id="date" class="manage-column column-title sortable desc" style="text-align:center;"><span>内容</span></th>
	<th scope="col" id="status" class="manage-column column-title sortable desc" style="text-align:center;"><span>是否显示</span></th>
	<th scope="col" id="option" class="manage-column column-title sortable desc" style="text-align:center;"><span>操作</span></th>
</thead>
<tbody id="the-list">
<?php
	foreach($nodes as $node):
		$date = date('Y-m-d', $node->node_date);
		$content = mb_strimwidth(strip_tags($node->node_content),0,50,'...');
		$status = $node->node_status == 1 ? "是" : "否";
		echo '<tr style="text-align:center;"><td style="text-align:center;">'.$i.'</td><td>'.$date.'</td><td>'.$content.'</td><td>'.$status.'</td><td><a href="admin.php?page=STL_admin_edit&node_id='.$node->id.'" style="margin-right:20px;">修改</a><a href="#" onclick="del('.$node->id.');">删除</a></td></tr>';
		$i++;
	endforeach;
?>
</tbody>
</table>
</div>
</div>
<script type="text/javascript">
	function del(id)
	{
		var ajax_url = "<?php echo admin_url('admin-ajax.php');?>";	
		json = id;
		data = {
				action: "stl_ajax_del_node",
				data: json
				};
		jQuery.post(ajax_url, data, function(response) {
			if(status=1)
			{
				window.location.reload();
			}

			});
	}
</script>