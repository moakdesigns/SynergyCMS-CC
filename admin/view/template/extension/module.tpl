<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="Extension Icon" />
  <span class="headingTitle"><?php echo $heading_title; ?></span>
  <div class="breadcrumb pull-right">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
</div>
<div id="container" class="container">  
<?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="alert alert-warning"><?php echo $error; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h3 class="formTitle">Extension List</h3>
    </div>
    <div class="content table-responsive">
      <table class="tile tableTop table table-bordered table-striped transbg">
        <thead>
          <tr>
            <th class="left"><?php echo $column_name; ?></th>
            <th class="right"><?php echo $column_action; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($extensions) { ?>
          <?php foreach ($extensions as $extension) { ?>
          <tr>
            <td class="left"><?php echo $extension['name']; ?></td>
            <td class="right">
            <?php
              $i=0;
              foreach ($extension['action'] as $action) { 
                if(sizeof($extension['action']) > 1 && $i === 0) { ?>                
                  <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> |
                  <?php $i++; ?>
                <?php } else { ?>
                  <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
              <?php } ?>
            <?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php echo $footer; ?>