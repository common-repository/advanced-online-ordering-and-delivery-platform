<?php 
/**
 * message template CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/message-template
 */

$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $db->getModelById("message_template", sanitize_text_field($_GET["id"]));
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Message Template</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'></div>
			<div class='data-button-area'>
				<div id='read-message-template'
					class='btn btn-primary pull-right m-b-15px mr-1 read-message-template-button'>
					<span class='fas fa-list'></span> Message Template List
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card mb-12">
			<div class="card-header"><h4 class="mb-0">Update Message Template</h4></div>
			<div class="card-body">
        		<form id='update-message-template-form' action='#' method='post'>
        			<input type="hidden" name="id" id="id" value="<?php echo $result->id;?>"/>
        			<table class='table table-hover table-bordered' style="width:100%">       
        				<tr>
        					<td>Name</td>
        					<td><?php echo $result->display_name;?></td>
        				</tr>
        				<tr>
        					<td>Text</td>
        					<td>
        						<textarea class="message_textarea" id="template_text" name="template_text">
        							<?php echo $result->template_text; ?>
        						</textarea>
        					</td>
        				</tr>
        				<tr>
        					<td>Parameter List</td>
        					<td>
        						<input type="text" name="param_list" id="param_list" value="<?php echo $result->param_list; ?>">
        					</td>
        				</tr>
        				<tr>
        					<td></td>
        					<td>
        						<button type='button' class='btn btn-primary' onclick="updateModel('#configuration-content', 'message-template')">
        							<span class='fas fa-plus'></span> Save
        						</button>
        					</td>
        				</tr>       	
        			</table>
        		</form>
        	</div>
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
ClassicEditor.create( document.querySelector( '#template_text' ), {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
        ]
    }
} )
.catch( error => {
    console.log( error );
} );
});
</script>