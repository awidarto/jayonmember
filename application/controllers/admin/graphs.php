<?php

class Graphs extends Application
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('plot');
	}

	public function monthlygraph($status = null){
		$lineplot = $this->plot->plot(500,130);

		$year = date('Y',time());
		$month = date('m',time());

		if(is_null($status)){
			$status = null;
		}else{
			$status = array('status'=>$status);
		}
		$series = getmonthlydatacountarray($year,$month,$status,null);
		//$series = getmonthlydatacountarray($year,$month,$status,null);

		$lineplot->SetPlotType('bars');
		$lineplot->setShading(0);
		$lineplot->SetDataValues($series);

		$lineplot->SetYDataLabelPos('plotin');

		# With Y data labels, we don't need Y ticks or their labels, so turn them off.
		//$lineplot->SetYTickLabelPos('none');
		//$lineplot->SetYTickPos('none');		

		$lineplot->SetYTickIncrement(1);
		$lineplot->SetPrecisionY(0);

		//Turn off X axis ticks and labels because they get in the way:
		$lineplot->SetXTickLabelPos('none');
		$lineplot->SetXTickPos('none');

		//Draw it
		$lineplot->DrawGraph();
	}

	public function monthlystackedgraph($status = null){

		$id = $this->session->userdata('userid');

		$lineplot = $this->plot->plot(520,130);

		$year = date('Y',time());
		$month = date('m',time());

		if(is_null($status)){
			$status = null;
		}else{
			$status = array('status'=>$status);
		}
		$buyerseries = getmonthlydatacountarray($year,$month,$status,array('buyer_id'=>$id));
		$sellerseries = getmonthlydatacountarray($year,$month,$status,array('merchant_id'=>$id));

		$series = array();

		for($i = 0; $i < sizeof($buyerseries);$i++){
			$series[] = array($buyerseries[$i][0],$buyerseries[$i][1],$sellerseries[$i][1]);
		}

		//print_r($buyerseries);
		//print_r($sellerseries);
		//print_r($series);
		//$series = getmonthlydatacountarray($year,$month,$status,null);

		$lineplot->SetPlotType('stackedbars');
		$lineplot->setShading(0);
		$lineplot->SetDataValues($series);

		$lineplot->SetYDataLabelPos('plotin');

		# With Y data labels, we don't need Y ticks or their labels, so turn them off.
		//$lineplot->SetYTickLabelPos('none');
		//$lineplot->SetYTickPos('none');		

		$lineplot->SetYTickIncrement(1);
		$lineplot->SetPrecisionY(0);

		$lineplot->SetLegend(array('Buy', 'Sell'));
		$lineplot->SetLegendPosition(1, 0, 'image', 1, 0, -2, 2);

		//Turn off X axis ticks and labels because they get in the way:
		$lineplot->SetXTickLabelPos('none');
		$lineplot->SetXTickPos('none');

		//Draw it
		$lineplot->DrawGraph();
	}

}

?>