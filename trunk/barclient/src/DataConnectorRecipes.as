package  
{
	
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorRecipes extends DataConnector
	{
		
		public function DataConnectorRecipes() 
		{
			
		}
		
		public function refresh():void {
			request("cocktail/get-all");
		}
		
		override protected function processResult(rsp:XML):void {
			c.newCocktails(rsp);	
		}
		
	}
	
}