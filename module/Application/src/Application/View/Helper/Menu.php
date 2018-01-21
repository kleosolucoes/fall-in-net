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

        $stringFoto = 'placeholder.png';

        // Start: Header 
        $html .= '
		<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega navbar-expand-md" role="navigation">

			<div class="navbar-header">
				<button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
								data-toggle="menubar">
					<span class="sr-only">Toggle navigation</span>
					<span class="hamburger-bar"></span>
				</button>
				<button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
								data-toggle="collapse">
					<i class="icon wb-more-horizontal" aria-hidden="true"></i>
				</button>
				<div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
					<img class="navbar-brand-logo" src="../../assets/images/logo.png" title="Remark">
					<span class="navbar-brand-text hidden-xs-down"> Remark</span>
				</div>
			</div>

			<div class="navbar-container container-fluid">
				<!-- Navbar Collapse -->
				<div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
					<!-- Navbar Toolbar -->
					<ul class="nav navbar-toolbar">
						<li class="nav-item hidden-float" id="toggleMenubar">
							<a class="nav-link" data-toggle="menubar" href="#" role="button">
								<i class="icon hamburger hamburger-arrow-left">
									<span class="sr-only">Toggle menubar</span>
									<span class="hamburger-bar"></span>
								</i>
							</a>
						</li>
						<li class="nav-item hidden-sm-down" id="toggleFullscreen">
							<a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
								<span class="sr-only">Toggle fullscreen</span>
							</a>
						</li>
					</ul>
					<!-- End Navbar Toolbar -->

					<!-- Navbar Toolbar Right -->
					<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">

						<li class="nav-item dropdown">
							<a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
								 data-animation="scale-up" role="button">
								<span class="avatar avatar-online">
									<img src="../../../global/portraits/5.jpg" alt="...">
									<i></i>
								</span>
							</a>
							<div class="dropdown-menu" role="menu">
								<a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Logout</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications"
								 aria-expanded="false" data-animation="scale-up" role="button">
								<i class="icon wb-bell" aria-hidden="true"></i>
								<span class="badge badge-pill badge-danger up"></span>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
								<div class="dropdown-menu-header">
									<h5>NOTIFICATIONS</h5>
									<span class="badge badge-round badge-danger">0</span>
								</div>

								
								<div class="dropdown-menu-footer">
									<a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
										<i class="icon wb-settings" aria-hidden="true"></i>
									</a>
									<a class="dropdown-item" href="javascript:void(0)" role="menuitem">
										All notifications
									</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Messages"
								 aria-expanded="false" data-animation="scale-up" role="button">
								<i class="icon wb-envelope" aria-hidden="true"></i>
								<span class="badge badge-pill badge-info up"></span>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
								<div class="dropdown-menu-header" role="presentation">
									<h5>MESSAGES</h5>
									<span class="badge badge-round badge-info">0</span>
								</div>

								
								<div class="dropdown-menu-footer" role="presentation">
									<a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
										<i class="icon wb-settings" aria-hidden="true"></i>
									</a>
									<a class="dropdown-item" href="javascript:void(0)" role="menuitem">
										See all messages
									</a>
								</div>
							</div>
						</li>
					</ul>
					<!-- End Navbar Toolbar Right -->
				</div>
				<!-- End Navbar Collapse -->

			</div>

		</nav>
';     

        return $html;
    }

}
