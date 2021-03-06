		<?php
			$query = "SELECT p.product_id as productID, pp.source as imagelink, pp.alt as alt, p.name as productname, ROUND(pr.discount) as discount, pr.net_price as price FROM (products p INNER JOIN prices pr ON pr.product_id = p.product_id)INNER JOIN productphotos pp ON pp.product_id = p.product_id WHERE pr.active = 1 GROUP BY p.product_id ORDER BY pr.discount DESC LIMIT 4";
			$result = $connection->query($query)->fetchAll();
			$sliderquery = "SELECT source, alt FROM slider s INNER JOIN sliderphotos sp ON s.slider_id = sp.slider_id WHERE active = 1";
			$sliderresult = $connection->query($sliderquery)->fetchAll();
			$sliderquery = "SELECT * FROM slider";
			$sliderTitle = $connection->query($sliderquery)->fetch();
		?>
		<div class="banner-section">
			<div id="popup">
				<div class="closeButton">&times;</div>
				<div id="popuphead">
						<h1>Welcome!</h1>
						<p>By clicking OK you are accepting our terms of use. JavaScript must be enabled all the time. Otherwise, the site won't work properly.</p>
				</div>
				<input type="button" value="OK" id="okdugme"/>
			</div>
			<div class="container">
				<div class="banner-grids">
					<div class="col-md-4 banner-grid">
						<h2><?= $sliderTitle->title ?></h2>
						<p>Browse our new collection.</p>
						<a href="index.php?page=products&category=men&subcategory=accessories" class="button"> shop now </a>
					</div>
				<div class="col-lg-8 banner-grid1 flexslider">
						<ul class="slides">
						<?php foreach($sliderresult as $row): ?>
						<li><img src="<?= $row->source ?>" class="img-responsive" alt="<?= $row->alt ?>"/></li>
						<?php endforeach; ?>
						</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		</div>
		<!--sreen-gallery-cursual-->
		</div>
		</div>
		<div class="gallery">
			<div class="container">
			<h3>Featured products - TOP SALE</h3>
			<div class="gallery-grids">
				<?php 
					foreach($result as $row):
				?>
				<div class="col-md-3 gallery-grid">
					<a href="<?php echo 'index.php?page=single&product='.$row->productID?>"><img src="<?= $row->imagelink?>" class="img-responsive" alt="<?=$row->alt?>"/>
					<div class="gallery-info">
					<div class="quick">
					<p><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> view</p>
					</div>
					</div></a>
					<div class="galy-info">
						<p><?=$row->productname?></p>
						<div class="galry">
						<div class="prices">
						<h5 class="item_price">€<?=$row->price?></h5>
						</div>
						<div><p><i>Discount: <?=$row->discount?>%</i></p></div>
					<div class="clearfix"></div>
					</div>
					</div>
				</div>
					<?php endforeach; ?>
				<div class="clearfix"></div>
			</div>
		</div>
		</div>