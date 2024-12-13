<header style="background: linear-gradient(0deg, rgba(220,220,220,1) 0%, rgba(238,255,238,1) 100%); padding: 5px 0px 4px 0px;">	
	<span class="login-signup"><a href="logout.php" style="background-color: #eab729; color: #484747; padding: 7px; border-radius: 3px; margin-right: 20px;">Logout</a></span>
	<span class="login-signup">Welcome &ensp;<strong style="text-transform: uppercase"><?php echo $fullname;?>! </strong></span>
	<label style="padding: 6px 0 0 15px; font-size: 20px"> Search Count : <?php if(!empty($userDetailResult)){echo $userDetailResult[0]['search_count'];} else {echo "-";}?> of <?php if(!empty($userDetailResult)){echo $userDetailResult[0]['max_search_count'];} else {echo "-";}?></label>
	<?php if($userDetailResult[0]['max_search_count']<=$userDetailResult[0]['search_count']){?>
		<label class="error-msg" style="padding: 0 20px 0 15px;">Maximum search count reached !</label>
		<a href="payments_paypal.php" style="background-color: #eab729; color: #484747; padding: 7px; border-radius: 3px; font-weight: 700;">Get More</a>
	<?php }?>
</header>
<!-- end header -->