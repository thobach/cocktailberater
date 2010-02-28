package org.cocktailberater.de.orderclient.client;

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
}
