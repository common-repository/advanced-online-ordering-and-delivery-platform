<?php 
$data = $response;
$selected_modifiers = explode(',',$data["selected_modifiers"]["modifiers"]);
?>
<div class="modal-header">
	<h5 class="modal-title">Make it delightful</h5>
	<button type="button" class="close" data-dismiss="modal"
		aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="p-3 m-0 bg-light w-100">
			<h6><?php echo $data["item"]->name;?></h6>
			<input type="hidden" name="key" value="<?php echo $data["key"];?>" id="key"/>
            <input type="hidden" name="extra-item" value="<?php echo $data["item"]->clid;?>" id="extra-item"/>
            <?php if (isset($data["single_modifiers"]) && !empty($data["single_modifiers"])) {?>
            	<input type="hidden" id="hdn-extra-item-price" value="0"/>
            <?php } else {?>	
            	<input type="hidden" id="hdn-extra-item-price" value="<?php echo $data["item"]->price/100;?>"/>
            <?php } ?>
		</div>
	</div>
</div>
<div class="modal-body">			
	<div class="row">
		<div class="col-sm-12">
			<div class="menu-extra-container">
                <?php foreach($data["single_modifiers"] as $modifier) { ?>
				<div class="menu-extra-group">
					<div class="menu-extra-title p-2 rounded">
						<strong><?php echo $modifier["name"];?></strong>
					</div>
                    <?php foreach($modifier["modifiers"] as $mod) {?>
					<div class="custom-control custom-radio border-bottom py-2">
						<input type="radio" id="extra-<?php echo $mod["id"];?>" name="extra-<?php echo $modifier["id"];?>"
							class="custom-control-input modifier-option" <?php echo (in_array($mod["id"],$selected_modifiers))?"checked":"";?>> <label
							class="custom-control-label" for="extra-<?php echo $mod["id"];?>"><?php echo $mod["name"];?> $<?php echo $mod["price"];?>
						</label>
                        <input type="hidden" id="hdn-extra-<?php echo $mod["id"];?>" value="<?php echo $mod["price"];?>"/>
					</div>
                    <?php } ?>
				</div>
                <?php } ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
             <?php foreach($data["multi_modifiers"] as $modifier ) {?>
			 <div class="menu-extra-title p-2 rounded">
			     <strong><?php echo $modifier["name"];?></strong>
			 </div>
			 <div class="menu-extra-container-multi">
                <?php foreach($modifier["modifiers"] as $mod) { ?>
		        <div class="menu-extra-group-multi border-bottom py-2">
			         <input type="checkbox" id="extra-<?php echo $mod["id"];?>" name="extra-<?php echo $modifier["id"];?>"  <?php echo (in_array($mod["id"],$selected_modifiers))?"checked":"";?> class="custom-control-input modifier-option"> <label class="custom-control-label" for="extra-<?php echo $mod["id"];?>"><?php echo $mod["name"];?> $<?php echo $mod["price"];?></label>
                     <input type="hidden" id="hdn-extra-<?php echo $mod["id"];?>" value="<?php echo $mod["price"];?>"/>
		        </div>
                <?php } ?>
             </div>
             <?php } ?>
	     </div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="menu-extra-title p-2 rounded">
				<strong>Special instructions</strong>
			</div>
			<textarea class="form-control" style="margin-top:5px;" name ="instructions" placeholder="Say anything you would like us to server better?" id="extra-instructions"></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="extra-price-container">
				<div class="extra-price-item">
                    <div class="extra-price-item-inner">Quantity&nbsp;</div>
					<div class="extra-price-item-inner">
                        <div class="number-spinner">
						  <span class="ns-btn">
								<a data-dir="dwn"><span class="icon-minus"></span></a>
						  </span>
						  <input type="text" class="pl-ns-value" value="<?php echo $data["selected_modifiers"]["quantity"]?>" maxlength=2 id="extra-quantity" name="extra-quantity">
						  <span class="ns-btn">
								<a data-dir="up"><span class="icon-plus"></span></a>
						  </span>
						</div>
                    </div>
				</div>
                <div class="extra-price-item" style="text-align:right">
                    <div class="extra-price-item-inner">Price&nbsp;</div>
					<div class="extra-price-item-inner"><span class="extra-price" id="extra-price">$0.00</span></div>
				</div>
			</div>			
		</div>
	</div>
</div>
<div class="modal-footer p-0 border-0">
	<div class="col-6 m-0 p-0">
		<button type="button" class="btn border-top btn-lg btn-block"
			data-dismiss="modal">Close</button>
	</div>
	<div class="col-6 m-0 p-0">
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick="updateCart()">Apply</button>
	</div>
</div>