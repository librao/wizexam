<section>
	<div class="row">
		<div class="span3">
			<div class="my_block">
				<h3><strong>测试帐号.</strong>librao</h3>
				<h3><strong>测试密码.</strong>123456</h3>
			</div>
		</div>
		<div class="span6">
			<div class="my_block">
				<blockquote>
					<p>用户登录</p>
					<small>Login</small>
				</blockquote>
				<p><strong>新闻管理系统</strong>, 登录后可进一步操作.</p>				
			</div>
		</div>
	</div>
</section>

<section class="inner_section">
	<div class="row">
		<div class="span9">
			<div class="my_block show-grids">
				<form action="{:U('Public/doLogin')}" method="post" id="login_form" class="js_ajax_submit">
					<input type="hidden" name="redirect_url" value="{$Think.get.request_url}" />
					<div class="top_b">用户登录</div>
					<div class="alert alert-info alert-login" id="result">
						请输入正确的用户名和密码。
					</div>
					<div class="cnt_b">
						<div class="formRow">
							<div class="input-prepend">
								<if condition="cookie('user_email') neq null">
								<span class="add-on"><i class="icon-user"></i></span><input type="text" id="user_email" name="user_email" value="{:cookie('user_email')}" />
								<else />
								<span class="add-on"><i class="icon-user"></i></span><input type="text" id="user_email" name="user_email" placeholder="用户名" />
								</if>
							</div>
						</div>
						<div class="formRow">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="user_pass" name="user_pass" placeholder="密码" />
							</div>
						</div>
						<div class="formRow clearfix">
							<label for="auto" class="checkbox"><input id="auto" name="auto" type="checkbox" checked="checked" />记住我?</label>
						</div>
					</div>
					<div class="btm_b clearfix">
						<!-- <button class="btn btn-inverse pull-right" type="button" onclick="ThinkAjax.sendForm('login_form','__URL__/checkLogin/',loginHandle,'result')">Sign In</button> -->
						<button class="btn btn-inverse pull-right" type="submit" >登录</button>
						<span class="link_reg"><a href="#reg_form">没有帐号? 快来注册一个吧！</a></span>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>