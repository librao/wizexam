<div id="jCrumbs" class="breadCrumb module">
	<ul>
		<li>
			<a href="#"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="#">一级菜单</a>
		</li>
		<li>
			<a href="#">二级菜单</a>
		</li>
		<li>
			<a href="#">三级菜单</a>
		</li>
		<li>
			<a href="#">四级菜单</a>
		</li>
		<li>
			当前信息<div id="list_ajax_loading" style="float:right;display:none;"><img src="__PUBLIC__/Assets/img/ajax_loader.gif" alt="加载中..." /></div>
		</li>
	</ul>
</div>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">{$SUBTITLE}</h3>
		<table class="table table-bordered table-striped table_vam" id="js_DataTable" sAjaxSource="{:U('Admin/User/doUserList')}">
			<thead>
				<tr>
					<!-- <th class="table_checkbox"><input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" /></th> -->
					<th width="5%">UID</th>
					<th width="25%">用户信息</th>
					<th width="6%">管理员</th>
					<th width="6%">积分</th>
					<th width="6%">经验</th>
					<th width="6%">游戏币</th>
					<th width="14%">注册时间</th>
					<th width="12%">最后登录IP</th>
					<th width="5%">状态</th>
					<th width="15%">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- hide elements-->
<div class="hide">
	
	<!-- actions for datatables -->
    <div class="dt_gal_actions">
        <div class="btn-group">
            <button data-toggle="dropdown" class="btn dropdown-toggle">操作 <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#" class="delete_rows_dt" data-tableid="dt_gal"><i class="icon-trash"></i>删除</a></li>
                <li><a href="javascript:void(0)">Lorem ipsum</a></li>
                <li><a href="javascript:void(0)">Lorem ipsum</a></li>
            </ul>
        </div>
    </div>
	
	<!-- confirmation box -->
	<div id="confirm_dialog" class="cbox_content">
		<div class="sepH_c tac"><strong>是否确定删除选中记录?</strong></div>
		<div class="tac">
			<a href="#" class="btn btn-lib confirm_yes">删除</a>
			<a href="#" class="btn confirm_no">取消</a>
		</div>
	</div>
	
</div>