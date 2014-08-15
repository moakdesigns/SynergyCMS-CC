<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="member" />
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
      <h3 class="formTitle">Member Ban Ip List</h3>
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
              <th class="left"><?php if ($sort == 'ip') { ?>
                <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                <?php } ?></th>
              <th class="right"><?php echo $column_member; ?></th>
              <th class="right"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($member_ban_ips) { ?>
            <?php foreach ($member_ban_ips as $member_ban_ip) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($member_ban_ip['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $member_ban_ip['member_ban_ip_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $member_ban_ip['member_ban_ip_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $member_ban_ip['ip']; ?></td>
              <td class="right"><?php if ($member_ban_ip['total']) { ?>
                <a href="<?php echo $member_ban_ip['member']; ?>"><?php echo $member_ban_ip['total']; ?></a>
                <?php } else { ?>
                <?php echo $member_ban_ip['total']; ?>
                <?php } ?></td>
              <td class="right"><?php foreach ($member_ban_ip['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
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