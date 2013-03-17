<div class="so_main">
<div class="page_tit">{$SUBTITLE}</div>
<div class="list">
<table id="js_DataTable" sAjaxSource="{:U('Admin/User/doUserList')}" width="100%" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr>
	<th class="line_l" width="5%">UID</th>
	<th class="line_l" width="25%">用户信息</th>
	<th class="line_l" width="6%">管理员</th>
	<th class="line_l" width="6%">积分</th>
	<th class="line_l" width="6%">经验</th>
	<th class="line_l" width="6%">游戏币</th>
	<th class="line_l" width="14%">注册时间</th>
	<th class="line_l" width="12%">最后登录IP</th>
	<th class="line_l" width="5%">状态</th>
	<th class="line_l" width="15%">操作</th>
</tr>
</thead>
<tbody>
</tbody>
</table>
<script>
$(document).ready(function(){
	var options = {
		/*aoColumns: [
			{"bSearchable": false, "bSortable": false},
			null,
			{"bSearchable": false},
			{"bSearchable": false},
			{"bSearchable": false},
			{"bSearchable": false},
			{"bSearchable": false, "bSortable": false}
		],
		aaSorting: [[0, 'asc']],*/
		//bStateSave: true,
		//iCookieDuration: 1800,
		//sCookiePrefix: Wilead.cookiesPrefix + 'dataTable_'
	};
    Wilead.dataTable(options);
});
</script>
</div>