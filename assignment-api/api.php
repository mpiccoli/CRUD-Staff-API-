<?php
/**
* @Author: Michael Piccoli
* @Version: 1.0
* @Date: 02/01/2016
* @Description: This class is the core of this project, which includes a list of APIs ready for the back-end user to use
*/
//These two files are very important for an api, which allow to create and interact with Response and Request data object
include ('StaffInfo.php');
include('StaffDAO.php');
//This files are required by the Micro-framework Silex for the creation of RESTful web services
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
require_once 'silex/vendor/autoload.php';
//Instantiate the web service
$app = new Silex\Application();
//Enable the debug during development
//$app['Debug']=True;
//This sets the header of any response sent back, with the aim of handling possible CORS issues
$app->after(function (Request $request, Response $response) {
   //All domains can connect to the API
   //$response->headers->set("Access-Control-Allow-Origin","https://mike-assignment-frontend.appspot.com");
   $response->headers->set("Access-Control-Allow-Origin","*");
   //Specify the methods accepted by this web service
   $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
   //$response->headers->set("Access-Control-Allow-Headers", "X-Requested-With,Content-Type");
});

//API routes
$app->get('/', 'welcomePage');
$app->get('/staff', 'retrieveAllMembersNoFormat');
$app->get('/staff/format/{dataTypeRequested}', 'retrieveAllMembers');
$app->get('/staff/{forename}/{surname}', 'searchByNameNoFormat');
$app->get('/staff/formatFS/{forename}/{surname}/{format}', 'searchByName');
$app->get('/staff/{id}', 'searchByIdNoFormat')->assert('id', '\d+');//Accept only id with value > 0
$app->get('/staff/formatID/{id}/{format}', 'searchById');
$app->post('staff/addMember', 'addNewStaff');
$app->post('staff/delete', 'deleteStaffMember')->assert('id', '\d+');//Accept only id with value > 0;
$app->post('staff/update', 'updateStaffMember');

/**
* @Description: This method is calles when the API home page is requested
* @Return a welcome string
*/
function welcomePage(){
   return 'Welcome to the Staff Management System API!!!';
}
/**
* @Description: This API retrieves and displays all the staff members from the DB, when no format has been specified
* @Return a response containing a json object where all the data in the database is included
*/
function retrieveAllMembersNoFormat(){
  //Call the DAO that returns a data object with all the details for each member of staff
  $staffList=StaffDAO::returnAllStaff();
  //Create a Response object that will contain the data in the right format to display to the final user
  $responsePage=new Response();
  if($staffList!=NULL){
    $responsePage->setStatusCode(200);
    $responsePage->headers->set('Content-Type', 'application/json');
    $arrayTemp= array();
    //Each element in the db response needs to be in array format for then be encoded to JSON
    foreach($staffList as $element){
        array_push($arrayTemp, $element->toArray());
    }
    //Remove reference to $staffList
    unset($element);
    $responsePage->setContent(json_encode($arrayTemp));
  }
  else{
    $responsePage->setStatusCode(200);
    $responsePage->headers->set('Content-Type', 'text/html');
    $responsePage->setContent('Sorry, There is no Data to Display');
  }
  return $responsePage;
}
/**
* @Description: This API retrieves and displays all the staff from the DB, using multiple formats
* @Param  $dataTypeRequested  this is a string that specifies the format of the requested output (JSON/XML/plainText)
* @Return a response containing an object where all the data in the database is included. The format of this Object
* is specified in the request when this method is called
*/
function retrieveAllMembers ($dataTypeRequested){
    $responde=new Response();
    //Call the DAO that returns a data object with all the details for each member of staff
    $staffList=StaffDAO::returnAllStaff();
    //Create a Response object that will contain the data in the right format to display to the final user
    $responsePage=new Response();
    if($staffList!=NULL){
        $responsePage->setStatusCode(200);
        //If the response needs to be formatted in XML
        if($dataTypeRequested=='xml'){
            $responsePage->headers->set('Content-Type', 'application/xml');
            //Create a XML tag for each element found and add it to the response page
            $xmlOutput = new SimpleXMLElement('<Staff/>');
            foreach($staffList as $element){
                $elementXML=$xmlOutput->addChild('StaffElement');
                $elementXML->addChild('ID', $element->getID());
                $elementXML->addChild('Surname', $element->getSurname());
                $elementXML->addChild('Forename', $element->getForename());
                $elementXML->addChild('Location', $element->getLocation());
                $elementXML->addChild('PhoneNumber', $element->getPhoneNumber());
                $elementXML->addChild('Email', $element->getEmail());
            }
            //Remove reference to $staffList
            unset($element);
            $responsePage->setContent($xmlOutput->asXML());
        }
        //If the response needs to be contain JSON objects
        else if($dataTypeRequested=='json'){
            $responsePage->headers->set('Content-Type', 'application/json');
            $arrayTemp= array();
            //Each element in the db response needs to be in array format for then be encoded to JSON
            foreach($staffList as $element){
                array_push($arrayTemp, $element->toArray());
            }
            //Remove reference to $staffList
            unset($element);
            $responsePage->setContent(json_encode($arrayTemp));
        }
        //If the response needs to be formatted in plain text
        else if($dataTypeRequested=='plainText'){
            $responsePage->headers->set('Content-Type', 'text/html');
            $arrayTemp= array();
            //Create a string to identify each object find in the db
            foreach($staffList as $element){
                array_push($arrayTemp, $element->toString());
            }
            //Remove reference to $staffList
            unset($element);
            $responsePage->setContent(json_encode($arrayTemp));
        }
        //If other outputs are requested, return an error page
        else{
            $responsePage->setStatusCode(400);
            $responsePage->headers->set('Content-Type', 'text/html');
            $responsePage->setContent('Type Not Yet Supported');
        }
    }
    //Return this message in case there is no data to display
    else{
        $responsePage->setStatusCode(200);
        $responsePage->headers->set('Content-Type', 'text/html');
        $responsePage->setContent('Sorry, There is no Data to Display');
    }
    return $responsePage;
}
/**
* @Description: This API looks for a specific staff member given his/her forename and surname, but not format specified for the response object
* @Param  $forename  this is the forename of the person
* @Param  $surname  this is the surname of the person
* @Return a response page containing the object found in the database in JSON format
*/
function searchByNameNoFormat ($forename, $surname){
    $retrievedStaff=null;
    $responsePage=new Response();
    //Verify the forename and surname given are valid and not empty object
    if($forename!="" && $surname!=""){
      $responsePage->setStatusCode(200);
      $responsePage->headers->set('Content-Type', 'application/json');
        //search for such member in the db through the DAO object
        $retrievedStaff=StaffDAO::findStaffByName($forename, $surname);
        //If an object has been returned, return it in JSON format
        if($retrievedStaff!=null){
            $responsePage->setContent(json_encode($retrievedStaff->toArray()));
        }
        //Else, create an empty StaffInfo object, encode it to json and return it as response
        else{
            $temp=new StaffInfo('Empty','Empty');
            $responsePage->setContent(json_encode($temp->toArray()));
        }
    }
    //Return this response page in case the values passed are not suitable
    else{
        $responsePage->setStatusCode(400);
        $responsePage->headers->set('Content-Type', 'text/html');
        $responsePage->setContent('Insert a valid name');
    }
    return $responsePage;
}
/**
* @Description: This API looks for a specific staff member given a forename, surname and response format that needs to be returned
* @Param  $forename  this is the forename of the person
* @Param  $surname  this is the surname of the person
* @Param  $format  this is the format of the response
* @Return a response page containing the object found in the database in the requested format
*/
function searchByName ($forename, $surname, $format){
    $retrievedStaff=null;
    $responsePage=new Response();
    //Verify the forename and surname given are valid and not empty object
    if($forename!="" && $surname!="" && $format!=""){
      $responsePage->setStatusCode(200);
      if($format=="json"){
        return searchByNameNoFormat($forename, $surname);
      }
      else if($format=="xml"){
        $responsePage->headers->set('Content-Type', 'application/xml');
        //search for such member in the db through the DAO object
        $retrievedStaff=StaffDAO::findStaffByName($forename, $surname);
        //Create a XML tag for each element found and add it to the response page
        $xmlOutput = new SimpleXMLElement('<Staff/>');
        $xmlOutput->addChild('ID', $retrievedStaff->getID());
        $xmlOutput->addChild('Surname', $retrievedStaff->getSurname());
        $xmlOutput->addChild('Forename', $retrievedStaff->getForename());
        $xmlOutput->addChild('Location', $retrievedStaff->getLocation());
        $xmlOutput->addChild('PhoneNumber', $retrievedStaff->getPhoneNumber());
        $xmlOutput->addChild('Email', $retrievedStaff->getEmail());
        $responsePage->setContent($xmlOutput->asXML());
      }
      else if($format=="plainText"){
        $responsePage->headers->set('Content-Type', 'text/html');
        //search for such member in the db through the DAO object
        $retrievedStaff=StaffDAO::findStaffByName($forename, $surname);
        $responsePage->setContent(json_encode($retrievedStaff->toArray()));
      }
      else{
        $responsePage->setStatusCode(500);
        $responsePage->headers->set('Content-Type', 'text/html');
        $responsePage->setContent('Format Type not Supported at this moment in time!');
      }
    }
    //Return this response page in case the values passed are not suitable
    else{
        $responsePage->setStatusCode(400);
        $responsePage->headers->set('Content-Type', 'text/html');
        $responsePage->setContent('Insert a valid name');
    }
    return $responsePage;
}
/**
* @Description: This API looks for a specific staff member given an ID, but no format specifies for the response object
* @Param  $id  this is the ID of the person
* @Return a response page containing the object found in the database in the JSON format
*/
function searchByIdNoFormat ($id){
    $retrievedStaff=null;
    $responsePage=new Response();
    //Verify the forename and surname given are valid and not empty object
    if($id>0){
        $responsePage->setStatusCode(200);
        $responsePage->headers->set('Content-Type', 'application/json');
        //search for such member in the db through the DAO object
        $retrievedStaff=StaffDAO::findStaffById($id);
        //If an object has been returned, return it in JSON format
        if($retrievedStaff!=null){
            $responsePage->setContent(json_encode($retrievedStaff->toArray()));
        }
        //Else, create an empty StaffInfo object, encode it to json and return it as response
        else{
            $temp=new StaffInfo('Empty','Empty');
            $responsePage->setContent(json_encode($temp->toArray()));
        }
    }
    //Return this response page in case the values passed are not suitable
    else{
        $responsePage->setStatusCode(400);
        $responsePage->headers->set('Content-Type', 'text/html');
        $responsePage->setContent('Insert a valid ID');
    }
    return $responsePage;
}
/**
* @Description: This API looks for a specific staff member given an ID and the response format that needs to be returned
* @Param  $id  this is the ID of the person
* @Param  $format  this is the format the response needs to be returned as
* @Return a response page containing the object found in the database in the requested format
*/
function searchById ($id, $format){
  $retrievedStaff=null;
  $responsePage=new Response();
  $responsePage->headers->set('Content-Type', 'text/html');
  //Verify the forename and surname given are valid and not empty object
  if($id>0 && $format!=""){
    $responsePage->setStatusCode(200);
    if($format=="json"){
      $responsePage=searchByIdNoFormat($id);
    }
    else if($format=="xml"){
      $responsePage->headers->set('Content-Type', 'application/xml');
      //search for such member in the db through the DAO object
      $retrievedStaff=StaffDAO::findStaffById($id);
      //Create a XML tag for each element found and add it to the response page
      $xmlOutput = new SimpleXMLElement('<Staff/>');
      $xmlOutput->addChild('ID', $retrievedStaff->getID());
      $xmlOutput->addChild('Surname', $retrievedStaff->getSurname());
      $xmlOutput->addChild('Forename', $retrievedStaff->getForename());
      $xmlOutput->addChild('Location', $retrievedStaff->getLocation());
      $xmlOutput->addChild('PhoneNumber', $retrievedStaff->getPhoneNumber());
      $xmlOutput->addChild('Email', $retrievedStaff->getEmail());
      $responsePage->setContent($xmlOutput->asXML());
    }
    else if($format=="plainText"){
      $responsePage->headers->set('Content-Type', 'text/html');
      //search for such member in the db through the DAO object
      $retrievedStaff=StaffDAO::findStaffById($id);
      $responsePage->setContent(json_encode($retrievedStaff->toArray()));
    }
    //Else, create an empty StaffInfo object, encode it to json and return it as response
    else{
        $temp=new StaffInfo('Empty','Empty');
        $responsePage->setContent(json_encode($temp->toArray()));
    }
  }
  //Return this response page in case the values passed are not suitable
  else{
      $responsePage->setStatusCode(400);
      $responsePage->headers->set('Content-Type', 'text/html');
      $responsePage->setContent('Insert a valid ID');
  }
  return $responsePage;
}
/**
* @Description: This API inserts a new member of Staff in the database
* @Param  $request  this is the request sent by the front end and it contains staff information
* @Return a response page containing a message regarding the execution of the query in the database
*/
function addNewStaff (Request $request){
    //Retrieve the needed data from the request object
    $forename = $request->get('forenameAdd');
    $surname = $request->get('surnameAdd');
    $location = $request->get('locationAdd');
    $phone = $request->get('phoneAdd');
    $email = $request->get('emailAdd');
    $responsePage=new Response();
    $res=5;
    //Verify the data format
    //Verify that the email address contains the '@' symbol and that the phone number is a numerical value
    if($forename!="" && $surname!="" && $location!="" && $phone!="" && $email!="" && strpos($email, '@') && is_numeric($phone)){
        $responsePage->setStatusCode(200);
        $responsePage->headers->set('Content-Type', 'text/html');
        //Create a temporary StaffInfo Object that will be passed as parameter to the DAO
        $staffInfoNewUser=StaffInfo::createWithParams(0, $surname, $forename, $location, $phone, $email);
        //Call to the DAO to establish a connection to the DB and insert a new member of Staff
        $res=StaffDAO::addStaffMember($staffInfoNewUser);
        //If the insertion returns a value 0, it means the member is already in the DB
        if($res==0){
            $responsePage->setContent('Staff Already in the Data Base');
        }
        //In case the response is 1, an element has been added successfully
        else if($res==1){
            $responsePage->setContent('Staff Added successfully to the Data Base!');
        }
        //Otherwise, a server error occurred
        else{
            $responsePage->setStatusCode(500);
            $responsePage->setContent('Server Side Error');
        }
    }
    //An error with the data passed has been discovered, therefore no DB insertion has took place
    else{
        $responsePage->setStatusCode(400);
        $responsePage->setContent('Input Error!, Check the input and try again!');
    }
    return $responsePage;
}
/**
* @Description: This API is for the removal of a Staff member from the system, given a surname and forename
* @Param  $request  this is the request sent by the front end and it contains a staff ID, which is essential for the removal
* @Return a response page containing a message regarding the execution of the query in the database
*/
function deleteStaffMember(Request $req){
  $id=$req->get('id');
  $responsePage=new Response();
  //Verify the data format
  if($id>0){
      $responsePage->setStatusCode(200);
      $responsePage->headers->set('Content-Type', 'text/html');
      $res=StaffDAO::deleteStaffMember($id);
      //If the delete returns a value 0, it means that no elements have been deleted
      if($res==0){
          $responsePage->setContent("It is not possible to delete a member that it is not in the system");
      }
      //In case the response is 1, an element has been added successfully
      else if($res==1){
          $responsePage->setContent("Staff Removed Successfully!");
      }
      //Otherwise, a server error occurred
      else{
          //$responsePage->setStatusCode(500);
          $responsePage->setContent('Server Side Error');
      }
  }
  //An error with the data passed has been discovered, therefore no DB insertion has took place
  else{
      $responsePage->setStatusCode(400);
      $responsePage->setContent('Input Error!, Check the input and try again!');
  }
  return $responsePage;
}
/**
* @Description: This API updates the record of a specific staff member
* @Param  $request  this is the request sent by the front end and it contains the staff data to be updated in the system
* @Return a response page containing a message regarding the execution of the query in the database
*/
function updateStaffMember(Request $req){
  //Retrieve the Staff data from the request object
  $id = $req->get('idUpdate');
  $forename = $req->get('forenameUpdate');
  $surname = $req->get('surnameUpdate');
  $location = $req->get('locationUpdate');
  $phone = $req->get('phoneUpdate');
  $email = $req->get('emailUpdate');
  $responsePage=new Response();
  $res=False;
  //Verify the data format
  //Verify that the email address contains the '@' symbol and that the phone number is a numerical value
  if($forename!="" && $surname!="" && $location!="" && $phone!="" && $email!="" && strpos($email, '@') && is_numeric($phone)){
      $responsePage->setStatusCode(200);
      $responsePage->headers->set('Content-Type', 'text/html');
      //Create a temporary StaffInfo Object that will be passed as parameter to the DAO
      $staffInfoUpdateUser=StaffInfo::createWithParams($id, $surname, $forename, $location, $phone, $email);
      //Call to the DAO to establish a connection to the DB and Update the Staff data
      $res=StaffDAO::updateStaffRecord($staffInfoUpdateUser);
      //Check the result of the Update
      if($res==False){
        $responsePage->setStatusCode(500);
        $responsePage->setContent('Server Side Error');
      }
      //Otherwise, The Update was successufull
      else{
          $responsePage->setContent('Staff Data Updated Correctly!');
      }
  }
  //An error with the data passed has been discovered, therefore no DB Update has took place
  else{
      $responsePage->setStatusCode(400);
      $responsePage->setContent('Input Error!, Check the input and try again!');
  }
  return $responsePage;
}
//This is fundamental for Silex to run
$app->run();

?>
