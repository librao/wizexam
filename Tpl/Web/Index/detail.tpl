<div class="row">
	<div class="span9">
		<div class="my_block">
			<h1><strong>{$news_info.title}</strong> - {$news_info.category}</h1>
			<table width="100%">
				<tr>
					<td width="25%">作者:&nbsp;&nbsp;{$news_info.created_by|get_user_info}</td>
					<td></td>
					<if condition="$news_info['created_by'] !== $news_info['updated_by']">
					<td width="25%">编辑:&nbsp;&nbsp;{$news_info.updated_by|get_user_info}</td>
					<else/>
					<td width="25%">&nbsp;</td>
					</if>
					<td width="25%">发布时间:&nbsp;&nbsp;{$news_info.created_at|date="Y-m-d H:i:s",###}</td>
					<td width="25%">关键字:&nbsp;&nbsp;{$news_info.keyword}</td>
				</tr>
				<if condition="!empty($news_info['from'])">
				<tr>
					<td colspan="4">新闻来源:&nbsp;&nbsp;{$news_info.from}</td>
				</tr>
				<else/>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				</if>
			</table>
			<h3>新闻祥情</h3>
			<p>{$news_info.summary}</p>
			<span style="padding:0 5px;"><i class="icon-eye-open"></i>View</span><strong>{$news_info.view}</strong>
			<span style="padding:0 5px;"><i class="icon-thumbs-up"></i><a href="" onclick="">Like</a></span><strong>{$news_info.like}</strong>
			<span style="padding:0 5px;"><i class="icon-thumbs-down"></i><a href="" onclick="">Not Like</a></span><strong>{$news_info.notlike}</strong>
			<section class="inner_section">
				<div class="row">
					<div class="span6">
						<h3><strong>相关链接</strong></h3>
						<ul class="unstyled features">
							<li><i class="icon-tag"></i> <strong>链接1:</strong> <a href="{}" target="_blank">{}</a></li>
							<li><hr></li>
							<li><i class="icon-tag"></i> <strong>链接2:</strong> <a href="{}" target="_blank">{}</a></li>
						</ul>
					</div>
				</div>
			</section>
			<span style="float:right;"><a href="/">返回列表</a></span>
			<div class="clearfix"></div>
		</div>
	</div>
</div>