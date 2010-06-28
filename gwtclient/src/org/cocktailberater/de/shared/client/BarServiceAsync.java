package org.cocktailberater.de.shared.client;

import java.util.HashMap;

import com.google.gwt.user.client.rpc.AsyncCallback;

/**
 * The async counterpart of <code>BarService</code>.
 */
public interface BarServiceAsync {

	/**
	 * Retrieves all available Bars from the backend
	 * 
	 * @param callback
	 */
	void getBars(AsyncCallback<HashMap<Integer, String>> callback);

	void authenticate(String email, String password,
			AsyncCallback<Boolean> callback);

	void getRecipes(AsyncCallback<HashMap<Integer, String>> asyncCallback);

	void orderRecipe(Integer recipeId, Integer partyId,
			AsyncCallback<Boolean> callback);

	void getPartys(Integer barId,
			AsyncCallback<HashMap<Integer, String>> callback);

	void getOrders(Integer partyId,
			AsyncCallback<HashMap<Integer, String>> callback);

	void getMemberName(Integer memberId, AsyncCallback<String> callback);

	void completeOrder(Integer orderId, AsyncCallback<Boolean> callback);

	void register(String email, String password, AsyncCallback<Boolean> callback);

}
