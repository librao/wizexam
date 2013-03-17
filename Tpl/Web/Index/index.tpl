<div class="row">
	<div class="span9">
		<div class="my_block">
			<h1>Lib <strong>新闻发布系统</strong></h1>
			<p>
				如需要对新闻进行管理，
				<if condition="is_login()"><a href="./admin2lib" title="Login">点此</a>进入管理<else/>>请<a href="./login" title="Login">点此</a>进行登录</if>
			</p>
			<div id="filters" class="btn-group">
				<button data-filter="*" class="btn btn-info btn-small">所有</button>
				<button data-filter=".design" class="btn btn-inverse btn-small">Web Design</button>
				<button data-filter=".coding" class="btn btn-inverse btn-small">Coding</button>
				<button data-filter=".marketing" class="btn btn-inverse btn-small">Marketing</button>
			</div>
		</div>
	</div>
</div>

<section>
	<div id="portfolio" class="row">
        
		<volist name="news_list" id="vo">
		<div class="span3 {$vo.category} block">
			<!--
			<div class="view view-first">
				<div class="tringle"></div>
				<img src="assets/img/gal1-2.jpg" alt="" />
				<div class="mask">
					<a href="assets/img/gal1-2.jpg" rel="prettyPhoto" class="info"></a>
					<a href="/detail/{$vo.id}" class="link"></a>
				</div>
			</div>
			-->
			<div class="descr">
				<h4><a href="/detail/{$vo.id}">{$vo.title}</a></h4>
				<p>{$vo.summary}</p>
			 </div>
		</div>
		</volist>

	</div>
</section>

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