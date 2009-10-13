package  
{
	
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorBars  extends DataConnector
	{
		
		public function DataConnectorBars() 
		{
			
		}
		
		public function getBars():void {
			request("party/get-all");
		}
		
		override protected function processResult(rsp:XML):void {
			c.showBar(rsp);	
		}
		
	}
	
}