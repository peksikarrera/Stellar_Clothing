
	<body>
		<!--header-->
		<div class="header">
			<div class="header-top">
				<div class="container">
					<div class="account_info">
						<?php 
						if(isset($_SESSION['user'])){
							if($_SESSION['user']->role_id==1)
								echo '<p>Logged in as admin &nbsp&nbsp&nbsp<a style="color:white" href="AdminPanel/adminpanel.php">Click here to open Admin Panel</a></p>';
						}
						?>
					</div>
					<div class="top-right">
						<ul>
							<?php
							if(empty($_SESSION['user']))
								echo '<li><a href="index.php?page=login">login</a></li>';
							else
								echo '<li><a href="logic/logout.php">logout</a></li>';
							?>
								<?php
								if(isset($_SESSION['user'])){
									if($_SESSION['user']->role_id == 2 ){
									$numberofitems = count($_SESSION['products']);
									echo '<li><div class="cart box_1">
									<a href="index.php?page=checkout">
										<span id="simpleCart_quantity" class="simpleCart_quantity">Cart - items : ' . $numberofitems . '</span>
									</a>	
									<div class="clearfix">
									</div>
									</div></li>';
									}
								}
							?>
						</ul>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="header-bottom">
				<div class="container">
					<!--/.content-->
					<div id="glavnimeni" class="content white">
						<nav class="navbar navbar-default" role="navigation">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<h1 class="navbar-brand">
									<a href="index.php">Stellar Clothing</a>
								</h1>
							</div>
							<!--/.navbar-header-->

							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<?php
									echo '<ul class="nav navbar-nav">';
									$query = "SELECT * FROM headingmenu WHERE parent=0 and level=1";
									$result = $connection->query($query);
									foreach($result as $row){
										$queryresult = $connection->query("SELECT * FROM headingmenu WHERE parent = {$row->item_id}");
										if($queryresult->rowcount()==0){
											echo "<li><a href='{$row->link}'>{$row->name}</a></li>";
										}
										else{
											$counter = $row->item_id-1;
											echo "<li class='dropdown'><a id='dropdown{$counter}' href='{$row->link}'>{$row->name}<b class='caret'></b></a>";
											echo "<ul id='dropdown{$counter}items' class='dropdown-menu multi-column columns-3'><div class='row'>";
											for($i=1;$i<=3;$i++){
												$queryresult2 = $connection->query("SELECT * FROM headingmenu WHERE parent = {$row->item_id} and item_column = $i");
												echo "<div class='col-sm-4'><ul class='multi-column-dropdown'>";
												foreach($queryresult2 as $row2){
													echo "<li><a class='list1' href='{$row2->link}'>{$row2->name}</a></li>";
												}
												echo "</ul></div>";
											}
											echo "</div></ul>";
										}
									}
									echo '</ul>';
								?>
							</div>
							<!--/.navbar-collapse-->
						</nav>
						<!--/.navbar-->
					</div>
				</div>
			</div>
		</div>
		<!--header-->