	<?php if(isset($_SESSION['cdbgusername'])) { 	?>
	
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
	<br> <br>
		<?php 

 $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
 $query = "select * from users where `sess` = :sess";
 $ins= $conn->prepare($query);
 $ins->bindValue( ":sess", $_SESSION['cdbgusername'], PDO::PARAM_STR );
 $ins->execute();
 $dta = $ins->fetch();

 if(isset($_GET['selected']))
{
	$selected = $_GET['selected'];
}
		?>
		<ul class="nav menu">
			<li class="<?php if($selected=="fetchdb") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=fetchdb"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>Fetch Database</a></li>
			<?php if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3)
{ ?>
			<li class="<?php if($selected=="listdb") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=viewdb"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>Database Lists</a></li> <?php } ?>
			
						<?php if($dta['level'] == 1)
{ ?>
			<li class="<?php if($selected=="listusers") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=listusers"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>List Users</a></li> <?php } ?>

			<!--	<li class="<?php if($selected=="updatedhotlist") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=updatedhotlist"><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg>Updated Hotlist</a></li>
			<li class="<?php if($selected=="assigned") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=assigned"><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg>Assigned Consultants</a></li>
<?php if($dta['level'] == 1 || $dta['level'] == 2)
{ ?>
			<li class="<?php if($selected=="assign") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=assign"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>Assign Consultants</a></li> 

			<li class="<?php if($selected=="listconsultants") { echo "active"; } else { echo "parent"; } ?>">
				<a href="admin.php?action=listconsultants">
					<svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Consultant Lists</use></span>
				</a>
			</li> <?php } ?> 
			
			<?php if($dta['level'] == 1 || $dta['level'] == 3)
{ ?>
			<li class="<?php if($selected=="reqs") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=showreqs"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>Requirements</a></li> 

			<li class="<?php if($selected=="applications") { echo "active"; } else { echo "parent"; } ?>">
				<a href="admin.php?action=showapplications">
					<svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Applications</use></span>
				</a>
			</li> 
			<li class="<?php if($selected=="rc") { echo "active"; } else { echo "parent"; } ?>">
				<a href="admin.php?action=rateconfirmations">
					<svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Rate Confirmations</use></span>
				</a>
			</li> 
			<li class="<?php if($selected=="submissions") { echo "active"; } else { echo "parent"; } ?>">
				<a href="admin.php?action=showsubmissions">
					<svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Submissions</use></span>
				</a>
			</li> 
			
			<li class="<?php if($selected=="ECI") { echo "active"; } else { echo "parent"; } ?>">
				<a href="admin.php?action=interviews">
					<svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Interviews</use></span>
				</a>
			</li> 
			
<?php } ?>
		
<li class="<?php if($selected=="rc") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=rateconfirmation"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>Rate Confirmations</a></li>

<li class="<?php if($selected=="hotlist") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=compose"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>End Client Submissions</a></li>

			<li class="<?php if($selected=="hotlist") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=sentmails"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg>Interview List</a></li>  

<li class="<?php if($selected=="hotlist") { echo "active"; } else { echo "parent"; } ?>">
				<a href="#">
					<span data-toggle="collapse" href="#sub-item-2"><svg class="glyph stroked chevron-down"><use xlink:href="#stroked-chevron-down"></svg>Reports       </use></span>
				</a>
				<ul class="children collapse" id="sub-item-2">
					<li>
						<a class="" href="#">
							<svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>Daily Reports
						</a>
					</li>
					
					<li>
						<a class="" href="#">
							<svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>Weekly Reports
						</a>
					</li>
					<li>
						<a class="" href="#">
							<svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>Monthly Reports
						</a>
					</li>

				</ul>
			</li>  
			
<?php if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3)
{ ?> <li class="<?php if($selected=="listall") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=listall"><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg>All Lists</a></li> <?php } ?>
			
			<li class="<?php if($selected=="clientslist") { echo "active"; } else { echo "parent"; } ?>"><a href="admin.php?action=clientslist"><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg>Clients List</a></li>
	-->
		</ul>

	</div><!--/.sidebar-->
		
	<?php
}
else
{ echo "<script>
alert('Not Authorised to view this page. !!!');
window.location.href='../login.php';
</script>";  } ?>