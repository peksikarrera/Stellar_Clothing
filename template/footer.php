<!--footer-->
<div class="footer-section">
			<div class="container">
					<div class="col-md-3 footer-grid">
					<ul>
						<h4>Quick options</h4>
						<?php
							$query = "SELECT * FROM footermenu";
							$result = $connection->query($query)->fetchAll();
							foreach($result as $row){
								echo "<li><a href='{$row->link}'>$row->name</a></li>";
							}
						?>
					</ul>
				</div>
					<div class="col-md-3 footer-grid">
					<ul>
						<h4>MEN</h4>
						<?php
							$query = "SELECT link,name FROM headingmenu WHERE parent = (SELECT item_id FROM headingmenu WHERE name = 'men')";
							$result = $connection->query($query)->fetchAll();
							foreach($result as $row){
								echo "<li><a href='{$row->link}'>$row->name</a></li>";
							}
						?>
					</ul>
					</div>
					<div class="col-md-3 footer-grid">
					<ul>
						<h4>WOMEN</h4>
						<?php
							$query = "SELECT link,name FROM headingmenu WHERE parent = (SELECT item_id FROM headingmenu WHERE name = 'women')";
							$result = $connection->query($query)->fetchAll();
							foreach($result as $row){
								echo "<li><a href='{$row->link}'>$row->name</a></li>";
							}
						?>
					</ul>
					</div>
					<div class="col-md-3 footer-grid1">
					<div class="social-icons">
						<a href="#"><i class="icon"></i></a>
						<a href="#"><i class="icon1"></i></a>
						<a href="#"><i class="icon2"></i></a>
						<a href="#"><i class="icon3"></i></a>
						<a href="#"><i class="icon4"></i></a>
					</div>
					<p>Copyright &copy; 2015 Swim Wear. All rights reserved | Design by <a href="http://w3layouts.com">W3layouts</a></p>
					</div>
				<div class="clearfix"></div>
				</div>
			</div>
		</div>
	<!--footer <!-->
	<?php
		if($page == "home"){
			echo('<script src="js/script1.js"></script>');
		}
		else if($page == ""){
			echo('<script src="js/script1.js"></script>');
		}
		else if($page == "login")
			echo('<script src="js/script2.js"></script>');
		else if($page == "account")
			echo('<script src="js/script3.js"></script>');
		else if($page == "contact")
			echo('<script src="js/script4.js"></script>');
		else if($page == "single"){
			echo("<script src='js/script5.js'></script>");
		}
		else if($page == "products"){
			echo("<script src='js/script6.js'></script>");
		}
	?>
	<script src="js/templatescript.js"></script>
</body>
</html>