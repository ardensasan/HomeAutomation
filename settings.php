<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <!--<![endif]-->
  ==
  <body>
    <?php 
    include "sessions.php";
include_once "navigator.php";
$currentPage = basename($_SERVER['PHP_SELF']);
$userPhoneNumber = $_SESSION['userPhoneNumber'];
$userPass = $_SESSION['userPass'];
$userFirstName = $_SESSION['userFirstName'];
$userLastName = $_SESSION['userLastName'];
?>
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
        <div class="card">
          <div class="card-body">
          <div class="container">
    <div class="picture-container">
        <div class="picture">
            <img src="https://lh3.googleusercontent.com/LfmMVU71g-HKXTCP_QWlDOemmWg4Dn1rJjxeEsZKMNaQprgunDTtEuzmcwUBgupKQVTuP0vczT9bH32ywaF7h68mF-osUSBAeM6MxyhvJhG6HKZMTYjgEv3WkWCfLB7czfODidNQPdja99HMb4qhCY1uFS8X0OQOVGeuhdHy8ln7eyr-6MnkCcy64wl6S_S6ep9j7aJIIopZ9wxk7Iqm-gFjmBtg6KJVkBD0IA6BnS-XlIVpbqL5LYi62elCrbDgiaD6Oe8uluucbYeL1i9kgr4c1b_NBSNe6zFwj7vrju4Zdbax-GPHmiuirf2h86eKdRl7A5h8PXGrCDNIYMID-J7_KuHKqaM-I7W5yI00QDpG9x5q5xOQMgCy1bbu3St1paqt9KHrvNS_SCx-QJgBTOIWW6T0DHVlvV_9YF5UZpN7aV5a79xvN1Gdrc7spvSs82v6gta8AJHCgzNSWQw5QUR8EN_-cTPF6S-vifLa2KtRdRAV7q-CQvhMrbBCaEYY73bQcPZFd9XE7HIbHXwXYA=s200-no" class="picture-src" id="wizardPicturePreview" title="">
            <input type="file" id="wizard-picture" class="">
        </div>
         <h6 class="">Choose Picture</h6>

    </div>
</div>
            <form>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputPassword4">First Name
                  </label>
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user">
                        </i>
                      </span>
                    </div>
                    <input type="text" class="form-control filter-list-input" maxlength="20" id="settingsFirstName" value = "<?php echo $userFirstName ?>" placeholder="First Name" aria-label="First Name">
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Last Name
                  </label>
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user">
                        </i>
                      </span>
                    </div>
                    <input type="text" class="form-control filter-list-input" maxlength="20" id="settingsLastName" value = "<?php echo $userLastName ?>" placeholder="Last Name" aria-label="Last Name">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Password
                  </label>
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key">
                        </i>
                      </span>
                    </div>
                    <input type="password" class="form-control filter-list-input" maxlength="10" id="settingsPass1" value = "<?php echo $userPass ?>" placeholder="Password" aria-label="Password">
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Confirm Password
                  </label>
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key">
                        </i>
                      </span>
                    </div>
                    <input type="password" class="form-control filter-list-input" maxlength="10" id="settingsPass2" value = "<?php echo $userPass ?>" placeholder="Confirm Password" aria-label="Confirm Password">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Phone Number
                  </label>
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-mobile"> +63
                        </i>
                      </span>
                    </div>
                    <input type="text" maxlength="10" class="form-control filter-list-input" oninput="this.value=this.value.replace(/[^0-9]/g,'');" id="settingsPhoneNumber" value = "<?php echo $userPhoneNumber ?>" placeholder="Phone Number" aria-label="Phone Number">
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-primary" onclick="saveSettings()">Save</button>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <style>
    /*Profile Pic Start*/
.picture-container{
    position: relative;
    cursor: pointer;
    text-align: center;
}
.picture{
    width: 106px;
    height: 106px;
    background-color: #999999;
    border: 4px solid #CCCCCC;
    color: #FFFFFF;
    border-radius: 50%;
    margin: 0px auto;
    overflow: hidden;
    transition: all 0.2s;
    -webkit-transition: all 0.2s;
}
.picture:hover{
    border-color: #2ca8ff;
}
.content.ct-wizard-green .picture:hover{
    border-color: #05ae0e;
}
.content.ct-wizard-blue .picture:hover{
    border-color: #3472f7;
}
.content.ct-wizard-orange .picture:hover{
    border-color: #ff9500;
}
.content.ct-wizard-red .picture:hover{
    border-color: #ff3b30;
}
.picture input[type="file"] {
    cursor: pointer;
    display: block;
    height: 100%;
    left: 0;
    opacity: 0 !important;
    position: absolute;
    top: 0;
    width: 100%;
}

.picture-src{
    width: 100%;
    
}
/*Profile Pic End*/
</style>
  </body>
</html>
