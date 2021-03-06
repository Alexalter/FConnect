<?php
/**
 * FConnect
 */

class FConnect_Controller_User extends Zikula_AbstractController
{
    /**
     * The default entry point.
     */
    public function main()
    {
    	
		$settings = ModUtil::getVar($this->name);
		
		//Parameter extraction and error checking
        if (!isset($settings['appid']) || !isset($settings['secretkey'])) {
            throw new Zikula_Exception_Fatal($this->__('Facebook login is not supported'));
        }
		
		$fb_id = ModUtil::apiFunc($this->name, 'FacebookUser', 'getId');		
		
		
		if(!$fb_id) {			
		$loginUrl = ModUtil::apiFunc($this->name, 'Facebook', 'logInUrl');		
		$this->redirect($loginUrl);			
		}
				
	
		/// we should have facebook id now lets do what we want
		// is user logged in? or not		
		$uid = UserUtil::getVar('uid');	
		// uid = 1 is the anonymous user
	    if ($uid < 2) {
							
		if(ModUtil::apiFunc($this->name, 'user', 'login', $fb_id)) {
					
								
				$this->redirect();						
			
		
		}else if(ModUtil::apiFunc($this->name, 'user', 'register')){
			
						 			    	
			if(ModUtil::apiFunc($this->name, 'user', 'login', $fb_id))	
			$this->redirect();						
	
			}
		
		
		}else{
		    //user is logged in but not connected just connect	
			//ModUtil::apiFunc($this->name, 'user', 'connectme');
		}

		 
		 
	// Assign all the module vars
    return $this->view->assign('fb_id', $fb_id)
           ->fetch('fconnect_user_main.tpl');
    }

	    /**
     * The default entry point.
     */
    public function updateavatar()
    {		
		ModUtil::apiFunc($this->name, 'user', 'avatar');
		return System::redirect(ModUtil::url('Avatar', 'user', 'main'));
	}

}
