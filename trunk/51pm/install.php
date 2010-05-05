<?php 
    header('Content-Type: text/html; charset=utf-8');

    $lang = $_GET['lang'];
    if (empty($lang))
    {
        $lang = 'zh'; 
    } 
    
    if ($lang == 'zh')
    {
    	$msgs = array(
            'app.name'=>'系统名称',
    		'title.installed'=>'您已经安装了51PM项目管理软件',
    		'error.installed'=>'对不起，您已经安装了51PM项目管理软件。你需要清除您的数据库，然后重新安装。',    		
    		'insufficient.requirements'=>'不够系统要求',
    		'less.php.mysql'=>'您不能安装 51PM %1$s，它需要 PHP %2$s 或更高版本以及 MySQL %3$s或更高版本. 您的PHP版本为 %4$s，MySQL版本为%5$s.',
    		'less.php'=>'您不能安装 51PM %1$s，它需要 PHP %2$s 或更高版本. 您的PHP版本为 %4$s',
    		'less.mysql'=>'您不能安装 51PM %1$s，它需要 MySQL %3$s或更高版本. 您的MySQL版本为%5$s.',
    		'welcome'=>'欢迎使用',
    		'welcome.message'=>'谢谢您选择51PM，您的终极项目管理软件！您只要花上几分钟，填写好如下的必要信息，系统就能投入使用了。如果您有问题，请读<a href="readme.html">README</a>文件。',
    		'info.needed'=>'系统需要的信息',
			'info.desc'=>'请填写如下信息。不要担心，这些信息稍后是可以修改的。',
    		'errors'=>'请纠正或填写如下有误的信息：', 
    		'install.now'=>'安装',
    		'install.success'=>'安装成功',
    		'install.success.desc'=>'51PM 已经安装完毕，如果您还在期待更多的步骤，我们只能说很抱歉了。',
    		'timezone'=>'时区',
    		'language'=>'使用语言',
    		'db.host'=>'数据库服务器',
    		'db.name'=>'数据库名称',
    		'db.user'=>'数据库用户名',
    		'db.password'=>'数据库密码',
            'admin.email'=>'系统管理员EMAIL',
            'login'=>'请点击 <a href="%1$s">此处</a>登录系统，登录信息如下： ',
            'user'=>'用户名',
            'password'=>'密码',
            'mailengine'=>'邮件发送方式',
            'smtp.host'=>'SMTP服务器',
            'smtp.port'=>'SMTP端口(default is 25)',
            'smtp.user'=>'SMTP用户名',
            'smtp.password'=>'SMTP密码',
         );
    }
    else
    {
    	$msgs = array(
            'app.name'=>'Application Name',
    		'title.installed'=>'Already Installed',
    		'error.installed'=>'Sorry, you have already installed 51PM project management system.',
    		'insufficient.requirements'=>'Insufficient Requirements',
    		'less.php.mysql'=>'You cannot install because 51PM %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.',
    		'less.php'=>'You cannot install because 51PM %1$s requires PHP version %2$s or higher. You are running version %3$s.',
    		'less.mysql'=>'You cannot install because 51PM %1$s requires MySQL version %2$s or higher. You are running version %3$s.',
    		'welcome'=>'Welcome',
    		'welcome.message'=>'Thanks for choosing 51PM, your utlimate project management tool! Fill out the following required information, you can start to use the system in just a few minutes. If you have questions, please read the <a href="readme.html">README</a> file. ',
    		'info.needed'=>'Information Needed',
    		'info.desc'=>'Please provide the following information.  Don&#8217;t worry, you can always change these settings later.',
    		'errors'=>'Please fix the following errors: ',
    		'install.now'=>'Install Now',
    		'install.success'=>'Installation Succeeded',
    		'install.success.desc'=>'51PM has been successfully installed. Waiting for more steps, sorry to disappoint',
    		'timezone'=>'Timezone',
    		'language'=>'Language',
            'db.host'=>'Database Host',
            'db.name'=>'Database Name',
            'db.user'=>'Database User ID',
            'db.password'=>'Database Password',
            'admin.email'=>'Admin Email',
            'login'=>'Please proceed with login by clicking <a href="%1$s">here</a> using the following account: ',
            'user'=>'User ID',
            'password'=>'Password',
            'mailengine'=>'Email Engine',
            'smtp.host'=>'SMTP Host',
            'smtp.port'=>'SMTP Port (default is 25)',
            'smtp.user'=>'SMTP User ID',
            'smtp.password'=>'SMTP Password',
    	);
    	
    }
        
	if (isset($_GET['step']))
		$step = $_GET['step'];
	else
		$step = 0;
    
    function is_installed() 
    {
        return false;
    }
    
    function home_link() {
        $self = 'http'
        . ( (isset($_SERVER['https']) && $_SERVER['https'] == 'on') ? 's' : '' ) . '://'
        . $_SERVER['SERVER_NAME']
        . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']) 
        . stripslashes($_SERVER['REQUEST_URI']);
        
        $last = strpos($self, 'install.php'); 
        return substr($self, 0,  $last);
    }
    
    function writeConfig($appname, $timezone, $language, $dbhost, $dbname, $dbuser, $dbpassword, $adminemail, 
        $mailengine, $smtphost, $smtpport, $smtpuser, $smtppassword
        )
    {
        $configtmpl = './protected/config/main.php.tmpl'; 
        $tmpl = file_get_contents($configtmpl);
         
        $configfile = './protected/config/main.php';
        $tmpl = str_replace('{APP_NAME}', $appname, $tmpl);
        $tmpl = str_replace('{TIME_ZONE}', $timezone, $tmpl);
        $tmpl = str_replace('{LANGUAGE}', $language, $tmpl);
        $tmpl = str_replace('{DB_HOST}', $dbhost, $tmpl);
        $tmpl = str_replace('{DB_NAME}', $dbname, $tmpl);
        $tmpl = str_replace('{DB_USER}', $dbuser, $tmpl);
        $tmpl = str_replace('{DB_PASSWORD}', $dbpassword, $tmpl);
        $tmpl = str_replace('{ADMIN_EMAIL}', $adminemail, $tmpl);        
        
        $tmpl = str_replace('{MAIL_ENGINE}', $mailengine, $tmpl);
        $tmpl = str_replace('{SMTP_USER}', $smtpuser, $tmpl);
        $tmpl = str_replace('{SMTP_PASSWORD}', $smtppassword, $tmpl);
        $tmpl = str_replace('{SMTP_HOST}', $smtphost, $tmpl);
        $tmpl = str_replace('{SMTP_PORT}', $smtpport, $tmpl);
                
        $fp = fopen($configfile, "w");
        fwrite($fp, $tmpl);
        fclose($fp);        
    }
    
    
    function populateDb($timezone, $dbhost, $dbname, $dbuser, $dbpassword, $adminemail)
    {
        try {       
        
            $schemafile = './protected/data/schema.sql'; 
            $sql = file_get_contents($schemafile);
            $sql = str_replace('{ADMIN_EMAIL}', $adminemail, $sql);
            
            date_default_timezone_set($timezone);
            $ts = date('Y-m-d H:i:s');
            
            $sql = str_replace('{TIME_STAMP}', $ts, $sql);                    
            $queries = explode(';', $sql); 
             
            $conn = mysql_pconnect($dbhost, $dbuser, $dbpassword);
            mysql_select_db($dbname, $conn);
             
            foreach ($queries as $q)
            {
                mysql_query($q, $conn); 
            }
             
            return null;         
        }
        catch (Exception $e)        
        {
            return $e->getMessage();         
        }
    }
    
    function esc_attr($text)
    {    	
    	return htmlspecialchars($text,ENT_QUOTES, 'UTF-8');
    }
?>


<?php 
function display_header() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="js/jquery.validate.min.js" type="text/javascript"></script>
    <script>
        $(function() {
            $('#mailengine').change(function(){
                if ($(this).val() == 'smtp') {
                    $('#smtpconfig').show();
                }
                else {
                    $('#smtpconfig').hide();
                }
            }); 
        }); 
    </script>
    <link rel="stylesheet" type="text/css"  href="css/install.css" />    
    <title>51PM Installation</title>    
</head>
<body>
<h1 id="logo"><img src="images/logoicon.png" /></h1>
<?php }  ?> 


<?php 
function display_setup_form( $errors = null) {
    global $msgs;
	if ( ! is_null( $errors ) ) {
		echo '<p class="error">', $msgs['errors'], implode(', ', $errors), '</p>';
	}
?>
<h1><?php echo $msgs['welcome']; ?></h1>
<p><?php  echo $msgs['welcome.message'];?></p>

<h1><?php echo $msgs['info.needed']; ?></h1>
<p><?php echo $msgs['info.desc']; ?></p>
<form id="setup" method="post" action="install.php?step=2">
	<table class="form-table">
	    <tbody id="maininfo">
		<tr>
			<th scope="row"><label for="app_name"><?php echo $msgs['app.name']; ?></label></th>
			<td><input name="app_name" type="text" id="app_name" size="25" 
				value="<?php echo ( isset($_POST['app_name']) ? esc_attr($_POST['app_name']) : '' ); ?>" /></td>
		</tr>
        <tr>
            <th scope="row"><label for="timezone"><?php echo $msgs['timezone']; ?></label></th>
            <td>
                <select name="timezone" id="timezone">
                    <option <?php if ('PRC' == $_POST['timezone']) echo 'selected'; ?> value="PRC">中国</option>
                    <option <?php if ('US/Central' == $_POST['timezone']) echo 'selected'; ?> value="US/Central">US/Central</option>
                    <option <?php if ('US/Eastern' == $_POST['timezone']) echo 'selected'; ?> value="US/Eastern">US/Eastern</option>
                    <option <?php if ('US/Mountain' == $_POST['timezone']) echo 'selected'; ?> value="US/Mountain">US/Mountain</option>
                    <option <?php if ('US/Pacific' == $_POST['timezone']) echo 'selected'; ?> value="US/Pacific">US/Pacific</option>
                    <option <?php if ('US/Hawaii' == $_POST['timezone']) echo 'selected'; ?> value="US/Hawaii">US/Hawaii</option>
                </select>            
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="language"><?php echo $msgs['language']; ?></label></th>
            <td>
                <select name="language" id="language">
                    <option <?php if ('zh_cn' == $_POST['language']) echo 'selected'; ?> value="zh_cn">中文</option>
                    <option <?php if ('en_us' == $_POST['language']) echo 'selected'; ?> value="en_us">English</option>
                </select>            
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="dbhost"><?php echo $msgs['db.host']; ?></label></th>
            <td><input name="dbhost" type="text" id="dbhost" size="25" 
                value="<?php echo ( isset($_POST['dbhost']) ? esc_attr($_POST['dbhost']) : '' ); ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dbname"><?php echo $msgs['db.name']; ?></label></th>
            <td><input name="dbname" type="text" id="dbname" size="25" 
                value="<?php echo ( isset($_POST['dbname']) ? esc_attr($_POST['dbname']) : '' ); ?>" /></td>
        </tr>        
        <tr>
            <th scope="row"><label for="dbuser"><?php echo $msgs['db.user']; ?></label></th>
            <td><input name="dbuser" type="text" id="dbuser" size="25" 
                value="<?php echo ( isset($_POST['dbuser']) ? esc_attr($_POST['dbuser']) : '' ); ?>" /></td>
        </tr>        
        <tr>
            <th scope="row"><label for="dbpassword"><?php echo $msgs['db.password']; ?></label></th>
            <td><input name="dbpassword" type="text" id="dbpassword" size="25" 
                value="<?php echo ( isset($_POST['dbpassword']) ? esc_attr($_POST['dbpassword']) : '' ); ?>" /></td>
        </tr>        
        <tr>
            <th scope="row"><label for="adminemail"><?php echo $msgs['admin.email']; ?></label></th>
            <td><input name="adminemail" type="text" id="adminemail" size="25" 
                value="<?php echo ( isset($_POST['adminemail']) ? esc_attr($_POST['adminemail']) : '' ); ?>" /></td>
        </tr>     
        <tr>
            <th scope="row"><label for="mailengine"><?php echo $msgs['mailengine']; ?></label></th>
            <td>
                <select name="mailengine" id="mailengine">
                    <option <?php if ('mail' == $_POST['mailengine']) echo 'selected'; ?> value="mail">Mail</option>
                    <option <?php if ('smtp' == $_POST['mailengine']) echo 'selected'; ?> value="smtp">SMTP</option>
                    <option <?php if ('sendmail' == $_POST['mailengine']) echo 'selected'; ?> value="sendmail">Sendmail</option>
                </select>            
            </td>
        </tr>
        
        </tbody>
        
        <tbody id="smtpconfig" <?php if (empty($_POST['mailengine']) || $_POST['mailengine'] != 'smtp') echo 'style="display:none;"';?> >
        <tr>
            <th scope="row"><label for="smtphost"><?php echo $msgs['smtp.host']; ?></label></th>
            <td><input name="smtphost" type="text" id="smtphost" size="25" 
                value="<?php echo ( isset($_POST['smtphost']) ? esc_attr($_POST['smtphost']) : '' ); ?>" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="smtpport"><?php echo $msgs['smtp.port']; ?></label></th>
            <td><input name="smtpport" type="text" id="smtpport" size="25" 
                value="<?php echo ( isset($_POST['smtpport']) ? esc_attr($_POST['smtpport']) : '25' ); ?>" /></td>
        </tr>        
        <tr>
            <th scope="row"><label for="smtpuser"><?php echo $msgs['smtp.user']; ?></label></th>
            <td><input name="smtpuser" type="text" id="smtpuser" size="25" 
                value="<?php echo ( isset($_POST['smtpuser']) ? esc_attr($_POST['smtpuser']) : '' ); ?>" /></td>
        </tr>        
        <tr>
            <th scope="row"><label for="smtppassword"><?php echo $msgs['smtp.password']; ?></label></th>
            <td><input name="smtppassword" type="text" id="smtppassword" size="25" 
                value="<?php echo ( isset($_POST['smtppassword']) ? esc_attr($_POST['smtppassword']) : '' ); ?>" /></td>
        </tr>   
        </tbody>
	</table>
	<p class="step"><input type="submit" name="Submit" value="<?php echo $msgs['install.now']; ?>" class="button" /></p>
</form>

<?php } //end function display_setup_form(); ?> 
	

<?php 
    if ( is_installed() ) 
    {
        display_header(); 
        die('<h1>'.$msgs['title.installed'].'</h1><p>'.$msgs['error.installed'].'</p></body></html>');
    }
    
    $php_version    = phpversion();
    $required_php_version = '5.1';
    $required_mysql_version = '5.0';
    $pm_version = '1.0';
    $mysql_version = '5.1'; 
    $php_compat     = version_compare( $php_version, $required_php_version, '>=' );
    $mysql_compat = true;
    
    if ( !$mysql_compat && !$php_compat )
        $compat = sprintf($msgs['less.php.mysql'] , $pm_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version );
    elseif ( !$php_compat )
        $compat = sprintf($msgs['less.php'], $pm_version, $required_php_version, $php_version );
    elseif ( !$mysql_compat )
        $compat = sprintf( $msgs['less.mysql'], $pm_version, $required_mysql_version, $mysql_version );

    if ( !$mysql_compat || !$php_compat ) {
        display_header();
        die('<h1>' . $msgs['insufficient.requirements'] . '</h1><p>' . $compat . '</p></body></html>');
    }
    
    switch($step) {
        case 0:
        case 1: // in case people are directly linking to this
          display_header();    
?>
<?php
          display_setup_form();
          break;
        case 2:
            display_header();
            $appname = isset($_POST['app_name']) ? stripslashes($_POST['app_name']) : '';
            $timezone = isset($_POST['timezone']) ? stripslashes($_POST['timezone']) : '';
            $language = isset($_POST['language']) ? stripslashes($_POST['language']) : '';
            $dbname = isset($_POST['dbname']) ? stripslashes($_POST['dbname']) : '';
            $dbhost = isset($_POST['dbhost']) ? stripslashes($_POST['dbhost']) : '';
            $dbuser = isset($_POST['dbuser']) ? stripslashes($_POST['dbuser']) : '';
            $dbpassword = isset($_POST['dbpassword']) ? stripslashes($_POST['dbpassword']) : '';
            $adminemail = isset($_POST['adminemail']) ? stripslashes($_POST['adminemail']) : '';
            
            $mailengine = isset($_POST['mailengine']) ? stripslashes($_POST['mailengine']) : '';
            $smtphost = isset($_POST['smtphost']) ? stripslashes($_POST['smtphost']) : '';
            $smtpport = isset($_POST['smtpport']) ? stripslashes($_POST['smtpport']) : '';
            $smtpuser = isset($_POST['smtpuser']) ? stripslashes($_POST['smtpuser']) : '';
            $smtppassword = isset($_POST['smtppassword']) ? stripslashes($_POST['smtppassword']) : '';
            
            $errors = array();
            if (empty($appname)) {
                $errors[] = $msgs['app.name']; 
            }
            if (empty($timezone)) {
                $errors[] = $msgs['timezone']; 
            }
            if (empty($language)) {
                $errors[] = $msgs['language']; 
            }
            if (empty($dbhost)) {
                $errors[] = $msgs['db.host']; 
            }
            if (empty($dbname)) {
                $errors[] = $msgs['db.name']; 
            }
            if (empty($dbuser)) {
                $errors[] = $msgs['db.user']; 
            }
            if (empty($dbpassword)) {
                $errors[] = $msgs['db.password']; 
            }
            if (empty($adminemail)) {
                $errors[] = $msgs['admin.email']; 
            }
            if ($mailengine == 'smtp')
            {
                if (empty($smtphost))
                {
                    $errors[] = $msgs['smtp.host'];
                }
            }
            
            if (count($errors) > 0) {
                display_setup_form($errors);
                die('</body></html>');
            }
            
            
            writeConfig($appname, $timezone, $language, $dbhost, $dbname, $dbuser, $dbpassword, 
                $adminemail, $mailengine, $smtphost, $smtpport, $smtpuser, $smtppassword);
            populateDb($timezone,$dbhost, $dbname, $dbuser, $dbpassword, $adminemail);
            
            
            $loginPrompt = sprintf( $msgs['login'], home_link() );
            
?>
            <h1><?php echo $msgs['install.success']; ?></h1>

            <p><?php echo $msgs['install.success.desc']; ?></p>
	        <p>
	           <?php echo $loginPrompt; ?>
	           <table class="form-table">
                    <tr>
                        <th scope="row"><label><?php echo $msgs['user']; ?></label></th>
                        <td><?php echo $adminemail; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label><?php echo $msgs['password']; ?></label></th>
                        <td>lovepm88</td>
                    </tr>
               </table>     
	        </p>
	        
	        <div class="warning">
	        <?php if ($lang == 'zh')
	        {
	        ?>
	        
	                       请删除安装文件 install.php. 如果需要修改配置, 请修改如下文件:
            ./protected/config/main.php.
	        
	        <?php 
	        }
	        else 
	        {
	        ?>
	        
	        Be sure to delete install.php. To modify the configuration, go to:
	        ./protected/config/main.php.
	        
	        <?php 
	        }
	        ?>
	        </div>   
	
<?php } ?>

</body>
</html>