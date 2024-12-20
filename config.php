<?php
	if(!defined('PLX_ROOT')) exit;
	/**
	* Plugin 			oups
	*
	* @CMS required		PluXml 
	* @page				config.php
	* @version			0.1
	* @date				2024-12-07
	* @author 			gcyrillus
		░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
		░       ░░  ░░░░░░░  ░░░░  ░  ░░░░  ░░      ░░       ░░░      ░░  ░░░░░░░        ░░      ░░░░░   ░░░  ░        ░        ░
		▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒  ▒▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒▒▒▒▒▒▒▒▒    ▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒
		▓       ▓▓  ▓▓▓▓▓▓▓  ▓▓▓▓  ▓▓▓    ▓▓▓  ▓▓▓▓  ▓       ▓▓  ▓▓▓▓  ▓  ▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓      ▓▓▓▓▓  ▓  ▓  ▓      ▓▓▓▓▓▓  ▓▓▓▓
		█  ███████  ███████  ████  ██  ██  ██  ████  █  ███████  ████  █  ██████████  ██████████  ████  ██    █  ██████████  ████
		█  ███████        ██      ██  ████  ██      ██  ████████      ██        █        ██      ██  █  ███   █        ████  ████
		█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
	**/	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);
	
	# parametres 
	$val['nb'] = $plxPlugin->getParam('nb') == '' ? $plxPlugin->nb : $plxPlugin->getParam('nb');
	$val['duration'] = $plxPlugin->getParam('duration') == '' ? $plxPlugin->duration : $plxPlugin->getParam('duration');
	
	if(!empty($_POST)) {
	$plxPlugin->setParam('nb', $_POST['nb'], 'numeric');
	$plxPlugin->setParam('duration', $_POST['duration'], 'numeric');
	
	
	$plxPlugin->saveParams();	
	header("Location: parametres_plugin.php?p=".basename(__DIR__));
	exit;
	}
	
	# initialisation des variables propres à chaque lanque
	$langs = array();
	foreach($aLangs as $lang) {
	# chargement de chaque fichier de langue
	$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.'oups/lang/'.$lang.'.php');
	$var[$lang]['mnuName'] =  $plxPlugin->getParam('mnuName_'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') : $plxPlugin->getParam('mnuName_'.$lang);
	}
	
	
	?>
	<link rel="stylesheet" href="<?php echo PLX_PLUGINS."oups/css/tabs.css" ?>" media="all" />
	<p>stocke en session les connexions
- fixe un nombre maximal sur une durée
- renvoi une erreur 429 si limite dépassée pendant 10 secondes.</p>	
	<h2><?php $plxPlugin->lang("L_CONFIG") ?></h2>
	 
	<div id="tabContainer">
	<form action="parametres_plugin.php?p=<?= basename(__DIR__) ?>" method="post" >
	<div class="tabs">
	<ul>
	
	
	</ul>
	</div>
	<div class="tabscontent">
	<div class="tabpage" id="tabpage_Param">	
	<fieldset><legend><?= $plxPlugin->getLang('L_PARAMS') ?></legend>
			<p>
				<label><?php $plxPlugin->lang('L_NB_REQUEST') ?></label> 
				<input type="text" name="nb" value="<?php echo plxUtils::strCheck($val['nb']) ?>" size=3>
			</p>
			
			<p>
				<label><?php $plxPlugin->lang('L_DURATION') ?></label> 
				<input type="text" name="duration" value="<?php echo plxUtils::strCheck($val['duration'])?>" size=3  ><?php $plxPlugin->lang('L_SECONDE') ?>.
			</p>	
		
	</fieldset>
	
	</div>
	
	<fieldset>
	<p class="in-action-bar">
	<?php echo plxToken::getTokenPostMethod() ?><br>
	<input type="submit" name="submit" value="<?= $plxPlugin->getLang('L_SAVE') ?>"/>
	</p>
	</fieldset>
	</form>
	</div>