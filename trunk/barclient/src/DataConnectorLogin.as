package  
{
	import mx.rpc.http.HTTPService;
	import mx.rpc.events.ResultEvent;
	/**
	 * ...
	 * @author Tobias
	 */
	public class DataConnectorLogin extends DataConnector
	{
		
		public function DataConnectorLogin() 
		{
			
		}
		
		public function login(username:String, md5pw:String):void {
			request("member/login/email/"+username+"/password-md5/"+md5pw);
		}
		
		override protected function processResult(rsp:XML):void {
			c.loginComplete(rsp);	
		}
		

	}
	
}