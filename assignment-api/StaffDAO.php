<?php
/**
* @Author: Michael Piccoli
* @Version: 1.0
* @Date: 10/12/2015
* @Description: This class allows the communication with the Google SQL database and defines the operations the user is allowed on the database
*/
Class StaffDAO{
    /**
    * @Description: This method returns a full list of object contained in the database
    * @Return an array of StaffInfo containing all the staff members
    */
    public static function returnAllStaff(){
        //instantiate the data array
        $list=array();
        $query='SELECT * FROM staff';
        try{
            //Establish the connection with the database
            $conn=StaffDAO::establishConnection();
            //Perform the query and gather the result
            $statement = $conn->query($query);
            //Set the Fetch mode to return an array containing the requested data
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            //Extract the data and save it into an array
            while($row = $statement->fetch()) {
                $tempData=StaffInfo::createWithParams($row['id'],$row['surname'],$row['forename'],$row['location'],$row['phoneNumber'],$row['email']);
                array_push($list, $tempData);
            }
            //To close the DB connection using the PDO, we need to change the value of the Object holding the DB connection to NULL
            $conn=NULL;
        }catch(Exception $e){
            echo 'Error: ',$e->getMessage();
        }
        return $list;
    }
    /**
    * @Description: This method searches in the database for a given surname and forename, if there is not a match, the result returned will be an empty object
    * @Param  $fName  the surname of the person
    * @Param  $sName the forename of the person
    * @Return the result of the query which is an object in case there was a match with the surname and forename, or null otherwise
    */
    public static function findStaffByName($fName, $sName){
        $query="SELECT * FROM staff WHERE forename = :forenameReq AND surname = :surnameReq";
        //verify that the elements passed to search are of type string
        if(is_string($fName) && is_string($sName)){
            try{
                //Connect to the DB
                $conn=StaffDAO::establishConnection();
                $statement = $conn->prepare($query);
                //Set the parameters to search with the values of the elements passed
                $statement->bindParam(':forenameReq',$fName, PDO::PARAM_STR,200);
                $statement->bindParam(':surnameReq',$sName, PDO::PARAM_STR,200);
                //Set the FetchMode to return an object of type StaffInfo
                $statement->setFetchMode(PDO::FETCH_CLASS, 'StaffInfo');
                $statement->execute();
                //Close the connection with the database
                $conn=NULL;
            }catch(Exception $e){
                echo 'Error: ',$e->getMessage();
            }
        }
        //In case there is no value contained within the surname and forename variables, the method wil return null
        else{
            return null;
        }
        //If everything went fine, return the element found in the DB
        return $statement->fetch();
    }
    /**
    * @Description: This method searches in the database for a given staff id, if there is not a match, the result returned will be an empty object
    * @Param  $id  the unique identifier of the person
    * @Return the result of the query which is an object in case there was a match with the staf id, or null otherwise
    */
    public static function findStaffById($id){
      $query="SELECT * FROM staff where id = :idReq";
      //Verify the data is valid
      if(is_numeric($id) && $id>0){
        try{
            //Establish the connection with the database
            $conn=StaffDAO::establishConnection();
            $statement = $conn->prepare($query);
            //Set the parameter to search with the id value
            $statement->bindParam(':idReq',$id, PDO::PARAM_INT,11);
            //Set the FetchMode to return an object of type StaffInfo
            $statement->setFetchMode(PDO::FETCH_CLASS, 'StaffInfo');
            $statement->execute();
            //Close the connection with the database
            $conn=NULL;
        }catch(Exception $e){
            echo 'Error: ',$e->getMessage();
        }
      }
      //return null in case the variable id is not valid
      else{
          return null;
      }
      //If everything went fine, return the element found in the DB
      return $statement->fetch();
    }
    /**
    * @Description: This method add an object of type StaffInfo to the database
    * @Param  $staffData  this is a object of type StaffInfo containing all the necessary information
    * @Return a value between -1 and 1, where -1 means error, 0 means staff already in the database and 1 query executed successfully
    */
    public static function addStaffMember(StaffInfo $staffData){
        $isInserted=-1;
        //Verify that the object passed is not null and it is an instance of the StaffInfo class
        if($staffData instanceof StaffInfo && $staffData!=NULL){
             try{
                //This insert query is has a select query inside that allows to add a new member only if the same member is not already
                //contained in the database
                $query="INSERT INTO staff (surname, forename, location, phoneNumber, email) SELECT :surnameIns,:forenameIns,:locIns,:phoneIns,:emailIns FROM dual WHERE NOT EXISTS (SELECT * FROM staff WHERE surname= :surnameGiven AND forename= :forenameGiven)";
                //Establish the connection with the database
                $conn=StaffDAO::establishConnection();
                //This saves the number of entities in the database before the insertion is executed
                $statament1 = $conn->query("SELECT COUNT(*) FROM staff");
                $rowsBeforeInsertion = $statament1->fetchColumn();
                //Prepare the insertion of data
                $statement = $conn->prepare($query);
                $statement->bindParam(':forenameIns',$staffData->getForename(), PDO::PARAM_STR,200);
                $statement->bindParam(':surnameIns',$staffData->getSurname(), PDO::PARAM_STR,200);
                $statement->bindParam(':locIns',$staffData->getLocation(), PDO::PARAM_STR,150);
                $statement->bindParam(':phoneIns',$staffData->getPhoneNumber(), PDO::PARAM_STR,15);
                $statement->bindParam(':emailIns',$staffData->getEmail(), PDO::PARAM_STR,250);
                $statement->bindParam(':surnameGiven',$staffData->getSurname(), PDO::PARAM_STR,200);
                $statement->bindParam(':forenameGiven',$staffData->getForename(), PDO::PARAM_STR,200);
                //If the insertion is successfull, verify that the element is actually being inserted
                if($statement->execute()){
                    //This saves the number of entities in the database after the insertion is performed
                    $statament2 = $conn->query("SELECT COUNT(*) FROM staff");
                    $rowsAfterInsertion = $statament2->fetchColumn();
                    //Has the number of entities changed after the insertion of data?
                    if($rowsAfterInsertion>$rowsBeforeInsertion){
                        //If so, set the value to 1 which means 1 change has took place in the database
                        $isInserted=1;
                    }
                    //else, set the value to 0 which stands for 0 changes in the database, meaning that the data is already stored
                    else if($rowsAfterInsertion==$rowsBeforeInsertion){
                        $isInserted=0;
                    }
                }
                //In case of error while the execution of the insertion, set the value to -1, which stands for error
                else{
                    $isInserted=-1;
                }
                //Close the DB connection
                $conn=NULL;
             }
             catch(PDOException $error){
                 $isInserted=-1;
                 echo $error->getMessage();
             }
        }
        //Return the meaningful value the user is going to interpret
        return $isInserted;
    }
    /**
    * @Description: This method deletes an member of staff from the system, given the id, surname and forename of the user
    * @Param  $id  this is the Staff ID that identifies himself in the system
    * @Return a value between -1 and 1, where -1 means error, 0 means staff not found and 1 means delete executed successfully
    */
    public static function deleteStaffMember($id){
        $resultDelete=-1;
        $query="DELETE FROM staff WHERE id = :idReq";
        //Verify the data
        if(is_numeric($id)){
          try{
              //Establish the connection with the database
              $conn=StaffDAO::establishConnection();
              //This saves the number of entities in the database before the delete takes place
              $statament1 = $conn->query("SELECT COUNT(*) FROM staff");
              $rowsBeforeDelete = $statament1->fetchColumn();
              $statement = $conn->prepare($query);
              //Set the parameter id to the query
              $statement->bindParam(':idReq',$id, PDO::PARAM_INT,11);
              //Execute the query
              $statement->execute();
              //This saves the number of entities in the database after the delete took place
              $statament2 = $conn->query("SELECT COUNT(*) FROM staff");
              $rowsAfterDelete = $statament2->fetchColumn();
              //Close the connection with the database
              $conn=NULL;
              $resultDelete=$rowsBeforeDelete-$rowsAfterDelete;
          }catch(Exception $e){
              $resultDelete=-1;
              echo 'Error: ',$e->getMessage();
          }
        }
        //Return -1 in case the data is not valid to indicate there has been an error before the executio of the query
        else{
          $resultDelete=-1;
        }
        return $resultDelete;
    }
    /**
    * @Description: This method allows to update the user data stored in the DB
    * @Param  $staffData  this is a object of type StaffInfo containing all the necessary information to be updated
    * @Return a boolean value which is true in case the staff has been correctly updated, false otherwise
    */
    public static function updateStaffRecord($staffData){
        $resultUpdate=False;
        $query="UPDATE staff SET surname = :surnameUp, forename = :forenameUp, location = :locationUp, phoneNumber = :phoneUp, email = :emailUp WHERE id = :idStaff";
        //Verify the data passed
        if($staffData instanceof StaffInfo && $staffData!=null){
            try{
            //Establish the connection with the database
            $conn=StaffDAO::establishConnection();
            //Bind the data to the query
            $statement = $conn->prepare($query);
            $statement->bindParam(':surnameUp',$staffData->getSurname(), PDO::PARAM_STR,200);
            $statement->bindParam(':forenameUp',$staffData->getForename(), PDO::PARAM_STR,200);
            $statement->bindParam(':locationUp',$staffData->getLocation(), PDO::PARAM_STR,150);
            $statement->bindParam(':phoneUp',$staffData->getPhoneNumber(), PDO::PARAM_STR,15);
            $statement->bindParam(':emailUp',$staffData->getEmail(), PDO::PARAM_STR,250);
            $statement->bindParam(':idStaff',$staffData->getID(), PDO::PARAM_INT,11);
            //Execute the query and store the boolean value
            $resultUpdate=$statement->execute();
            //Close the connection with the database
            $conn=NULL;
          }
          catch(PDOException $e){
            $resultDelete=False;
            echo 'Error: ',$e->getMessage();
          }
        }
        return $resultUpdate;
    }
    /**
    * @Description: This method is essential to establish a connection with the DB that the other methods within this class are going to use
    * @Return an instance of the connection object to allow other functions to use it and operates on the Database
    */
    private function establishConnection(){
        $username=root;
        $password='';
        $dbName='StaffData';
        $mysql_conn_host = "mysql:unix_socket=/cloudsql/mike-assignment-api:assignmentdb;dbname=$dbName";
        try{
            //Create the connection
            $connection = new PDO($mysql_conn_host, $username, $password);
            //This will fire an exception and hide data that can help attackers to exploit the system
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            return $connection;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}

?>
