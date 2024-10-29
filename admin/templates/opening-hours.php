<?php
/**
 * Opening hours
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$results = $model->getOpeningHours();
$START_HOURS=
    ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30",
     "06:00","06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30",
     "12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
     "18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
$END_HOURS=
    ["00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30",
        "06:00","06:30","07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30",
        "12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30",
        "18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00"];
$DAYS=["monday","tuesday","wednesday","thursday","friday","saturday","sunday"]; 
?>
<div class="row">
	<div class="col-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Store Opening Hours</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
					<?php foreach($results as $row) {
					       $rowArray = (array)$row;
					    ?>
            				<table class="table table-bordered table-striped">
            					<thead>
            						<tr>
            							<th>Day</th>
            							<th>Start Time</th>
            							<th>End Time</th>
            							<th>Update</th>
            						</tr>
            					</thead>
        					<tbody>
        					<?php foreach($DAYS as $day) {
        					    $time = $rowArray[$day];
            					$timeArray = explode("-",$time);
            					$startTime = str_pad($timeArray[0],4,"0",STR_PAD_LEFT);
            					$endTime = str_pad($timeArray[1],4,"0",STR_PAD_LEFT);
        					    ?>
        						<tr>
        							<td>
        								<?php echo ucfirst($day);?>
        							</td>
        							<td>
        								<select name="<?php echo $day;?>-start" id="<?php echo $day;?>-start">
        									<?php foreach($START_HOURS as $hour) {
        									    $currentHour = str_replace(":","",$hour);
        										if ($currentHour==$startTime) { ?>
        											<option value="<?php echo $currentHour;?>" selected><?php echo $hour;?></option>
        											
        									<?php } else {?>
        									     <option value="<?php echo $currentHour;?>"><?php echo $hour;?></option>
        									<?php } 
        									}?>
        								</select>
        							</td>
        							<td>
        								<select name="<?php echo $day;?>-end"  id="<?php echo $day;?>-end">
        									<?php foreach($END_HOURS as $hour) {
        									    $currentHour = str_replace(":","",$hour);
        										if ($currentHour==$endTime) { ?>
        											<option value="<?php echo $currentHour;?>" selected><?php echo $hour;?></option>
        											
        									<?php } else {?>
        									     <option value="<?php echo $currentHour;?>"><?php echo $hour;?></option>
        									<?php } 
        									}?>
        								</select>
        							</td>
        							<td>
        								<button type="button" class="btn btn-primary" onclick="updateOpeningHours('<?php echo $rowArray["clid"];?>','<?php echo $day;?>')">Update</button>
        							</td>
        						</tr>
        					<?php }?>
        					</tbody>
        				</table>
				 <?php } ?>
			</div>
		</div>
	</div>
</div>