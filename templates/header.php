<header>
	<a class="header-img-a" href="/user.php"><img src="/img/toWatchListLogoWhite.png"></a>
	<div>
	<a><?php echo $_SESSION['login']; ?><div class="profilImg"></div></a>
	<ul>
		<li><a href="edituser.php">Paramètres</a></li>
		<li><a href="deconnexion.php">Déconnexion</a></li>
	</ul>
	</div>
	<script>$(".profilImg").css('background-image','url("<?php echo $_SESSION['img'] ?>")')</script>
</header>