<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
	//Delete permission levels
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deletePermission($deletions)){
		$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
	}
	
	//Create new permission level
	if(!empty($_POST['newPermission'])) {
		$permission = trim($_POST['newPermission']);
		
		//Validate request
		if (permissionNameExists($permission)){
			$errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
		}
		elseif (minMaxRange(1, 50, $permission)){
			$errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));	
		}
		else{
			if (createPermission($permission)) {
			$successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
		}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}
}

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels

require_once("models/header.php"); ?>

<body>
<div class="container-fluid">
  <div class="row-fluid">
	<?php include("left-nav.php"); ?>

	<div class="span9">
		<?php echo resultBlock($errors,$successes); ?>

		
<form name='adminPermissions' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
<table class='table'>
<tr>
<th style="width: 10%;">Delete</th><th>Permission Name</th>
</tr>

<?php
//List each permission level
foreach ($permissionData as $v1) { ?>
	<tr>
	<td><input type='checkbox' name='delete[<?php echo $v1['id']; ?>]' id='delete[<?php echo $v1['id']; ?>]' value='<?php echo $v1['id']; ?>'></td>
	<td><a href='admin_permission.php?id=<?php echo $v1['id']; ?>'><?php echo $v1['name']; ?></a></td>
	</tr>
<?php } ?>

</table>
<p>
<label>Permission Name:</label>
<input type='text' name='newPermission' />
</p>                                
<input class="btn btn-primary" type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.nav-left li').removeClass('active');
		$('.nav-left .admin-perm').addClass('active');
	});
</script>
</body>
</html>