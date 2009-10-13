package  
{
	
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorOrderFinish extends DataConnector
	{
		private var anzahl:int;
		
		public function DataConnectorOrderFinish() 
		{
			
		}
		
		public function finish(objects:XMLList, n:int):void {
			anzahl = n;
			var i:int;
			
			for (i = 0; i < n; i++) {
				request("order/get-all"); // Muss angepasst werden
			}
			
		}
		
		override protected function processResult(rsp:XML):void {
			if (rsp.@status != "ok"); //Fehlerbehandlung;
			anzahl--;
			if (anzahl == 0) {
				c.refreshOrders();
			}
			
		}
		
	}
	
}