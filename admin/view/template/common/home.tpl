<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/home.png" class="headingIcon" alt="home image" />
  <span class="headingTitle"><?php echo $heading_title; ?></span>
  <div class="breadcrumb pull-right">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
</div>
<div id="container" class="container">
  <?php if ($error_install) { ?>
  <div class="alert alert-warning"><?php echo $error_install; ?></div>
  <?php } ?>
  <?php if ($error_image) { ?>
  <div class="alert alert-warning"><?php echo $error_image; ?></div>
  <?php } ?>
  <?php if ($error_image_cache) { ?>
  <div class="alert alert-warning"><?php echo $error_image_cache; ?></div>
  <?php } ?>
  <?php if ($error_cache) { ?>
  <div class="alert alert-warning"><?php echo $error_cache; ?></div>
  <?php } ?>
  <?php if ($error_download) { ?>
  <div class="alert alert-warning"><?php echo $error_download; ?></div>
  <?php } ?>
  <?php if ($error_logs) { ?>
  <div class="alert alert-warning"><?php echo $error_logs; ?></div>
  <?php } ?>
</div>

<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]--> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript"><!--
// function getSalesChart(range) {
// 	$.ajax({
// 		type: 'get',
// 		url: 'index.php?route=common/home/chart&token=<?php echo $token; ?>&range=' + range,
// 		dataType: 'json',
// 		async: false,
// 		success: function(json) {
// 			var option = {	
// 				shadowSize: 0,
// 				lines: { 
// 					show: true,
// 					fill: true,
// 					lineWidth: 1
// 				},
// 				grid: {
// 					backgroundColor: '#FFFFFF'
// 				},	
// 				xaxis: {
//             		ticks: json.xaxis
// 				}
// 			}

// 			$.plot($('#report'), [json.order, json.customer], option);
// 		}
// 	});
// }

// getSalesChart($('#range').val());
//--></script>
<?php //echo $footer; ?>