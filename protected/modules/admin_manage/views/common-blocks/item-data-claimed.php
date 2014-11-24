<?php /** info business **/ ?>
    <div class='heading'><?php echo $k+1;?>. <?php echo $bizInfo->name;?></div>
    <label class='label'>Address: </label> <?php echo $bizInfo->address;?><br />
<?php if(isset($bizInfo->email)) : ?>                                            
    <label class='label'>Email: </label><?php echo $bizInfo->email;?><br />
<?php endif; ?>
<?php if(!empty($bizInfo->national_phone)) : ?>                                            
	<label class='label'>National Phone:</label><?php echo $bizInfo->national_phone;?>
<?php endif; ?>    
<?php if(!empty($bizInfo->mobile)): $mobile = implode(", ", json_decode($bizInfo->mobile, true)); ?>
    <label class='label'>Mobile:</label><?php echo $mobile;?>
<?php endif; ?>
<?php if(!empty($bizInfo->fax)) : $fax = implode(", ", json_decode($bizInfo->fax, true)); ?>                                            
    <label class='label'>Fax:</label><?php echo $fax;?>
<?php endif; ?>
<br/>
<?php /** registered date **/ ?>
	<label class='label'>Claimed date: </label> <?php echo date("Y-m-d",strtotime($bizInfo->created));?>
	- 

<?php /** info user **/ ?>    
	<label class='label'>By: </label> 
    <a href='<?php echo JLRouter::createUrl('/profiles/' . $userInfo->username); ?>'><?php echo $userInfo->username; ?></a><br />

<?php /** businesses owner **/ ?>
<?php if(isset($bizOwners)): ?>
	<label class='label'>Owner business:</label>
	<?php foreach ($bizOwners as $key => $bizOfOwner) : ?>
		<a class="biz_detail" href='#'><span> - </span><?php echo $bizOfOwner['name']; ?></a>
	<?php endforeach; ?>
    <br />
<?php endif ?>

<?php /** mail info **/ ?>
<?php if(isset($emailType)): ?>
	<label class='label'>Sent date: </label>
	<?php 
		switch ($emailType) {
			case 1://email to company
				{
					echo date("Y-m-d",strtotime($mailInfo->email_company_date));
					$status = $mailInfo->company_status;
					$expired = date("Y-m-d",strtotime(JLRegisteredClaimed::DAY_EXPIRED_EMAIL_PERSONAL,strtotime($mailInfo->email_company_date)));
					$email_date = date("Y-m-d",strtotime($mailInfo->email_company_date));
				}
				break;
			case 2://email to personal
				{
					echo date("Y-m-d",strtotime($mailInfo->email_personal_date));
					$status = $mailInfo->personal_status;					
					$expired = date("Y-m-d",strtotime(JLRegisteredClaimed::DAY_EXPIRED_EMAIL_PERSONAL,strtotime($mailInfo->email_personal_date)));
					$email_date = date("Y-m-d",strtotime($mailInfo->email_personal_date));
				}
				break;
		}
	?> 
    -
	<?php switch ($status): case(JLRegisteredClaimed::STATUS_WAITING): ?>
			<label class='label'>Expired date: </label>
			<?php echo $expired ; ?>
			<label class='label'>Lasted status: </label>Waiting
		<?php break;?>
        <?php case(JLRegisteredClaimed::STATUS_CONFIRMED):?>
			<label class='label'>Confirmed date: </label>
			<?php echo $email_date; ?>
			<label class='label'>Lasted status: </label>Confirmed        
		<?php break;?>
        <?php case(JLRegisteredClaimed::STATUS_NOT_CONFIRMED):?>
			<label class='label'>Notconfirmed date: </label>
			<?php echo $email_date; ?>            
			<label class='label'>Lasted status: </label>Not confirmed
		<?php break;?>
        <?php case(JLRegisteredClaimed::STATUS_EXPIRED):?>
            <label class='label'>Expired date: </label>
			<?php echo $email_date; ?>            
            <label class='label'>Lasted status: </label>Expired        
		<?php break;?>
		<?php //@todo: claime status ?>                        
    <?php endswitch; ?>
<?php endif; ?>

<?php /** cause **/ ?>
<?php if(isset($cause)) :?>
	<label class='label'>With cause: </label> <?php echo $cause ;?>
<?php endif; ?>