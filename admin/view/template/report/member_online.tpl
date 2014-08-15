<?php echo $header; ?>
<div class="pageTitle">
  <img src="view/image/information.png" class="headingIcon" alt="member icon" />
  <span class="headingTitle"><?php echo $heading_title; ?></span>
  <div class="breadcrumb pull-right">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
</div>
<div id="container" class="container">  
  <div class="box">
    <div class="heading">
      <h3 class="formTitle">Members Online</h3>
    </div>
    <div class="content table-responsive">
      <table class="tile table table-bordered table-striped transbg">
        <thead>
          <tr>
            <th class="left"><?php echo $column_ip; ?></th>
            <th class="left"><?php echo $column_member; ?></th>
            <th class="left"><?php echo $column_url; ?></th>
            <th class="left"><?php echo $column_referer; ?></th>
            <th class="left"><?php echo $column_date_added; ?></th>
            <th class="right"><?php echo $column_action; ?></th>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td align="left"><input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" /></td>
            <td align="left"><input type="text" name="filter_member" value="<?php echo $filter_member; ?>" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right"><a onclick="filter();" class="btn btn-warning"><?php echo $button_filter; ?></a></td>
          </tr>
          <?php if ($members) { ?>
          <?php foreach ($members as $member) { ?>
          <tr>
            <td class="left"><a href="http://whatismyipaddress.com/ip/<?php echo $member['ip']; ?>" target="_blank"><?php echo $member['ip']; ?></a></td>
            <td class="left"><?php echo $member['member']; ?></td>
            <td class="left"><a href="<?php echo $member['url']; ?>" target="_blank"><?php echo implode('<br/>', str_split($member['url'], 30)); ?></a></td>
            <td class="left"><?php if ($member['referer']) { ?>
              <a href="<?php echo $member['referer']; ?>" target="_blank"><?php echo implode('<br/>', str_split($member['referer'], 30)); ?></a>
              <?php } ?></td>
            <td class="left"><?php echo $member['date_added']; ?></td>
            <td class="right"><?php foreach ($member['action'] as $action) { ?>
              <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
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
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/member_online&token=<?php echo $token; ?>';
	
	var filter_member = $('input[name=\'filter_member\']').attr('value');
	
	if (filter_member) {
		url += '&filter_member=' + encodeURIComponent(filter_member);
	}
		
	var filter_ip = $('input[name=\'filter_ip\']').attr('value');
	
	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}
				
	location = url;
}
//--></script> 
<?php echo $footer; ?>