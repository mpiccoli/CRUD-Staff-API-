/**
* @Author: Michael Piccoli
* @Version: 1.0
* @Date: 05/01/2016
* @Description: This class is the core of this front end project, which specifies actions when a button fires events and elements behavior
*/
$(document).ready(function(){
  //This is the root url for the API
  var url='https://mike-assignment-api.appspot.com/staff/';
  //This id variable will be used during the staff update
  var idUp=0;
  //When the home page is opened, the search view is the one chosen to be displayed
  $("#searchStaff").show();
  $("#addStaff").hide();
  $("#updateStaff").hide();
  //When the user wants to search for a member of staff, display this view
  $("#loadSearchStaffPHPRequest").click(function(event) {
    $("#searchStaff").show();
    $("#addStaff").hide();
    $("#updateStaff").hide();
	});
  //When the user wants to add a member of staff, display this view
  $("#loadAddStaffPHPRequest").click(function(event) {
    $("#searchStaff").hide();
    $("#addStaff").show();
    $("#updateStaff").hide();
	});
  //When the user wants to update some staff details, display this view
  $("#loadUpdateStaffPHPRequest").click(function(event) {
    idUp=0;
    $("#idUpdate").val('');
    $("#idUpdate").prop("readonly", false);
    $("#searchStaff").hide();
    $("#addStaff").hide();
    $("#updateStage1").show();
    $("#updateStage2").hide();
    $("#updateStaff").show();
	});
  //Action for when the user chooses to retrieve and display all the member of staff in a specific format
	$("#showStaffButton").click(function(event) {
		var selection = $("#inputSelected").find(":selected").val();
		showAllStaffData(selection,url);
	});
  //Action that clears the DIV containing the data from the DB
  $("#clearShowStaffButton").click(function(event) {
		$("#staffListOutput").empty();
	});
  //Action for when the user wants to search for a specific staff given a surname and a forename
	$("#searchFSRequest").click(function(event) {
		var forenameIn = $("#forenameSearch").val();
		var surnameIn = $("#surnameSearch").val();
		searchFSStaff(forenameIn, surnameIn, url);
	});
  //Action for when the user wants to search for a specific staff given only the ID
  $("#searchIDRequest").click(function(event) {
      var idIn=$("#idSearch").val();
      searchIDStaff(idIn,'search',url);
  });
  $("#deleteRequest").click(function(event) {
      var idIn=$("#idSearch").val();
      deleteStaff(idIn, url);
  });
  //Action for when a new staff needs to be added to the system
	$("#addNewStaffRequest").click(function(event){
		// serialize the form and store input variable
		// call the addStaff function, passing in the form containing the new staff data
		var data=$("#addStaffForm").serialize();
		addStaff(data,url);
	});
  //Action for when a staff needs to be updated to the system
  $("#searchIDUpdateRequest").click(function(event){
    idUp=$("#idUpdate").val();
    searchIDStaff(idUp,'update',url);
  });
  //Action for when a staff needs to be updated to the system
  $("#clearUpdateStaffRequest").click(function(event){
    searchIDStaff(idUp,'update',url);
  });
  //Action for when a staff needs to be updated to the system
  $("#updateStaffRequest").click(function(event){
    //The form has not got an attribute ID that specifies the staff ID, therefore this will add it to the form before it is serialized;
    var dataForm=$("#updateStaffForm").serialize()+'&'+$.param({ 'idUpdate': idUp });
    //call the updateStaff function, passing in the form containing the updated details
		updateStaff(dataForm,url);
  });
  //Action that clears the form fields
  $("#clearAddNewStaffRequest").click(function(event){
    $("#forenameAdd").val('');
    $("#surnameAdd").val('');
    $("#locationAdd").val('');
    $("#phoneAdd").val('');
    $("#emailAdd").val('');
	});
  //Action that clears the DIV from the API result
  $("#resetSearchFSRequest").click(function(event){
    //Set the Staff ID to editable, to allow the user to search for another staff
    $("#idSearch").prop("readonly", false);
    $("#surnameSearch").val('');
    $("#forenameSearch").val('');
    $("#idSearch").val('');
    $("#locationSearch").val('');
    $("#phoneSearch").val('');
    $("#emailSearch").val('');
	});
});
/**
* @Description: This method is called when a list of all the member of staff is asked to be displayed
* @Param  sel  this is a string that defines the format of the output requested (JSON/XML/plainText)
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a view containing all the staff elements contained in the database
*/
function showAllStaffData(sel,urlReq){
	$.ajax({
        type: 'GET',
        //url: urlReq + "format/" + sel,
        url: urlReq + "format/" + sel,
        success: function(result){
            $("#staffListOutput").empty();
            //JSON is requested
            if(sel=='json'){
                //Extract the JSON data from the response object returned and add it to the correct DIV
                $.each(result, function(index, element) {
                    $("#staffListOutput").append('<p>'+
                    'Staff ID: '+ result[index].ID + '<br>'+
                    'Surname: '+ result[index].Surname + '<br>'+
                    'Forename: '+result[index].Forename + '<br>'+
                    'Location: '+result[index].Location + '<br>'+
                    'Phone Number: '+result[index].PhoneNumber + '<br>'+
                    'Email: '+result[index].Email +
                    '</p>');
                });
            }
            //XML is requested
            else if(sel=='xml'){
                //Extract the XML data from the response object returned and add it to the correct DIV
                //Adding the specific XML structure
                $(result).find('StaffElement').each(function () {
                    $("#staffListOutput").append('<p>'+
                    '&lt;ID&gt; '+ $(this).find('ID').text() +' &lt;/ID&gt; '+ '<br>'+
                    '&lt;Surname&gt; '+ $(this).find('Surname').text() +' &lt;/Surname&gt; '+ '<br>'+
                    '&lt;Forename&gt; '+ $(this).find('Forename').text() +' &lt;/Forename&gt; '+ '<br>'+
                    '&lt;Location&gt; '+ $(this).find('Location').text() +' &lt;/Location&gt; '+ '<br>'+
                    '&lt;PhoneNumber&gt; '+ $(this).find('PhoneNumber').text() +' &lt;/PhoneNumber&gt; '+ '<br>'+
                    '&lt;Email&gt; '+ $(this).find('Email').text() +' &lt;/Email&gt; '+
                    '</p>');
                });
            }
            //plain text is requested
            else if(sel=='plainText'){
                //Add the result to the DIV as it is since the response does not need decoding
                //since it already contains data in plain text
                $("#staffListOutput").append(result);
            }
        },
        error: function(error){
        	//Display error message
            $("#staffListOutput").empty();
            $("#staffListOutput").append('Error!!! :'+error.statusText);
        }
    });
}
/**
* @Description: This method is called to search for a specific member of staff given a forename and a surname
* @Param  fName  this string contains the staff forename
* @Param  sName  this string contains the staff surname
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a view containing a staff member that corresponds to the forename ans surname requested
*/
function searchFSStaff(fName,sName,urlReq) {
    //Validate the input
    if(fName!='' && sName!=''){
        $.ajax({
            type: 'GET',
            url: urlReq + fName + '/' + sName,
            success: function(result){
                //Extract the JSON object from the response page
                if(result.ID!=null){
                  //Set the Staff ID to not editable, in case the user decides to remove it
                  $("#idSearch").prop("readonly", true);
                  $("#idSearch").val(result.ID);
                  $("#forenameSearch").val(result.Forename);
                  $("#surnameSearch").val(result.Surname);
                  $("#locationSearch").val(result.Location);
                  $("#phoneSearch").val(result.PhoneNumber);
                  $("#emailSearch").val(result.Email);
                }
                //In case the object returned does not contain a valid id, this means no such object with the
                //given forename and surname exists in the system, therefore return this message
                else{
                    alert('No Staff Found!');
                }
            },
            timeout: 10000,
            error: function(error){
                //Display error message
                alert('Error: '+error.statusText);
            }
        });
    }
    //Ask the user to re-insert a valid forename and surname
    else{
        alert('Insert a valid forename and surname');
    }
}
/**
* @Description: This method is called to search for a specific member of staff given an ID
* @Param  id  this string contains the staff unique identifier
* @Param  outputDiv  this string contains a value that is used to check where the output response is going to be placed
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a view containing a staff member that corresponds to the forename ans surname requested
*/
function searchIDStaff(id,outputDiv,urlReq) {
    //Validate the input
    if(id>0){
        $.ajax({
            type: 'GET',
            url: urlReq + id,
            success: function(result){
                //Extract the JSON object from the response page
                if(result.ID!=null){
                  if(outputDiv=='search'){
                    //Set the Staff ID to not editable, in case the user decides to remove it
                    $("#idSearch").prop("readonly", true);
                    $("#idSearch").val(result.ID);
                    $("#forenameSearch").val(result.Forename);
                    $("#surnameSearch").val(result.Surname);
                    $("#locationSearch").val(result.Location);
                    $("#phoneSearch").val(result.PhoneNumber);
                    $("#emailSearch").val(result.Email);
                  }
                  else{
                    idUp=result.ID;
                    //The ID is not editable, therefore, once the user has retrieved the element with a specific ID,
                    //he/she can change data strickly related to that ID, avoiding that another staff data is modified
                    $("#idUpdate").prop("readonly", true);
                    $("#idUpdate").val(result.ID);
                    $("#forenameUpdate").val(result.Forename);
                    $("#surnameUpdate").val(result.Surname);
                    $("#locationUpdate").val(result.Location);
                    $("#phoneUpdate").val(result.PhoneNumber);
                    $("#emailUpdate").val(result.Email);
                    $("#updateStage2").show();
                  }
                }
                //In case the object returned does not contain a valid id, this means no such object with the
                //given forename and surname exists in the system, therefore return this message
                else{
                    alert('No Staff Found!');
                }
            },
            timeout: 10000,
            error: function(error){
                //Display error message
                alert('Error: '+error.statusText);
            }
        });
    }
    //Ask the user to re-insert a valid forename and surname
    else{
        alert('Insert a numerical ID value');
    }
}
/**
* @Description: This method adds a new member of the Staff to the system
* @Param  data  this JSON object contains all the staff data that is going to be inserted in the database
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a message specifying whether the query was successull or not
*/
function addStaff(data,urlReq) {
    //Exctract the surname and forename passed, since they will be used again in this section
    var surnameTemp=$("input[name=surnameAdd]").val();
    var forenameTemp=$("input[name=forenameAdd]").val();
    $.ajax({
        type: 'POST',
        url: urlReq+'addMember',
        data: data,
        //When the post request is successfull, display the data inserted, calling the search method passing
        //the forename and surname previously extracted
        success: function(result){
            //Show result of insertion
            alert(result);
            //Empty text fields
            $("#forenameAdd").val('');
            $("#surnameAdd").val('');
            $("#locationAdd").val('');
            $("#phoneAdd").val('');
            $("#emailAdd").val('');
            //Display the new member and set up the approriate view
            $("#staffListOutput").empty();
            $("#searchStaff").show();
            $("#addStaff").hide();
            $("#updateStaff").hide();
            searchFSStaff(forenameTemp,surnameTemp,urlReq);
        },
        //Display this message in case of error, to remind the user about the rules for a valid email address and phone number
        error: function(error){
          //console.log(error);
           alert('Staff Details Incorrect or incomplete-->\n\nRemember:\n'+
                '-)A valid email address contains the @ symbol\n'+
                '-)A phone number contains only numbers, omit the + for the cuntry code, Ex: for UK(+44), use only 44');
        }
    });
}
/**
* @Description: This method deletes a Staff member from the system
* @Param  id  this string contains the staff unique identifier
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a message specifying whether the query was successull or not
*/
function deleteStaff(id, urlReq){
  //Validate the input
  if(id>0){
      $.ajax({
          type: 'POST',
          url: urlReq+ 'delete',
          data: {'id': id},
          success: function(result){
              alert(result);
              $("#idSearch").prop("readonly", false);
              //Empty the text fields
              $("#staffListOutput").empty();
              $("#idSearch").val('');
              $("#surnameSearch").val('');
              $("#forenameSearch").val('');
              $("#locationSearch").val('');
              $("#phoneSearch").val('');
              $("#emailSearch").val('');
          },
          timeout: 10000,
          error: function(error){
              //Display error message
              alert('Error: '+error.statusText);
          }
      });
  }
  //Ask the user to re-insert a valid forename and surname
  else{
      alert('Insert a valid Staff ID');
  }
}
/**
* @Description: This method updates a Staff member details from the system
* @Param  dataStaff  this JSON object contains all the staff data that is going to be updated in the database
* @Param  urlReq  this string contains the API root url that is going to be used during the AJAX call
* @Return  a message specifying whether the query was successull or not
*/
function updateStaff(dataStaff, urlReq){
  var idStaff=$("input[name=idUpdate]").val();
  $.ajax({
      type: 'POST',
      url: urlReq+'update',
      data: dataStaff,
      //When the post request is successfull, display the data inserted, calling the search method passing the ID
      success: function(result){
          //Show result of insertion
          alert(result);
          //Display the updated member and set up the approriate view
          $("#staffListOutput").empty();
          $("#searchStaff").show();
          $("#addStaff").hide();
          $("#updateStaff").hide();
          searchIDStaff(idStaff,'search',urlReq);
      },
      //Display this message in case of error, to remind the user about the rules for a valid email address and phone number
      error: function(error){
         console.log(error);
         alert('Staff Details Incorrect or incomplete-->\n\nRemember:\n'+
              '-)A valid email address contains the @ symbol\n'+
              '-)A phone number contains only numbers, omit the + for the cuntry code, Ex: for UK(+44), use only 44');
      }
  });
}
