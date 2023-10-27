<?php 
	class Pages extends Controller {
		public function __construct(){
            $this->userModel = $this->model('User');
        }
					
	    public function index(){
			$user = $this->userModel->getUsers(); 
			
            $data = ['title' => 'Página principal',
					 'name'  => 'Jorge Ruiz',
					 'users' => $user];
            
            $this->view('pages/index', $data);
        }
		
		public function about(){            
            $this->view('pages/about');
        }

		public function eventos(){            
            $this->view('pages/eventos');
        }

		public function cuidadosCaninos(){            
            $this->view('pages/cuidadosCaninos');
        }
	}
?>