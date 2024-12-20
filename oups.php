<?php if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			oups
		*
		* @CMS required			PluXml 
		*
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
	class oups extends plxPlugin {
		
		
		
		const BEGIN_CODE = '<?php' . PHP_EOL;
		const END_CODE = PHP_EOL . '?>';
		public $lang = ''; 
		public $nb;
		public $duration;
		
		
		public function __construct($default_lang) {
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			
			
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN, PROFIL_MANAGER);		
			
			
			# Declaration des hooks		
			$this->addHook('AdminTopBottom', 'AdminTopBottom');
			$this->addHook('ThemeEndHead', 'ThemeEndHead');
			$this->addHook('Index', 'Index');
			
			# configuration
			$this->nb =  $this->getParam('nb') == '' ? 6 : $this->getParam('nb');
			$this->duration =  $this->getParam('duration') == '' ? 10 : $this->getParam('duration');
			
		}
		
		# Activation / desactivation
		
		public function OnActivate() {
			# code à executer à l’activation du plugin
			//nowizards set
		}
		
		public function OnDeactivate() {
			# code à executer à la désactivation du plugin
		}	
		
		
		public function ThemeEndHead() {
			#gestion multilingue
			if(defined('PLX_MYMULTILINGUE')) {		
				$plxMML = is_array(PLX_MYMULTILINGUE) ? PLX_MYMULTILINGUE : unserialize(PLX_MYMULTILINGUE);
				$langues = empty($plxMML['langs']) ? array() : explode(',', $plxMML['langs']);
				$string = '';
				foreach($langues as $k=>$v)	{
					$url_lang="";
					if($_SESSION['default_lang'] != $v) $url_lang = $v.'/';
					$string .= 'echo "\\t<link rel=\\"alternate\\" hreflang=\\"'.$v.'\\" href=\\"".$plxMotor->urlRewrite("?'.$url_lang.$this->getParam('url').'")."\" />\\n";';
				}
				echo '<?php if($plxMotor->mode=="'.$this->getParam('url').'") { '.$string.'} ?>';
			}
			
			
			// ajouter ici vos propre codes (insertion balises link, script , ou autre)
		}
		
		/**
			* Méthode qui affiche un message si le plugin n'a pas la langue du site dans sa traduction
			* Ajout gestion du wizard si inclus au plugin
			* @return	stdio
			* @author	Stephane F
		**/
		public function AdminTopBottom() {
			
			echo '<?php
			$file = PLX_PLUGINS."'.$this->plug['name'].'/lang/".$plxAdmin->aConf["default_lang"].".php";
			if(!file_exists($file)) {
			echo "<p class=\\"warning\\">'.basename(__DIR__).'<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
			}
			?>';
		}
		
		/** 
			* Méthode Index
			* 
			* Descrition	:
			* @author		: TheCrok
			* 
		**/
		public function Index() {
			$now= strtotime(date("Y-m-d h:i:s"));
			# attente en cours
			if(isset($_SESSION['HOLD_ON']) && ($now - $_SESSION['HOLD_ON']) < $this->duration ) {
				$this->Oups429($now);				
			}
			if (isset($_SESSION['LAST_CALL'])) {
				$calls=$_SESSION['LAST_CALL'];
				$records=json_decode($calls,true);
				if(isset($records[$now])) 
				$records[$now] = ++$records[$now];
				else 
				$records[$now] = 1;
			}
			else {
				$records[$now] = 1;		
			}
			
			$update= json_encode($records);
			$_SESSION['LAST_CALL'] = $update;
			# atteinte de limite ?
			if($records[$now] > $this->nb ) {
				$this->Oups429($now);
			}
			else {
			unset($_SESSION['HOLD_ON']);}
		}
		public function Oups429($now){
			$_SESSION['HOLD_ON'] =$now;
				include 'oupsy429.php';
				exit;			
		}
	}		