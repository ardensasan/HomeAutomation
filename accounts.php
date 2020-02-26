<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <!--<![endif]-->
  <body>
  <?php 
include_once "navigator.php";
$currentPage = basename($_SERVER['PHP_SELF']);
?>
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
      <div class="card">
  <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Accounts</h3>
  <div class="card-body">
    <div id="table" class="table-editable">
      <span class="table-add float-left mb-3 mr-2"><a data-toggle="modal" href="#addAccountModal" class="text-success"><i
            class="fas fa-plus" aria-hidden="true"></i></a> Add Account</span>
      <table class="table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            <th class="text-center">Name</th>
            <th class="text-center">Username</th>
            <th class="text-center">Password</th>
            <th class="text-center">Remove</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            $query = "SELECT * FROM `tbl_users` WHERE `userType` != ?";
            $getUserDetails=$conn->prepare($query);
            $getUserDetails->execute([ADMIN]);
            while($userDetails = $getUserDetails->fetch(PDO::FETCH_ASSOC))
            {
              $userFullName = $userDetails["userFirstName"].' '.$userDetails["userLastName"];
              $userName = $userDetails["userName"];
              $userPass = $userDetails["userPass"];
              $userID = $userDetails["userID"];
              echo '<tr>
              <td>
                  '.$userFullName.'
              </td>
              <td class="pt-3-half">'.$userName.'</td>
              <td class="pt-3-half">'.$userPass.'</td>
              <td>
                <span class="table-remove"><button type="button"
                    class="btn btn-danger btn-rounded btn-sm my-0" onclick="removeAccount('.$userID.')">Remove</button></span>
              </td>
            </tr>';
            }
            ?>
          <!-- This is our clonable table line -->
        </tbody>
      </table>
    </div>
  </div>
</div>
      </div>
    </div>

 <!-- add account modal-->
    <div class="modal fade" id="addAccountModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <div id = "scheduleTitle"> Add Account
              </div>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;
            </button>
          </div>
          <div class="modal-body">
            First Name
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="text" id="addAccountFirstName" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                </div>
              </div>
            </div>
            Last Name
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="text" id="addAccountLastName" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                </div>
              </div>
            </div>
            Username
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="text"id="addAccountUserName" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                </div>
              </div>
            </div>
            Password
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="password" id="addAccountPassword" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                </div>
              </div>
            </div>
          Confirm Password
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="password" id="addAccountCPassword" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                </div>
              </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick ="addAccount()">Add Account
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- add account modal end -->
  </body>
</html>
