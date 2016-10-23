<?php
use App\Lib\ValidationUtil;
use App\Lib\Constants;
?>
<!-- Top content -->
<div id="login">
	<div class="inner-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-offset-3 form-box">
					<div class="form-top">
						<div class="form-top-left">
							<h3><?php echo Constants::SYSTEM_NAME?></h3>
							<p>Enter your email and password to log on</p>
						</div>
						<div class="form-top-right">
							<i class="fa fa-lock"></i>
						</div>
					</div>
					<div class="form-bottom">
						<?php 
						echo $this->ExForm->create('', ['url' => ['controller' => 'Logins', 'action' => 'login'], 'class' => 'login-form']);
						?>
							<div class="form-group">
								<label class="sr-only" for="form-username">Username</label>
								<?php echo $this->ExForm->text('email', ['class' => 'form-username form-control', 'id' => 'email', 'placeholder' => 'Email...'])?>
							</div>
							<div class="form-group">
								<label class="sr-only" for="form-password">Password</label>
								<?php echo $this->ExForm->password('password', ['class' => 'form-password form-control', 'id' => 'password', 'placeholder' => 'Password...'])?>
							</div>
							<div class="form-group">
								<div class="check-box">
									<?php echo $this->ExForm->input('rememberMe', ['id' => 'rememberMe', 'type' => 'checkbox', 'required' => FALSE, 'value' => Constants::CHECKBOX_ON])?>
								</div>
							</div>
							<?php echo $this->ExForm->button('Sign in', ['class' => 'btn', 'type' => 'submit', 'value' => 'submit', 'onClick' => 'return checkValid()'])?>
							<div class="form-group">
								<label class="sr-only" for="form-password">Forgot password</label>
								<p>Forgot your password, please click <?= $this->Html->link('here', ['controller' => 'Logins', 'action' => 'changePassword'])?>
							</div>
							
							<div class="form-group">
								<?php echo $this->Flash->render()?>
							</div>
						<?php echo $this->ExForm->end()?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function checkValid()
{
	var mail = document.getElementById('email');
	var password = document.getElementById('password');
	var email_reg = <?php echo ValidationUtil::CHECK_EMAIL?>

	if ((mail.value.length == 0) || (password.value.length == 0)) {
		alert('Email or password can\'t be blank !');
		return false;
	}

	if (!email_reg.test(mail.value)) {
		alert('Email is not valid');
		return false;
	}
	return true;
}
</script>