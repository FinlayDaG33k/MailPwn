<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">MailPwn</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
				<?php if($_SESSION['Loggedon']){ ?>
				<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">My Account <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Welcome, <?= htmlentities(ucfirst($_SESSION['Username'])); ?></a></li>
						<li><a href="?page=myaccount">My Account</a></li>
            <li class="divider"></li>
            <li><a href="<?= htmlentities($SimpleLogins->sl_Vars()['System_url']); ?>?Action=logout">Logout</a></li>
          </ul>
        </li>
				<?php } ?>
        <li><a href="https://www.github.com/finlaydag33k/MailPwn" target="_new">Github</a></li>
      </ul>
    </div>
  </div>
</nav>
