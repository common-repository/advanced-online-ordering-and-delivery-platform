<?php 
/**
 * Clover connection
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$BndSettings = (array)get_option("bnd_settings");
$apiUrl="";
if ($BndSettings["api_env"]=="sandbox") {
    $apiUrl = "https://buynowdepot.com/is3M5mBEKLKHqWhq";
} else {
    $apiUrl = "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip";
}
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header"><h4 class="mb-0">Connection Settings</h4></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
        		<?php if ($BndSettings["api_key"]!=null && $BndSettings["api_key"]!="") { ?>
                <div class="alert alert-success">
                	You are connected to clover. . If
						you want to reset your connection, please specify a new license
						key.
                </div>
                <?php } ?>
                <form name="apiKeyForm" action="">
                   <div class="form-group">
                            <label for="bnd_license_key">License Key</label> <input
                            type="text" class="form-control" id="bnd_license_key"
                 aria-describedby="apiHelp" placeholder="Enter License key"
                required=""> <small id="apiHelp" class="form-text text-muted">You
                must have access to a License key in order to connect your store
                to clover.</small>
                </div>
                <p>
                You can obtain your API key by going to the following <a
                href="<?php echo $apiUrl?>/cloverapi/connect" style="color:blue">link</a>
                </p>
                <button type="button" class="btn btn-primary mb-0"
            		    onclick="saveKey()">Submit</button>
		    	</form>
		    	<div class="alert" id="connectMessage" role="alert" style="display: none;"></div>
		    	<br/>
		    	<br/>
		    	<?php 
                if  ($BndSettings["transaction_mid"]!=null && $BndSettings["transaction_mid"]!="") { ?>
                <div class="alert alert-success">
                	You have a valid merchant account.
                </div>
                <?php 
                } 
                
                if  ($BndSettings["transaction_mid"]==null || $BndSettings["transaction_mid"]=="") { ?>
                <div class="alert alert-warning">
                	You do not have a valid merchant account. Obtain a merchant account by clicking this <a href="<?php echo $apiUrl ?>/merchant-copilot/add" id="copilot-url-merchant" style="color: black;text-decoration:underline"  class="link" target="_new">link</a>.
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($BndSettings["api_key"]==null && $BndSettings["api_key"]=="") { ?>
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">I have a CloverConnect account.</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form name="apiKeyForm" action="">
        			<div class="form-group">
        				<label for="bnd_license_key">License Key</label> <input type="text"
        					class="form-control" id="bnd_license_key"
        					aria-describedby="apiHelp" placeholder="Enter License key"
        					required="" /> <small id="apiHelp" class="form-text text-muted">You
        					must have access to a License key in order to connect your store
        					to clover.</small>
        			</div>
        			<button type="button" class="btn btn-primary mb-0" id="save-connection-key">Submit</button>
        			<div>
        			 If you do not have an API key, you can obtain your API key by following this <a href="<?php echo $apiUrl?>/cloverapi/connect" id="clover-connect-url" style="color: black;text-decoration:underline"  class="link">link</a>.</p>
            		</div>
        		</form>
          </div>
          <!-- /.card-body -->
        </div>
        <div class="card card-warning  collapsed-card">
          <div class="card-header">
            <h3 class="card-title">I do not have a CloverConnect account.</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            If you do not have a clover connect account, you need to follow the following steps to get connected. <br/>
            <strong>Step 1:</strong><br/>
            <p>Obtain an MID by requesting a merchant account from this <a href="#" id="copilot-url" style="color: black;text-decoration:underline">link</a>.</p>
            <br/>
            <strong>Step 2:</strong><br>
            <p>Download the clover connect account registration form from this <a href="#" id="clover-form-download" style="color: black;text-decoration:underline">link</a>.</p>
            <br/>
            <strong>Step 3:</strong><br>
            <p>Submit the completed form using this <a href="#" id="clover-form-upload" style="color: black;text-decoration:underline">link</a>.</p>
          </div>
          <!-- /.card-body -->
        </div>
        <?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
</script>