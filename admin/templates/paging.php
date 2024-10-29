<?php
/**
 * Paging
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$pagination_class = ($keywords == "") ? "pagination-normal" : "pagination-search";
?>
<ul class='pagination pagination-sm <?php echo $pagination_class ?> pull-left margin-zero padding-bottom-2em'>
    <?php
    if ($response["paging"]["first"] != "") {
        echo "<li class='paginate_button page-item' ><a href='#' data-page='" . $response["paging"]["first"] . "' class='page-link'>First Page</a></li>";
    }
    foreach ($response["paging"]["pages"] as $key => $val) {
        $active_page = ($val["current_page"] == "yes") ? "class='paginate_button page-item active'" : " class='paginate_button page-item'";
        echo "<li " . $active_page . "><a href='#'  data-page='" . $val['url'] . "'  class='page-link'>" . $val['page'] . "</a></li>";
    }
    if($response["paging"]["last"]!=""){
        echo "<li class='paginate_button page-item' ><a  href='#'  data-page='".$response["paging"]["last"] ."'  class='page-link'>Last Page</a></li>";
    }
    ?>
</ul>