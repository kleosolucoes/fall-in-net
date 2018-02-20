<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\KleoController;

/**
 * Nome: Menu.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o menu
 */
class Menu extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $html .= '<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega navbar-expand-md"
				 role="navigation">

			<div class="navbar-header">			
				<button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
								data-toggle="collapse">
					<i class="icon wb-more-horizontal" aria-hidden="true"></i>
				</button>
				<div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
					<img class="navbar-brand-logo" width="32px" heigth="32px" src="/img/logoursa.png" title="URSA">
					<span class="navbar-brand-text">'.KleoController::nomeAplicacaoFormatado.'</span>
				</div>
			</div>

			<div class="navbar-container container-fluid">
				<!-- Navbar Collapse -->
				<div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
					<!-- Navbar Toolbar -->
					<ul class="nav navbar-toolbar">
						<li class="nav-item dropdown dropdown-fw dropdown-mega">
							<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="fade"
								 role="button">Menu <i class="icon wb-chevron-down-mini" aria-hidden="true"></i></a>
							<div class="dropdown-menu" role="menu">
								<div class="mega-content">
									<div class="row">
										<div class="col-md-4">
											<h5>Menu</h5>
											<ul class="blocks-2">
												<li class="mega-menu m-0">
													<ul class="list-icons">
														<li>
															<i class="wb-chevron-right-mini" aria-hidden="true"></i>
															<i class="wb-home" aria-hidden="true"></i>
															<a href="/adm">Principal</a>
														</li>
														<li>
															<i class="wb-chevron-right-mini" aria-hidden="true"></i>
															<i class="wb-dashboard" aria-hidden="true"></i>
															<a href="/admRelatorio">Dashboard</a>
														</li>
														<li>
															<i class="wb-chevron-right-mini" aria-hidden="true"></i>
															<i class="wb-user" aria-hidden="true"></i>
															<a href="/admAtivo">Novo Ativo</a>
														</li>
													
													</ul>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
					<!-- End Navbar Toolbar -->

					<!-- Navbar Toolbar Right -->
					<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
						<li class="nav-item dropdown">
							<a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
								 data-animation="scale-up" role="button">
								<span class="avatar avatar-online">
									<img src="/img/avatars/placeholder.png" alt="...">
									<i></i>
								</span>
							</a>
							<div class="dropdown-menu" role="menu">
								<a class="dropdown-item" href="/admSair" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Sair</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications"
								 aria-expanded="false" data-animation="scale-up" role="button">
								<i class="icon wb-bell" aria-hidden="true"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
								<div class="dropdown-menu-header">
									<h5>NOTIFICAÇÕES</h5>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Messages"
								 aria-expanded="false" data-animation="scale-up" role="button">
								<i class="icon wb-envelope" aria-hidden="true"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
								<div class="dropdown-menu-header" role="presentation">
									<h5>MENSAGENS</h5>
								</div>
							</div>
						</li>
					</ul>
					<!-- End Navbar Toolbar Right -->
				</div>
				<!-- End Navbar Collapse -->			
			</div>
		</nav>';
        return $html;
    }

}
