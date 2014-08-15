<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="custom field icon" />
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
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h3 class="formTitle">Custom Field List</h3>
      <div id="buttonGroup" class="pull-right">
        <a href="<?php echo $insert; ?>" class="btn transbg insert"><?php echo $button_insert; ?></a>
        <a onclick="$('#form').submit();" class="btn transbg delete"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form" class="table-response">
        <table class="tile table table-bordered table-striped transbg">
          <thead>
            <tr>
              <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
              <th class="left"><?php if ($sort == 'cfd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></th>
              <th class="left"><?php if ($sort == 'cf.type') { ?>
                <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                <?php } ?></th>
              <th class="left"><?php if ($sort == 'cf.location') { ?>
                <a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_location; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_location; ?>"><?php echo $column_location; ?></a>
                <?php } ?></th>
              <th class="right"><?php if ($sort == 'cf.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></th>
              <th class="right"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($custom_fields) { ?>
            <?php foreach ($custom_fields as $custom_field) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($custom_field['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $custom_field['custom_field_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $custom_field['custom_field_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $custom_field['name']; ?></td>
              <td class="left"><?php echo $custom_field['type']; ?></td>
              <td class="left"><?php echo $custom_field['location']; ?></td>
              <td class="right"><?php echo $custom_field['sort_order']; ?></td>
              <td class="right"><?php foreach ($custom_field['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>