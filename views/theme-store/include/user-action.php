<?php $links = $ci->action_links();?>
<h2 class="user-header-title">THÔNG TIN</h2>
<div class="user-email">
	<span><?php echo (!empty($user->email))?$user->email:'';?></span>
</div>
<ul>
	<?php foreach ($links as $link): ?>
	<li><a href="<?php echo $link['url'];?>"><?php echo $link['icon'];?> <?php echo $link['label'];?> </a></li>
	<?php endforeach ?>
</ul>

<style type="text/css">
	.warper {
		background-color: #F1F1F1;
	}
	.user-profile {
		margin: 50px 0;
	}
	.user-header-title {
		margin-top: 0; padding:0;
		font-size: 18px; font-weight: bold;
		text-transform: uppercase;
		margin-bottom:20px;
	}
	.user-header h1 { margin-top: 0; font-size: 18px; padding:0; }
	.user-action .user-header-title {
		margin-top: 20px; 
	}
	.user-action .user-email {
		color:#888888; font-weight: 700;
		border-top:2px dotted #888888;
		border-bottom:2px dotted #888888;
		padding:5px 0;
		margin:20px 0;
	}
	.user-action ul {
		list-style: none; overflow: hidden;
	}
	.user-action ul li {
		display: block;
	}
	.user-action ul li a {
		display: block; padding:10px; font-size: 15px; color:#4a4a4a;
	}
	.user-action ul li a i {
		font-size: 20px; padding-right: 20px;
	}
	.user-action ul li a:hover, .user-action ul li.active a {
		color: #cc3300;
	}
	.user-content { 
		min-height: 50vh; 
		background-color:#fff; 
		padding:20px;
		margin-bottom:10px;
		box-shadow: 3px 5px 10px #cccccca6;
		border-radius:5px;
	}
	.user-content .form-group { margin: 0 0 15px 0; }
	.user-content .form-group .control-label { text-align: left; font-weight: 700; }
	.user-content .form-control { box-shadow: none; height:40px; }
	.user-content .btn {
		border-radius:8px;
		margin-top:6px;
	}
	
</style>