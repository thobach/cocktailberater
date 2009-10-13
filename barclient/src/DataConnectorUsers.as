package  
{
	
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorUsers extends DataConnector
	{
		
		public function DataConnectorUsers() 
		{
			
		}
		
		public function refresh():void {
			request("member/get-all");
		}
		
		override protected function processResult(rsp:XML):void {
			c.newMembers(rsp);	
		}
		
	}
	
}