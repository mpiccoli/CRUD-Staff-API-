<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<!-- Import JQuery remote library and the scripts.js file-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="Resources/scripts.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<title>Staff Management</title>
</head>
<body>
	<h1><center> Management System </center></h1>
	<button id="loadSearchStaffPHPRequest" type="button" class="btn">Search a Member of Staff</button>
	<button id="loadAddStaffPHPRequest" type="button" class="btn">Add Staff</button>
	<button id="loadUpdateStaffPHPRequest" type="button" class="btn">Update Staff Details</button><br><br>
	<!-- This section is specific for the search staff given a forename and surname-->
	<div id="searchStaff">
		<div class="col-md-5">
			<div class="panel panel-primary">
				<div class="panel-heading">Search for a Staff Member</div>
				<div class="panel-body">
					<div class="form-group row">
						<label for="idSearch" class="col-sm-2 form-control-label">Staff ID</label>
						<div class="col-sm-10">
							<input type="text" id="idSearch" name="idSearch" value="" class="form-control" placeholder="ID"><br>
							<button id="searchIDRequest" type="button" class="btn btn-info">Search By ID</button>
						</div>
					</div>
					<div class="form-group row">
						<label for="forenameSearch" class="col-sm-2 form-control-label">Forename</label>
						<div class="col-sm-10">
							<input type="text" id="forenameSearch" name="forenameSearch" value="" class="form-control" placeholder="Forename">
						</div>
					</div>
					<div class="form-group row">
						<label for="surnameSearch" class="col-sm-2 form-control-label">Surname</label>
						<div class="col-sm-10">
							<input type="text" id="surnameSearch" name="surnameSearch" value="" class="form-control" placeholder="Surname"><br>
							<button id="searchFSRequest" type="button" class="btn btn-info">Search By Name</button>
						</div>
					</div>
					<div class="form-group row">
						<label for="locationSearch" class="col-sm-2 form-control-label">Location</label>
						<div class="col-sm-10">
							<input type="text" id="locationSearch" name="locationSearch" value="" class="form-control" placeholder="Location">
						</div>
					</div>
					<div class="form-group row">
						<label for="phoneSearch" class="col-sm-2 form-control-label">Phone Number</label>
						<div class="col-sm-10">
							<input type="text" id="phoneSearch" name="phoneSearch" value="" class="form-control" placeholder="Phone Number (E.g. 4416123745598)">
						</div>
					</div>
					<div class="form-group row">
						<label for="emailSearch" class="col-sm-2 form-control-label">Email</label>
						<div class="col-sm-10">
							<input type="text" id="emailSearch" name="emailSearch" value="" class="form-control" placeholder="Email Address">
						</div>
					</div>
					<button id="deleteRequest" type="button" class="btn btn-danger">Delete Staff</button>
					<button id="resetSearchFSRequest" type="button" class="btn btn-warning">Reset Fields</button>
				</div>
			</div>
		</div>
		<!-- This section is dedicated to the retrieval of all the staff members and display them in different format-->
		<div class="col-md-5">
			<div class="panel panel-primary">
				<div class="panel-heading">Retrieve all the staff members</div>
				<div class="panel-body">
					<label for="inputSelected" class="col-sm-2 form-control-label">Select Output</label>
					<div class="col-sm-4">
						<select name="format" id="inputSelected" class="form-control">
							<option value="json">Default (JSON)</option>
							<option value="xml">XML</option>
							<option value="plainText">Plain Text</option>
						</select>
					</div>
					<button id="showStaffButton" type="button" class="btn btn-success">Show List </button>
					<button id="clearShowStaffButton" type="button" class="btn btn-warning">Clear Results</button><br><br>
					<!-- The resulting data containing information regarding all the staff will be placed here-->
					<div class="panel panel-warning">
						<div class="panel-heading">Output</div>
						<div class="panel-body">
							<div id="staffListOutput"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- This section focuses on the creation of the new member of staff-->
	<div id="addStaff" class="col-xs-4">
		<h3>Add a new Staff Member</h3><br>
		<form id='addStaffForm' class="form-group">
			<div class="form-group row">
				<label for="forenameAdd" class="col-sm-2 form-control-label">Forename</label>
				<div class="col-sm-10">
					<input type="text" id="forenameAdd" name="forenameAdd" value="" class="form-control" placeholder="Forename">
				</div>
			</div>
			<div class="form-group row">
				<label for="surnameAdd" class="col-sm-2 form-control-label">Surname</label>
				<div class="col-sm-10">
					<input type="text" id="surnameAdd" name="surnameAdd" value="" class="form-control" placeholder="Surname">
				</div>
			</div>
			<div class="form-group row">
				<label for="locationAdd" class="col-sm-2 form-control-label">Location</label>
				<div class="col-sm-10">
					<input type="text" id="locationAdd" name="locationAdd" value="" class="form-control" placeholder="Location">
				</div>
			</div>
			<div class="form-group row">
				<label for="emailAdd" class="col-sm-2 form-control-label">Email Address</label>
				<div class="col-sm-10">
					<input type="text" id="emailAdd" name="emailAdd" value="" class="form-control" placeholder="Email Address">
				</div>
			</div>
			<div class="form-group row">
				<label for="phoneAdd" class="col-sm-2 form-control-label">Phone Number</label>
				<div class="col-sm-10">
					<input type="text" id="phoneAdd" name="phoneAdd" value="" class="form-control" placeholder="Phone Address">
				</div>
			</div>
		</form>
		<button id="addNewStaffRequest" type="button" class="btn btn-success">Add Staff</button>
		<button id="clearAddNewStaffRequest" type="button" class="btn btn-warning">Clear Fields</button>
	</div>
	<!-- This section focuses on the Update of a staff data-->
	<div id="updateStaff" class="col-xs-4">
		<h3>Update Staff Details</h3><br>
		<div id="updateStage1">
			ID: <input type="text" id="idUpdate" name="idUpdate" value="">
			<button id="searchIDUpdateRequest" type="button" class="btn btn-success">Search</button>
		</div>
		<div id="updateStage2">
			<form id='updateStaffForm' class="form-group">
				<div class="form-group row"><br>
					<label for="forenameUpdate" class="col-sm-2 form-control-label">Forename</label>
					<div class="col-sm-10">
						<input type="text" id="forenameUpdate" name="forenameUpdate" value="" class="form-control" placeholder="Forename">
					</div>
				</div>
				<div class="form-group row">
					<label for="surnameUpdate" class="col-sm-2 form-control-label">Surname</label>
					<div class="col-sm-10">
						<input type="text" id="surnameUpdate" name="surnameUpdate" value="" class="form-control" placeholder="Surname">
					</div>
				</div>
				<div class="form-group row">
					<label for="locationUpdate" class="col-sm-2 form-control-label">Location</label>
					<div class="col-sm-10">
						<input type="text" id="locationUpdate" name="locationUpdate" value="" class="form-control" placeholder="Location">
					</div>
				</div>
				<div class="form-group row">
					<label for="emailUpdate" class="col-sm-2 form-control-label">Email Address</label>
					<div class="col-sm-10">
						<input type="text" id="emailUpdate" name="emailUpdate" value="" class="form-control" placeholder="Email Address">
					</div>
				</div>
				<div class="form-group row">
					<label for="phoneUpdate" class="col-sm-2 form-control-label">Phone Number</label>
					<div class="col-sm-10">
						<input type="text" id="phoneUpdate" name="phoneUpdate" value="" class="form-control" placeholder="Phone Address">
					</div>
				</div>
			</form>
			<button id="updateStaffRequest" type="button" class="btn btn-success">Update</button>
			<button id="clearUpdateStaffRequest" type="button" class="btn btn-warning">Undo Changes</button>
		</div>
	</div>
</body>
</html>
