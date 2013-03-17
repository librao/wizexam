<div class="row">
	<div class="span9">
		<div class="my_block">
			<h3 class="heading">{$SUBTITLE}</h3>
			<span><a href="{:U('/Admin/Index/edit')}" title="添加"><i class="icon-plus"></i>添加</a></span>
			<table class="table table-bordered table-striped table_vam">
				<thead>
					<tr>
						<th class="line_l" width="5%">ID</th>
						<th class="line_l" width="15%">Title</th>
						<th class="line_l" width="5%">Category</th>
						<th class="line_l" width="15%">Summary</th>
						<th class="line_l" width="5%">View</th>
						<th class="line_l" width="5%">Like</th>
						<th class="line_l" width="5%">NotLike</th>
						<th class="line_l" width="15%">Creat Date</th>
						<th class="line_l" width="15%">Creat By</th>
						<th class="line_l" width="15%">Action</th>
					</tr>
				</thead>
				<tbody>
					<volist name="news_list" id="vo">
					<tr>
						<th class="line_l" width="5%">{$vo.id}</th>
						<th class="line_l" width="15%">{$vo.title}</th>
						<th class="line_l" width="5%">{$vo.category}</th>
						<th class="line_l" width="15%">{$vo.summary}</th>
						<th class="line_l" width="5%">{$vo.view}</th>
						<th class="line_l" width="5%">{$vo.like}</th>
						<th class="line_l" width="5%">{$vo.notlike}</th>
						<th class="line_l" width="15%">{$vo.created_at|date="Y-m-d H:i:s",###}</th>
						<th class="line_l" width="15%">{$vo.created_by|get_user_info}</th>
						<th class="line_l" width="15%">
							<a href="{:U('/Admin/Index/edit?nid=')}{$vo.id}" title="修改"><i class="icon-edit"></i></a>
							<a href="" title="删除"><i class="icon-remove"></i></a>
						</th>
					</tr>
					</volist>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="span9">
		<div class="my_block pag">
			<button class="btn btn-inverse btn-small">Previous</button>
			<button class="btn btn-info btn-small">1</button>
			<button class="btn btn-inverse btn-small">2</button>
			<button class="btn btn-inverse btn-small">3</button>
			<button class="btn btn-inverse btn-small">4</button>
			<button class="btn btn-inverse btn-small">Next</button>
		</div>
	</div>
</div>