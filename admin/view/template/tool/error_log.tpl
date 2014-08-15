<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="error icon" />
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
      <h3 class="formTitle">Error Log</h3>
      <div id="buttonGroup" class="pull-right">
        <a href="<?php echo $clear; ?>" class="btn clear transbg"><?php echo $button_clear; ?></a>
      </div>
    </div>
    <div class="content table-responsive">
      <table class="tile table table-bordered table-striped transbg" style="margin-bottom:0px;">
        <thead>
          <tr>
            <th>Date:</th>
            <th>Time:</th>
            <th>Error:</th>
            <th>File Location:</th>
            <th>Line:</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($lines as $line) { ?>
          <?php $split = explode(" - ",$line); ?>
          <?php $split1 = explode(" ", $split[0]); ?>
          <?php $split2 = explode(" in ",$split[1]); ?>
          <?php $split3 = explode(" on line ", $split2[1]); ?>
          <tr>
            <td id="date"><?php echo $split1[0]; ?></td>
            <td id="time"><?php echo $split1[1]; ?></td>
            <td id="error"><?php echo $split2[0]; ?></td>
            <td id="file_location"><?php echo $split3[0]; ?></td>
            <td style="text-align: center;" id="line"><?php echo $split3[1]; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>           
  </div>
</div>

<?php echo $footer; ?>