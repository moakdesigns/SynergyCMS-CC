<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="report icon" />
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
  <div class="box">
    <div class="heading">
      <h3 class="formTitle">Posts Viewed</h3>
      <div id="buttonGroup" class="pull-right">
        <a href="<?php echo $reset; ?>" class="btn reset transbg"><?php echo $button_reset; ?></a>
      </div>
    </div>
    <div class="content table-responsive">
      <table class="tile table table-bordered table-striped transbg">
        <thead>
          <tr>
            <th class="left"><?php echo $column_title; ?></th>
            <th class="left"><?php echo $column_model; ?></th>
            <th class="right"><?php echo $column_viewed; ?></th>
            <th class="right"><?php echo $column_percent; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($posts) { ?>
          <?php foreach ($posts as $post) { ?>
          <tr>
            <td class="left"><?php echo $post['title']; ?></td>
            <td class="left"><?php echo $post['model']; ?></td>
            <td class="right"><?php echo $post['viewed']; ?></td>
            <td class="right"><?php echo $post['percent']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>