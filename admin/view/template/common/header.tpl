<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'> -->
<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="view/javascript/jquery/tabs.js"></script> -->
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
        });
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]> -->
      <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
      <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <!--[endif]-->
</head>
<body>
<header id="header" class="transbg">
  <button type="button" class="menuBtn2 pull-left btn btn-link">
    <span style="padding-left:0px;" class="glyphicon glyphicon-align-justify"></span>
  </button>
  <div class="container-fluid" style="padding-left:0px; margin-left:53px;">
    <!-- <a href id="menu-toggle" class="menuBtn pull-left"><span style="padding-left:0px;" class="glyphicon glyphicon-align-justify"></span></a> -->
    <a href="<?php echo $home; ?>" class="logo pull-left"><strong><?php echo $heading_title; ?></strong></a>
    
    <?php if ($logged) { ?>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="view/image/lock.png" alt="" style="position: relative; top: 0px;" />&nbsp;<?php echo $logged; ?><span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="http://snwebdm.com" target="_blank"><?php echo $text_synergycms; ?></a></li>
          <li><a href="http://www.opencart.com/index.php?route=documentation/introduction" target="_blank"><?php echo $text_documentation; ?></a></li>
          <li><a href="http://forum.opencart.com" target="_blank"><?php echo $text_support; ?></a></li>
          <li class="divider"></li>
          <li id="site"><a href="<?php echo $site; ?>" target="_blank" class="top"><?php echo $text_front; ?></a>
          <?php foreach ($sites as $sites) { ?>
          <li><a href="<?php echo $sites['href']; ?>" target="_blank"><?php echo $sites['name']; ?></a></li>
          <?php } ?>
          <li class="divider"></li>
          <li><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
        </ul>
      </li>
    </ul>
    <?php } ?>
  </div>  
</header>
<?php if ($logged) { ?>
  <nav class="pull-left">
    <ul class="nav nav-pills nav-stacked">
      <li id="dashboard"><a href="<?php echo $home; ?>"><span class="fa fa-dashboard"></span></a></li>
      
      <li id="catalog" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $post; ?>"><?php echo $text_post; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
          <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
        </ul>
      </li>

      <li id="extension" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-paperclip"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
          <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
        </ul>
      </li>

      <li id="members" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $member; ?>"><?php echo $text_member; ?></a></li>
          <li><a href="<?php echo $member_group; ?>"><?php echo $text_member_group; ?></a></li>
          <li><a href="<?php echo $member_ban_ip; ?>"><?php echo $text_member_ban_ip; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>

      <li id="system" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_design; ?></li>
          <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
          <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
          <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_users; ?></li>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li class="divider"></li>
          <li class="dropdown-header"><?php echo $text_localisation; ?></li>
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
          <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
        </ul>
      </li>

      <li id="reports" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-info-sign"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $report_post_viewed; ?>"><?php echo $text_report_post_viewed; ?></a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $report_member_online; ?>"><?php echo $text_report_member_online; ?></a></li>
        </ul>
      </li> 
    </ul>
  </nav>
<?php } ?>
