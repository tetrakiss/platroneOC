<?php
class ControllerPaymentplatron extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/platron');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('platron', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_security'] = $this->language->get('entry_security');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['security'])) {
			$this->data['error_security'] = $this->error['security'];
		} else {
			$this->data['error_security'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/platron', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/platron', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['platron_merchant'])) {
			$this->data['platron_merchant'] = $this->request->post['platron_merchant'];
		} else {
			$this->data['platron_merchant'] = $this->config->get('platron_merchant');
		}

		if (isset($this->request->post['platron_security'])) {
			$this->data['platron_security'] = $this->request->post['platron_security'];
		} else {
			$this->data['platron_security'] = $this->config->get('platron_security');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/platron/callback';
		
		if (isset($this->request->post['platron_total'])) {
			$this->data['platron_total'] = $this->request->post['platron_total'];
		} else {
			$this->data['platron_total'] = $this->config->get('platron_total'); 
		} 
				
		if (isset($this->request->post['platron_order_status_id'])) {
			$this->data['platron_order_status_id'] = $this->request->post['platron_order_status_id'];
		} else {
			$this->data['platron_order_status_id'] = $this->config->get('platron_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['platron_geo_zone_id'])) {
			$this->data['platron_geo_zone_id'] = $this->request->post['platron_geo_zone_id'];
		} else {
			$this->data['platron_geo_zone_id'] = $this->config->get('platron_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['platron_status'])) {
			$this->data['platron_status'] = $this->request->post['platron_status'];
		} else {
			$this->data['platron_status'] = $this->config->get('platron_status');
		}
		
		if (isset($this->request->post['platron_sort_order'])) {
			$this->data['platron_sort_order'] = $this->request->post['platron_sort_order'];
		} else {
			$this->data['platron_sort_order'] = $this->config->get('platron_sort_order');
		}

		$this->template = 'payment/platron.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/platron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['platron_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['platron_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>