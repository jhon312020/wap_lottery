<?php
	require_once("includes/head.php");
?>
<div class="col-md-6">
	<div class="red-box">
		<p class="heading">Latest Deposit : </p>
		<div class="col-xs-12" style="height: 245px;">
			<?php 
				$resDepositHistory = mysql_query("SELECT * FROM lottery_amount_deposit WHERE admin_notes='' ORDER BY RAND() LIMIT 30");
			?>
			<ul class="deposit_Block">
				<?php
					if($resDepositHistory && mysql_num_rows($resDepositHistory) >0)
					{
						while($arrDepositHistory = mysql_fetch_array($resDepositHistory)) {
						?>
						<li class="news-item">
							<p>
								<span class="bName">
									<?php echo "***".substr($arrDepositHistory['deposit_to_bank'], -rand(4,8)); ?>
								</span>
								<span class="bAmmount">Rs. <?php echo $arrDepositHistory['deposit_amount']; ?></span>
							</p>
						</li>
						<?php
						}
					}
					else{
						for($i = 0; $i<30;$i++)
						{
						?>
						<li class="news-item">
							<p>
								<span class="bName">
									<?php echo "*****RIYANTO " ?>
								</span>
								<span class="bAmmount">Rs. <?php echo rand(11111,999999); ?></span>
							</p>
						</li>
						
						<?php
						}
					}
				?>
			</ul>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="red-box">
		<p class="heading">Latest Withdrawal :</p>
		<div class="col-xs-12" style="height: 245px;">
			<?php 
				$resDepositHistory = mysql_query("SELECT * FROM lottery_amount_withdraw WHERE admin_notes='' ORDER BY RAND() LIMIT 30");
			?>
			<ul class="deposit_Block">
				<?php
					if($resDepositHistory && mysql_num_rows($resDepositHistory) >0)
					{
						while($arrDepositHistory = mysql_fetch_array($resDepositHistory)) {
						?>
						<li class="news-item">
							<p>
								<span class="bName">
									<?php echo "***".substr($arrDepositHistory['w_from_bank'], -7); ?>
								</span>
								<span class="bAmmount">Rs. <?php echo $arrDepositHistory['w_amount']; ?></span>
							</p>
						</li>
						<?php
						}
					}
					else{
						for($i = 0; $i<30;$i++)
						{
						?>
						<li class="news-item">
							<p>
								<span class="bName">
									<?php echo "*****RIYANTO " ?>
								</span>
								<span class="bAmmount">Rs. <?php echo rand(11111,999999); ?></span>
							</p>
						</li>
						
						<?php
						}
					}
				?>
			</ul>
		</div>
	</div>
</div>

<script src="js/jquery.marquee.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function () {
		
		$('.deposit_Block').marquee();  
	});
</script>
