<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="layout icon" />
  <span class="headingTitle"><?php echo $heading_title; ?></span>
  <div class="breadcrumb pull-right">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
</div>
<div id="container" class="container">  
<?php if ($error_warning) { ?>
  <div class="alert alert-warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h3 class="formTitle">Layout Form</h3>
      <div id="buttonGroup" class="pull-right">
        <a onclick="$('#form').submit();" class="btn save transbg"><?php echo $button_save; ?></a>
        <a href="<?php echo $cancel; ?>" class="btn cancel transbg"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="table-responsive">
        <table class="form tile table table-bordered table-striped transbg">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <hr />
        <table id="route" class="tile table table-bordered table-striped transbg">
          <thead>
            <tr>
              <th class="left"><?php echo $entry_site; ?></th>
              <th class="left"><?php echo $entry_route; ?></th>
              <th></th>
            </tr>
          </thead>
          <?php $route_row = 0; ?>
          <?php foreach ($layout_routes as $layout_route) { ?>
          <tbody id="route-row<?php echo $route_row; ?>">
            <tr>
              <td class="left"><select name="layout_route[<?php echo $route_row; ?>][site_id]">
                  <option value="0"><?php echo $text_default; ?></option>
                  <?php foreach ($sites as $site) { ?>
                  <?php if ($site['site_id'] == $layout_route['site_id']) { ?>
                  <option value="<?php echo $site['site_id']; ?>" selected="selected"><?php echo $site['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $site['site_id']; ?>"><?php echo $site['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><input type="text" name="layout_route[<?php echo $route_row; ?>][route]" value="<?php echo $layout_route['route']; ?>" /></td>
              <td class="left"><a onclick="$('#route-row<?php echo $route_row; ?>').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $route_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addRoute();" class="btn btn-warning"><?php echo $button_add_route; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var route_row = <?php echo $route_row; ?>;

function addRoute() {
	html  = '<tbody id="route-row' + route_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="layout_route[' + route_row + '][site_id]">';
	html += '    <option value="0"><?php echo $text_default; ?></option>';
	<?php foreach ($sites as $site) { ?>
	html += '<option value="<?php echo $site['site_id']; ?>"><?php echo addslashes($site['name']); ?></option>';
	<?php } ?>   
	html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="layout_route[' + route_row + '][route]" value="" /></td>';
	html += '    <td class="left"><a onclick="$(\'#route-row' + route_row + '\').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#route > tfoot').before(html);
	
	route_row++;
}
//--></script> 
<?php echo $footer; ?>