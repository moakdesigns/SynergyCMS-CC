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
<div class="box">
  <div class="heading">
     <h3 class="formTitle">Mail Form</h3>
     <div id="buttonGroup" class="pull-right">
     <a id="button-send" onclick="send('index.php?route=members/contact/send&token=<?php echo $token; ?>');" class="btn send transbg"><?php echo $button_send; ?></a>
     <a href="<?php echo $cancel; ?>" class="btn cancel transbg"><?php echo $button_cancel; ?></a>
     </div>
    </div>
    <div class="content">
        <table id="mail" class="tile form table table-bordered table-striped transbg">
          <tr>
            <td><?php echo $entry_site; ?></td>
            <td><select name="site_id">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($sites as $site) { ?>
                <option value="<?php echo $site['site_id']; ?>"><?php echo $site['name']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_to; ?></td>
            <td><select name="to">
                <option value="newsletter"><?php echo $text_newsletter; ?></option>
                <option value="member_all"><?php echo $text_member_all; ?></option>
                <option value="member_group"><?php echo $text_member_group; ?></option>
                <option value="member"><?php echo $text_member; ?></option>
                <!-- <option value="affiliate_all"><?php //echo $text_affiliate_all; ?></option> -->
                <!-- <option value="affiliate"><?php //echo $text_affiliate; ?></option> -->
                <!-- <option value="post"><?php //echo $text_post; ?></option> -->
              </select></td>
          </tr>
          <tbody id="to-member-group" class="to">
            <tr>
              <td><?php echo $entry_member_group; ?></td>
              <td><select name="member_group_id">
                  <?php foreach ($member_groups as $member_group) { ?>
                  <option value="<?php echo $member_group['member_group_id']; ?>"><?php echo $member_group['name']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </tbody>
          <tbody id="to-member" class="to">
            <tr>
              <td><?php echo $entry_member; ?></td>
              <td><input type="text" name="members" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="member" class="scrollbox"></div></td>
            </tr>
          </tbody>
          <!-- <tbody id="to-affiliate" class="to">
            <tr>
              <td><?php //echo $entry_affiliate; ?></td>
              <td><input type="text" name="affiliates" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="affiliate" class="scrollbox"></div></td>
            </tr>
          </tbody> -->
          <!-- <tbody id="to-post" class="to">
            <tr>
              <td><?php //echo $entry_post; ?></td>
              <td><input type="text" name="posts" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="post" class="scrollbox"></div></td>
            </tr>
          </tbody> -->
          <tr>
            <td><span class="required">*</span> <?php echo $entry_subject; ?></td>
            <td><input type="text" name="subject" value="" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_message; ?></td>
            <td><textarea name="message"></textarea></td>
          </tr>
        </table>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script> 
<script type="text/javascript"><!--	
$('select[name=\'to\']').bind('change', function() {
	$('#mail .to').hide();
	
	$('#mail #to-' + $(this).attr('value').replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'members\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=members/member/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						category: item.member_group,
						label: item.name,
						value: item.member_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#member' + ui.item.value).remove();
		
		$('#member').append('<div id="member' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="member[]" value="' + ui.item.value + '" /></div>');

		$('#member div:odd').attr('class', 'odd');
		$('#member div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#member div img').live('click', function() {
	$(this).parent().remove();
	
	$('#member div:odd').attr('class', 'odd');
	$('#member div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--	
// $('input[name=\'affiliates\']').autocomplete({
// 	delay: 500,
// 	source: function(request, response) {
// 		$.ajax({
// 			url: 'index.php?route=members/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
// 			dataType: 'json',
// 			success: function(json) {		
// 				response($.map(json, function(item) {
// 					return {
// 						label: item.name,
// 						value: item.affiliate_id
// 					}
// 				}));
// 			}
// 		});
		
// 	}, 
// 	select: function(event, ui) {
// 		$('#affiliate' + ui.item.value).remove();
		
// 		$('#affiliate').append('<div id="affiliate' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="affiliate[]" value="' + ui.item.value + '" /></div>');

// 		$('#affiliate div:odd').attr('class', 'odd');
// 		$('#affiliate div:even').attr('class', 'even');
				
// 		return false;
// 	},
// 	focus: function(event, ui) {
//       	return false;
//    	}
// });

// $('#affiliate div img').live('click', function() {
// 	$(this).parent().remove();
	
// 	$('#affiliate div:odd').attr('class', 'odd');
// 	$('#affiliate div:even').attr('class', 'even');	
// });

// $('input[name=\'posts\']').autocomplete({
// 	delay: 500,
// 	source: function(request, response) {
// 		$.ajax({
// 			url: 'index.php?route=catalog/post/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
// 			dataType: 'json',
// 			success: function(json) {		
// 				response($.map(json, function(item) {
// 					return {
// 						label: item.title,
// 						value: item.post_id
// 					}
// 				}));
// 			}
// 		});
// 	}, 
// 	select: function(event, ui) {
// 		$('#post' + ui.item.value).remove();
		
// 		$('#post').append('<div id="post' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="post[]" value="' + ui.item.value + '" /></div>');

// 		$('#post div:odd').attr('class', 'odd');
// 		$('#post div:even').attr('class', 'even');
				
// 		return false;
// 	},
// 	focus: function(event, ui) {
//       	return false;
//    	}
// });

// $('#post div img').live('click', function() {
// 	$(this).parent().remove();
	
// 	$('#post div:odd').attr('class', 'odd');
// 	$('#post div:even').attr('class', 'even');	
// });

function send(url) { 
	$('textarea[name="message"]').val(CKEDITOR.instances.message.getData());
	
	$.ajax({
		url: url,
		type: 'post',
		data: $('select, input, textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').attr('disabled', true);
			$('#button-send').before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
		},
		complete: function() {
			$('#button-send').attr('disabled', false);
			$('.wait').remove();
		},				
		success: function(json) {
			$('.success, .warning, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('.box').before('<div class="alert alert-warning" style="display: none;">' + json['error']['warning'] + '</div>');
			
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<span class="error">' + json['error']['subject'] + '</span>');
				}	
				
				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<span class="error">' + json['error']['message'] + '</span>');
				}									
			}			
			
			if (json['next']) {
				if (json['success']) {
					$('.box').before('<div class="alert alert-success">' + json['success'] + '</div>');
					
					send(json['next']);
				}		
			} else {
				if (json['success']) {
					$('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
			
					$('.success').fadeIn('slow');
				}					
			}				
		}
	});
}
//--></script> 
<?php echo $footer; ?>
