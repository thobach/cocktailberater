package  
{
	
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorOrder extends DataConnector
	{
		
		public function DataConnectorOrder() 
		{
			
		}
		
		public function refresh():void {
			request("order/get-all");
		}
		
		override protected function processResult(rsp:XML):void {
			c.newOrders(rsp);	
		}
		
	}
	
}