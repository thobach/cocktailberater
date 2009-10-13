package  
{
	import mx.rpc.http.HTTPService;
	import mx.rpc.events.ResultEvent;
	/**
	 * ...
	 * @author DefaultUser (Tools -> Custom Arguments...)
	 */
	public class DataConnector 
	{
		protected var c:Control = Control.getInstace();
		protected var http:HTTPService;
		protected var serverurl:String = "http://thobach.dyndns.org/cocktailberater.de/html/api/";
		
		public function DataConnector() 
		{
			
		}
		
		protected function request(url:String):void {
			http = new HTTPService();
			http.url = serverurl + url;
			http.method = "get";
			http.resultFormat = "e4x";
			http.requestTimeout = 15;
			http.addEventListener(ResultEvent.RESULT, processhttpresult);
			http.send();
		}
		
		protected function processhttpresult (evt:ResultEvent):void 
		{
			var result:XML = XML(evt.result);  
			processResult(result);
		}
		
		protected function processResult(rsp:XML):void {
			c.loginComplete(rsp);	
		}
		
		
		
	}
	
}