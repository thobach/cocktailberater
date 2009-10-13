package  
{
	/**
	 * ...
	 * @author DefaultUser (Tools -> Custom Arguments...)
	 */
	public class Control 
	{
		// Wichtige Objekte
		private static var c:Control;
		
		// View Objekte
		public var controlOrders:ControlOrders;
		public var controlLogin:ControlLogin;
		public var controlMain:Main;
		
		// Daten User
		private var userid:String;
		private var userhash:String;
		private var username:String;
		//Daten Bar
		private var barlist:XMLList;
		private var currentbar:XML;
		//Daten Orders
		private var orderlist:XMLList;
		private var currentOrder:XML;
		//Daten Members
		private var memberlist:XMLList;
		//Daten Cocktails
		private var cocktaillist:XMLList;
		
		
		public function Control() 
		{
			if (Control.c != null) ;//throw exception
		}
		
		public static function getInstace():Control {
			if (Control.c == null) {
				Control.c = new Control();
			}
			return Control.c;
		}
		
		public function startLogin(username:String, pw:String):void {
			//Check
			trace("test");
			var dc:DataConnectorLogin = new DataConnectorLogin();
			dc.login(username,MD5.encrypt(pw));
			var dc2:DataConnectorUsers = new DataConnectorUsers();
			dc2.refresh();
			var dc3:DataConnectorRecipes = new DataConnectorRecipes();
			dc3.refresh();
			
		}
		
		public function loginComplete(rsp:XML):void {
			if (rsp.@status == "ok") {
				userhash = rsp.member.@hashCode;
				userid = rsp.member.@id;
				username = rsp.member.@firstname + " " + rsp.member.@lastname;
				//controlLogin.showAlert(username);
				var dc:DataConnectorBars = new DataConnectorBars();
				dc.getBars();
				
			
				
			} else {
				controlLogin.showAlert("Fehlerhafte Userdaten");
			}
		}
		
		public function showBar(rsp:XML):void {
			barlist = rsp.partys.party;
			controlLogin.showBars(barlist);
			
		}
		
		public function barChosen(barindex:int):void {
			//controlOrders.showAlert("muh");
			refreshOrders();
			currentbar =  barlist[barindex];
			controlMain.switchViewStack("tabs");
			
			
		}
		
		public function refreshOrders():void {
			var dc:DataConnectorOrder = new DataConnectorOrder();
			dc.refresh();
		}
		
		public function newOrders(rsp:XML):void {
			
			orderlist = rsp.orders.order.(@status=="ordered");
		
		
			
			for each (var o:XML in orderlist)
			{
				var userid:String = o.@member;
				o.@username = memberlist.(@id == userid).@firstname+ " "+memberlist.(@id == userid).@lastname;
				o.@count = orderlist.(@recipeId == o.@recipeId).length().toString();
				o.appendChild(cocktaillist.(@id == o.@recipeId));
			}
			
						
			controlOrders.newOrders(orderlist);
		}
		
		public function orderChosen(order:XML):void {
			currentOrder = order;
			controlOrders.newOrderChosen(currentOrder);
		}
		
		public function bestellungFertig(anzahl:String):void  {
			var fertigeBestellungen:XMLList;
			var n:int;
			fertigeBestellungen = orderlist.(@recipeId == currentOrder.@recipeId);
			if (anzahl == "all") 
			{
				n = fertigeBestellungen.length();
			} else {
				n = parseInt(anzahl)+1;
			}
			var i:int;
			var dc:DataConnectorOrderFinish = new DataConnectorOrderFinish();
			
			dc.finish(fertigeBestellungen, n);
				
			
			
		}	
		
		
		public function newMembers(rsp:XML):void {
			memberlist = rsp.members.member;
		}
		
		public function newCocktails(rsp:XML):void {
			cocktaillist = rsp.cocktails.cocktail.recipes.recipe;
		}
		
	}
	
}