<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="geo zone icon" />
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
      <h3 class="formTitle">Geo Zone Form</h3>
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
          <tr>
            <td><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td><input type="text" name="description" value="<?php echo $description; ?>" />
              <?php if ($error_description) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <br />
        <table id="zone-to-geo-zone" class="tile table table-bordered table-striped transbg">
          <thead>
            <tr>
              <th class="left"><?php echo $entry_country; ?></th>
              <th class="left"><?php echo $entry_zone; ?></th>
              <th></th>
            </tr>
          </thead>
          <?php $zone_to_geo_zone_row = 0; ?>
          <?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
          <tbody id="zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>">
            <tr>
              <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=0');">
                  <?php foreach ($countries as $country) { ?>
                  <?php  if ($country['country_id'] == $zone_to_geo_zone['country_id']) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>">
                </select></td>
              <td class="left"><a onclick="$('#zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $zone_to_geo_zone_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addGeoZone();" class="btn btn-warning"><?php echo $button_add_geo_zone; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#zone-id').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + $('#country-id').attr('value') + '&zone_id=0');
//--></script>
<?php $zone_to_geo_zone_row = 0; ?>
<?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
<script type="text/javascript"><!--
$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=<?php echo $zone_to_geo_zone['country_id']; ?>&zone_id=<?php echo $zone_to_geo_zone['zone_id']; ?>');
//--></script>
<?php $zone_to_geo_zone_row++; ?>
<?php } ?>
<script type="text/javascript"><!--
var zone_to_geo_zone_row = <?php echo $zone_to_geo_zone_row; ?>;

function addGeoZone() {
	html  = '<tbody id="zone-to-geo-zone-row' + zone_to_geo_zone_row + '">';
	html += '<tr>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" id="country' + zone_to_geo_zone_row + '" onchange="$(\'#zone' + zone_to_geo_zone_row + '\').load(\'index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=\' + this.value + \'&zone_id=0\');">';
	<?php foreach ($countries as $country) { ?>
	html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" id="zone' + zone_to_geo_zone_row + '"></select></td>';
	html += '<td class="left"><a onclick="$(\'#zone-to-geo-zone-row' + zone_to_geo_zone_row + '\').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#zone-to-geo-zone > tfoot').before(html);
		
	$('#zone' + zone_to_geo_zone_row).load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + $('#country' + zone_to_geo_zone_row).attr('value') + '&zone_id=0');
	
	zone_to_geo_zone_row++;
}
//--></script> 
<?php echo $footer; ?>