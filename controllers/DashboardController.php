<?php
require_once BASE_PATH.'/core/DB.php';

class DashboardController {
  public function index(){
    Auth::requireLogin();
    view('dashboard/index');
  }
}