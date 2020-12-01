
<script>
	$(function() {
		var Accordion = function(el, multiple) {
			this.el = el || {};
			this.multiple = multiple || false;

			// Variables privadas
			var links = this.el.find('.link');
			// Evento
			links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
		}

		Accordion.prototype.dropdown = function(e) {
			var $el = e.data.el;
				$this = $(this),
				$next = $this.next();

			$next.slideToggle();
			$this.parent().toggleClass('open');

			if (!e.data.multiple) {
				$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
			};
		}   

		var accordion = new Accordion($('#accordion'), false);
	});
</script>

<div id="head">
	<div id="head-inner"> 
		<div class="content-dropdownList" id="menuList"> 
			<ul id="accordion" class="accordion">
				<li>
					<div class="link"><i class="fa fa-paint-brush"></i>メニュー<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<li><a href="?page=おすすめ一覧">おすすめ一覧</a></li>
						<li><a href="?page=お知らせ一覧">お知らせ一覧</a></li>
						<li><a href="?page=ショップ検索">ショップ検索</a></li>
						<!--
						<li><a href="?page=お気に入り">お気に入り</a></li>
						<li>
							<?php
								$linkA = '';
								$textA = '';
								if($_SESSION['ID'] == ゲストユーザ['ID']){
									$linkA = '?page=新規ユーザ登録';
									$textA = '新規登録';
								}else{
									$linkA = '?page=ユーザ情報';
									$textA = 'ユーザ情報';
								}
							?>
							<a href="<?php echo $linkA ?>"><?php echo $textA ?></a>
						</li>
						<li><a href="ログイン.php"><?php
							if($_SESSION['ID'] == ゲストユーザ['ID']){
								echo 'ログイン';
							}else{
								echo 'ログアウト';
							}
						?></a></li>
						-->
						<!--
						<li><a href="?page=ヘルプ">ヘルプ</a></li>
						-->
					</ul>
				</li>
			</ul>
		</div> 
		<div id="headerText"> 
			<div id="headerText-inner"> 		
				<label id="site-title">D-Flyer Service</label>
				<!--
    			<label id="site-wlcomeMsg"> ようこそ、<?php echo $_SESSION["ニックネーム"]; ?> さん</label>
				--> 
			</div> 
		</div> 
	</div> 
</div> 
