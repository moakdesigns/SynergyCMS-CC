<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/post.png" class="headingIcon" alt="password icon" />
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
     <h3 class="formTitle">Password Reset</h3>
    </div>
  </div> 
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten" class="table-responsive">
        <p class="white"><?php echo $text_email; ?></p>
        <table class="form tile table table-bordered table-striped transbg">
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
        </table>
      </form>
      <div id="buttonGroup" class="pull-right">
        <a onclick="$('#forgotten').submit();" class="btn reset transbg"><?php echo $button_reset; ?></a>
        <a href="<?php echo $cancel; ?>" class="btn cancel transbg"><?php echo $button_cancel; ?></a>
      </div>
    </div>
</div>
<?php echo $footer; ?>